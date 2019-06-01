<?php
/* @var $this yii\web\View */
/* @var $filter array*/


use \app\models\Prices;
use \yii\helpers\Html;
use \app\components\helper\Helper;
use \yii\widgets\Pjax;

Pjax::begin(['id' => 'js-frontend-content', 'options' => ['tag' => 'div', 'class' => 'layoutFix']]);

$models = Prices::find()->orderBy(['id' => SORT_DESC])->where('disabled = 0')->orderBy(['id' => SORT_DESC])->all();
?>

<div class="title mainTitle flex flexJCSB flexAIC">
    <h1>Услуги и&nbsp;цены</h1>
</div>

<? if( !empty( $models ) ) {?>
    <ul class="reset cardsBasic cols2 flex flexWrap">
        <? foreach ($models as $model) {?>
            <li>
                <div>
                    <div class="title"><h3><?=Html::encode($model->title)?></h3></div>
                    <div class="description">
                        <?=nl2br($model->description)?>
                    </div>
                </div>
                <div class="price">От&nbsp;<?=Helper::normalizePrice($model->price)?>&nbsp;₽</div>
            </li>
        <? } ?>
    </ul>
<? } ?>
<? Pjax::end(); ?>