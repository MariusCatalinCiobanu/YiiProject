<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Trip */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="trip-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textArea(['rows' => 4, 'maxlength' => true]) ?>

    <?= $form->field($model, 'destination_country')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'destination_city')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'destination_latitude')->textInput(['maxlength' => true, 'id' => 'destinationLatitude']) ?>

    <?= $form->field($model, 'destination_longitude')->textInput(['maxlength' => true, 'id' => 'destinationLongitude']) ?>

    <?= $form->field($model, 'home_latitude')->textInput(['maxlength' => true, 'id' => 'homeLatitude']) ?>
    
    <?= $form->field($model, 'home_longitude')->textInput(['maxlength' => true, 'id' => 'homeLongitude']) ?>

    <?= $form->field($model, 'pictures[]')->fileInput(['multiple' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
