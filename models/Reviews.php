<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%reviews}}".
 *
 * @property integer $id
 * @property string $firstname
 * @property string $lastname
 * @property string $text
 * @property integer $timestamp
 * @property integer $disabled
 */
class Reviews extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%reviews}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['firstname', 'lastname', 'text'], 'required'],
            [['text'], 'string'],
            [['timestamp', 'disabled'], 'integer'],
            [['firstname', 'lastname'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'firstname' => 'Firstname',
            'lastname' => 'Lastname',
            'text' => 'Text',
            'timestamp' => 'Timestamp',
            'disabled' => 'Disabled',
        ];
    }

}
