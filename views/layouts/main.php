<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use app\assets\FrontendAsset;
use yii\helpers\Url;
use \app\models\Settings;
use \app\components\helper\Helper;
use \app\widgets\FrontMenu;

FrontendAsset::register($this);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,700,800&amp;subset=cyrillic" rel="stylesheet">
    <link rel="shortcut icon" href="<?= Url::base()?>/img/favicon.ico" type="image/x-icon" />
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode(Yii::$app->name) ?></title>
    <?php $this->head()?>
</head>
<body>
<?php $this->beginBody() ?>
<?php $action = Yii::$app->controller->action->id; ?>
<?php $settings = Settings::findOne(1); ?>

<section class="mainCover" <?=($action == 'index' ? '' : 'style="display:none;"')?>>
    <div class="decor">
        <figure class="f1-1"></figure>
        <figure class="f1-2"></figure>
        <figure class="f1-3"></figure>
        <figure class="f1-4"></figure>
        <figure class="f1-5"></figure>

        <figure class="f2-2"></figure>
        <figure class="f2-4"></figure>
        <figure class="f2-5"></figure>

        <figure class="f3-2"></figure>
        <figure class="f3-4"></figure>

        <figure class="f4-2"></figure>
        <figure class="f4-4"></figure>
        <figure class="f4-5"></figure>

        <figure class="f5-1"></figure>
        <figure class="f5-2"></figure>
        <figure class="f5-3"></figure>
        <figure class="f5-4"></figure>
        <figure class="f5-5"></figure>


        <div class="d1-1"></div>
        <div class="d1-2"></div>
        <div class="d1-3"></div>
        <div class="d1-4"></div>

        <div class="d2-2"></div>
        <div class="d2-3"></div>
        <div class="d2-4"></div>

        <div class="d3-2"></div>
        <div class="d3-3"></div>
        <div class="d3-4"></div>

        <div class="d4-1"></div>
        <div class="d4-2"></div>
        <div class="d4-3"></div>
        <div class="d4-4"></div>
    </div>
    <header class="aCenter">
        <div class="logotype"><?= Html::a(Html::img('@web/img/logo.svg'), Url::to(['/index']))?></div>
        <nav>
            <div class="layoutFix">
                <?= FrontMenu::widget();?>
            </div>
        </nav>
    </header>
    <div class="info aCenter">
        <h1>Кожа, текстиль, мех, трикотаж</h1>
        <p>пошив и перекрой, подгонка по фигуре, замена подкладки,<br> замена молний, ремонт любой сложности</p>
    </div>
    <footer class="linksWhite noVisited flex flexDC aCenter">
        <div class="flex flexDC flexJCSB">
            <div class="socials flex flexJCC">
                <? Helper::socialButtons($settings); ?>
            </div>
            <div>
                <p><b><?=$settings->phone?></b></p>
                <p><?=nl2br($settings->address)?></p>
            </div>
        </div>
    </footer>
</section>


<!-- Header -->
<header class="mainHeader">
    <section>
        <div class="layoutFix flex flexJCSB">
            <div class="logotype"><?= Html::a(Html::img('@web/img/logo.svg'), Url::to(['/index']))?></div>
            <div class="info linksWhite noVisited flex flexJCSB">
                <div class="flex flexDC flexJCSB">
                    <div><?=nl2br($settings->address)?></div>
                    <div><a href="<?=Url::to(['/atelier', 'to_loc' => 1])?>" class="js-atelier-location">Как добраться</a></div>
                </div>
                <div class="flex flexDC flexJCSB">
                    <div><b><?=$settings->phone?></b></div>
                    <div class="socials flex flexJCSB">
                        <? Helper::socialButtons($settings); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <nav>
        <div class="layoutFix">
            <?= FrontMenu::widget();?>
        </div>
    </nav>
</header>
<!-- End of Header -->

<main class="mainContent">
    <section>
        <?= $content ?>
    </section>
</main>


<!-- Footer -->
<footer class="mainFooter">
    <section>
        <div class="layoutFix flex flexJCSB">
            <nav class="menu linksWhite noVisited flex flexJCSB">
                <?= FrontMenu::widget(['is_footer' => true]);?>
            </nav>
            <div class="info flex flexJCSB">
                <div class="schedule">
                    <?= $settings->schedule?>
                </div>
                <div class="linksWhite flex flexDC flexJCSB">
                    <div><?=nl2br($settings->address)?></div>
                    <div><b><?=Html::encode($settings->phone)?></b></div>
                    <div class="socials flex flexJCSB">
                        <? Helper::socialButtons($settings); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <hr>
    <section>
        <div class="layoutFix flex flexJCSB">
            <div class="copyright">&copy; 2017&mdash;<?= date('Y')?> Ателье &laquo;Одёжка&raquo;</div>
            <div class="developer linksWhite">Создано в&nbsp;<a href="mailto:info@itlogic.info">ITLogic</a></div>
        </div>
    </section>
</footer>
<!-- End of Footer -->

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
