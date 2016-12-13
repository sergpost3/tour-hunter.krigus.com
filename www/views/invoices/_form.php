<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Invoices */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="invoices-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'recipient')->textInput()->label("Recipient"); ?>

    <?= $form->field($model, 'total')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Create', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
