<?php
use yii\helpers\Html;
use yii\helpers\Url;
Yii::info(json_encode($model));
/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\BaseMessage instance of newly created mail message */

?>
<h2>Click below to reset your password</h2>

<?= Html::a('Click me', $model['url']) ?>