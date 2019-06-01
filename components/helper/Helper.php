<?php
/**
 * Created by PhpStorm.
 * User: Arty
 * Date: 26.02.2018
 * Time: 22:56
 */

namespace app\components\helper;

use yii\bootstrap\Html;
use yii\data\Pagination;



class Helper {

    public static function translateMonth($month, $plural=false){
        $ret = strtr($month, [
            "January" => "январ" . ($plural ? 'я' : 'ь'),
            "February" => "феврал" . ($plural ? 'я' : 'ь'),
            "March" => "март" . ($plural ? 'а' : ''),
            "April" => "апрел" . ($plural ? 'я' : 'ь'),
            "May" => "ма" . ($plural ? 'я' : 'й'),
            "June" => "июн" . ($plural ? 'я' : 'ь'),
            "July" => "июл" . ($plural ? 'я' : 'ь'),
            "August" => "август" . ($plural ? 'а' : ''),
            "September" => "сентябр" . ($plural ? 'я' : 'ь'),
            "October" => "октябр" . ($plural ? 'я' : 'ь'),
            "November" => "ноябр" . ($plural ? 'я' : 'ь'),
            "December" => "декабр" . ($plural ? 'я' : 'ь')
        ]);

        return $ret;
    }

    /**
     * @param string $table
     * @param null|int $page
     * @param null|int $pageSize
     * @param null|string $route
     * @param array $arSettings
     * @return array
     */
    public static function pagination($table, $page=null, $pageSize=null, $route=null, $arSettings=[]){
        $model_class = "app\\models\\" . ucfirst($table);
        $query = $model_class::find();

        if( !empty($arSettings['with']) ){
            $query->with($arSettings['with']);
        }

        if( !empty($arSettings['where']) ){
            $query->where($arSettings['where']);
        }

        if( !empty($arSettings['order']) ){
            $query->orderBy($arSettings['order']);
        }

        $countQuery = clone $query;
        $totalCount = $countQuery->count();

        $pageSize = $pageSize ? $pageSize : 10;
        if( $page !== null ){
            $maxPage = ceil($totalCount/$pageSize);
            if($page > $maxPage){
                $page = $maxPage;
            }
        }

        $pages = new Pagination([
            'route' => $route,
            'params' => false,
            'totalCount' => $totalCount,
            'forcePageParam' => false,
            'pageSizeParam' => false,
            'pageSize' => $pageSize,
            'page' => $page !== null ? (int)$page-1 : null
        ]);

        $models = $query->offset($pages->offset)->limit($pages->limit)->all();
        return [$models, $pages];
    }


    /**
     * Converts number to money format
     * @param int|string $number
     * @return string
     */
    public static function normalizePrice($number){
        $nParts = explode('.', $number);
        $nParts[0] = str_split((string)$nParts[0]);
        $r = '';
        $j = 0;

        for( $i=count($nParts[0]) - 1; $i>=0; --$i ){

            if( !($j%3) ){
                $r = ' ' . $r;
            }

            $j++;
            $r = $nParts[0][$i] . $r;
        }

        return trim($r) . (isset($nParts[1]) ? '.' . $nParts[1] : '');
    }

    public static function socialButtons($settings){
        echo Html::a(Html::img('@web/img/vk.svg', ['alt' => '', 'title' => '']), $settings->vk, ['target' => '_blank']);
        echo Html::a(Html::img('@web/img/fb.svg', ['alt' => '', 'title' => '']), $settings->facebook, ['target' => '_blank']);
        echo Html::a(Html::img('@web/img/in.svg', ['alt' => '', 'title' => '']), $settings->inst, ['target' => '_blank']);
    }

}