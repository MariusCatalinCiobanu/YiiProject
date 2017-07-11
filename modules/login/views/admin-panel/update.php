<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\UsersAdmin */

$this->title = 'Update User: ' . $model->email;
$this->params['breadcrumbs'][] = ['label' => 'Admin Panel', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->email, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="users-admin-update">

    <h1><?= Html::encode($this->title) ?></h1>
    <?=
    $this->render('_form', [
        'model' => $model,
        'roles' => $roles,
    ])
    ?>

</div>
