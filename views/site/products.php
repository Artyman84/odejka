<?php
/* @var $this yii\web\View */
/* @var $filter array*/

use \app\models\Clothes;
use \app\models\Products;
use \yii\helpers\Html;
use yii\helpers\Url;
use \yii\widgets\Pjax;

Pjax::begin(['id' => 'js-frontend-content', 'options' => ['tag' => 'div', 'class' => 'layoutFix']]);

/******* UNCOMMENT For pagination! *******/

//use app\components\helper\Helper;
//use app\widgets\Pager;
//$page = Yii::$app->request->get('page', Yii::$app->request->post('page', 0));
//
//list($models, $pages) = Helper::pagination('products',
//    $page,
//    null,
//    'admin/products',
//    [
//        'with' => ['productImages' => function ($query) { $query->orderBy(['position' => SORT_ASC]); }, 'clothes'],
//        'order' => ['id' => SORT_DESC],
//        'where' => $filter != null ? ['clothes_id' => (array)$filter] : null
//    ]
//);

$models = Products::find()
->with(['productImages' => function ($query) { $query->orderBy(['position' => SORT_ASC]); }, 'clothes'])
->orderBy(['id' => SORT_DESC])
->where($filter != null ? ['clothes_id' => (array)$filter] : null)
->all();


?>

<div class="title mainTitle flex flexJCSB flexAIC">
    <h1>Наши работы</h1>
</div>

<? if( !empty($models) ) {?>
    <section class="tags noVisited">
        <ul class="reset flex flexWrap uppercase" url="<?=Url::to(['products'])?>">
            <? $clothes = Clothes::find()->orderBy('name asc')->all(); ?>
            <? $is_empty = empty($filter); ?>
            <? foreach ($clothes as $clothing) {?>
                <li id="clothing_<?=$clothing->id?>" class="js-clothes-filter <?=$is_empty || in_array($clothing->id, $filter) ? '' : 'off'?>" ><a href="javascript://"><?=Html::encode($clothing->name)?></a>
            <? } ?>
        </ul>
    </section>

    <ul class="reset cardsBasic cardsPortfolio flex flexWrap js-portfolio-card">
        <? foreach ($models as $j => $product) {
            $images = $product->productImages;
            ?><li <?=!$j ? 'class="cardAccent"' : ''?>>
                <div class="sideFront">
                    <figure>
                        <? $markers = ''; ?>
                        <? foreach ($images as $i => $image) {
                            echo Html::img('@web/img/products/' . $image->image, ['alt' => '', 'title' => '', 'class' => 'photo' . (!$i ? ' showItem' : '')]);
                            $markers .= '<span class="' . (!$i ? 'current' : '') . '"></span>';
                        } ?>
                    </figure>
                    <nav>
                        <? if($product->description) {?>
                            <div class="descriptionLink aCenter linksWhite"><a href="#" class="showDesc js-show-desc">Описание</a></div>
                        <? } ?>
                        <div class="slideshowMarkers flex flexJCC js-marker-slides"><?=$markers?></div>
                        <div class="slideshowArrows flex flexJCSB">
                            <div class="leftArrow js-left-arrow"><span class="iconArrowLeft"></span></div>
                            <div class="rightArrow js-right-arrow"><span class="iconArrowRight"></span></div>
                        </div>
                        <div class="tag uppercase aCenter linksWhite"><a href="#" class="js-clothes-name" id="<?=$product->clothes_id?>"><?=Html::encode($product->clothes->name)?></a></div>
                    </nav>
                </div>

                <? if($product->description) {?>
                    <div class="sideBack hide">
                        <div class="descriptionContent flex flexDC">
                            <div class="iconClose js-close-description"></div>
                            <h4 class="aCenter">Описание</h4>
                            <div class="content scroll"><?=$product->description?></div>
                        </div>
                    </div>
                <? } ?>
            </li><?
        }?>
    </ul>
<? } ?>
<?

/******* UNCOMMENT For pagination! *******/

//    echo Pager::widget([
//        'pagination' => $pages,
//        'pjax_id' => 'js-frontend-content'
//    ]);
?>

<? Pjax::end(); ?>