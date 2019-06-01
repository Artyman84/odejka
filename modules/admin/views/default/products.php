<?
/* @var $this yii\web\View */
/* @var $products array */
/* @var $page int */
/* @var $pages \yii\data\Pagination*/
/* @var $filter array*/

use \app\widgets\Pager;
use \yii\helpers\Url;
use \yii\helpers\Html;
use \yii\helpers\Json;
use \yii\widgets\ActiveForm;
use \app\models\Clothes;
use \app\models\Products;
use \app\assets\CropitAsset;
use yii\jui\Sortable;
use yii\widgets\Pjax;

CropitAsset::register($this);

Pjax::begin(['id' => 'js-content-article', 'options' => ['tag' => 'div', 'class' => 'sectionWrapper flex']]); ?>
<section class="content">
    <article>
            <h1>Наши работы</h1>
            <div class="navigation flex flexBetween">
                <button class="t-open-editing">Добавить работу</button>
            </div>

            <div class="marks noVisited" url="<?=Url::to(['products'])?>">
                <? $clothes = Clothes::find()->orderBy('name asc')->all(); ?>
                <? foreach ($clothes as $clothing) {?>
                    <a href="javascript://" id="clothing_<?=$clothing->id?>" class="<?=in_array($clothing->id, $filter) ? '' : 'off gray'?> js-clothes-filter"><?=Html::encode($clothing->name)?></a>
                <? } ?>
            </div>

            <div class="formItem images">
                <ul class="reset flex flexWrap noMove" model="products">
                    <? foreach ($products as $product) {
                        $images = $product->productImages;
                        $json_data = ['id' => $product->id, 'clothes_id' => $product->clothes_id, 'description' => $product->description, 'image' => $images];?>
                        <li class="js-model-object">
                            <div class="icon iconClose" onclick="window.okConfirm(function(){ $.pjax({container: '#js-content-article', timeout: 10000, url: '<?=Url::to(['/admin/products', 'id' => $product->id, 'page' => $page, 'action' => 'delete'])?>', 'push': 0}); });"></div>
                            <div class="icon iconEdit js-edit-data"></div>
                            <?=Html::img('@web/img/products/' . (!empty($images) ? $images[0]->image : '../no_photo.png'), ['alt' => '', 'title' => ''])?>
                            <span class="js-json-data" style="display: none;"><?= Json::encode($json_data)?></span>
                        </li>
                    <? } ?>
                </ul>

            </div>

            <?= Pager::widget([
                'pagination' => $pages,
                'pjax_id' => 'js-content-article'
            ]); ?>

    </article>
</section>

<section class="formEdit flex flexDirCol flexBetween">
    <div class="icon iconClose js-close-edit-section"></div>
    <h2>Добавление работы</h2>


    <? $model = new Products(); ?>
    <? $form = ActiveForm::begin([
        'id' => 'backend-form',
        'action' => Url::to(['products']),
        'enableClientValidation' => true,
        'options' => ['class' => 'formContent form', 'data-pjax' => true, 'data-push' => false],
        'fieldConfig' => [
            'template' => "<div class=\"formItem\">{input}</div>",
            'options' => [
                'tag' => false
            ]
        ],
    ]); ?>

    <?= Html::hiddenInput('action', 'save'); ?>

    <?= Html::hiddenInput('page', $page); ?>

    <?= $form->field($model, 'id', ['template' => '{input}'])->hiddenInput(); ?>

    <?=$form->field($model, 'image[]', ['template' => '<div class="formItem js-image-block" style="display: none;">{input}</div>'])->fileInput(['accept' => 'image/png, image/jpeg', 'class' => 'js-load-image', 'multiple' => true]) ?>

    <div class="formItem images js-crop-images">
        <ul class="reset flex flexWrap js-images"></ul>
    </div>

    <div class="formItem"><?= Html::button('Выбрать фото', ['class' => 'js-upload-image'])?></div>

    <?= $form->field($model, 'description')->textarea(['class' => 'multiline', 'placeholder' => 'Описание']); ?>
    <?= $form->field($model, 'clothes_id')->dropDownList(\yii\helpers\ArrayHelper::map($clothes, 'id', 'name'), ['prompt' => 'Тип одежды']); ?>

    <? ActiveForm::end() ?>

    <div>
        <?= Html::submitButton('Сохранить', ['onclick' => '$("#backend-form").submit();'])?>
        <span class="formNote"></span>
    </div>

</section>