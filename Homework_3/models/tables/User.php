<?php

namespace app\models\tables;

use Yii;

/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property string $login
 * @property string $password
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['login', 'password'], 'required'],
            [['login'], 'string', 'max' => 50],
            [['password'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'login' => 'Login',
            'password' => 'Password',
        ];
    }
    
    public function fields(){
        return [
            'id',
            'username' => 'login',
            'password',
        ];
    }
    
    public static function getIdLoginArray(){
        $result = static::find()->select(['login', 'id'])->asArray()->all();
        $finalResult = [];
        foreach ($result as $key => $value) {
            $finalResult["{$value['id']}"] = "{$value['id']} - " . $value['login'];
        }
        return $finalResult;
    }
}
