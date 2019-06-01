<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class ReviewForm extends Model {

    public $firstname;
    public $lastname;
    public $text;
    public $verifyCode;


    /**
     * @return array the validation rules.
     */
    public function rules() {
        return [
            [['firstname', 'lastname', 'text'], 'required'],
            ['verifyCode', 'captcha'],
        ];
    }

    public function saveReview() {

        if ($this->validate()) {
            $model = new Reviews();

                $model->firstname = $this->firstname;
                $model->lastname = $this->lastname;
                $model->text = $this->text;
                $model->timestamp = strtotime('midnight');
                $model->disabled = 1;

            if ($model->validate()) {
                $model->save();
                return true;
            }
        }

        return false;
    }
}
