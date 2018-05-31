<?php

namespace app\models;

use yii\base\Model;
/**
 * Description of Task
 *
 * @author Lex
 */
class Task extends Model{
    
    public $name;
    public $description;
    public $deadline;
    
    public function rules(){
        return [
            [['name', 'description'], 'required'],
            ['deadline', 'date', 'format' => 'MM/dd/yyyy'],
        ];
    }
    
}
