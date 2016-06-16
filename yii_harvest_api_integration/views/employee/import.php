<?php

use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;

$this->title = 'Employee Import';
$this->params['breadcrumbs'][] = ['label' => 'Employees', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="employee-import">
    <h1><?= Html::encode($this->title) ?></h1>
<?php
    $dataProvider = new ArrayDataProvider(['allModels' => $new_employees]);
//    var_dump($new_employees);
    echo GridView::widget([
       'dataProvider' => $dataProvider,
       'columns'=>[
            'id', 
            'first-name', 
            'last-name', 
            'is-active',
            [
                'attribute'=>'Action',
                'format' => 'raw',
                'value'=>function ($data) {
                    return Html::a('Import', array('/employee/import/' . $data->id), ['class' => 'btn btn-success']);
                },
            ],
        ]
    ]); 
?>
</div>