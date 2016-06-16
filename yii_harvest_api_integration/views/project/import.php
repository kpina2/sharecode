<?php

use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use app\models\HarvestModel;
use app\models\Company;

$this->title = 'Projects Import';
$this->params['breadcrumbs'][] = ['label' => 'Projects', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="project-import">
    <h1><?= Html::encode($this->title) ?></h1>
 
<?php
    $harvest = new HarvestModel;
    
    $dataProvider = new ArrayDataProvider(['allModels' => $new_projects]);
    echo GridView::widget([
       'dataProvider' => $dataProvider,
       'columns'=>[
            'id', 
            'name',
            'code',
            'active',
            [
                'attribute'=>'Client',
                'format' => 'raw',
                'value'=>function ($data) {
                    $client_id = "client-id";
                    $company = Company::find()->where(["harvest_id"=>$data->$client_id])->one();
                    if(!empty($company)){return $company->harvest_name;}else{return "-";}
                },
            ],
            [
                'attribute'=>'Action',
                'format' => 'raw',
                'value'=>function ($data) {
                    return Html::a('Import', array('/project/import/' . $data->id), ['class' => 'btn btn-success']);
                },
            ],
        ]
    ]); 
?>
</div>