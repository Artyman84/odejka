<?
/* @var $this yii\web\View */
/* @var $staff array */
/* @var $page int */
/* @var $pages \yii\data\Pagination*/

use app\widgets\Pager;
use \yii\helpers\Url;
use \yii\helpers\Html;
use \yii\helpers\Json;
use \yii\widgets\ActiveForm;
use \app\models\Staff;
use yii\widgets\Pjax;
use app\assets\CropitAsset;

CropitAsset::register($this);

Pjax::begin(['id' => 'js-content-article', 'options' => ['tag' => 'div', 'class' => 'sectionWrapper flex']]); ?>
<section class="content">
    <article>
        <h1>Коллектив</h1>
        <div class="navigation flex flexBetween">
            <button class="t-open-editing">Добавить сотрудника</button>
        </div>

        <table class="noVisited">
            <tbody model="staff">
            <? foreach ($staff as $employee) {?>
                <tr class="js-model-object">
                    <td class="small userpic"><?=Html::img('@web/img/staff/' . $employee->photo, ['alt' => '', 'title' => ''])?></td>
                    <td>
                        <a href="#" id="<?=$employee->id?>" class="js-edit-data" onclick="return false;"><?=Html::encode($employee->full_name)?></a>
                        <div><?= $employee->position?></div>
                    </td>
                    <td class="small">
                        <div class="slider">
                            <input type="checkbox" onchange="$.pjax({container: '#js-content-article', url: '<?=Url::to(['/admin/staff', 'id' => $employee->id, 'action' => 'status', 'page' => $page])?>', 'push': 0}); return false;" id="price_<?=$employee->id?>" <?=$employee->disabled ? '' : 'checked'?>>
                            <label for="price_<?=$employee->id?>"></label>
                        </div>
                    </td>
                    <td class="tableActionRight">
                        <a href="javascript://" onclick="window.okConfirm(function(){ $.pjax({container: '#js-content-article', timeout: 10000, url: '<?=Url::to(['/admin/staff', 'id' => $employee->id, 'page' => $page, 'action' => 'delete'])?>', 'push': 0}); });" class="delete"></a>
                    </td>
                    <td class="js-json-data" style="display: none"><?= Json::encode($employee)?></td>
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
    <h2>Добавление сотрудника</h2>

    <? $model = new Staff(); ?>
    <? $form = ActiveForm::begin([
        'id' => 'backend-form',
        'action' => Url::to(['staff']),
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

    <?= $form->field($model, 'full_name')->textInput(['placeholder' => 'Имя и фамилия']); ?>

    <?= $form->field($model, 'position')->textInput(['placeholder' => 'Должность']); ?>

    <?=$form->field($model, 'image', ['template' => '<div class="formItem js-image-block" style="display: none;">{input}</div>'])->fileInput(['accept' => 'image/png, image/jpeg', 'class' => 'js-load-image']) ?>

    <div class="formItem images js-crop-images">
        <ul class="reset flex flexWrap js-images"></ul>
    </div>

    <div class="formItem"><?= Html::button('Заменить фото', ['class' => 'js-upload-image'])?></div>

    <? ActiveForm::end() ?>

    <div>
        <?= Html::submitButton('Сохранить', ['onclick' => '$("#backend-form").submit();'])?>
        <span class="formNote"></span>
    </div>
</section>
<? Pjax::end(); ?>