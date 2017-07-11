<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\UsersAdmin */

$this->title = 'Create Users Admin';
$this->params['breadcrumbs'][] = ['label' => 'Users Admins', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="users-admin-create">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php
    if (isset($exception)) {
        echo $this->render('@app/views/shared/_exception', [
            'exception' => $exception,
        ]);
    }
    ?>
    <?=
    $this->render('_form', [
        'model' => $model,
        'roles' => $roles,
    ])
    ?>

</div>
