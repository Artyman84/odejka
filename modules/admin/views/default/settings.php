<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $settings app\models\Settings */
/* @var $admin app\models\Admins*/

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use mihaildev\ckeditor\CKEditor;
use yii\widgets\Pjax;


Pjax::begin(['id' => 'js-content-article', 'options' => ['tag' => 'div', 'class' => 'sectionWrapper flex']]); ?>
<section class="content">
    <article>
        <h1>Настройки</h1>
        <div class="form formComplex">

            <?php $form = ActiveForm::begin([
                'options' => ['class' => false, 'data-pjax' => true],
                'layout' => 'horizontal',
                'fieldConfig' => [
                    'template' => "<div class=\"formItem\">{input}{label}</div>",
                    'labelOptions' => ['class' => false],
                    'options' => [
                        'tag' => false
                    ]
                ],
            ])?>


            <div class="flex flexBetween formCol2">
                <?= $form->field($admin, 'username')->textInput(); ?>
                <?= $form->field($admin, 'password')->passwordInput(['value' => '']); ?>
            </div>

            <div class="flex flexBetween formCol2">
                <?= $form->field($settings, 'email')->textInput(); ?>
                <?= $form->field($settings, 'phone')->textInput(); ?>
            </div>
            <div class="flex flexBetween formCol2">
                <?= $form->field($settings, 'address')->textarea(['class' => 'multiline']); ?>
                <?= $form->field($settings, 'chart')->textarea(['class' => 'multiline']); ?>
            </div>
            <div class="flex flexBetween formCol3">
                <?= $form->field($settings, 'vk')->textInput(); ?>
                <?= $form->field($settings, 'facebook')->textInput(); ?>
                <?= $form->field($settings, 'inst')->textInput(); ?>
            </div>
            <div class="formItem">
                <?= $form->field($settings, 'schedule')->widget(CKEditor::className(),[
                    'editorOptions' => [
                        'height' => 200,
                        'preset' => 'basic', //разработанны стандартные настройки basic, standard, full данную возможность не обязательно использовать
                        'inline' => false, //по умолчанию false
                    ],
                ]);?>

                <? // $form->field($settings, 'schedule')->textarea(['class' => 'multiline']); ?>
            </div>
            <div>
                <?= Html::submitButton('Сохранить');?>
                <?if( ($message = Yii::$app->session->getFlash('success')) ){?>
                    <span class="formNote success"><?= $message?></span>
                <? } ?>
            </div>

            <?php ActiveForm::end();?>

        </div>
    </article>
</section>
<? Pjax::end(); ?>