<?php

namespace app\modules\login\models;

class ForgotPasswordForm extends \yii\base\Model
{

    public $email;


    public function rules()
    {
        return[
            [['email'], 'required'],
            [['email'], 'string', 'max' => 45],
        ];
    }

}
