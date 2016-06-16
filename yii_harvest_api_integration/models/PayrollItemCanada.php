<?php

namespace app\models;

use Yii;
use app\models\PayrollItem;
use app\components\Canada_Holidays;

/**
 * This is the model class for table "payroll_item".
 *
 * @property integer $id
 * @property integer $payroll_id
 * @property integer $employee_harvest_id
 * @property string $wage
 * @property string $raw_hours
 * @property string $created_on
 * @property string $modified_on
 */
class PayrollItemCanada extends PayrollItem
{
    public function processWeek($week_data){
        $daycount = 0;
        $week_hours = 0;
        $regular_hours = 0;
        $overtime_hours = 0;
        $doubletime_hours = 0;
        $ot = false;
        foreach($week_data as $date => $hours){
            // only do OT for non-exempt employees
            if(!$this->exempt){
                if(!isset($hours['raw_hours'])){
                    continue;
                }

                $week_hours += $hours['raw_hours'];

                if($ot == true){
                    $overtime_hours += $hours['raw_hours'];
                }else{
                    if($week_hours >= 40){
                        $overtime_hours += $week_hours - 40;
                        $regular_hours += $hours['raw_hours'] - $overtime_hours;
                        $ot = true;
                    }else{
                        $regular_hours += $hours['raw_hours'];
                    }
                }
            }else{
                $regular_hours += $hours['raw_hours'];
            }

        }
        $week_data['regular_hours'] = $regular_hours;
        $week_data['overtime_hours'] = $overtime_hours;
        $week_data['doubletime_hours'] = $doubletime_hours;

        $this->prorateAndProcessSpecial($week_data);
    }
    
    public function prorateAndProcessSpecial($week_data){
        $reg_ratio=1;
        if($week_data['overtime_hours'] > 0){
            $reg_ratio = 40/($week_data['overtime_hours'] + $week_data['regular_hours']);
        }

        foreach($week_data as $date => $data){
            if(!empty($data['projects'])){
                foreach($data['projects'] as $project_id => $task){
                    foreach($task as $task_id => $taskdata){
                        if($data['raw_hours'] == 0){continue;}
                        $prorated_regular = round($reg_ratio * $taskdata['raw_hours'], 3, PHP_ROUND_HALF_UP);
                        $prorated_overtime = round($taskdata['raw_hours'] - $prorated_regular, 3, PHP_ROUND_HALF_UP);
                        $prorated_doubletime = 0;

                        $this->payperiod_totals[$project_id][$task_id]['wage'] = $taskdata['wage'];

                        if(empty($this->payperiod_totals[$project_id][$task_id]['regular_hours'])){
                            $this->payperiod_totals[$project_id][$task_id]['regular_hours'] = 0;
                        }
                        if(empty($this->payperiod_totals[$project_id][$task_id]['overtime_hours'])){
                            $this->payperiod_totals[$project_id][$task_id]['overtime_hours'] = 0;
                        }
                        if(empty($this->payperiod_totals[$project_id][$task_id]['doubletime_hours'])){
                            $this->payperiod_totals[$project_id][$task_id]['doubletime_hours'] = 0;
                        }

                        $this->payperiod_totals[$project_id][$task_id]['regular_hours'] += $prorated_regular;
                        $this->payperiod_totals[$project_id][$task_id]['overtime_hours'] += $prorated_overtime;
                        $this->payperiod_totals[$project_id][$task_id]['doubletime_hours'] += $prorated_doubletime;
                    }
                }
            }

             if(!empty($data['special_hours'])){
                foreach($data['special_hours'] as $project_id => $task){
                    foreach($task as $task_id => $taskdata){
                        $this->payperiod_other_totals[$project_id][$task_id]['wage'] = $taskdata['wage'];
                         if(empty($this->payperiod_other_totals[$project_id][$task_id]['hours'])){
                            $this->payperiod_other_totals[$project_id][$task_id]['hours'] = 0;
                        }
                        $this->payperiod_other_totals[$project_id][$task_id]['hours'] += $taskdata['hours'];
                    }
                }
            }
        }

    }
}