<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\Carousel;
use app\assets\TripViewAsset;
use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $model app\models\Trip */

TripViewAsset::register($this);

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Trips', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="trip-view">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php
    $images = $model->getTripImage()->all();
    Yii::info('view/Trip/ show tripImages paths');
    $carouselArray = array();
    foreach ($images as $image) {
        Yii::info($image->image_path);
        $carouselArray[] = ['content' => Html::img($image->image_path, ['class' => 'img-responsive'])];
        Yii::info('carousel elements: ' . json_encode($carouselArray));
    }
    ?>
    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?=
        Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ])
        ?>
    </p>
    <?=
    Carousel::widget([
        'items' => $carouselArray
    ])
    ?>
    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            'description',
            'destination_country',
            'destination_city',
            'destination_latitude',
            'destination_longitude',
            'home_latitude',
            'home_longitude'
        ],
    ])
    ?>
    <div id="map" style="width:100%;height:300px;"></div>
    <?php
    $homeCoordinates = "{$model->home_latitude}, {$model->home_longitude}";
    $destinationCoordinates = "{$model->destination_latitude}, {$model->destination_longitude}";
    ?>
    <script>
        var homeCoordinates = '<?= $homeCoordinates ?>';
        var destinationCoordinates = '<?= $destinationCoordinates ?>';
        var destinationCity = '<?= $model->destination_city ?>';
        console.log('Home coordonates are:' + homeCoordinates);
        console.log('Destination city: ' + destinationCity);
    </script>
</div>
