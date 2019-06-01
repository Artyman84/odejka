<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\AttributeBehavior;

/**
 * This is the model class for table "{{%posts}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $text
 * @property integer $timestamp
 * @property integer $disabled
 */
class Posts extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%posts}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'introductory_text', 'text'], 'required', 'message' => '«{attribute}» - не заполнено'],
            [['text'], 'string'],
            [['introductory_text'], 'string'],
            [['timestamp', 'disabled'], 'integer'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'timestamp',
                ],
                'value' => function ($event) {
                    return time();
                },
            ],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Заголовок',
            'introductory_text' => 'Вводный текст',
            'text' => 'Содержание',
            'timestamp' => 'Дата',
            'disabled' => 'Состояние',
        ];
    }
}
