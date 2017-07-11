<?php

namespace app\modules\trip\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Trip;

/**
 * TripsSearch2 represents the model behind the search form of `app\models\Trip`.
 */
class TripSearch extends Trip
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id'], 'integer'],
            [['name', 'description', 'destination_country', 'destination_city'], 'safe'],
            [['destination_latitude', 'destination_longitude', 'home_latitude', 'home_longitude'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Trip::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 10],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        //only show the trips for the current user
        $query->andFilterWhere(['user_id' => Yii::$app->user->id]);

        // grid filtering conditions
        $query->andFilterWhere([
            
            //only show the trips for the current user
            'id' => $this->id,
            'destination_latitude' => $this->destination_latitude,
            'destination_longitude' => $this->destination_longitude,
            'home_latitude' => $this->home_latitude,
            'home_longitude' => $this->home_longitude,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
                ->andFilterWhere(['like', 'description', $this->description])
                ->andFilterWhere(['like', 'destination_country', $this->destination_country])
                ->andFilterWhere(['like', 'destination_city', $this->destination_city]);

        return $dataProvider;
    }

}
