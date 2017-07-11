<?php

namespace app\modules\login\models;

use app\models\User;
use yii\helpers\ArrayHelper;

class ForgotPasswordForm extends User
{

    public $email;

    public function rules()
    {
        $rules = parent::rules();
        $rules = ArrayHelper::merge($rules, [
                    [['email'], 'required']
        ]);
        return $rules;
    }

}
