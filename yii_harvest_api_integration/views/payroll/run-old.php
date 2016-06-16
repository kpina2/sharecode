<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\data\ArrayDataProvider;
use app\models\Project;
use app\models\Department;
use app\models\Payroll;
use app\components\Helpers;
use app\components\US_Federal_Holidays;
use app\components\Canada_Holidays;

/* @var $this yii\web\View */
/* @var $model app\models\Employee */

$this->title = "Run " .$payrolltype. " Payroll";
$this->params['breadcrumbs'][] = $this->title;

$year = (empty($lookup) ? date("Y") : date("Y", strtotime($lookup)));
if($rules == 'canada'){
    $holiday_lookup = new Canada_Holidays($year);
}else{
    $holiday_lookup = new US_Federal_Holidays($year);
}
$departments = Department::find()->all();
$departments_lookup = array();
foreach($departments as $d)
{
    $departments_lookup[$d->harvest_id] = $d->harvest_name;
}
?>
<div class="payroll-run">
    <div class="row" style="margin-bottom: 20px;">
        <div class="col-lg-7">
            <h1><?php echo $this->title; ?></h1>
        </div>
        <div class="col-lg-4" style="text-align: right;">
            <h5>Select Another Pay Period</h5>
            <form method="post" action="/payroll/run_lookup">
                <?php 
                    $dates = Helpers::getPayPeriodDates();
                    echo Html::dropDownList("lookup", $lookup,  array_reverse($dates), ["class"=>"btn"]);
                ?>
                <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
                <input type="hidden" name="payrolltype" value="<?php echo $payrolltype; ?>" />
                <input type="submit" value="Submit" class="btn btn-default">
            </form>
        </div>
        <div class="col-lg-1" style="text-align: right; padding-top: 35px;">
            <?= Html::a('Export Payroll', ['payroll/exportfile'], ['class'=>'btn btn-primary']) ?>
        </div>
    </div>
    <?php if(!empty($table_data)): ?>
        <div class="row">
            <div class="col-lg-12">
            <table>
                <tr>
                    <th>Employee</th>
                    <?php foreach($table_data['headerdates'] as $d => $date): ?>
                        <?php $holiday_class = ($holiday_lookup->is_holiday(strtotime($date)) ? "holiday" : ""); ?>
                        <th class="<?php echo $holiday_class; ?>"><?php echo date("D M j, Y", strtotime($date)); ?></th>
                    <?php endforeach; ?>
                    <th>Regular</th>
                    <th>Overtime </th>
                    <th>Total</th>
                    <th>Pay</th>
                </tr>


                <?php foreach($table_data['allentries'] as $employee => $entries): ?>
                <?php 
                    $employee_object = app\models\Employee::find()->where('harvest_id = ' . $employee)->one(); 
                    $total_hours = 0;
                    $overtime_hours = 0;
                    $regular_hours = 0;
                    $total = 0;
                ?>
                <tr>
                    <td><?php echo (!empty($employee_object) ? $employee_object->fullname : $employee); ?></td>

                    <?php foreach($table_data['headerdates'] as $d => $date): ?>
                        <?php 
                            $hours = (empty($entries[$date]) ? 0 : $entries[$date]['hours']);
                            if($hours > 12){
                                $hours = 12;
                                $overtime_hours += $hours-12;
                            }
                            $total_hours += $hours;
                        ?>
                        <?php if(empty($hours)): ?>
                            <td class='data-alert'>-</td>
                        <?php else: ?>
                            <td><?php echo $hours; ?></td>
                        <?php endif; ?>
                            
                        <?php $detail_row = "<tr colspan=''><td>Test</td><tr>"; ?>
                    <?php endforeach; ?>

                    <?php 
                        if($total_hours > 40){
                            $regular_hours = 40;
                            $overtime_hours += $total_hours - 40;
                        }else{
                            $regular_hours = $total_hours;
                        }
                    ?>
                    <td><?php echo $regular_hours; ?></td>
                    <td><?php echo $overtime_hours; ?></td>
                    <td><?php echo $total_hours; ?></td>

                    <?php if(empty($employee_object) || empty($employee_object->wage)): ?>
                    <td class='data-alert' title="no wage data">-</td>
                    <?php else: ?>
                        <td>
                            <?php 
                                if($rules == 'canada'){
                                    $pay = Payroll::getCanadaPay($employee_object->wage, $regular_hours, $overtime_hours); 
                                }elseif($rules == 'usa'){
                                    $pay = Payroll::getUSPay($employee_object->wage, $regular_hours, $overtime_hours); 
                                }
                                echo Yii::$app->formatter->asCurrency($pay);
                            ?>
                        </td>
                    <?php endif; ?>
                </tr>
                <?php echo $detail_row; ?>
                <?php endforeach; ?>
            </table>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php $this->registerJsFile("/js/runpayroll.js", ['depends' => [\yii\web\JqueryAsset::className()]]); ?>

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