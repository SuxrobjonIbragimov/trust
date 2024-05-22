<?php

namespace backend\models\sliders;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\sliders\SliderItems;

/**
 * SliderItemsSearch represents the model behind the search form about `backend\models\sliders\SliderItems`.
 */
class SliderItemsSearch extends SliderItems
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'slider_id', 'weight', 'status', 'created_at', 'updated_at'], 'integer'],
            [['title', 'image', 'link', 'description'], 'safe'],
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
        $query = SliderItems::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['weight' => SORT_ASC, 'id' => SORT_DESC]],
        ]);

        $dataProvider->sort->defaultOrder = ['weight' => SORT_ASC];

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
            'slider_id' => $this->slider_id,
            'weight' => $this->weight,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'image', $this->image])
            ->andFilterWhere(['like', 'link', $this->link])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
