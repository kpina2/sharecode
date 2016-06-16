<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\data\ArrayDataProvider;
use app\models\Project;
use app\models\Department;

/* @var $this yii\web\View */
/* @var $model app\models\Employee */

$this->title = "Payroll History";
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="payroll-history">
    <h1><?php echo $this->title; ?></h1>
    <div class="col-lg-2">
        <p><b>Week Beginning: </b></p>
        <?php foreach($payperiods as $payperiod): ?>
            <p><?php echo Html::a($payperiod, ['history', 'lookup' => $payperiod]); ?></p>
        <?php endforeach; ?>
    </div>
    <div class="col-lg-10">
        <?php if(!empty($employee_time_list)): ?>
            <?php foreach($employee_time_list as $harvest_id => $employee): ?>
                <b><?php echo $employee['employee_data']->fullname; ?></b>
                <?php $dataProvider = new ArrayDataProvider(['allModels' => $employee['time_entries']]); ?>
                <?php 
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
                                    if(!empty($project)){return $project->harvest_name;}else{return "Harvest ID: " . $data->$project_id;}
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
                                'footer' => Yii::$app->formatter->asDecimal($employee['employee_data']->getHours($employee['time_entries']), 1)
                            ],
                        ],
                        'showFooter' => true,
                    ]);
                ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>