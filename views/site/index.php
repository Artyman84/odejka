<?php
/* @var $this yii\web\View */

use \yii\widgets\Pjax;
use \app\models\Clothes;
use \app\models\Products;
use \app\models\Reviews;
use \app\models\Posts;
use \yii\helpers\Html;
use yii\helpers\Url;
use \app\components\helper\Helper;


Pjax::begin(['id' => 'js-frontend-content', 'options' => ['tag' => 'div', 'class' => 'layoutFix']]); ?>

<? $products = Products::find()
    ->with(['productImages' => function ($query) { $query->orderBy(['position' => SORT_ASC]); }, 'clothes'])
    ->orderBy(['id' => SORT_DESC])
    ->limit(7)
    ->all(); ?>

<? if( !empty( $products ) ) {?>
    <div class="title mainTitle flex flexJCSB flexAIC">
        <h1>Новинки</h1>
        <nav class="noVisited">
            <a href="javascript://" item="products" class="js-view-item uppercase">Посмотреть другие работы</a>
        </nav>
    </div>

    <ul class="reset cardsBasic cardsPortfolio flex flexWrap js-portfolio-card">
        <? foreach ($products as $j => $product) {
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
                    <div class="tag uppercase aCenter linksWhite"><a href="#" class="js-index-clothes-name" id="<?=$product->clothes_id?>"><?=Html::encode($product->clothes->name)?></a></div>
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

<? $posts = Posts::find()->where('disabled = 0')->orderBy(['timestamp' => SORT_DESC])->limit(4)->all(); ?>

<? if( !empty($posts) ) {?>
    <div class="title mainTitle flex flexJCSB flexAIC">
        <h1>Публикации в&nbsp;блоге</h1>
        <nav class="noVisited">
            <a href="javascript://" item="blog" class="js-view-item uppercase">Перейти к остальным публикациям</a>
        </nav>
    </div>
    <?
    $main = $posts[0];
    unset($posts[0]);
    ?>

    <section class="mainPublication borderDecorated">
        <article>
            <time class="date"><?= Helper::translateMonth(Yii::$app->formatter->asDate($main->timestamp), true)?></time>
            <div class="title"><h3><?= Html::a(Html::encode($main->title), Url::to(['/blog-post', 'id' => $main->id]), ['class' => 'js-post-link'])?></h3></div>
            <div class="description"><?= nl2br($main->introductory_text)?></div>
        </article>
    </section>
    <section class="blogLast">
        <ul class="reset cardsBasic cols3 cardsLight flex flexWrap">
            <? foreach ($posts as $post){?>
                <li>
                    <div>
                        <time class="date"><?= Helper::translateMonth(Yii::$app->formatter->asDate($post->timestamp), true)?></time>
                        <div class="title"><h3><?= Html::a(Html::encode($post->title), Url::to(['/blog-post', 'id' => $post->id]), ['class' => 'js-post-link'])?></h3></div>
                    </div>
                </li>
            <? } ?>
        </ul>
    </section>
<? } ?>

<? $reviews = Reviews::find()->where('disabled = 0')->orderBy(['timestamp' => SORT_DESC])->all(); ?>
<? if( !(empty($reviews)) ) {?>
    <div class="title mainTitle flex flexJCSB flexAIC">
        <h1>Отзывы клиентов</h1>
        <nav class="noVisited">
            <a href="javascript://" item="feedback" class="js-view-item uppercase">Посмотреть все отзывы</a>
        </nav>
    </div>

    <section class="slider">
        <div class="sliderCrop">
            <ul class="reset cardsBasic cols2 cardsSlides flex flexWrap js-list-reviews">
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
        </div>
        <div class="slideshowArrows flex flexJCSB">
            <div class="leftArrow js-left-reviews"><span class="iconArrowLeft"></span></div>
            <div class="rightArrow js-right-reviews"><span class="iconArrowRight"></span></div>
        </div>
    </section>
<? } ?>

<? Pjax::end(); ?>