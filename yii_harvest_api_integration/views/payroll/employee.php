<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\data\ArrayDataProvider;
use app\models\Project;
use app\models\Department;

$this->params['breadcrumbs'][] = ['label' => 'Payroll: ' . $payroll->weekending, 'url' => ['payroll/' . $payroll->id]];
$this->params['breadcrumbs'][] = $employee->fullname;

?>
<div class="payroll-employee">
    <div class="row" style="margin-bottom: 20px;">
        <div class="col-lg-7">
            <h1>Payroll Employee - <?php echo $employee->fullname; ?> </h3>
            <h3>Pay period ending: <?php echo $payroll->weekending; ?></h3>
        </div>
        <div class="col-lg-1" style="text-align: right;"></div>
        <div class="col-lg-4" style="text-align: right; padding-top: 35px;">
            <?php echo Html::a('Reimport', ['payroll/reimport/' . $payroll->id . '/employee/' . $employee->id], ['class'=>'btn btn-primary']) ?>
        </div>
    </div>
    <div>
        <div>
            <?php if(!empty($time_entries)): ?>
        <h3>Current Time</h3>
        <?php 
            
            $dataProvider = new ArrayDataProvider([
                'allModels' => $time_entries,
                'pagination' => [
                    'pagesize' => 80,
                ],
            ]);
            
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
                        'footer' => Yii::$app->formatter->asDecimal($employee->getHours($time_entries), 2)
                    ],
                ],
                'showFooter' => true,
            ]); 
        ?>
    <?php endif; ?>
            
        </div>
        <div>Show current hours in database vs. Harvest</div>
        <div>Forms adding adjustments</div>
        <div>Show by project/task...</div>
        <div>OT Special</div>
    </div>
</div>