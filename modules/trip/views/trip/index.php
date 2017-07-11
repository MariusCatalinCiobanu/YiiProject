<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TripSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Trips';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="trip-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Trip', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
//            'description',
            'destination_country',
            'destination_city',
//             'destination_coordinates',
//             'home_coordinates',
//             'user_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
