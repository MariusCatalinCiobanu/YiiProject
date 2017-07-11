<?php

namespace app\components;

use yii\base\Component;
use Yii;
class AuthorizationMethods extends Component
{

    public static function isAdmin()
    {
        if(Yii::$app->user->isGuest) {
            return false;
        }
        $userType = Yii::$app->user->identity->type;
        if (strcmp($userType, AuthorizationConstants::ADMIN_ROLE) === 0) {
            return true;
        } else {
            return false;
        }
    }

}
