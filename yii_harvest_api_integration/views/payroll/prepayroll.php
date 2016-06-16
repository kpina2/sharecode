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
$table_header = array(); $table_data = array();
$spent_at = "spent-at";
foreach($employee_time_entries as $employee_id => $data){
    if(is_array($data['time_entries'])){
        foreach($data['time_entries'] as $entry_id => $entry){
            $table_header[$entry->$spent_at] = date("D n/j", strtotime($entry->$spent_at));
            if(empty($table_data[$employee_id][$entry->$spent_at])){
                $table_data[$employee_id][$entry->$spent_at] = 0;
            }
            $table_data[$employee_id][$entry->$spent_at] += $entry->hours; 
        }
    }else{
        $table_data[$employee_id] = "No time entry data";
    }
}
ksort($table_header);
$year = (empty($lookup) ? date("Y") : date("Y", strtotime($lookup)));
if($rules == 'canada'){
    $holiday_lookup = new Canada_Holidays($year);
}else{
    $holiday_lookup = new US_Federal_Holidays($year);
}

$this->title = "Pre-payroll - " . strtoupper($payroll->type);
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="payroll-pre">
    <div class="row" style="margin-bottom: 20px;">
        <div class="col-lg-5">
            <h1><?php echo $this->title; ?></h1>
        </div>
        <div class="col-lg-3" style="text-align: right;">
            <h5>Select Another Pay Period</h5>
            <form method="post" action="/payroll/import_lookup">
                <?php 
                    $dates = Helpers::getPayPeriodDates();
                    echo Html::dropDownList("lookup", $lookup,  array_reverse($dates), ["class"=>"btn"]);
                ?>
                <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
                <input type="hidden" name="payrolltype" value="<?php echo $payroll->type; ?>" />
                <input type="submit" value="Submit" class="btn btn-default">
            </form>
        </div>
        <div class="col-lg-4" style="text-align: right; padding-top: 35px;">
            <?php echo Html::a('View Reports', ['payroll/reports/' . $payroll->id], ['class'=>'btn btn-info']) ?>
            <?php echo Html::a('Run Payroll', ['payroll/run/' . $payroll->id], ['class'=>'btn btn-primary']) ?>
            <?php echo Html::a('Re-Import', ['payroll/reimport/' . $payroll->id], ['class'=>'btn btn-warning import-warn']) ?>
        </div>
    </div>
    <div>
        <table>
            <tr>
                <th>Employee</th>
                <?php foreach($table_header as $date => $dateheader): ?>
                    <th><?php echo $dateheader; ?></th>
                <?php endforeach; ?>
            </tr>
                <?php foreach($employee_time_entries as $employee_id => $data): ?>
                    
                    <tr>
                        <?php if(!empty($data['atomic_employee'])): ?>
                            <td>
                                <?php if(is_object($data['atomic_employee'])): ?>
                                   <?php echo Html::a(
                                           $data['atomic_employee']->fullname_lastname_first, 
                                            ['payroll/employee/' . $data['atomic_employee']->id . '/payroll_id/' . $payroll->id ], 
                                            []);
                                   ?>
                                <?php else: ?>
                                    <?php echo $data['atomic_employee']; ?>
                                <?php endif; ?>
                            </td>
                        <?php endif; ?>
                        <?php if(!empty($table_data[$employee_id]) && is_array($table_data[$employee_id])): ?>
                            <?php foreach($table_header as $date => $dateheader): ?>
                                <?php echo (empty($table_data[$employee_id][$date]) ? '<td>-</td>' : '<td>' . $table_data[$employee_id][$date] . '</td>'); ?> 
                            <?php endforeach; ?>  
                        <?php else: ?>
                            <td colspan="<?php echo count($table_header); ?>" class='td-centered'><?php echo $table_data[$employee_id]; ?></td>
                        <?php endif; ?>
                    </tr>
                <?php  endforeach; ?>
        </table>
    </div>
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
