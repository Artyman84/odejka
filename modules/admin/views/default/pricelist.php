<?
/* @var $this yii\web\View */
/* @var $prices array */
/* @var $page int */
/* @var $pages \yii\data\Pagination*/

use app\widgets\Pager;
use \yii\helpers\Url;
use \yii\helpers\Html;
use \yii\helpers\Json;
use \yii\widgets\ActiveForm;
use \app\models\Prices;
use yii\widgets\Pjax;

Pjax::begin(['id' => 'js-content-article', 'options' => ['tag' => 'div', 'class' => 'sectionWrapper flex']]); ?>
<section class="content">
    <article>
            <h1>Услуги и цены</h1>
            <div class="navigation flex flexBetween">
                <button class="t-open-editing">Добавить услугу</button>
            </div>

            <table class="noVisited">
                <tbody model="prices">
                <? foreach ($prices as $price) {?>
                    <tr class="js-model-object">
                        <td>
                            <a href="#" id="<?=$price->id?>" class="js-edit-data" onclick="return false;"><?=Html::encode($price->title)?></a>
                            <div>от <?= $price->price?> ₽</div>
                        </td>
                        <td class="small">
                            <div class="slider">
                                <input type="checkbox" onchange="$.pjax({container: '#js-content-article', url: '<?=Url::to(['/admin/pricelist', 'id' => $price->id, 'action' => 'status', 'page' => $page])?>', 'push': 0}); return false;" id="price_<?=$price->id?>" <?=$price->disabled ? '' : 'checked'?>>
                                <label for="price_<?=$price->id?>"></label>
                            </div>
                        </td>
                        <td class="tableActionRight">
                            <a href="javascript://" onclick="window.okConfirm(function(){ $.pjax({container: '#js-content-article', url: '<?=Url::to(['/admin/pricelist', 'id' => $price->id, 'page' => $page, 'action' => 'delete'])?>', 'push': 0}); });" class="delete"></a>
                        </td>
                        <td class="js-json-data" style="display: none"><?= Json::encode($price)?></td>
                    </tr>
                <? } ?>

                </tbody>
            </table>

            <?= Pager::widget([
                'pagination' => $pages,
                'pjax_id' => 'js-content-article'
            ]); ?>

    </article>
</section>

<section class="formEdit flex flexDirCol flexBetween">
    <div class="icon iconClose js-close-edit-section"></div>
    <h2>Добавление услуги</h2>

    <? $model = new Prices(); ?>
    <? $form = ActiveForm::begin([
        'id' => 'backend-form',
        'action' => Url::to(['pricelist']),
        'enableClientValidation' => true,
        'options' => ['class' => 'formContent form', 'data-pjax' => true],
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

    <?= $form->field($model, 'title')->textInput(['placeholder' => 'Название услуги']); ?>

    <?= $form->field($model, 'description', ['template' => '<div class="formItem">{input}</div>'])->textarea(['class' => 'multiline', 'placeholder' => 'Описание']); ?>

    <?= $form->field($model, 'price')->textInput(['placeholder' => 'Минимальная стоимость в рублях']); ?>

    <? ActiveForm::end() ?>

    <div>
        <?= Html::submitButton('Сохранить', ['onclick' => '$("#backend-form").submit();'])?>
        <span class="formNote"></span>
    </div>

</section>
<? Pjax::end(); ?>