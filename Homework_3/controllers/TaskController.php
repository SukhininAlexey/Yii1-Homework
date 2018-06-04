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

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
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
        
        if($model->load(\Yii::$app->request->post()) && $model->save()){
            $this->redirect(['task/index']);
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
}
