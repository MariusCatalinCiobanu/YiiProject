<?php

namespace app\modules\login\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\modules\login\models\LoginForm;
use app\models\EmailConfirmation;
use app\modules\login\models\ForgotPasswordForm;
use app\modules\login\models\ResetPasswordForm;
use app\classes\Constants;
class LoginController extends Controller
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'forgot-password', 'reset-password'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['forgot-password', 'reset-password'],
                        'allow' => true,
                        'roles' => ['?']
                    ]
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
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
     * Index action.
     *
     * @return Response|string
     */
    public function actionIndex()
    {
        Yii::info('Bootstraping works:' . Constants::TEST);
        Yii::info('Alias works:' . Yii::getAlias('@constants'));
        
        //If logged in redirect to a page accordingly to it's user role
        if (!Yii::$app->user->isGuest) {
            $this->redirectBasedOnRole();
        }
     
        $model = new LoginForm(['scenario' => LoginForm::SCENARIO_LOGIN]);
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            
            //Go to the last page visited, or go home which is this controller
            //If the user is redirected back here, he will be redirected to a 
            //page based on his user role
            return $this->goBack();
        }
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

        return $this->goHome();
    }

    //
    //Forgot password action, collects the email adress and sends a email with a 
    //url for changing the password
    //@return string
    //@throws \yii\db\Exception
    public function actionForgotPassword()
    {
    
        $model = new ForgotPasswordForm(['scenario' =>
            ForgotPasswordForm::SCENARIO_FORGOT_PASSWORD]);
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $emailConfirmationModel = EmailConfirmation::findOrCreate($model);
            if ($emailConfirmationModel === null) {
                throw new \yii\base\UserException("The email you typed doesn't exist");
            }

            //generates random string to use for the password reset
            $emailConfirmationModel->generateForgotPasssword();

            //send the email with the link
            try {
                //send email
                $result = Yii::$app->mailer->compose('forgotPassword', [
                            'model' => [
                                'url' => 'http://yii2project/login/login/reset-password?key='
                                . $emailConfirmationModel->forgot_password
                    ]])
                        ->setFrom('clarisotmarius.ciobanu@gmail.com')
                        ->setTo($model->email)
                        ->setSubject('ForgotPassowrd')
                        ->send();

                //save the random string in the database for password reset
                $emailConfirmationModel->save();
                
                return $this->render('forgotConfirm');
            } catch (\Exception $e) {
                Yii::error($e->getMessage() . ' ' . $e->getFile() . ' '
                        . $e->getLine() . ' ' . $e->getTraceAsString());

                //throw back the exception
                throw $e;
//                return $this->render('forgotPassword', ['model' => $model]);
            }
        } else {
            return $this->render('forgotPassword', ['model' => $model]);
        }
    }

    //
    //This is the action called by the link in the email for password reset, it 
    //should have a key as query string. If the key is correct the user can change 
    //his password
    //@param string key, the random string that was generated and send in mail
    //@return string | Response
    //@throws mixed
    //
    public function actionResetPassword($key = null)
    {
        //Check at every request if the forgot password token is good
        $emailConfirmModel = EmailConfirmation::findByForgotPasswordToken($key);
        if ($emailConfirmModel == null) {
            throw new \yii\base\UserException('Invalid request');
        }

        //check if the password has expired
        if ($emailConfirmModel->forgotPasswordHasExpired() == true) {
            $now = (new \DateTime())->format('H:i:s');
            Yii::info('now = ' . $now . ' expire: ' . $emailConfirmModel->expiration_timestamp);
            throw new \yii\base\UserException('Request has expired');
        }


        $resetPasswordModel = new ResetPasswordForm();
        if ($resetPasswordModel->load(Yii::$app->request->post()) && $resetPasswordModel->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                //get the user
                $user = $emailConfirmModel->getUser()->one();
                Yii::info('user id:' . $user->id);

                //change the password
                $user->changePassword($resetPasswordModel->password);
                $user->save();
                $emailConfirmModel->delete();
                $transaction->commit();
                //go to home page
                return $this->goHome();
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::error($e->getMessage() . ' ' . $e->getFile() . ' '
                        . $e->getLine() . ' ' . $e->getTraceAsString());
            }
        } else {
            Yii::info('submited not successfully');
            return $this->render('resetPassword', ['model' => $resetPasswordModel]);
        }
    }

    //
    //This method is used to redirect to the home page of the user based on 
    //his role
    //@return Response
    //
    public function redirectBasedOnRole()
    {

        $authorizationMethods = Yii::$app->authorizationMethods;
        $isAdmin = $authorizationMethods->isAdmin();
        if ($isAdmin) {
            $this->redirect(['/login/admin-panel/index']);
        } else {
            $this->redirect(['/trip/trip/index']);
        }
    }

}
