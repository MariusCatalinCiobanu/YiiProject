<?php

use yii\helpers\Html;
use app\assets\TripUpdateAsset;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Trip */

TripUpdateAsset::register($this);
$this->title = 'Update Trip: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Trips', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>

<div class="trip-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>
</div>
<?= $this->render('_getCoordinates'); ?>
<hr/>
<h1 class="text-center"> Trip pictures</h1>
<div id="picturesForUpdate">
</div>

<div class="modal fade" id="confirmDeletePicture" tabindex="-1" role="dialog" aria-labelledBy="confirmDeletePictureLabel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Delete picture</h4>
            </div>
            <div class="modal-body">
                <p> Are you sure you want to delete this picture?</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-default" type="button" data-dismiss="modal">Cancel</button>
                <button class="btn btn-danger btn-ok" type="button" onclick="deletePictureOK()">Delete</button>
            </div>
        </div>
    </div>
</div>

<script>
    var destinationCity = '<?= $model->destination_city ?>';
    var destinationLatitudeVar = '<?= $model->destination_latitude ?>';
    var destinationLongitudeVar = '<?= $model->destination_longitude ?>';
    var homeLatitudeVar = '<?= $model->home_latitude ?>';
    var homeLongitudeVar = '<?= $model->home_longitude ?>';
</script>

