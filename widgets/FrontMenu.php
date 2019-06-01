<?php

namespace app\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Url;
use yii\bootstrap\Html;


class FrontMenu extends Widget {

    public $is_footer=false;

    public function init() {
        parent::init();
    }

    public function run() {
        $action = Yii::$app->controller->action->id;

        $items = [
            '<li item="atelier" ' . ($action == 'atelier' ? 'class="current"' : '') . '><a href="' . Url::to(['/atelier']) . '">Ателье</a></li>',
            '<li item="products" ' . ($action == 'products' ? 'class="current"' : '') . '><a href="' . Url::to(['/products']) . '">Наши работы</a></li>',
            '<li item="pricelist" ' . ($action == 'pricelist' ? 'class="current"' : '') . '><a href="' . Url::to(['/pricelist']) . '">Услуги и цены</a></li>',
            '<li item="feedback" ' . ($action == 'feedback' ? 'class="current"' : '') . '><a href="' . Url::to(['/feedback']) . '">Отзывы</a></li>',
            '<li item="blog" ' . ($action == 'blog' ? 'class="current"' : '') . '><a href="' . Url::to(['/blog']) . '">Блог</a></li>'
        ];

        if( $this->is_footer ){
            echo Html::beginTag('ul', ['class' => 'reset flex flexDC js-frontend-menu']);
            echo $items[0], $items[1], $items[2];
            echo Html::endTag('ul');

            echo Html::beginTag('ul', ['class' => 'reset flex flexDC js-frontend-menu']);
            echo $items[3], $items[4];
            echo Html::endTag('ul');

        } else {
            echo Html::beginTag('ul', ['class' => 'reset flex linksWhite noVisited js-frontend-menu']);
            echo implode("", $items);
            echo Html::endTag('ul');
        }
    }

}