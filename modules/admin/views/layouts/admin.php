<?php

/* @var $this \yii\web\View */
/* @var $content string */

//use app\widgets\Alert;
use yii\helpers\Html;
use app\assets\AdminAsset;
use yii\helpers\Url;
use \yii\widgets\Pjax;

AdminAsset::register($this);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,400i,800,800i" rel="stylesheet">
    <link rel="shortcut icon" href="<?= Url::base()?>/img/favicon.ico" type="image/x-icon" />
    <?= Html::csrfMetaTags() ?>
    <title>Админка | <?= Html::encode(Yii::$app->name) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<?php $action = Yii::$app->controller->action->id; ?>
<?php $new_reviews = Yii::$app->db->createCommand('SELECT COUNT(*) FROM {{%reviews}} WHERE disabled = 1')->queryScalar(); ?>

<div class="all flex scrollbars <?=Yii::$app->view->params['editing']?>">

    <nav class="menu white flex flexDirCol noVisited flexBetween">
        <div class="logo"><a href="<?=Url::to(['/admin/index'])?>"><?=Html::img(Url::base() . '/img/logo_admin.jpg', ['alt' => '', 'title' => ''])?></a></div>
        <ul class="reset">
            <li><span>Ателье</span>
                <ul>
                    <li <?php echo $action == 'index' ? 'class="current"' : ''?>><a href="<?=Url::to(['/admin/index'])?>">Легенда</a></li>
                    <li <?php echo $action == 'staff' ? 'class="current"' : ''?>><a href="<?=Url::to(['/admin/staff'])?>">Коллектив</a></li>
                </ul>
            </li>
            <li <?php echo $action == 'pricelist' ? 'class="current"' : ''?>><a href="<?=Url::to(['/admin/pricelist'])?>">Услуги и цены</a></li>
            <li <?php echo $action == 'products' ? 'class="current"' : ''?>><a href="<?=Url::to(['/admin/products'])?>">Наши работы</a></li>
            <li <?php echo $action == 'blog' ? 'class="current"' : ''?>><a href="<?=Url::to(['/admin/blog'])?>">Блог</a></li>
            <li <?php echo $action == 'feedback' ? 'class="current"' : ''?>><a href="<?=Url::to(['/admin/feedback'])?>">Отзывы<span class="counter"><? Pjax::begin(['id' => 'count_of_new_reviews']); echo $new_reviews > 0 ? $new_reviews : ''; Pjax::end();?></span></a></li>
            <li <?php echo $action == 'settings' ? 'class="current"' : ''?>><a href="<?=Url::to(['/admin/settings'])?>">Настройки</a></li>
        </ul>
        <a href="<?=Url::to(['/site/logout'])?>" class="logout">Выйти</a>
    </nav>

    <?= $content ?>

</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
