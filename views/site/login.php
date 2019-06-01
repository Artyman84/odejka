<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

?>
<div class="popupBody">
    <div class="popupContent">
        <h2>Идентификация</h2>

        <?php $form = ActiveForm::begin([
            'id' => 'login-form',
            'options' => ['class' => 'form formSimple'],
            'layout' => 'horizontal',
            'enableClientValidation' => true,
            'fieldConfig' => [
                'template' => "<div class=\"formItem\">{input}{label}</div>",
                'labelOptions' => ['class' => false],
                'options' => [
                    'tag' => false
                ]
            ],
        ]); ?>

        <?= $form->field($model, 'username')->textInput(['autofocus' => true]); ?>

        <?= $form->field($model, 'password')->passwordInput() ?>

        <div>
            <?= Html::submitButton('Войти') ?>
            <? $has_errors = !empty($model->errors);?>
            <span class="formNote error <?=$has_errors ? '' : 'hidden'?>"><?=$has_errors ? $model->errors['password'][0] : ''?></span>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>

<?php
$JS = <<<JS

$('#login-form').on('afterValidate', function(event, messages, errorAttributes) {

    var message = "";
    if(errorAttributes.length == 2){
        message = "Пожалуйста заполните форму";
    } else if(errorAttributes.length == 1){
        if(errorAttributes[0].name == "username"){
            message = messages['loginform-username'][0];
        } else {
            message = messages['loginform-password'][0];
        }
    }
    if( message ){
        $("span.error").text(message).removeClass("hidden");
    } else {
        $("span.error").text("").addClass("hidden");
    }    
});

$('#login-form').on('afterValidateAttribute', function(event, attribute, messages) {
    if( $.trim(attribute.value) ){
        $("#" + attribute.id).attr("invalid", null);
    } else {
        $("#" + attribute.id).attr("invalid", true);
    }    
});
JS;

$this->registerJs($JS);

