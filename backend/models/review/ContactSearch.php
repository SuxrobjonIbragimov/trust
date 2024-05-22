<?php

namespace backend\models\review;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\review\Contact;

/**
 * ContactSearch represents the model behind the search form of `backend\models\review\Contact`.
 */
class ContactSearch extends Contact
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'type', 'weight', 'status', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['full_name', 'phone', 'email', 'policy_series', 'policy_number', 'policy_issue_date', 'subject', 'message', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
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
        $query = Contact::find();

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
            'policy_issue_date' => $this->policy_issue_date,
            'user_id' => $this->user_id,
            'type' => $this->type,
            'weight' => $this->weight,
            'status' => $this->status,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_by' => $this->deleted_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ]);

        $query->andFilterWhere(['ilike', 'full_name', $this->full_name])
            ->andFilterWhere(['ilike', 'phone', $this->phone])
            ->andFilterWhere(['ilike', 'email', $this->email])
            ->andFilterWhere(['ilike', 'policy_series', $this->policy_series])
            ->andFilterWhere(['ilike', 'policy_number', $this->policy_number])
            ->andFilterWhere(['ilike', 'subject', $this->subject])
            ->andFilterWhere(['ilike', 'message', $this->message]);

        return $dataProvider;
    }
}
