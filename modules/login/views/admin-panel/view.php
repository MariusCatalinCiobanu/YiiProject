<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\UsersAdmin */
//var_dump($model);
Yii::info('The model looks like:' . json_encode($model));
$this->title = $model->email;
$this->params['breadcrumbs'][] = ['label' => 'Users Admins', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="users-admin-view">

    <h1><?= Html::encode($this->title) ?></h1>

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
    <?php
    ?>
    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'email:email',
            'type',
            [
                'attribute' => 'Created By',
                'value' => $model->createdBy->email
            ],
            [
                'attribute' => 'Updated By',
                'value' => $model->updatedBy->email
            ],
//            'created_by',
//            'updated_by',
            'created_at',
            'updated_at'
        ],
    ])
    ?>

</div>
