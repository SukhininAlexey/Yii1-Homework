<?php

namespace app\controllers;

use \app\models\Task;
/**
 * Description of TaskController
 *
 * @author Lex
 */
class TaskController extends \yii\web\Controller {
    
    public function actionIndex(){
        
        $task = new \app\models\Task();
        
        // Загружаем информацию
        $task->load(['task' => [
                'name' => 'Домашняя работа Yii 1 Lesson 1',
                'description' => 'Сделать домашнюю работу по курсу Yii Framework 1 по уроку 1',
                'deadline' => '05/28/2018',
            ]], 'task');
         
            
        // Проверяем резальтат валидации и создаем сообщение о результате
        if($task->validate()){
            $checked = 'Задача задана правильно';
        }else{
            $checked = 'Задача задана неверно'; 
        }
        
        // Рендерим
        return $this->render("index", ['task' => $task, 'checked' => $checked]);
    }
    
    
    public function actionTest(){
        
        var_dump(\Yii::$app->db);
        exit;
    }
    
    public function actionArTest(){
        
        $user = new \app\models\tables\User(['login' => 'Nik', 'password' => md5("qwerty")]);
        $user->save();
        var_dump($user);
        exit;
    }
}
