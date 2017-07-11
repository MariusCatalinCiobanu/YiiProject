<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UsersAdminSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Admin Panel';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="users-admin-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create User', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php
    Yii::info('Views/admin-panel/ index, dataProvider structure: ' . json_encode($dataProvider));
    // $dataProvider->createdBy = $dataProvider->getCreatedBy()->one()->email;
    // $dataProvider->updatedBy = $dataProvider->getUpdatedBy()->one()->email;
    ?>
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'email:email',
            'type',
            [
                'attribute' => 'created_by',
                'value' => function($data) {
                    return $data->updatedBy->email;
                }
            ],
            [
                'attribute' => 'updated_by',
                'value' => function($data) {
                    return $data->updatedBy->email;
                }
            ],
            'created_at',
            'updated_at',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);
    ?>
</div>
