<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\CompanyLoginForm;
use app\models\CompanyRegisterForm;
use app\models\CompanyRegisterJobForm;
use app\models\ContactForm;
use app\models\Job;

class CompanyController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors() {
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
    public function actions() {
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
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin() {
        $model = new CompanyLoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Register action.
     *
     * @return Response|string
     */
    public function actionRegister() {
        $model = new CompanyRegisterForm();
        if ($model->load(Yii::$app->request->post()) && $model->register()) {
            return $this->goBack();
        }

        $model->password = '';

        return $this->render('register', [
            'model' => $model,
        ]);
    }

    public function actionCadastrarVaga() {
        $model = new CompanyRegisterJobForm();
        if ($model->load(Yii::$app->request->post()) && $model->registerJob()) {
            return $this->goBack();
        }

        return $this->render('registerJob', [
            'model' => $model
        ]);
    }

    public function actionCandidaturas($jobId) {
        return $this->render('jobApplications', [
            'jobId' => $jobId
        ]);
    }

    public function actionAcceptApplication($applicationId) {
        $model = new Job();
        $model->acceptApplication($applicationId);
    }

    public function actionRejectApplication($applicationId) {
        $model = new Job();
        $model->rejectApplication($applicationId);
    }
}
