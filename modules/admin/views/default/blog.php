<?
/* @var $this yii\web\View */
/* @var $posts array */
/* @var $page int */
/* @var $pages \yii\data\Pagination*/

use app\widgets\Pager;
use \yii\helpers\Url;
use \yii\helpers\Html;
use app\components\helper\Helper;
use \yii\helpers\Json;
use \yii\widgets\ActiveForm;
use \app\models\Posts;
use mihaildev\ckeditor\CKEditor;
use yii\widgets\Pjax;

Pjax::begin(['id' => 'js-content-article', 'options' => ['tag' => 'div', 'class' => 'sectionWrapper flex']]); ?>
<section class="content">
    <article>
        <h1>Блог</h1>
        <div class="navigation flex flexBetween">
            <button class="t-open-editing">Добавить публикацию</button>
        </div>
        <table class="noVisited">
            <tbody model="posts">
            <? foreach ($posts as $post) {?>
                <tr class="js-model-object">
                    <td>
                        <a href="#" id="<?=$post->id?>" class="js-edit-data" onclick="return false;"><?=Html::encode($post->title)?></a>
                        <div><?=  Helper::translateMonth(Yii::$app->formatter->asDate($post->timestamp), true)?></div>
                    </td>
                    <td class="small">
                        <div class="slider">
                            <input type="checkbox" onchange="$.pjax({container: '#js-content-article', url: '<?=Url::to(['/admin/blog', 'id' => $post->id, 'action' => 'status', 'page' => $page])?>', 'push': 0}); return false;" id="post_<?=$post->id?>" <?=$post->disabled ? '' : 'checked'?>>
                            <label for="post_<?=$post->id?>"></label>
                        </div>
                    </td>
                    <td class="tableActionRight">
                        <a href="javascript://" onclick="window.okConfirm(function(){ $.pjax({container: '#js-content-article', url: '<?=Url::to(['/admin/blog', 'id' => $post->id, 'page' => $page, 'action' => 'delete'])?>', 'push': 0}); });" class="delete"></a>
                    </td>
                    <td class="js-json-data" style="display: none"><?= Json::encode($post)?></td>
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
    <h2>Добавление публикации</h2>

    <? $model = new Posts(); ?>
    <? $form = ActiveForm::begin([
        'id' => 'backend-form',
        'action' => Url::to(['blog']),
        'enableClientValidation' => true,
        'options' => ['class' => 'formContent form flex flexDirCol flexBetween', 'data-pjax' => true],
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

        <?= $form->field($model, 'title', ['template' => '<div class="formItem flexShrink">{input}</div>'])->textInput(['placeholder' => 'Заголовок']); ?>

        <?= $form->field($model, 'introductory_text', ['template' => '<div class="formItem flexShrink">{input}<p class="marginReset fontSmaller textGray aRight js-remain-text">Осталось <b>230</b> символов</p></div>'])->textarea(['class' => 'multiline', 'placeholder' => 'Вводный текст']); ?>

        <?= $form->field($model, 'text', ['template' => '<div class="formItem textareaFullsize">{input}</div>'])->textarea(['class' => 'multiline', 'placeholder' => 'Содержание']); ?>

        <?

//        $form->field($model, 'text', ['template' => '<div class="formItem textareaFullsize">{input}</div>'])->widget(CKEditor::className(),[
//            'editorOptions' => [
//                //'height' => 300,
//                'preset' => 'basic', //разработанны стандартные настройки basic, standard, full данную возможность не обязательно использовать
//                'inline' => false, //по умолчанию false
//            ],
//        ])->textarea(['class' => 'multiline', 'placeholder' => 'Содержание']);
        ?>


    <? ActiveForm::end() ?>

    <div>
        <?= Html::submitButton('Сохранить', ['onclick' => '$("#backend-form").submit();'])?>
        <span class="formNote"></span>
    </div>

</section>
<? Pjax::end(); ?>