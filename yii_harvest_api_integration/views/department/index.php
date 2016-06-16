<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Departments';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="department-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php // Html::a('Create Department', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php if(!empty($new_departments)): ?>
        <p>
            <?php echo (count($new_departments) > 1 ? "There are new ".count($new_departments)." departments ready to import" : "There is a new department ready to import"); ?>
        </p>
        <?php echo Html::a('Import New Departments', ['import'], ['class' => 'btn btn-success']) ?>
    <?php endif; ?>
        
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],

//            'id',
            'harvest_id',
            'harvest_name',
            'atomic_id',
            'adp_code',
            'created_on',
            'exclude',
            // 'modified_on',
            // 'is_deleted',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
