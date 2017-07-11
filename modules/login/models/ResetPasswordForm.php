<?php

namespace app\modules\login\models;
use yii\base\Model;

class ResetPasswordForm extends Model
{

    public $password;
    public $confirmPassword;

    public function rules()
    {
        return[
            [['password', 'confirmPassword'], 'required'],
            [['password', 'confirmPassword'], 'string', 'max' => 60],
            ['confirmPassword', 'compare', 'compareAttribute' => 'password', 'message' => 'Passwords don\'t match'],
        ];
    }

}
