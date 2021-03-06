<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\ProjectUser;
use yii\helpers\Url;

class SiteController extends Controller
{
    /**
     * @inheritdoc
     */

    public function behaviors()
    {
        return [
            'access' => [
               'class' => \yii\filters\AccessControl::className(), 
              //  'only' => ['logout','login','index','about','contact','project'],
                'rules' => [
                    [
                        'actions' => ['login','signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                      [
                        'actions' => ['index','logout','about','contact'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                     [
                        'actions' => ['project','index','logout','about','contact'],
                        'allow' => true,
                        'roles' => ['project_cordinator'],

                    ],
                  [
                        'actions' => ['projectdetails','index','logout','about','contact'],
                        'allow' => true,
                        'roles' => ['project_manager'],

                    ],
                    
                ],
            ],
          
        ];
    }
    
    /**
     * @inheritdoc
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
        return $this->render('index');
    }
    

      public function actionLogin()
   {
       
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
  
       // $model = new LoginForm();
         $model = new LoginForm(['scenario' => ProjectUser::SCENARIO_LOGIN]);
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
       return $this->goBack();
      
        }
        else
        {
        return $this->render('login', [
            'model' => $model,
        ]);
    }
   }

   
    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
    public function actionSignup()
    {
        $model=new ProjectUser();
        $model = new ProjectUser(['scenario' => ProjectUser::SCENARIO_REGISTER]);
        if ($model->load(Yii::$app->request->post())) {
          // $hash = Yii::$app->getSecurity()->generatePasswordHash($model->project_user_password);
         //  $model->project_user_password=$hash;
                $model->save();
             \Yii::$app->session->setFlash('flashMessage', 'Sucessfully Registerd'); 
            return $this->redirect('site/login');
       
        } 
            return $this->render('signup', [
                'model' => $model,
         ]);
            
        
    }
    public function actionProject()
    {
        //var_dump("hello");exit;
        echo("hello welcome to the project");
    }
    public function actionProjectdetails()
    {
        echo("welcome");
    }
  
    
}
