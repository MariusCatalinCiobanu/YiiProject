<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use app\modules\login\models\ForgotPasswordForm;
use app\models\User;

/**
 * This is the model class for table "email_confirmation".
 *
 * @property int $id
 * @property string $register_key
 * @property string $forgot_password
 * @property int $user_id
 * @property DateTime expiration_timestamp
 */
class EmailConfirmation extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'email_confirmation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'integer'],
            [['register_key', 'forgot_password'], 'string', 'max' => 32],
            [['register_key'], 'unique'],
            [['forgot_password'], 'unique'],
            [['expiration_timestamp'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'register_key' => 'Register Key',
            'forgot_password' => 'Forgot Password',
            'user_id' => 'User ID',
            'expiration_timestamp' => 'Expiration Timestamp',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function generateForgotPasssword()
    {
        $this->forgot_password = Yii::$app->security->generateRandomString();
    }

    //
    //Finds a emailConfirmation based on user_id
    //@return ActiveQuery
    //
    public static function findByUserId($userId)
    {
        return EmailConfirmation::find()
                        ->where(['user_id' => $userId])
                        ->one();
    }

    //
    //The method first search if there is a user with the specified email. If there 
    //isn't it returns null. If there is it searches for a record with the this user 
    //id alredy set, if it finds one it returns it. If the method doesn't find a
    //record with the current user id set it creates a new one.
    //@return null|EmailConfirmation
    //
    public static function findOrCreate(ForgotPasswordForm $model)
    {
        //search if there is a user with that email
        $user = User::findByEmail($model->email);
        if ($user === null) {
            return null;
        }
        $model = EmailConfirmation::findByUserId($user->id);

        //The user has 1 hour to reset his password or the request will expire
        $now = new \DateTime();
        $hours = Yii::$app->params['ForgotPasswodReqExpireInNrHours'];
        Yii::info('ForgotPasswodReqExpireInNrHours' . $hours);
        //add 1 hour to datetime
        $now->add(new \DateInterval("PT{$hours}H"));
        $expirationTimeStamp = $now->format('Y-m-d H:i:s');
        Yii::info('expiration timestamp' . $expirationTimeStamp);
        if ($model === null) {
            Yii::info('Record does not exists');
            $model = new EmailConfirmation();
            $model->user_id = $user->id;
            $model->expiration_timestamp = $expirationTimeStamp;
            return $model;
        } else {
            Yii::info('Record alredy exists');
            $model->expiration_timestamp = $expirationTimeStamp;
            return $model;
        }
    }

    //
    //Checks if the request for reset password has expired
    //@return boolean 
    //
    public function forgotPasswordHasExpired()
    {
        Yii::info('type of expiration_timestamp = ' . gettype($this->expiration_timestamp));
        $now = (new \DateTime())->format('Y-m-d H:i:s');
        if ($now > $this->expiration_timestamp) {
            return true;
        }
        return false;
    }

    //
    //@return null|ActiveQuery
    //
    public static function findByForgotPasswordToken($token)
    {
        return EmailConfirmation::find()
                        ->where(['forgot_password' => $token])
                        ->one();
    }

}
