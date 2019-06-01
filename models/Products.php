<?php

namespace app\models;

use Yii;
use yii\helpers\VarDumper;
use yii\web\UploadedFile;
use app\components\helper\CropImage;


/**
 * This is the model class for table "{{%products}}".
 *
 * @property int $id
 * @property int $clothes_id
 * @property string $description
 */
class Products extends \yii\db\ActiveRecord {

    /**
     * @var UploadedFile
     */
    public $image;

    /**
     *
     */
    const CANVAS = '800:1200';

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%products}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['clothes_id'], 'required', 'message' => '«{attribute}» - не заполнено'],
            [['clothes_id'], 'integer'],
            [['description'], 'string'],
            [['image'], 'file', 'checkExtensionByMimeType' => false, 'skipOnEmpty' => true, 'maxFiles' => 100, 'extensions' => ['png', 'jpg']],
        ];
    }

    /**
     * @return array
     */
    public function getProductImages() {
        return $this->hasMany(ProductImages::className(), ['product_id' => 'id']);
    }

    /**
     * @return array
     */
    public function getClothes() {
        return $this->hasOne(Clothes::className(), ['id' => 'clothes_id']);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'image' => 'Фото',
            'clothes_id' => 'Тип одежды',
            'description' => 'Описание',
        ];
    }

    public function beforeDelete() {
        if( parent::beforeDelete() ){
            $this->deleteProductImages();
            return true;
        }

        return false;
    }

    private function deleteProductImages(){
        $images = $this->getProductImages()->all();
        if( !empty($images) ){
            foreach ($images as $image){
                $image->delete();
            }
        }
    }

    public function afterSave($insert, $changedAttributes) {
        parent::afterSave($insert, $changedAttributes);

        $crop_data = (array)Yii::$app->request->post('crop-edit-images');
        $path = 'img/products/';

        if ($this->image ) {

            $this->deleteProductImages();
            $j = 0;
            foreach ($crop_data as $i => $settings){
                if( ($image = $this->image[$i]) ) {

                    $file_name = uniqid('product_photo_') . '.' . $image->extension;
                    $original_file = $path . 'original_' . $file_name;
                    $crop_file = $path . $file_name;

                    if ($image->saveAs($original_file)) {
                        CropImage::crop($original_file, $crop_file, $settings, self::CANVAS);
                        $productImage = new ProductImages();
                        $productImage->setAttributes(['image' => $file_name, 'product_id' => $this->id, 'position' => $j, 'settings' => $settings]);
                        $productImage->save();
                        $j++;
                    }
                }
            }

        } elseif( !$this->isNewRecord ) {
            $images = $this->getProductImages()->all();
            $positions = array_keys($crop_data);

            foreach ($images as $i => $image){
                $img_id = $image->id;

                if( !isset($crop_data[$img_id]) ){
                    $image->delete();
                } else {

                    if( $crop_data[$img_id] != $image->settings ){

                        $old_file = $image->image;
                        $ext = explode('.', $old_file);
                        $new_file = uniqid('product_photo_') . '.' . $ext[1];

                        if( rename($path . 'original_' . $old_file, $path . 'original_' . $new_file) ) {

                            CropImage::crop($path . 'original_' . $new_file, $path . $new_file, $crop_data[$img_id], self::CANVAS);
                            @unlink($path . $image->image);

                            $image->settings = $crop_data[$img_id];
                            $image->image = $new_file;
                        }

                    }

                    $image->position = array_search($img_id, $positions);
                    $image->save();
                }
            }
        }

    }

}
