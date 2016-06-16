<?php

use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;

$this->title = 'Import';
$this->params['breadcrumbs'][] = ['label' => 'Companies', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="company-import">
    <h1><?= Html::encode($this->title) ?></h1>
<?php
    $dataProvider = new ArrayDataProvider(['allModels' => $new_companies]);
    echo GridView::widget([
       'dataProvider' => $dataProvider,
       'columns'=>[
            'id', 
            'name', 
            'active',
            [
                'attribute'=>'Action',
                'format' => 'raw',
                'value'=>function ($data) {
                    return Html::a('Import', array('/company/import/' . $data->id), ['class' => 'btn btn-success']);
                },
            ],
        ]
    ]); 
?>
</div>