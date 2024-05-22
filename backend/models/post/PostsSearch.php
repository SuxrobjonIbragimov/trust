<?php

namespace backend\models\post;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * PostsSearch represents the model behind the search form about `backend\models\post\Posts`.
 */
class PostsSearch extends Posts
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'category_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['slug', 'title', 'image', 'summary', 'body', 'meta_title', 'meta_keywords', 'meta_description'], 'safe'],
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
        $query = Posts::find();

        if (!\Yii::$app->user->can('accessAdministrator')) {
            $query->andWhere(['!=', 'status', Posts::STATUS_DELETED]);
        }

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
            'category_id' => $this->category_id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['ilike', 'slug', $this->slug])
            ->andFilterWhere(['ilike', 'title', $this->title])
            ->andFilterWhere(['ilike', 'image', $this->image])
            ->andFilterWhere(['ilike', 'summary', $this->summary])
            ->andFilterWhere(['ilike', 'body', $this->body])
            ->andFilterWhere(['ilike', 'meta_title', $this->meta_title])
            ->andFilterWhere(['ilike', 'meta_keywords', $this->meta_keywords])
            ->andFilterWhere(['ilike', 'meta_description', $this->meta_description]);

        return $dataProvider;
    }
}
