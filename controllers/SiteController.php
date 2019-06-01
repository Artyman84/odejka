<?php

namespace app\controllers;

use app\components\helper\Helper;
use app\models\Posts;
use app\models\ReviewForm;
use app\models\Reviews;
use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\Response;
use app\models\LoginForm;
use app\models\ContactForm;
use yii\helpers\Json;
use \app\models\Settings;
use \app\models\Staff;

class SiteController extends Controller{

    /**
     * @inheritdoc
     */
    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                'foreColor' => 0xC7962A,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex() {
        return $this->render('index');
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionAtelier() {
        $settings = Settings::findOne(1);
        $staff = Staff::find()->where('disabled = 0')->orderBy(['id' => SORT_ASC])->all();
        $to_loc = Yii::$app->request->get('to_loc', 0);

        return $this->render('atelier', ['settings' => $settings, 'staff' => $staff, 'to_loc' => $to_loc]);
    }

    /**
     * Displays Products.
     *
     * @return string
     */
    public function actionProducts() {
        $filter = (array)Json::decode(urldecode(Yii::$app->request->get('filter', Yii::$app->request->post('filter', ''))));
        return $this->render('products', ['filter' => $filter]);
    }

    /**
     * Displays Price list.
     *
     * @return string
     */
    public function actionPricelist() {
        return $this->render('pricelist');
    }

    /**
     * Displays Feedback.
     *
     * @return string
     */
    public function actionFeedback() {
        $model = new ReviewForm();
        if ($model->load(Yii::$app->request->post())) {

            $model->saveReview();
            return $this->refresh();
        }

        $reviews = Reviews::find()->where('disabled = 0')->orderBy(['timestamp' => SORT_DESC])->all();
        return $this->render('feedback', ['reviews' => $reviews, 'errors' => $model->errors]);
    }

    /**
     * Displays Blog.
     *
     * @return string
     */
    public function actionBlog() {
        $page = Yii::$app->request->get('page', Yii::$app->request->post('page', 0));
        list($models, $pages) = Helper::pagination('posts', $page, 5, 'site/blog', ['order' => ['timestamp' => SORT_DESC], 'where' => ['disabled' => '0']]);

        return $this->render('blog', ['models' => $models, 'pages' => $pages]);
    }

    /**
     * Displays Blog.
     *
     * @return string
     */
    public function actionBlogPost() {
        $id = Yii::$app->request->get('id', 0);
        $posts = Posts::find()->where('disabled = 0')->orderBy(['timestamp' => SORT_DESC])->indexBy('id')->all();

        return $this->render('blog_post', ['id' => $id, 'posts' => $posts]);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin() {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $this->layout = 'login';

        return $this->render('login', [
            'model' => $model,
        ]);
    }


    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout() {
        Yii::$app->user->logout();
        return Yii::$app->getResponse()->redirect(Url::to('/admin'));
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact() {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

}
