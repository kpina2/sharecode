<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Employees';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="employee-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php //  Html::a('Create Employee', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php if(!empty($new_employees)): ?>
        <p>
            <?php echo (count($new_employees) > 1 ? "There are new ".count($new_employees)." employees ready to import" : "There is a new employee ready to import"); ?>
        </p>
        <?php echo Html::a('Import New Employees', ['import'], ['class' => 'btn btn-success']) ?>
    <?php endif; ?>
        <?php // $dataProvider->sort = ; ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],
            [ 
                'attribute' => 'id',
            ],
            
            'harvest_id',
            'atomic_id',
            'first_name',
            'last_name',
             'wage',
            'is_exempt',
             'created_on',
//             'modified_on',
             'is_deleted',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
