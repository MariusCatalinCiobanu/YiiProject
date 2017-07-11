<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "trip_image".
 *
 * @property int $id
 * @property string $image_path
 * @property int $trip_id
 *
 * @property Trip $trip
 */
class TripImage extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'trip_image';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['image_path', 'trip_id'], 'required'],
            [['image_path'], 'string', 'max' => '100'],
            [['trip_id'], 'integer'],
            [['trip_id'], 'exist', 'skipOnError' => true, 'targetClass' => Trip::className(), 'targetAttribute' => ['trip_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'image_path' => 'Image Path',
            'trip_id' => 'Trips ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrip()
    {
        return $this->hasOne(Trip::className(), ['id' => 'trip_id']);
    }

    //
    //The image from server is also deleted
    //
    public function afterDelete()
    {
        Yii::info('After delete:' . Yii::getAlias('@webroot') . $this->image_path);
        unlink(Yii::getAlias('@webroot') . $this->image_path);
    }

}
