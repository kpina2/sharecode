<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PayrollOtherEarningsCode */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="payroll-other-earnings-code-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'code_id')->hiddenInput(['value' => "[unused]"]) ?>

    <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<style>
    .field-payrollotherearningscode-code_id{
        display: none;
    }
</style>
