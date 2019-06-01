<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\Settings */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use mihaildev\ckeditor\CKEditor;
use yii\widgets\Pjax;


Pjax::begin(['id' => 'js-content-article', 'options' => ['tag' => 'div', 'class' => 'sectionWrapper flex']]); ?>

<section class="content">
    <article>
        <h1>Легенда</h1>
        <div class="form">

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


            <?= $form->field($model, 'legend')->widget(CKEditor::className(),[
                'editorOptions' => [
                    'height' => 300,
                    'preset' => 'basic', //разработанны стандартные настройки basic, standard, full данную возможность не обязательно использовать
                    'inline' => false, //по умолчанию false
                ],
            ]);?>

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