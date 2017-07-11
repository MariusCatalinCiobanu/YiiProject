<?php

namespace app\modules\webService\controllers;

use yii\filters\auth\HttpBasicAuth;
use yii\rest\ActiveController;

class UserController extends ActiveController
{
    //public $enableCsrfValidation = false;
    public $modelClass = 'app\modules\login\models\UsersAdmin';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBasicAuth::className(),
        ];
        $behaviors['rateLimiter']['enableRateLimitHeaders'] = false;
        return $behaviors;
    }

//    public function beforeAction($action)
//    {
//        $results = parent::beforeAction($action);
//        
//        if($action->id == 'create') {
//          Yii::info('BeforeAction actionId: ' . json_encode(Yii::$app->request->post()));
//        }
//        return $results;
//    }

}
