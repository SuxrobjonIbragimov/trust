<?php

namespace backend\models\insurance;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\insurance\InsuranceProduct;

/**
 * InsuranceProductSearch represents the model behind the search form of `backend\models\insurance\InsuranceProduct`.
 */
class InsuranceProductSearch extends InsuranceProduct
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'parent_id', 'views', 'weight', 'status', 'created_at', 'updated_at'], 'integer'],
            [['title', 'subtitle', 'slug', 'summary', 'description', 'image', 'icon', 'meta_title', 'meta_keywords', 'meta_description'], 'safe'],
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
    public function search($params,$type = null)
    {
        $query = InsuranceProduct::find();
        if (!empty($type))
        {
            $query->joinWith(['insuranceProductToLegalTypes'])->andWhere(['insurance_product_to_legal_type.legal_type_id' => $type]);
        }
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['id' => SORT_ASC]],
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
            'views' => $this->views,
            'weight' => $this->weight,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['ilike', 'title', $this->title])
            ->andFilterWhere(['ilike', 'subtitle', $this->subtitle])
            ->andFilterWhere(['ilike', 'slug', $this->slug])
            ->andFilterWhere(['ilike', 'summary', $this->summary])
            ->andFilterWhere(['ilike', 'description', $this->description])
            ->andFilterWhere(['ilike', 'image', $this->image])
            ->andFilterWhere(['ilike', 'icon', $this->icon])
            ->andFilterWhere(['ilike', 'meta_title', $this->meta_title])
            ->andFilterWhere(['ilike', 'meta_keywords', $this->meta_keywords])
            ->andFilterWhere(['ilike', 'meta_description', $this->meta_description]);

        return $dataProvider;
    }
}
