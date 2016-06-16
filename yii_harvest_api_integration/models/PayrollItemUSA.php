<?php

namespace app\models;

use Yii;
use app\models\PayrollItem;
use app\components\US_Federal_Holidays;

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
class PayrollItemUSA extends PayrollItem
{
    public function processWeek($week_data){
        $daycount = 0;
        $running_total = 0;
        $running_OT_total = 0;
        $running_RT_total = 0;
        foreach($week_data as $date => $hours){
            $daycount++;
            $overtime_hours = 0;
            $doubletime_hours = 0;
            $regular_hours = 0;
            // only do OT for non-exempt employees
            if(!$this->exempt){
                if($daycount==6){ // day six
                    if($running_RT_total + $hours['raw_hours'] > 40){
//                        echo "Hours RAW plus: " . ($running_RT_total + $hours['raw_hours']);
                        $hours_over = ( $running_RT_total + $hours['raw_hours'] )- 40;
//                        echo "Hours OVER: $hours_over";
                        $hours_under = $hours['raw_hours'] - $hours_over;
//                        echo "Hours UNDER: $hours_under";
                        if($hours['raw_hours'] > 12){
                            echo "<h1>Over</h1>";
                            $overtime_hours = 12;
                            $doubletime_hours = $hours['raw_hours']-12;
                        }else{
                            $overtime_hours = $hours_over;
//                            echo  "<br>Adding OT. Current OT hours: " . $running_OT_total . "<br>";
                            $regular_hours = $hours_under;
                        }
                    }else{
                        if($hours['raw_hours'] > 8){
                            $regular_hours = 8;
                            $overtime_hours = $hours['raw_hours']-8;
                            if($overtime_hours > 4){
                                $doubletime_hours = $overtime_hours - 4;
                                $overtime_hours = 4;
                            }
                        }else{
                            $regular_hours = $hours['raw_hours'];
                        }
                    }
                }elseif($daycount==7){ // day seven
                    if($running_RT_total + $hours['raw_hours'] > 40){
                        $hours_over = ( $running_RT_total + $hours['raw_hours'] )- 40;
                        $hours_under = $hours['raw_hours'] - $hours_over;
                        if($hours['raw_hours'] > 8){
                            $regular_hours = $hours_under;
                            $overtime_hours = 8 - $hours_under;
                            $doubletime_hours = $hours['raw_hours'] - $overtime_hours;
                        }else{
                            $regular_hours = $hours_under;
                            $overtime_hours = $hours_over;
                        }
                    }else{
                        if($hours['raw_hours'] > 8){
                            $regular_hours = 8;
                            $overtime_hours = $hours['raw_hours']-8;
                            if($overtime_hours > 4){
                                $doubletime_hours = $overtime_hours - 4;
                                $overtime_hours = 4;
                            }
                        }else{
                            $regular_hours = $hours['raw_hours'];
                        }
                    }
                }else{
                    if($hours['raw_hours'] > 8){
                        $regular_hours =  8;
                        $overtime_hours = $hours['raw_hours'] - 8;
                        if($overtime_hours > 4){
                            $doubletime_hours = $overtime_hours -  4;
                            $overtime_hours = 4;
                        }
                    }else{
                        $regular_hours = $hours['raw_hours'];
                    }
                }
                
                $running_RT_total += $regular_hours;
                $running_OT_total += $overtime_hours;
                $running_total += $hours['raw_hours'];
//                echo "Day " . $daycount . ": " . $hours['raw_hours'] . "->" . $running_total;
//                echo "<br>";
//                echo "Running RT: $running_RT_total";
//                echo "<br>";
//                echo "Running OT: $running_OT_total";
//                echo "<br>";
                $this->unprorated_RT += $regular_hours;
                $this->unprorated_OT += $overtime_hours;
//                $this->unprorated_DT += $doubletime_hours;
                $week_data[$date]['regular_hours'] = $regular_hours;
                $week_data[$date]['overtime_hours'] = $overtime_hours;
                $week_data[$date]['doubletime_hours'] = $doubletime_hours;
            }else{ // exempt employees can not have overtime
                
//                echo "Exempt! No OT";
                $week_data[$date]['regular_hours'] = $hours['raw_hours'];
                $week_data[$date]['overtime_hours'] = 0;
                $week_data[$date]['doubletime_hours'] = 0;
            }
        }

        // run four hour rules on exempt employees
        if($this->exempt){
            $week_data = $this->applyFourHourRule($week_data);
            $week_data = $this->correctSpecialHours($week_data);
            
            
            // Also for exempt employess we are only tracking special hours for certain codes (exempt_unpaid_tasks)
            // The rest just get rolled into regular hours
            foreach($week_data as $date => $data){
                if(!empty($week_data[$date]['special_hours'])){
                    foreach($week_data[$date]['special_hours'] as $project_id => $taskdata){
                        foreach($taskdata as $task_id => $task_hours){
                            // items that are not unpaid need to get moved from special to regular hours
                            if(!in_array($task_id, $this->exempt_unpaid_tasks)){
//                                echo "<h2>" . $task_id . "</h2>";
                                if(empty($week_data[$date]['projects'][$project_id][$task_id])){
                                    $week_data[$date]['projects'][$project_id][$task_id] = array();
                                    $week_data[$date]['projects'][$project_id][$task_id]['raw_hours'] = 0;
                                    $week_data[$date]['projects'][$project_id][$task_id]['regular_hours'] = 0;
                                    $week_data[$date]['projects'][$project_id][$task_id]['wage'] = $this->wage;
                                }
                                
                                $week_data[$date]['regular_hours'] += $task_hours['hours'];
                                $week_data[$date]['projects'][$project_id][$task_id]['raw_hours'] += $task_hours['hours'];
                                $week_data[$date]['projects'][$project_id][$task_id]['regular_hours'] += $task_hours['hours'];
//                                echo $project_id;
//                                echo "<br>";
//                                echo $task_id;
//                                var_dump($week_data[$date]['projects'][$project_id][$task_id]);
                                unset($week_data[$date]['special_hours'][$project_id][$task_id]);
                            }
                        }
                    }
                }
            }
        }
        $this->prorateAndProcessSpecial($week_data);
    }
    
    public function prorateAndProcessSpecial($week_data){
        foreach($week_data as $date => $data){
            if(!empty($data['projects'])){
                $total_tasks = 0;
                foreach($data['projects'] as $project_id => $projects){
                    $total_tasks ++;
                }
                $count = 0; 
                $running_doubletime = 0; $running_overtime = 0; $running_regulartime = 0;
                
                foreach($data['projects'] as $project_id => $task){
                    $count ++;
                    foreach($task as $task_id => $taskdata){
                        if($data['raw_hours'] == 0){continue;}
                        // calculate the ratio of overtime that should be billed to each project
                        $ratio = $taskdata['raw_hours']/$data['raw_hours'];
                        $prorated_regular = round($ratio * $data['regular_hours'], 3);
                        $prorated_overtime = round($ratio * $data['overtime_hours'], 3);
                        $prorated_doubletime = round($ratio * $data['doubletime_hours'], 3);
                        
                        $running_regulartime += $prorated_regular;
                        $running_overtime += $prorated_overtime;
                        $running_doubletime += $prorated_doubletime;
                        
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
                    if($count == $total_tasks){
                        $difference = abs( $data['regular_hours'] - $running_regulartime );
                        if($difference > 0){
//                            var_dump($difference);
                            if($data['regular_hours'] > $running_regulartime){
                                if(empty($this->payperiod_totals[$project_id][$task_id])){
                                    continue;
                                }
                                $this->payperiod_totals[$project_id][$task_id]['regular_hours'] += $difference;
                            }else{
                                 $this->payperiod_totals[$project_id][$task_id]['regular_hours'] -= $difference;
                            }
                        }
                        
                        $difference = abs( $data['overtime_hours'] - $running_overtime );
                        if($difference > 0){
//                            var_dump($difference);
                            if($data['overtime_hours'] > $running_overtime){
                                $this->payperiod_totals[$project_id][$task_id]['overtime_hours'] += $difference;
                            }else{
                                 $this->payperiod_totals[$project_id][$task_id]['overtime_hours'] -= $difference;
                            }
                        }
                        
                        $difference = abs( $data['doubletime_hours'] - $running_doubletime );
                        if($difference > 0){
//                            var_dump($difference);
                            if($data['doubletime_hours'] > $running_doubletime){
                                $this->payperiod_totals[$project_id][$task_id]['doubletime_hours'] += $difference;
                            }else{
                                 $this->payperiod_totals[$project_id][$task_id]['doubletime_hours'] -= $difference;
                            }
                        }
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
        return;
    }
}