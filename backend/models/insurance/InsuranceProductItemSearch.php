<?php

namespace backend\models\insurance;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\insurance\InsuranceProductItem;

/**
 * InsuranceProductItemSearch represents the model behind the search form of `backend\models\insurance\InsuranceProductItem`.
 */
class InsuranceProductItemSearch extends InsuranceProductItem
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'insurance_product_id', 'parent_id', 'weight', 'status', 'created_at', 'updated_at'], 'integer'],
            [['title', 'type', 'description', 'image', 'icon'], 'safe'],
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
        $query = InsuranceProductItem::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['weight' => SORT_ASC, 'id' => SORT_DESC]],
        ]);

        if (isset($params['per_page']))
            $dataProvider->pagination->pageSize = $params['per_page'];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'insurance_product_id' => $this->insurance_product_id,
            'parent_id' => $this->parent_id,
            'weight' => $this->weight,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['ilike', 'title', $this->title])
            ->andFilterWhere(['ilike', 'type', $this->type])
            ->andFilterWhere(['ilike', 'description', $this->description])
            ->andFilterWhere(['ilike', 'image', $this->image])
            ->andFilterWhere(['ilike', 'icon', $this->icon]);

        return $dataProvider;
    }
}
