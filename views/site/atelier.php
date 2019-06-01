<?php
/* @var $this yii\web\View */
/* @var $settings app\models\Settings */
/* @var $staff app\models\Staff */

use yii\helpers\Html;
use \yii\widgets\Pjax;

Pjax::begin(['id' => 'js-frontend-content', 'options' => ['tag' => 'div', 'class' => 'layoutFix']]); ?>
<div class="title mainTitle flex flexJCSB flexAIC">
    <h1>Ателье</h1>
</div>
<section class="about">
    <div class="description">
        <?= $settings->legend?>
    </div>
</section>

<? if( !empty($staff) ) {?>
    <div class="title mainTitle flex flexJCSB flexAIC">
        <h2>Коллектив</h2>
    </div>
    <ul class="reset cardsBasic cols3 cardsPeople flex flexWrap">
        <? foreach ($staff as $employee) { ?>
            <li>
                <figure>
                    <div class="image"><?= Html::img('@web/img/staff/' . $employee->photo, ['alt' => '', 'title' => ''])?></div>
                    <figcaption>
                        <h3><?= Html::encode($employee->full_name)?></h3>
                        <p><?= Html::encode($employee->position)?></p>
                    </figcaption>
                </figure>
            </li>
        <? } ?>
    </ul>
<? } ?>

<div class="title mainTitle flex flexJCSB flexAIC">
    <h2 class="js-atelier-coords">Координаты</h2>
</div>
<section class="contacts">
    <div class="flex flexJCSB">
        <div class="column col2 flex flexDC flexJCSB description">
            <div class="info">
                <p><?= nl2br($settings->address)?></p>
                <p><b><?= $settings->phone?></b></p>
            </div>
            <div class="socials">
                <p><?= Html::a('vk профиль Вконтакте', $settings->vk, ['target' => '_blank']);?></p>
                <p><?= Html::a('f профиль на Фейсбуке', $settings->facebook, ['target' => '_blank'])?></p>
                <p><?= Html::a('in лента в Инстаграм', $settings->inst, ['target' => '_blank'])?></p>
            </div>
        </div>
        <div class="column col2 map">
            <iframe src="<?= $settings->chart?>" width="100%" height="100%" frameborder="0"></iframe>
        </div>
    </div>
</section>
<?

$script = <<< JS
    (function($){
        $(function(){
            if( $to_loc == 1 ){            
                window.scrollTo(0, $(".js-atelier-coords").offset().top);               
            }
        });
    })(jQuery);
JS;
$this->registerJs($script);

Pjax::end(); ?>
