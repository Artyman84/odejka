<?php
/* @var $this yii\web\View */
/* @var $reviews array */

use \yii\helpers\Html;
use \yii\widgets\ActiveForm;
use \yii\helpers\Url;
use yii\captcha\Captcha;
use app\models\ReviewForm;
use \yii\widgets\Pjax;


Pjax::begin(['id' => 'js-frontend-content', 'options' => ['tag' => 'div', 'class' => 'layoutFix']]); ?>
<div class="title mainTitle flex flexJCSB flexAIC">
    <h1>Отзывы клиентов</h1>
    <nav class="noVisited js-open-review-form"><a href="#" class="uppercase">Оставить отзыв</a></nav>
</div>
<section class="feedbackForm hide">
    <? $model = new ReviewForm(); ?>
    <? $form = ActiveForm::begin([
        'id' => 'frontend-form',
        'action' => Url::to(['feedback']),
        'enableClientValidation' => true,
        'options' => ['class' => 'formContent form', 'data-pjax' => true],
        'fieldConfig' => [
            'template' => "<div class=\"column col2\">{input}</div>",
            'options' => [
                'tag' => false
            ]
        ],
    ]); ?>


        <div class="iconClose"></div>
        <h3>Поделитесь мнением</h3>

        <div class="row flex flexJCSB">
            <?=$form->field($model, 'firstname')->textInput(['placeholder' => 'Имя']); ?>
            <?=$form->field($model, 'lastname')->textInput(['placeholder' => 'Фамилия']); ?>
        </div>

        <div class="row">
            <?=$form->field($model, 'text', ['template' => '{input}'])->textarea(['placeholder' => 'Сообщение', 'class' => 'multiline']); ?>
        </div>

        <div class="row flex flexJCSB">
            <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
                'template' => '<div class="captcha flex flexAIC">{image}</div>{input}',
                'options' => ['class' => false, 'placeholder' => 'Введите символы слева'],
                'imageOptions' => ['class' => 'captchaImg']
            ]) ?>

            <div class="column col2 flex flexJCFE">
                <div class="validation flex flexAIC">
                    <div class="error hide">Заполните форму</div>
                    <div class="success hide">Отзыв отправлен</div>
                </div>
                <?= Html::submitButton('Сохранить')?>
            </div>

        </div>
    <?php ActiveForm::end(); ?>

</section>

<? if( !empty( $reviews ) ) {?>
    <ul class="reset cardsBasic cols2 flex flexWrap">
        <?foreach ($reviews as $review){?>
            <li>
                <div>
                    <div class="title"><h3><?=Html::encode($review->firstname . ' ' . $review->lastname)?></h3></div>
                    <div class="description">
                        <p><?=nl2br(Html::encode($review->text))?></p>
                    </div>
                </div>
            </li>
        <? } ?>
    </ul>
<? } ?>
<? Pjax::end(); ?>
