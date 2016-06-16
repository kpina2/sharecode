<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\PayrollOtherEarningsCode */

$this->title = 'Create Earnings Code';
$this->params['breadcrumbs'][] = ['label' => 'Payroll Other Earnings Codes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="payroll-other-earnings-code-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
