<?php

use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;

$this->title = 'Department Import';
$this->params['breadcrumbs'][] = ['label' => 'Departments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="department-import">
    <h1><?= Html::encode($this->title) ?></h1>
<?php
    $dataProvider = new ArrayDataProvider(['allModels' => $new_departments]);
    echo GridView::widget([
       'dataProvider' => $dataProvider,
       'columns'=>[
            'id', 
            'name', 
            'deactivated',
            [
                'attribute'=>'Action',
                'format' => 'raw',
                'value'=>function ($data) {
                    return Html::a('Import', array('/department/import/' . $data->id), ['class' => 'btn btn-success']);
                },
            ],
        ]
    ]); 
?>
</div>