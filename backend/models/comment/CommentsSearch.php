<?php

namespace backend\models\comment;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * CommentsSearch represents the model behind the search form about `backend\models\comment\Comments`.
 */
class CommentsSearch extends Comments
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'model_id', 'author_id', 'parent_id', 'likes', 'unlikes', 'status', 'created_at', 'updated_at'], 'integer'],
            [['model', 'text', 'sessions'], 'safe'],
            [['rating'], 'number'],
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
        $query = Comments::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->defaultOrder = ['id' => SORT_DESC];
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
            'model_id' => $this->model_id,
            'author_id' => $this->author_id,
            'parent_id' => $this->parent_id,
            'rating' => $this->rating,
            'likes' => $this->likes,
            'unlikes' => $this->unlikes,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'model', $this->model])
            ->andFilterWhere(['like', 'text', $this->text])
            ->andFilterWhere(['like', 'sessions', $this->sessions]);

        return $dataProvider;
    }
}
