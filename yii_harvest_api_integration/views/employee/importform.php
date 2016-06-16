<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Company */

$this->title = 'Import Employee';
$this->params['breadcrumbs'][] = ['label' => 'Employees', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="employee-import-form">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'harvest_id')->textInput(['disabled' => true]) ?>

    <?= $form->field($model, 'atomic_id')->textInput() ?>

    <?= $form->field($model, 'first_name')->textInput(['maxlength' => true, 'disabled' => true]) ?>

    <?= $form->field($model, 'last_name')->textInput(['maxlength' => true, 'disabled' => true]) ?>

    <?php $form->field($model, 'wage')->textInput(['maxlength' => true]); ?>

    <?= $form->field($model, 'is_deleted')->dropDownList(array("0" => "No", "1"  => "Yes")); ?>
    
    <?= $form->field($model, 'is_exempt')->dropDownList(array("0" => "No", "1"  => "Yes")); ?>

    <div class="form-group">
        <?= Html::submitButton('Import', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
