<?php
/**
 * Created by PhpStorm.
 * User: Arty
 * Date: 09.02.2018
 * Time: 13:07
 */

namespace app\modules\admin\controllers;
use yii\filters\AccessControl;

use yii\base\Controller;

class AppAdminController extends Controller {

    public function behaviors() {

        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ]
        ];

    }
}