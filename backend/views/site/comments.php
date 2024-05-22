<?php

use yii\widgets\Pjax;
use yii\grid\GridView;
use kartik\select2\Select2;
use backend\widgets\PageSize;
use backend\models\comment\Comments;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\comment\CommentsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('views', 'Comments');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="comments box box-default">
    <div class="box-body">
        <?php Pjax::begin(['id' => 'pjax_comment']); ?>
        <?= PageSize::widget() ?>
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
                'model',
                'model_id',
                [
                    'attribute' => 'author_id',
                    'format' => 'raw',
                    'filter' => Comments::getAuthorsList(),
                    'value' => function (Comments $data) {
                        return $data->author->email;
                    },
                ],
                'rating',
                'text:ntext',
                'likes',
                'unlikes',
                [
                    'attribute' => 'status',
                    'format' => 'raw',
                    'filter' => Comments::getStatusArray(),
                    'contentOptions' => function (Comments $data) {
                        return [
                            'class' => $data->getStatusClass()
                        ];
                    },
                    'value' => function (Comments $data) {
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
                                        url: "set-comment-status",
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
                    'format' => 'datetime',
                    'options' => ['width' => '100'],
                    'filter' => false,
                ],

            ],
        ]); ?>
        <?php Pjax::end(); ?>
    </div>
</div>
