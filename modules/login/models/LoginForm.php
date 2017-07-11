<?php

namespace app\modules\login\models;

use Yii;
use yii\base\Model;
use app\models\User;
use yii\helpers\ArrayHelper;

/**
 * LoginForm is the model behind the login form.
 *
 * Extends the base class Users.
 * @param boolean $rememberMe specifies if cookies should be used at the authentication
 * @param confirmPassword 
 * @param boolean | Users $_user used for storing a Users instance
 *
 */
class LoginForm extends User
{

    public $rememberMe = true;
    private $_user = false;

    //add the remember me rules to the scenarios
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_LOGIN] = ArrayHelper::merge(
                        $scenarios[self::SCENARIO_LOGIN], [
                    'rememberMe'
        ]);

        return $scenarios;
    }

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        $rules = parent::rules();
        $rules = ArrayHelper::merge($rules, [
                    [['rememberMe'], 'required'],
                    // rememberMe must be a boolean value
                    ['rememberMe', 'boolean'],
                    // password is validated by validatePassword()
                    ['password', 'validatePassword'],
        ]);
        return $rules;
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->checkPassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {

            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        }
        return false;
    }

    /**
     * Finds user by [[email]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByEmail($this->email);
        }

        return $this->_user;
    }

}
