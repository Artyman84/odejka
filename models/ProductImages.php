<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%product_images}}".
 *
 * @property int $id
 * @property int $product_id
 * @property string $image
 * @property int $position
 * @property string $settings
 */
class ProductImages extends \yii\db\ActiveRecord {
    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%product_images}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['product_id', 'image', 'settings'], 'required'],
            [['product_id', 'position'], 'integer'],
            [['image', 'settings'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'product_id' => 'Product ID',
            'image' => 'Image',
            'settings' => 'Settings',
            'position' => 'Position',
        ];
    }

    public function beforeDelete() {
        if( parent::beforeDelete() ){

            @unlink('img/products/' . $this->image);
            @unlink('img/products/original_' . $this->image);
            return true;
        }

        return false;
    }

}
