<?php
/**
 * Created by PhpStorm.
 * User: Arty
 * Date: 22.02.2018
 * Time: 18:23
 */

namespace app\widgets;

use Yii;
use yii\base\Widget;
use yii\bootstrap\Html;
use yii\data\Pagination;

class Pager extends Widget {

    /**
     * @var Pagination the pagination object that this pager is associated with.
     * You must set this property in order to make LinkPager work.
     */
    public $pagination;

    public $pjax_id;

    private $is_backend;

    public function init() {
        parent::init();
        $this->is_backend = Yii::$app->controller->module->id === 'admin';
    }

    public function run() {
        if( $this->pagination->totalCount > $this->pagination->pageSize) {

            $last_page = ceil($this->pagination->totalCount/$this->pagination->pageSize);
            $page = $this->pagination->page;

            if( $this->is_backend ) {
               echo Html::beginTag('div', ['class' => 'pagination flex', 'id' => $this->getId()]);

                   echo Html::beginTag('div', ['class' => 'buttonsMultiple flex']);
                       echo Html::button('〈', ['title' => 'Предыдущая страница', 'disabled' => $page - 1 < 0, 'onclick' => '$.pjax.reload({"container": "#' . $this->pjax_id . '", "url": "' . $this->pagination->createUrl($page - 1) . '"});']);
                       echo Html::button('〉', ['title' => 'Следующая страница', 'class' => 'js-pager-next', 'disabled' => ($page + 1) * $this->pagination->pageSize >= $this->pagination->totalCount, 'onclick' => '$.pjax.reload({"container": "#' . $this->pjax_id . '", "url": "' . $this->pagination->createUrl($page + 1) . '"});']);
                   echo Html::endTag('div');

                   echo Html::beginForm('/' . Yii::$app->getRequest()->pathInfo, 'get', ['id' => 'pagerForm', 'data-pjax' => '']);
                       echo Html::textInput('page', '', ['placeholder' => ($page + 1) . ' из ' . $last_page, 'class' => 't-pagination-input', 'onkeyup' => 'if( !parseInt(this.value) ) this.value = ""']);
                   echo Html::endForm();

                   echo Html::beginTag('div', ['class' => 'buttonsMultiple flex']);
                       echo Html::button('《', ['title' => 'Первые записи', 'disabled' => $page - 1 < 0, 'onclick' => '$.pjax.reload({"container": "#' . $this->pjax_id . '", "url": "' . $this->pagination->createUrl(0) . '"});']);
                       echo Html::button('》', ['title' => 'Последние записи', 'disabled' => ($page + 1) * $this->pagination->pageSize >= $this->pagination->totalCount, 'onclick' => '$.pjax.reload({"container": "#' . $this->pjax_id . '", "url": "' . $this->pagination->createUrl($last_page) . '"});']);
                   echo Html::endTag('div');

               echo Html::endTag('div');

            } else {
                echo Html::beginTag('div', ['class' => 'pagination flex flexJCSB', 'id' => $this->getId()]);

                    echo Html::beginTag('div', ['class' => 'buttonsMultiply flex']);
                        echo Html::button('<span>&larr;</span> Сюда', ['title' => 'Предыдущая страница', 'disabled' => $page - 1 < 0, 'onclick' => '$.pjax.reload({"container": "#' . $this->pjax_id . '", "url": "' . $this->pagination->createUrl($page - 1) . '"});']);
                        echo Html::button('Туда <span>&rarr;</span>', ['title' => 'Следующая страница', 'class' => 'js-pager-next', 'disabled' => ($page + 1) * $this->pagination->pageSize >= $this->pagination->totalCount, 'onclick' => '$.pjax.reload({"container": "#' . $this->pjax_id . '", "url": "' . $this->pagination->createUrl($page + 1) . '"});']);
                    echo Html::endTag('div');

                    echo Html::beginForm('/' . Yii::$app->getRequest()->pathInfo, 'get', ['id' => 'pagerForm', 'data-pjax' => '']);
                        echo Html::textInput('page', '', ['placeholder' => ($page + 1) . ' из ' . $last_page, 'class' => 't-pagination-input', 'onkeyup' => 'if( !parseInt(this.value) ) this.value = ""']);
                    echo Html::endForm();

                    echo Html::beginTag('div', ['class' => 'buttonsMultiply flex']);
                        echo Html::button('Старые', ['title' => 'Первые записи', 'disabled' => $page - 1 < 0, 'onclick' => '$.pjax.reload({"container": "#' . $this->pjax_id . '", "url": "' . $this->pagination->createUrl(0) . '"});']);
                        echo Html::button('Новые', ['title' => 'Последние записи', 'disabled' => ($page + 1) * $this->pagination->pageSize >= $this->pagination->totalCount, 'onclick' => '$.pjax.reload({"container": "#' . $this->pjax_id . '", "url": "' . $this->pagination->createUrl($last_page) . '"});']);
                    echo Html::endTag('div');

                echo Html::endTag('div');

            }

        }
    }

}