<?php
/**
 * Created by PhpStorm.
 * User: Arty
 * Date: 21.06.2018
 * Time: 19:50
 */

use app\components\helper\Helper;
use yii\helpers\Html;
use \yii\helpers\Url;

$post = $posts[$id];
$posts = array_values($posts);

$next_post = $prev_post = null;
foreach ($posts as $i => $_post){
    if( $_post->id == $id ){
        if( $i > 0 ){
            $prev_post = $posts[$i-1];
        }

        if( $i < count($posts) - 1 ){
            $next_post = $posts[$i+1];
        }
        break;
    }
}
?>
<div class="layoutFix">
    <div class="title mainTitle"><h1><?= Html::encode($post->title)?></h1></div>
    <time class="date mainDate"><?= Helper::translateMonth(Yii::$app->formatter->asDate($post->timestamp), true)?></time>
    <section class="mainPublication borderDecorated">
        <article>
            <div class="description">
                <?= nl2br($post->text)?>
            </div>
        </article>
    </section>

    <? if( $prev_post || $next_post ) {?>
        <ul class="reset cardsBasic cols2 flex flexWrap">
            <li class="title"><h2>Ранее</h2></li>
            <li class="title"><h2>Далее</h2></li>
            <li>
                <? if( $prev_post ) {?>
                    <div>
                        <time class="date"><?= Helper::translateMonth(Yii::$app->formatter->asDate($prev_post->timestamp), true)?></time>
                        <div class="title"><h3><?= Html::a(Html::encode($prev_post->title), Url::to(['/blog-post', 'id' => $prev_post->id]), ['class' => 'js-post-link'])?></h3></div>
                        <div class="description"><?= nl2br($prev_post->introductory_text)?></div>
                    </div>
                <? } ?>
            </li>
            <li>
                <? if( $next_post ) {?>
                    <div>
                        <time class="date"><?= Helper::translateMonth(Yii::$app->formatter->asDate($next_post->timestamp), true)?></time>
                        <div class="title"><h3><?= Html::a(Html::encode($next_post->title), Url::to(['/blog-post', 'id' => $next_post->id]), ['class' => 'js-post-link'])?></h3></div>
                        <div class="description"><?= nl2br($next_post->introductory_text)?></div>
                    </div>
                <? } ?>
            </li>
        </ul>
    <? } ?>
</div>
