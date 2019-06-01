<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%settings}}".
 *
 * @property integer $id
 * @property string $email
 * @property string $phone
 * @property string $address
 * @property string $chart
 * @property string $vk
 * @property string $facebook
 * @property string $inst
 * @property string $schedule
 * @property string $legend
 */
class Settings extends \yii\db\ActiveRecord {
    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%settings}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['address', 'chart', 'schedule', 'legend'], 'string'],
            [['email', 'phone', 'vk', 'facebook', 'inst'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'email' => 'Электронный адрес',
            'phone' => 'Номер телефона',
            'address' => 'Почтовый адрес',
            'chart' => 'Код карты',
            'vk' => 'Вконтакте',
            'facebook' => 'Фейсбук',
            'inst' => 'Инстаграм',
            'schedule' => 'Рабочий график',
            'legend' => 'Ключевые факты',
        ];
    }
}
