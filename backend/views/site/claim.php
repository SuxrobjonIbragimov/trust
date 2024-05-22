<?php

use backend\models\review\Contact;
use backend\models\review\Subscribe;
use yii\widgets\Pjax;
use yii\grid\GridView;
use kartik\select2\Select2;
use backend\widgets\PageSize;
use backend\models\comment\Comments;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\comment\CommentsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('views', 'Страховой случай');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="comments box box-default">
    <div class="box-body">
        <?php Pjax::begin(['id' => 'pjax_comment']); ?>
        <?= PageSize::widget() ?>
        <div class="table-responsive">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'filterSelector' => 'select[name="per_page"]',
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    [
                        'attribute' => 'id',
                        'options' => ['width' => '70'],
                    ],
                    'full_name',
                    [
                        'attribute' => 'phone',
                        'format' => 'raw',
                        'value' => function (Contact $data) {
                            $full_str = '+'.$data->phone;
                            return mask_to_phone_format($full_str);
                        },
                    ],
                    [
                        'attribute' => 'policy_series',
                        'format' => 'raw',
                        'filter' => Contact::getPolicySeriesList(),
                        'value' => function (Contact $data) {
                            return $data->policy_series;
                        },
                    ],
                    'policy_number',
//                'policy_issue_date',
                    'message:ntext',
                    [
                        'attribute' => 'status',
                        'format' => 'raw',
                        'filter' => Contact::getStatusArray(),
                        'contentOptions' => function (Contact $data) {
                            return [
                                'class' => $data->getStatusName()
                            ];
                        },
                        'value' => function (Contact $data) {
                            return Select2::widget([
                                'name' => 'status',
                                'hideSearch' => true,
                                'id' => $data->id,
                                'value' => $data->status,
                                'data' => $data->getStatusArray(),
                                'theme' => Select2::THEME_DEFAULT,
                                'pluginEvents' => [
                                    'change' => 'function(e) {
                                    $("#overlay").show();
                                    $.ajax({
                                        url: "set-contact-status",
                                        type: "post",
                                        data: {
                                            id: parseInt(e.currentTarget.id),
                                            status: parseInt(e.currentTarget.value)
                                        },
                                        success: function (data) {
                                            $.pjax.reload({
                                                container: "#pjax_comment"
                                            }).done(function () {
                                                $("#overlay").hide();
                                            });
                                        }
                                    });
                                }',
                                ],
                            ]);
                        },
                    ],
                    [
                        'attribute' => 'created_at',
//                    'format' => 'datetime',
                        'options' => ['width' => '100'],
                        'filter' => false,
                    ],

                ],
            ]); ?>
        </div>
        <?php Pjax::end(); ?>
    </div>
</div>
