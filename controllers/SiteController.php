<?php

namespace app\controllers;

use app\components\Communicator;
use Yii;
use yii\base\DynamicModel;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;


class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $apiAuth = Yii::$app->session->get('APIAuth');
        if ($apiAuth) {
            $products = Communicator::getAllProduct() ?? [];
            $message = Yii::$app->session->get('message');

            if ($message) {
                Yii::$app->session->remove('message');
            }

            return $this->render('index', ['products' => $products, 'message' => $message]);
        } else {
            $this->actionLogout();
        }
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect('/');
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->redirect(['site/login']);
    }

    public function actionCreate()
    {
        return $this->render('create', ['title' => 'Új termék rögzítése', 'action' => '/site/create']);
    }

    public function actionStore()
    {
        $session = Yii::$app->session;
        $post = Yii::$app->request->post();
        $model = $this->createValidator($post);

        if ($model->hasErrors()) {
            $session->setFlash('error', $model->getFirstErrors());
            $session->set('data', $post);
            return $this->render('create', ['title' => 'Új termék rögzítése', 'action' => '/site/create']);;
        } else {
            Communicator::insertProduct($post);
            $session->setFlash('error', $model->getFirstErrors());
            $session->set('product', $post);
            $session->set('message', 'Sikeres rögzítés!');
        }

        return $this->redirect('/');
    }


    public function actionEdit($id)
    {
        $product = Communicator::getProduct($id);
        return $this->render('update', ['title' => 'Termék módosítása', 'action' => '/site/update/' . $product['id'], 'method' => 'PUT', 'product' => $product]);
    }

    public function actionUpdate($id)
    {
        $session = Yii::$app->session;
        $data = Yii::$app->request->post();
        $data['id'] = $id;

        $model = $this->createValidator($data);

        if ($model->hasErrors()) {
            $session->setFlash('error', $model->getFirstErrors());
            $session->set('data', $data);

            return $this->render('update', ['title' => 'Termék módosítása', 'action' => '/site/update/' . $data['id'], 'method' => 'PUT', 'product' => $data]);
        } else {
            Communicator::updateProduct($data);
            $session->set('message', 'Sikeres módosítás!');
        }

        return $this->redirect('/');
    }

    public function actionDelete($id)
    {
        $session = Yii::$app->session;

        Communicator::deleteProduct($id);
        $session->set('message', 'Sikeres törlés!');

        return $this->redirect('/');
    }

    /**
     * @param $post
     * @return DynamicModel
     */
    private function createValidator($post): DynamicModel
    {
        $model = new DynamicModel([
            'name' => $post['name'],
            'description' => $post['description'],
            'price' => $post['price'],
        ]);

        $model->setAttributeLabels([
            'name' => 'Termék neve',
            'date_from' => 'Termék leírás',
            'date_to' => 'Ár',
        ]);

        $model->addRule(['name', 'price'], 'required')
            ->addRule(['price'], 'integer')
            ->addRule(['name'], 'string', ['max' => 50])
            ->validate();

        return $model;
    }
}
