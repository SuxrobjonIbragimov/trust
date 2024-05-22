<?php

namespace backend\modules\telegram\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\telegram\models\BotUser;

/**
 * BotUserSearch represents the model behind the search form of `backend\modules\telegram\models\BotUser`.
 */
class BotUserSearch extends BotUser
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 't_id', 'message_id_l', 'message_id_d', 'message_id_e', 'status', 'created_at', 'updated_at'], 'integer'],
            [['is_bot', 'is_admin'], 'boolean'],
            [['first_name', 'last_name', 't_username', 'phone', 'language_code', 'callback_data', 'current_product', 'current_step_type', 'current_step_val', 'is_premium', 'info'], 'safe'],
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
        $query = BotUser::find();

        if (!\Yii::$app->user->can('accessAdministrator')) {
            $query->andWhere(['!=', 'bot_user.status', self::STATUS_DELETED]);
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
            't_id' => $this->t_id,
            'is_bot' => $this->is_bot,
            'message_id_l' => $this->message_id_l,
            'message_id_d' => $this->message_id_d,
            'message_id_e' => $this->message_id_e,
            'is_admin' => $this->is_admin,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['ilike', 'first_name', $this->first_name])
            ->andFilterWhere(['ilike', 'last_name', $this->last_name])
            ->andFilterWhere(['ilike', 't_username', $this->t_username])
            ->andFilterWhere(['ilike', 'phone', $this->phone])
            ->andFilterWhere(['ilike', 'language_code', $this->language_code])
            ->andFilterWhere(['ilike', 'callback_data', $this->callback_data])
            ->andFilterWhere(['ilike', 'current_product', $this->current_product])
            ->andFilterWhere(['ilike', 'current_step_type', $this->current_step_type])
            ->andFilterWhere(['ilike', 'current_step_val', $this->current_step_val])
            ->andFilterWhere(['ilike', 'is_premium', $this->is_premium])
            ->andFilterWhere(['ilike', 'info', $this->info]);

        return $dataProvider;
    }
}
