<?php

namespace backend\models\page;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\page\SourceCounter;

/**
 * SourceCounterSearch represents the model behind the search form of `backend\models\page\SourceCounter`.
 */
class SourceCounterSearch extends SourceCounter
{
    public $count_buyer;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'count', 'weight', 'status', 'created_at', 'updated_at'], 'integer'],
            [['name', 'code', 'lang', 'redirect_url'], 'safe'],
            [['count_buyer', ], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = SourceCounter::find();

        if (!\Yii::$app->user->can('accessAdministrator')) {
            $query->andWhere(['!=', 'status', self::STATUS_DELETED]);
        }
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['id' => SORT_DESC]],
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
            'count' => $this->count,
            'weight' => $this->weight,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['ilike', 'name', $this->name])
            ->andFilterWhere(['ilike', 'code', $this->code])
            ->andFilterWhere(['ilike', 'lang', $this->lang])
            ->andFilterWhere(['ilike', 'redirect_url', $this->redirect_url]);

        return $dataProvider;
    }
}
