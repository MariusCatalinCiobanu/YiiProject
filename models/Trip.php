<?php

namespace app\models;

use Yii;
use yii\db\Query;

/**
 * This is the model class for table "trip".
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $destination_country
 * @property string $destination_city
 * @property double $destination_latitude
 * @property double $destination_longitude
 * @property double $home_latitude
 * @property double $home_longitude
 * @property int $user_id
 *
 * @property User $user
 */
class Trip extends \yii\db\ActiveRecord
{

    public function rules()
    {
        return [
            [['destination_latitude', 'destination_longitude', 'home_latitude', 'home_longitude'], 'number'],
            [['name', 'destination_country', 'destination_city'], 'string', 'max' => 45],
            [['description'], 'string', 'max' => 2000],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'description' => 'Description',
            'destination_country' => 'Destination Country',
            'destination_city' => 'Destination City',
            'destination_latitude' => 'Destination Latitude',
            'destination_longitude' => 'Destination Longitude',
            'home_latitude' => 'Home Latitude',
            'home_longitude' => 'Home Longitude',
            'user_id' => 'User ID',
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'trip';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTripImage()
    {
        return $this->hasMany(TripImage::className(), ['trip_id' => 'id']);
    }

    //
    //This method adds the current user id to each record before saving
    //@return boolean
    //
    public function beforeSave($insert)
    {
        Yii::info('Trip user_id:' . Yii::$app->user->id);
        $this->user_id = Yii::$app->user->id;
        if (!parent::beforeSave($insert)) {
            return false;
        }

        return true;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    //
    //@return \yii\db\ActiveQuery
    //
    public static function getTrip($tripId)
    {
        return Trip::findOne($tripId);
    }

    //
    //The method checks if the user has authorization to view the trip with the id given
    //@return boolean access value
    //
    public static function hasViewAuthorization($id)
    {
        return Trip::isAuthor($id);
    }

    //
    //The method checks if the user has authorization to delete the trip with the id given
    //@return boolean access value
    //
    public static function hasDeleteAuthorization($id)
    {
        return Trip::isAuthor($id);
    }

    //
    //The method checks if the user has authorization to update the trip with the id given
    //@return boolean access value
    //
    public static function hasUpdateAuthorization($id)
    {
        return Trip::isAuthor($id);
    }

    //
    //The method checks if the authenticated user is the author of the trip
    //with the id given
    //@param integer id the trip id
    //@return boolean if the authentificated user is the author or not of the
    //trip with the id given
    //
    public static function isAuthor($id)
    {
        $query = new Query();
        $model = $query->select("user.id")
                ->from('trip')
                ->innerJoin('user', 'user.id = trip.user_id')
                ->andWhere(['trip.id' => $id])
                ->one();
        $authordId = $model['id'];

        //the authenticated user id
        $userId = Yii::$app->user->id;
        Yii::info('authordId = ' . $authordId . ' , userId = ' . $userId);
        if ($authordId == $userId) {
            return true;
        } else {
            return false;
        }
    }

}
