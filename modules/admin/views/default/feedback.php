<?
/* @var $this yii\web\View */
/* @var $reviews array */
/* @var $pages \yii\data\Pagination*/

use app\widgets\Pager;
use \yii\helpers\Url;
use \yii\helpers\Html;
use yii\widgets\Pjax;


Pjax::begin(['id' => 'js-content-article', 'options' => ['tag' => 'div', 'class' => 'sectionWrapper flex']]); ?>
<section class="content">
    <article>
        <h1>Отзывы</h1>

        <table class="noVisited">
            <tbody new_reviews_count="<?=$new_reviews_count?>">
                <? foreach ($reviews as $review) {
                    ?><tr id="<?=$review->id?>">
                    <td>
                        <a href="javascript://" onclick="return false;"><?=Html::encode($review->firstname . ' ' . $review->lastname)?></a>
                        <div class="textDark"><?= Html::encode($review->text)?></div>
                    </td>
                    <td class="small">
                        <div class="slider">
                            <input type="checkbox" onchange="$.pjax({container: '#count_of_new_reviews', url: '<?=Url::to(['/admin/change-review-status', 'id' => $review->id])?>', 'push': 0}); return false;" id="review_<?=$review->id?>" <?=$review->disabled ? '' : 'checked'?>>
                            <label for="review_<?=$review->id?>"></label>
                        </div>
                    </td>
                    <td class="tableActionRight">
                        <a href="javascript://" onclick="window.okConfirm(function(){
                            $.pjax({
                                container: '#js-content-article',
                                url: '<?=Url::to(['/admin/feedback', 'id' => $review->id, 'page' => $page, 'action' => 'delete'])?>', 'push': 0
                            }).done(function(){
                                $('#count_of_new_reviews').html($('table.noVisited tbody').attr('new_reviews_count'));
                            });
                        });" class="delete"></a>
                    </td>
                    </tr><?
                }?>
            </tbody>
        </table>
        <div class="t-pager">
            <?= Pager::widget([
                'pagination' => $pages,
                'pjax_id' => 'js-content-article'
            ]); ?>
        </div>
    </article>
</section>
<? Pjax::end(); ?>