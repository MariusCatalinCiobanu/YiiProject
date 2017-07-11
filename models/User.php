<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\filters\RateLimitInterface;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $email
 * @property string $password
 * @property string $type
 * @property string $auth_key
 * @property string $created_by
 * @proprery string $updated_by
 * @property DateTime $created_at
 * @property DateTime $updated_at
 * @property string $access_token
 *  
 */
class User extends ActiveRecord implements IdentityInterface
{

    const SCENARIO_LOGIN = 'login';
    const SCENARIO_REGISTER = 'register';

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'email' => 'Email',
            'password' => 'Password',
            'type' => 'Type',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'auth_key' => 'Auth Key',
            'access_token' => 'Access Token'
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_LOGIN] = ['email', 'password'];
        $scenarios[self::SCENARIO_REGISTER] = ['email', 'password', 'type'];
        return $scenarios;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email', 'type'], 'string', 'max' => 45],
            [['password'], 'string', 'max' => 60],
            // [['email'], 'unique'],
            ['email', 'email'],
            //check if the type is a valid role
            ['type', 'checkType', 'message' => 'The type selected does not exist'],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string current user auth key
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @param string $authKey
     * @return bool if auth key is valid for current user
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    //
    // @param string email the email used to find a user
    // @return Users | null user with the email given
    //
    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email]);
    }

    //
    //@param string password the password given by the user is checked with the 
    //hashed password saved in the db
    //@return boolean the result of the authentification
    //
    public function checkPassword($password)
    {
        return Yii::$app->getSecurity()->validatePassword($password, $this->password);
    }

    //
    //Every time a row is inserted or updated the date and user who does the
    //modification and the date of the modification it's inserted as well. 
    //@param insert
    //@return boolean the result of the insert/update
    //
    public function beforeSave($insert)
    {

        Yii::info('Before save');
        $now = (new \DateTime())->format('Y-m-d H:i:s');

        //for insert
        if ($this->isNewRecord) {
            $this->created_at = $now;
            $this->updated_at = $now;
            $this->created_by = Yii::$app->user->id;
            $this->updated_by = Yii::$app->user->id;

            //generate auth_key
            $this->auth_key = \Yii::$app->security->generateRandomString();

            //hashing the password
            Yii::info('Before saving');
            $this->password = Yii::$app->getSecurity()->generatePasswordHash($this->password);

            //web service access token
            $this->access_token = Yii::$app->getSecurity()->generateRandomString();

            //for update
        } else {
            $this->updated_at = $now;

            //in case of forgot password change
            if (Yii::$app->user->identity === null) {
                $this->updated_by = $this->id;

                //in case of admin update    
            } else {
                $this->updated_by = Yii::$app->user->id;
            }


            //check if password has been modifyied. If it has, hash it.
            if (strcmp($this->password, $this->oldAttributes['password']) != 0) {
                Yii::info('Password is dirty');
                $this->password = Yii::$app->getSecurity()->generatePasswordHash($this->password);
            }
        }

        if (!parent::beforeSave($insert)) {
            return false;
        }

        return true;
    }

    //
    //Method used to change the password
    //
    public function changePassword($newPassword)
    {
        $this->password = $newPassword;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrips()
    {
        return $this->hasMany(Trip::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsersThatWereUpdated()
    {
        return $this->hasMany(User::className(), ['updated_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsersThatWereCreated()
    {
        return $this->hasMany(User::className(), ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmailCOnfirmation()
    {
        return $this->hasOne(EmailConfirmation::className(), ['user_id' => 'id']);
    }

    //
    // Specify what columns should be returned when toArray() is called
    // with no arguments
    //
    public function fields()
    {
        $fields = parent::fields();
        unset($fields['password'], $fields['auth_key'], $fields['access_token']);
        return $fields;
    }

    //
    //The method is used in rules to check if the selected role exists in the db
    //
    public function checkType($attribute, $params)
    {
        $roles = AuthItem::getRolesNames();
        if (!isset($roles[$this->$attribute])) {
            $this->addError($attribute, 'The type selected does not exist');
        }
    }

//    //Rate limiting does not work
//    //migration set rate limit
//    public function getRateLimit($request = null, $action = null)
//    {
//        //number of request per 600 seconds
//        return [$this->rate_limit, 600]; // $rateLimit requests per second
//    }
//
//    //check current allowance
//    public function loadAllowance($request, $action)
//    {
//        return [$this->allowance, $this->allowance_updated_at];
//    }
//
//    //save allowance
//    public function saveAllowance($request, $action, $allowance, $timestamp)
//    {
////        $this->allowance = $this->allowance + 1;
////        $this->allowance_updated_at = (new \DateTime())->format('Y-m-d H:i:s');
////        $this->save();
//        $this->allowance = $allowance;
//        $this->allowance_updated_at = $timestamp;
//        $this->save();
//    }
}
