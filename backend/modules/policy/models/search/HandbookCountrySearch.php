<?php

namespace backend\modules\policy\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\policy\models\HandbookCountry;

/**
 * HandbookCountrySearch represents the model behind the search form of `backend\modules\policy\models\HandbookCountry`.
 */
class HandbookCountrySearch extends HandbookCountry
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'parent_id', 'ins_id', 'weight', 'status', 'created_at', 'updated_at'], 'integer'],
            [['name_ru', 'name_uz', 'name_en', 'code', 'flag'], 'safe'],
            [['is_shengen'], 'boolean'],
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
        $query = HandbookCountry::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
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
            'parent_id' => $this->parent_id,
            'ins_id' => $this->ins_id,
            'is_shengen' => $this->is_shengen,
            'weight' => $this->weight,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['ilike', 'name_ru', $this->name_ru])
            ->andFilterWhere(['ilike', 'name_uz', $this->name_uz])
            ->andFilterWhere(['ilike', 'name_en', $this->name_en])
            ->andFilterWhere(['ilike', 'code', $this->code])
            ->andFilterWhere(['ilike', 'flag', $this->flag]);

        return $dataProvider;
    }
}
