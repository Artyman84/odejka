<?php
/* @var $this yii\web\View */
/* @var $pages Pagination */

use \yii\helpers\Html;
use \app\components\helper\Helper;
use \yii\helpers\Url;
use app\widgets\Pager;
use \yii\widgets\Pjax;

$main = isset($models[0]) ? $models[0] : null;
unset($models[0]);

Pjax::begin(['id' => 'js-frontend-content', 'options' => ['tag' => 'div', 'class' => 'layoutFix']]); ?>
<div class="title mainTitle"><h1>Публикации в&nbsp;блоге</h1></div>

<? if( !empty( $main ) ) {?>
    <section class="mainPublication borderDecorated">
        <article>
            <time class="date"><?= Helper::translateMonth(Yii::$app->formatter->asDate($main->timestamp), true)?></time>
            <div class="title"><h3><?= Html::a(Html::encode($main->title), Url::to(['/blog-post', 'id' => $main->id]), ['class' => 'js-post-link'])?></h3></div>
            <div class="description"><?= nl2br($main->introductory_text)?></div>
        </article>
    </section>
<? } ?>

<? if( !empty( $models ) ) {?>
    <ul class="reset cardsBasic cols2 flex flexWrap">
        <? foreach ($models as $model){?>
            <li>
                <div>
                    <time class="date"><?= Helper::translateMonth(Yii::$app->formatter->asDate($model->timestamp), true)?></time>
                    <div class="title"><h3><?= Html::a(Html::encode($model->title), Url::to(['/blog-post', 'id' => $model->id]), ['class' => 'js-post-link'])?></h3></div>
                    <div class="description"><?= nl2br($model->introductory_text)?></div>
                </div>
            </li>
        <? } ?>
    </ul>

    <? echo Pager::widget([
            'pagination' => $pages,
            'pjax_id' => 'js-frontend-content'
    ]); ?>

<? } ?>
<? Pjax::end(); ?>