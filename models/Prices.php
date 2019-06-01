<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%prices}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property integer $price
 * @property integer $disabled
 */
class Prices extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%prices}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'description', 'price'], 'required', 'message' => '«{attribute}» - не заполнено'],
            [['description'], 'string'],
            [['price', 'disabled'], 'integer', 'message' => '«{attribute}» - не число.'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Название услуги',
            'description' => 'Описание',
            'price' => 'Стоимость',
            'disabled' => 'Состояние',
        ];
    }
}
