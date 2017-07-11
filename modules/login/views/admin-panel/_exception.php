<?php use yii\helpers\Html;
?>
<?= '<div class="alert alert-danger">'; ?>
<?= nl2br(Html::encode($exception['message'])); ?>
<?= '</div>'; ?>

