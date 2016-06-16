<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Company;
use app\models\HarvestModel;
use app\models\Payroll;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'company'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['company'],
                        'roles' => ['admin'],
                    ]
                ],
            ],
//            'verbs' => [
//                'class' => VerbFilter::className(),
//                'actions' => [
//                    'logout' => ['post'],
//                ],
//            ],
        ];
    }

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

    public function actionOffline(){
        echo "Offline";
    }
    public function actionManage()
    {
	$query = Company::find();
	$companies = $query->all();
	var_dump($companies);
    }

    public function actionIndex()
    {
        $query = Payroll::find();
        $payroll_list = $query->orderBy("week_of DESC")->limit(6)->all();
        if(!Yii::$app->user->isGuest){
           
//            $payroll_history = // get info for last three payrolls
        }
        return $this->render('index', [
            'recent_payroll' => $payroll_list
        ]);
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        $form = Yii::$app->request->post('LoginForm');
        if(!empty($form)){
            $model->attributes = Yii::$app->request->post('LoginForm');
        
            if($model->validate() && $model->login()){
                $this->goHome();
            }
        }
        
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }
}
