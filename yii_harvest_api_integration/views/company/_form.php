<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Company */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="company-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'harvest_id')->textInput() ?>

    <?= $form->field($model, 'harvest_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'atomic_id')->textInput() ?>

    <?= $form->field($model, 'is_deleted')->dropDownList(array("0" => "No", "1"  => "Yes")); ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
