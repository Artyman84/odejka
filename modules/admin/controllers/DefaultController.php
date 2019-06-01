<?php

namespace app\modules\admin\controllers;
use app\components\helper\Helper;
use app\models\Admins;
use app\models\Posts;
use app\models\Prices;
use app\models\Staff;
use app\models\Products;
use app\models\Reviews;
use app\models\Settings;
use app\widgets\Pager;
use yii\data\Pagination;
use Yii;
use yii\filters\AccessControl;
use yii\filters\AjaxFilter;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\helpers\Json;


/**
 * Default controller for the `admin` module
 */
class DefaultController extends AppAdminController {

    public function behaviors() {

        return array_merge([
            'ajax' => [
                'class' => AjaxFilter::className(),
                'only' => ['change-review-status']
            ]
        ], parent::behaviors());
    }


    public function init() {
        $this->view->params['editing'] = '';
        parent::init();
    }

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex() {
        $model = Settings::findOne(Yii::$app->getUser()->id);

        if( ($post = Yii::$app->request->post()) && $model->load($post)){
            $model->save();
            Yii::$app->session->setFlash('success', 'Сохранено');
        }

        return $this->render('legend', ['model' => $model]);
    }

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionStaff() {
        $action = Yii::$app->request->get('action', Yii::$app->request->post('action', null));

        switch ($action){
            case 'save':
                $data = Yii::$app->request->post();

                if( isset($data) && !empty($data['Staff']['id']) ){
                    $model = Staff::findOne($data['Staff']['id']);
                } else {
                    $model = new Staff();
                }

                if( $model->load($data) ){

                    $model->image = UploadedFile::getInstance($model, 'image');
                    $model->save();

                }

                break;

            case 'delete':
                $id = Yii::$app->request->get('id');
                if( ($model = Staff::findOne($id)) ) {
                    $model->delete();
                }
                break;

            case 'status':
                $id = Yii::$app->request->get('id');
                $model = Staff::findOne($id);
                $model->disabled = 1 - (int)$model->disabled;
                $model->save();
                break;
        }

        $page = Yii::$app->request->get('page', Yii::$app->request->post('page', 0));
        list($models, $pages) = Helper::pagination('staff', $page, 10, 'admin/staff', ['order' => ['id' => SORT_DESC]]);

        return $this->render('staff', [
            'staff' => $models,
            'pages' => $pages,
            'page' => $page
        ]);
    }

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionPricelist() {
        $action = Yii::$app->request->get('action', Yii::$app->request->post('action', null));

        switch ($action){
            case 'save':
                $data = Yii::$app->request->post();

                if( isset($data) && !empty($data['Prices']['id']) ){
                    $post = Prices::findOne($data['Prices']['id']);
                } else {
                    $post = new Prices();
                }

                if( $post->load($data) ){
                    $post->save();
                }
                break;

            case 'delete':
                $id = Yii::$app->request->get('id');
                Prices::deleteAll(['id' => $id]);
                break;

            case 'status':
                $id = Yii::$app->request->get('id');
                $model = Prices::findOne($id);
                $model->disabled = 1 - (int)$model->disabled;
                $model->save();
                break;
        }

        $page = Yii::$app->request->get('page', Yii::$app->request->post('page', 0));
        list($models, $pages) = Helper::pagination('prices', $page, null, 'admin/pricelist', ['order' => ['id' => SORT_DESC]]);

        return $this->render('pricelist', [
            'prices' => $models,
            'pages' => $pages,
            'page' => $page
        ]);
    }

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionProducts() {
        $action = Yii::$app->request->get('action', Yii::$app->request->post('action', null));
        $filter = (array)Json::decode(urldecode(Yii::$app->request->get('filter', Yii::$app->request->post('filter', ''))));

        switch ($action){
            case 'save':
                $data = Yii::$app->request->post();

                if( isset($data) && !empty($data['Products']['id']) ){
                    $model = Products::findOne($data['Products']['id']);
                } else {
                    $model = new Products();
                }

                if( $model->load($data) ){
                    $model->image = UploadedFile::getInstances($model, 'image');
                    $model->save();
                }

                break;

            case 'delete':
                $id = Yii::$app->request->get('id');
                if( ($model = Products::findOne($id)) ) {
                    $model->delete();
                }
                break;
        }

        $page = Yii::$app->request->get('page', Yii::$app->request->post('page', 0));

        list($models, $pages) = Helper::pagination('products',
            $page,
            null,
            'admin/products',
            [
                'with' => ['productImages' => function ($query) { $query->orderBy(['position' => SORT_ASC]); }],
                'order' => ['id' => SORT_DESC],
                'where' => $filter != null ? ['clothes_id' => (array)$filter] : null
            ]
        );

        return $this->render('products', [
            'products' => $models,
            'filter' => $filter,
            'pages' => $pages,
            'page' => $page
        ]);
    }

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionBlog() {

        $action = Yii::$app->request->get('action', Yii::$app->request->post('action', null));

        switch ($action){
            case 'save':
                $data = Yii::$app->request->post();

                if( isset($data) && !empty($data['Posts']['id']) ){
                    $post = Posts::findOne($data['Posts']['id']);
                } else {
                    $post = new Posts();
                }

                if( $post->load($data) ){
                    $post->save();
                }
                break;

            case 'delete':
                $id = Yii::$app->request->get('id');
                Posts::deleteAll(['id' => $id]);
                break;

            case 'status':
                $id = Yii::$app->request->get('id');
                $model = Posts::findOne($id);
                $model->disabled = 1 - (int)$model->disabled;
                $model->save();
                break;
        }

        $page = Yii::$app->request->get('page', Yii::$app->request->post('page', 0));
        list($models, $pages) = Helper::pagination('posts', $page, null, 'admin/blog', ['order' => ['timestamp' => SORT_DESC]]);

        return $this->render('blog', [
            'posts' => $models,
            'pages' => $pages,
            'page' => $page
        ]);
    }


    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionFeedback() {

        if( Yii::$app->request->get('action', null) == 'delete' ){
            $id = Yii::$app->request->get('id');
            Reviews::deleteAll(['id' => $id]);
        }

        $page = Yii::$app->request->get('page', null);
        list($models, $pages) = Helper::pagination('reviews', $page, null, 'admin/feedback', ['order' => ['disabled' => SORT_DESC, 'timestamp' => SORT_DESC]]);
        $new_reviews_count = Yii::$app->db->createCommand('SELECT COUNT(*) FROM {{%reviews}} WHERE disabled = 1')->queryScalar();
        $new_reviews_count = $new_reviews_count ? $new_reviews_count : '';

        return $this->render('feedback', [
            'reviews' => $models,
            'pages' => $pages,
            'page' => $page,
            'new_reviews_count' => $new_reviews_count
        ]);
    }

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionSettings() {
        $settings = Settings::findOne(Yii::$app->getUser()->id);
        $admin = Admins::findOne(Yii::$app->getUser()->id);

        if( ($post = Yii::$app->request->post()) ){
            if($settings->load($post)) {
                $settings->save();
            }

            $old_password = $admin->password;
            if($admin->load($post)) {
                if( !empty($post['Admins']['password']) ){
                    $admin->password = Yii::$app->getSecurity()->generatePasswordHash($post['Admins']['password']);
                } else {
                    $admin->password = $old_password;
                }

                $admin->save();
            }

            Yii::$app->session->setFlash('success', 'Сохранено');
        }

        return $this->render('settings', [
            'settings' => $settings,
            'admin' => $admin
        ]);
    }

    /**
     * @return false|null|string
     */
    public function actionChangeReviewStatus(){
        $review_id = Yii::$app->request->get('id');
        $model = Reviews::findOne($review_id);
        $model->disabled = 1 - (int)$model->disabled;
        $model->save();
        $new_reviews = Yii::$app->db->createCommand('SELECT COUNT(*) FROM {{%reviews}} WHERE disabled = 1')->queryScalar();
        return $new_reviews ? $new_reviews : ' ';
    }

}
