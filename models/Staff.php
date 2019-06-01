<?php

namespace app\models;

use Yii;
use yii\helpers\VarDumper;
use yii\web\UploadedFile;
use app\components\helper\CropImage;

/**
 * This is the model class for table "{{%staff}}".
 *
 * @property integer $id
 * @property string $full_name
 * @property string $position
 * @property string $photo
 * @property string $settings
 * @property integer $disabled
 */
class Staff extends \yii\db\ActiveRecord {

    const CANVAS = '400:400';

    /**
     * @var UploadedFile
     */
    public $image;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%staff}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['full_name', 'position'], 'required', 'message' => '«{attribute}» - не заполнено'],
            [['disabled'], 'integer'],
            [['full_name', 'position', 'photo', 'settings'], 'string', 'max' => 255],

            [['image'], 'file', 'checkExtensionByMimeType' => false, 'skipOnEmpty' => true, 'extensions' => ['png', 'jpg']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'full_name' => 'Имя и фамилия',
            'photo' => 'Фото',
            'position' => 'Должность',
            'disabled' => 'Состояние',
        ];
    }

    public function beforeDelete() {
        if( parent::beforeDelete() ){
            @unlink('img/staff/' . $this->photo);
            @unlink('img/staff/original_' . $this->photo);
            return true;
        }

        return false;
    }

    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {

            $crop_data = (array)Yii::$app->request->post('crop-edit-images');
            $old_photo = $this->photo;
            $this->settings = !empty($crop_data) && $crop_data[0] ? $crop_data[0] : '';

            if ($this->image ) {

                @unlink('img/staff/' . $old_photo);
                @unlink('img/staff/original_' . $old_photo);

                $file_name = uniqid('staff_photo_') . '.' . $this->image->extension;
                if( $this->image->saveAs('img/staff/original_' . $file_name) ){
                    $this->photo = $file_name;

                    CropImage::crop(
                        'img/staff/original_' . $file_name,
                        'img/staff/' . $file_name,
                        $crop_data[0],
                        self::CANVAS
                    );
                }

            } elseif( !$this->isNewRecord ) {

                if( $crop_data[0] ){

                    $ext = explode('.', $old_photo);
                    $file_name = uniqid('staff_photo_') . '.' . $ext[1];

                    if( rename('img/staff/original_' . $old_photo, 'img/staff/original_' . $file_name) ) {

                        CropImage::crop(
                            'img/staff/original_' . $file_name,
                            'img/staff/' . $file_name,
                            $crop_data[0],
                            self::CANVAS
                        );

                        @unlink('img/staff/' . $old_photo);
                        $this->photo = $file_name;
                    }
                }

            }

            return true;
        }
        return false;
    }
}
