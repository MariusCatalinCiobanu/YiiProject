<?php

namespace app\modules\login\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\login\models\UsersAdmin;

/**
 * UsersAdminSearch represents the model behind the search form of `app\models\UsersAdmin`.
 */
class UsersAdminSearch extends UsersAdmin
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email', 'type',], 'safe'],
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
        $query = UsersAdmin::find()
                ->from('user u')
                ->innerJoinWith('createdBy cb')
                ->innerJoinWith('updatedBy ub');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 10],
            'sort' => [
                'defaultOrder' => [
                    'email' => SORT_ASC
                ]
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'u.email', $this->email])
                ->andFilterWhere(['like', 'u.type', $this->type]);

        return $dataProvider;
    }

}
