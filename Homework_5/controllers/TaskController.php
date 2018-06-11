<?php

namespace app\controllers;

use \app\models\Task;
use app\models\tables\TaskSearch;
/**
 * Description of TaskController
 *
 * @author Lex
 */
class TaskController extends \yii\web\Controller {
    
    public function actionIndex(){
        
        // Получаем пользователя и проверяем авторизацию
        $user_id = \Yii::$app->user->id;
        if(!$user_id){
            return $this->render('_notLoggedIn');
        }
        
        // Получаем месяц-год с предварительной установкой верного времени
        date_default_timezone_set('Europe/Moscow'); // Постоянно съезжает на час. В рунете говорят, что из-за переводов часов.
        if($date = \Yii::$app->request->post('date')){
            $year = mb_substr($date, 0, 4);
            $month = mb_substr($date, 5, 2);
        }else{
            $date = date("Y-m-d", time());
            $year = date('Y');
            $month = date('m');
        }
        
        
        $cache = \Yii::$app->cache;
        $key = 'calendar_' . $user_id;
        
        $dependency = new \yii\caching\DbDependency();
        $dependency->sql = "SELECT count(*) from tasks WHERE user_id = :user_id";
        $dependency->params = [':user_id' => $user_id];
        
        
        if(!$calendar = $cache->get($key)){
            
            $tasksResult = \app\models\tables\Task::getUserTasksByMonth($user_id, $year, $month);
            
            $calendar = array_fill_keys(range(1, date('t', strtotime($date))), []);

            foreach ($tasksResult as $index => $task) {
                array_push($calendar[date("j", strtotime($task->date))], $task);
            }
            
            $cache->set($key, $calendar, 300, $dependency);
        }
        
        return $this->render('index', [
            'calendar' => $calendar,
        ]);
    }
    
    
    
    
    public function actionSingle(){
        $user_id = \Yii::$app->user->id;
        if(!$user_id){
            return $this->render('_notLoggedIn');
        }
        
        $id = \Yii::$app->request->get('id');
        if(!$id){
            return $this->render('_err404');
        }
        
        $task = \app\models\tables\Task::findOne($id);
        $user = \app\models\tables\User::findOne(['id' => $task->user_id]);
        
        return $this->render('single', ['model' => $task, 'user' => $user]);
        
        
    }
    
    

    
    public function actionTest(){
        $cache = \Yii::$app->cache;
        $key = 'number';
        //$cache->flush();
        if($cache->exists($key)){
            $number = $cache->get($key);
        }else{
            $number = rand();
            $cache->set($key, $number, 10);
        }
        
        var_dump($number);
        exit;
    }


    
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }
    
    
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->setScenario(\app\models\tables\Task::SCENARIO_USER);

        if ($model->load(\Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }
    
    
    public function actionCreate(){
        
        $model = new \app\models\tables\Task();
        $model->setScenario(\app\models\tables\Task::SCENARIO_ADMIN);
        
        
        $modelInsertHandler = function($event){
            $user = \app\models\tables\User::findOne($event->sender->user_id);
            $contacter = new \app\models\Contacter([
                'name' => 'Ваш Любимый Начальник',
                'subject' => 'Новое задание: ' . $event->sender->name,
                'email' =>  'nachalnik@thegreat.org',
                'body' => 'На вас повесили новый таск: ' . $event->sender->description . 'Выполнить до ' . $event->sender->date,
            ]);
            
            if($contacter->contact($user->email)){
                echo "Сообщение отправлено пользователю под логином {$user->login} на действие '{$event->sender->name}'"; //exit;
            }else{
                echo "Не отправлено";
            }
        };
        
        
        $model->on($model::EVENT_AFTER_INSERT, $modelInsertHandler);
        
        if($model->load(\Yii::$app->request->post()) && $model->save()){
            $this->redirect(['admin-task/index']);
        }

        return $this->render('create', ['model' => $model]);
    }
    
    
    // Обслуживает интересы actionView
    protected function findModel($id)
    {
        if (($model = \app\models\tables\Task::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    
    
    
    // Устаревший контроллер - хочу поэкспериментировать с ним позднее
    public function actionTaskTable(){
        
        // Собираем параметры
        $user_id = \Yii::$app->user->id;
        $sql = "`user_id` = :user_id and `date` between :month01 and :month31"; // Использую between
        
        // Проверка авторизации
        if(!$user_id){
            return $this->render('_notLoggedIn');
        }
        
        // Собираем SQL-строку
        date_default_timezone_set('Europe/Moscow'); // Постоянно съезжает на час. В рунете говорят, что из-за переводов часов.
        $month01 = date('Y-m-01');
        $month31 = date('Y-m-31');
        $params = [
            ':user_id' => $user_id,
            ':month01' => $month01,
            ':month31' => $month31
        ];
        
        // Предварительно задаем условия перед отправкой в search model.
        $query = \app\models\tables\Task::find()->where($sql, $params);
        
        
        $searchModel = new TaskSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams, $query);
        
        $model = new \app\models\tables\Task();

        return $this->render('task_table', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model,
        ]);
    }
}
