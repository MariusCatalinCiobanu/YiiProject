<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = 'Forgot password';

?>
<h1><?= Html::encode($this->title) ?></h1>
<?php $form = ActiveForm::begin([
        'id' => 'forgot-form',
        'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],
    ]); ?>

        <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>

        <div class="form-group">
            <div class="col-lg-offset-1 col-lg-11">
                <?= Html::submitButton('Reset password', ['class' => 'btn btn-primary', 'name' => 'reset-button']) ?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>