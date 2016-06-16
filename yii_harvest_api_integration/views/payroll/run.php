<?php

use yii\helpers\Html;
use app\models\Employee;
use app\models\Project;
use app\models\Department;

$this->title = "Run Payroll " . strtoupper($payroll->type) . " " . $payroll->weekending;
$this->params['breadcrumbs'][] = $this->title;

$departments = Department::getDepartmentArray();

?>
<div class="payroll-run">
    <div class="row" style="margin-bottom: 20px;">
        <div class="col-lg-7">
            <h1>Run Payroll - <?php echo strtoupper($payroll->type); ?> </h3>
            <h3>Pay period ending: <?php echo $payroll->weekending; ?></h3>
        </div>
        <div class="col-lg-1" style="text-align: right;"></div>
        <div class="col-lg-4" style="text-align: right; padding-top: 35px;">
            <?php echo Html::a('Export', ['payroll/export/' . $payroll->id], ['class'=>'btn btn-primary']) ?>
            <?php echo Html::a('Export - Summary', ['payroll/summary/' . $payroll->id], ['class'=>'btn btn-primary']) ?>
            <?php echo Html::a('Uncomplete', ['payroll/uncomplete/' . $payroll->id], ['class'=>'btn btn-warning uncomplete-warn']) ?>
        </div>
    </div>
    <div>
        <table>
            <tr>
                <th>Co Code</th>
                <th>Batch Id</th>
                <th>File #</th>
                <th>Last Name</th>
                <th>First Name</th>
                <th>Temp Dept</th>
                <th>Rate 1</th>
                <th>Reg Hours</th>
                <th>O/T Hours</th>
                <th>Other Hours Code</th>
                <th>Other Hours Amount</th>
                <th>Other Earnings Code</th>
                <th>Other Earnings Amount</th>
                <th>Adjust Ded Code</th>
                <th>Adjust Ded Amount</th>
            </tr>
            <?php function sort_by_name_2($a, $b){
                $employee_a = Employee::find()->where("harvest_id = " . $a->employee_harvest_id)->one();
                $employee_b = Employee::find()->where("harvest_id = " . $b->employee_harvest_id)->one();
                
//                var_dump($employee_a);
//                var_dump($employee_b);
//                exit;
                $return = 0;
                 if ( empty($employee_a) || empty($employee_b)){
                    if( empty($employee_b)){
                        $return = -1;
                    }else{
                        $return = 1;
                    }
                }else{
                    if($employee_a->last_name == $employee_b->last_name){
                        $return = 0;
                    }
                    $return = ($employee_a->last_name < $employee_b->last_name) ? -1 : 1;
                }
                return $return;
            }?>
            <?php $testarray = $payroll->payrollitems; ?>
            <?php uasort($testarray, "sort_by_name_2"); ?>
        <?php foreach($testarray as $payrollitem): ?>
            <?php $payrollhours = $payrollitem->payrollitemhours; ?>
            <?php $payroll_other_hours = $payrollitem->payrollitemotherhours; ?>
            <?php $employee = Employee::find()->where("harvest_id = " . $payrollitem->employee_harvest_id)->one(); ?>
            <?php if(!empty($employee)): ?>
                <?php if($employee->is_deleted){continue;} ?>
                    <?php foreach($payrollhours as $itemhours): ?>
                        <tr>
                            <td>5HQ</td>
                            <td>5HQ15071-01</td>
                            <td><?php echo $employee->atomic_id; ?></td>
                            <td><?php echo Html::a($employee->last_name, ['/employee/' . $employee->id], ["target"=>'_blank']); ?></td>
                            <td><?php echo Html::a($employee->first_name, ['/employee/' . $employee->id], ["target"=>'_blank']); ?></td>
                            <td><?php echo $itemhours->project_harvest_id; ?></td>
                            <?php if(!empty($itemhours->wage) && $itemhours->wage != '0.00'): ?>
                                <td><?php echo $itemhours->wage; ?></td>
                            <?php else: ?>
                                <td><?php echo $payrollitem->wage; ?></td>
                            <?php endif; ?>
                            <td><?php echo $itemhours->hours_regular; ?></td>
                            <td><?php echo $itemhours->hours_overtime; ?></td>
                            
                            <?php if(!empty($itemhours->hours_doubletime) && $itemhours->hours_doubletime != '0.00'): ?>
                                <?php // var_dump($itemhours->hours_doubletime != "0.00"); ?>
                                <td>D</td>
                                <td><?php echo $itemhours->hours_doubletime; ?></td>
                            <?php else: ?>
                                <td></td>
                                <td></td>
                            <?php endif; ?>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    <?php endforeach; ?>
                    
                    <?php foreach($payroll_other_hours as $itemhours): ?>
                        <?php if($departments[$itemhours->department_harvest_id]->adp_code == 'E'){continue;} ?>
                        <tr>
                            <td>5HQ</td>
                            <td>5HQ15071-01</td>
                            <td><?php echo $employee->atomic_id; ?></td>
                            <td><?php echo Html::a($employee->last_name, ['/employee/' . $employee->id], ["target"=>'_blank']); ?></td>
                            <td><?php echo Html::a($employee->first_name, ['/employee/' . $employee->id], ["target"=>'_blank']); ?></td>
                            <td><?php echo $itemhours->project_harvest_id; ?></td>
                            <?php if(!empty($itemhours->wage) && $itemhours->wage != '0.00'): ?>
                                <td><?php echo $itemhours->wage; ?></td>
                            <?php else: ?>
                                <td><?php echo $payrollitem->wage; ?></td>
                            <?php endif; ?>
                            <td></td>
                            <td></td>
                            <td><?php echo $departments[$itemhours->department_harvest_id]->adp_code; ?></td>
                            <td><?php echo $itemhours->hours; ?></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    <?php endforeach; ?>
            <?php else: ?>
                <?php continue; ?>
            <?php endif; ?>
        <?php endforeach; ?>
        </table>
    </div>
</div>
 
<?php $this->registerJsFile("/js/runpayroll.js", ['depends' => [\yii\web\JqueryAsset::className()]]); ?>

<style>
    table{
        width: 100%;
    }
    
    table th{
        font-size: 12px;
        font-weight: bold;
        padding: 1px 5px;
        min-width: 75px;
        text-align: left;
    }
    
    table tr td{
        font-size: 12px;
        padding: 1px 5px;
        text-align: left;
    }
    table tr th.td-centered,
    table tr td.td-centered{
        text-align: center;
    }
</style>