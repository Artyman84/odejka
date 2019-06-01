<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%admins}}".
 *
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $auth_key
 */
class Admins extends \yii\db\ActiveRecord {
    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%admins}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
//            [['username', 'password'], 'required'],
            [['username', 'password', 'auth_key'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'username' => 'Логин',
            'password' => 'Пароль',
            'auth_key' => 'Auth Key',
        ];
    }
}
