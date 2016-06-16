<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\data\ArrayDataProvider;
use app\models\Project;
use app\models\Department;

/* @var $this yii\web\View */
/* @var $model app\models\Employee */

$this->title = $model->first_name . " " . $model->last_name;
$this->params['breadcrumbs'][] = ['label' => 'Employees', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="employee-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'harvest_id',
            'atomic_id',
            'first_name',
            'last_name',
            'wage',
            'is_exempt',
            'created_on',
            'modified_on',
            'is_deleted',
        ],
    ]) ?>
    
    <?php if(!empty($model->wagehistory)): ?>
        <h3>Wage History</h3>
        <?php 
         
            $dataProvider = new ArrayDataProvider(['allModels' => $model->wagehistory]);
//            var_dump($dataProvider);
            echo GridView::widget([
                'dataProvider' => $dataProvider,
                'columns'=>['change_date', 'wage']
            ]); 
        ?>
    <?php endif; ?>
        
    <?php if(!empty($time)): ?>
        <h3>Current Time</h3>
        <?php 
            
            $dataProvider = new ArrayDataProvider(['allModels' => $time]);
            
            echo GridView::widget([
                'dataProvider' => $dataProvider,
                'columns'=>[
                    'spent-at', 'notes', 
                    [
                        'attribute'=>'Project',
                        'format' => 'raw',
                        'value'=>function ($data) {
                            $project_id = 'project-id';
                            $project = Project::find()->where(["harvest_id"=>$data->$project_id])->one();
                            if(!empty($project)){return $project->harvest_name;}else{return "-";}
                        },
                    ],
                    [
                        'attribute'=>'Task/Department',
                        'format' => 'raw',
                        'value'=>function ($data) {
                            $task_id = 'task-id';
                            $task = Department::find()->where(["harvest_id"=>$data->$task_id])->one();
                            if(!empty($task)){return $task->harvest_name;}else{return "-";}
                        },
                    ],
                    [
                        'attribute'=>'Hours',
                        'format' => 'raw',
                        'value'=>function ($data){
                            return $data->hours;
                        },
                        'footer' => Yii::$app->formatter->asDecimal($model->getHours($time), 2)
                    ],
                ],
                'showFooter' => true,
            ]); 
        ?>
    <?php endif; ?>
</div>