<?php

use yii\helpers\Html;
use app\models\Employee;
use app\models\Project;
use app\models\Department;

$this->title = "Export Payroll Preview" . ucfirst($payroll->type) . " ID: " . $payroll->id;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="payroll-export">
    <div class="row" style="margin-bottom: 20px;">
        <div class="col-lg-7">
            <h1>Export Payroll</h3>
            <h3>Pay period ending: <?php echo $payroll->weekending; ?></h3>
        </div>
    </div>
    <div>
        <table>
            <tr>
                <th>Project</th>
                <th>Department</th>
                <th>Regular</th>
                <th>Overtime</th>
                <th>Doubletime</th>
                <th>Total</th>
            </tr>
        <?php foreach($payroll->payrollitems as $payrollitem): ?>
            <?php $employee = Employee::find()->where("harvest_id = " . $payrollitem->employee_harvest_id)->one(); ?>
            <?php $grandtotal=0; ?>
            <?php if(!empty($employee)): ?>
                    <tr>
                        <td colspan="6" class='td-centered'>
                            <strong><?php echo $employee->fullname; ?> </strong><br>
                            Atomic ID: <?php echo $employee->atomic_id; ?>
                        </td>
                    </tr>
                    
                    <?php $payrollhours = $payrollitem->payrollitemhours; ?>
                    <?php foreach($payrollhours as $itemhours): ?>
                        <tr>
                            <?php 
                                $project = Project::find()->where("harvest_id = " . $itemhours->project_harvest_id)->one(); 
                                $department = Department::find()->where("harvest_id = " . $itemhours->department_harvest_id)->one();
                            ?>
                            <td><?php echo $project->harvest_name; ?></td>
                            <td><?php echo $department->harvest_name; ?></td>
                            <td><?php echo $itemhours->hours_regular; ?></td>
                            <td><?php echo $itemhours->hours_overtime; ?></td>
                            <td><?php echo $itemhours->hours_doubletime; ?></td>
                            <?php 
                                $total = 0; 
                                $total += $payrollitem->wage * $itemhours->hours_regular;
                                $total += $payrollitem->wage * $itemhours->hours_overtime * 1.5;
                                $total += $payrollitem->wage * $itemhours->hours_doubletime * 2;
                                $grandtotal += $total;
                            ?>
                            <td><?php echo Yii::$app->formatter->asCurrency($total); ?></td>
                        </tr>
                    <?php endforeach; ?>
                        <tr><td colspan="5"></td>
                            <td><strong><?php echo Yii::$app->formatter->asCurrency($grandtotal); ?></strong></td>
                        </tr>
            <?php endif; ?>
        <?php endforeach; ?>
        </table>
    </div>
</div>
 
<style>
    table{
        width: 100%;
    }
    table th:first-child{
        text-align: left;
        width: 160px;
    }
    table th{
        font-size: 12px;
        font-weight: bold;
        padding: 1px 5px;
        min-width: 75px;
        text-align: right;
    }
    table th.holiday{
        color: red;
    }
    table tr td:first-child{
        text-align: left;
    }
    table tr td{
        font-size: 12px;
        padding: 1px 5px;
        text-align: right;
    }
    table tr th.td-centered,
    table tr td.td-centered{
        text-align: center;
    }
</style>