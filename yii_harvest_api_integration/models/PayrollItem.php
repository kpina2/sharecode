<?php

namespace app\models;

use Yii;
use app\models\Department;

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
class PayrollItem extends \yii\db\ActiveRecord
{
   
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'payroll_item';
    }
    
    // 1190784 = Holiday
    // 1843313 = Vacation
    // 1231548 = Sick
    public $exempt_unpaid_tasks = array(1231548, 1843313, 1190784); 
            
    public $excluded_tasks;
    public $exempt;
    public $payperiod_totals = array();
    public $payperiod_other_totals = array();
    public $unprorated_OT = 0;
    public $unprorated_DT = 0;
    public $unprorated_RT = 0;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['payroll_id', 'employee_harvest_id'], 'required'],
            [['payroll_id', 'employee_harvest_id'], 'integer'],
            [['wage', "raw_hours"], 'number'],
            [['created_on', 'modified_on'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'payroll_id' => 'Payroll ID',
            'employee_harvest_id' => 'Employee Harvest ID',
            'wage' => 'Wage',
            "raw_hours" => "Raw Hours (from Harvest)",
            'created_on' => 'Created On',
            'modified_on' => 'Modified On',
        ];
    }
    public function beforeSave($insert)
    {
        parent::beforeSave($insert);
        if ($insert) {
            $this->created_on = date("Y-m-d h:i:s");
        }else{
            $this->modified_on = date("Y-m-d h:i:s");
        }
        return true;
    }
    
    // Some tasks and employees are not eligible for overtime
    public function overtimeable($timeentry){
        $taskid = "task-id";
        return !in_array($timeentry->$taskid, $this->excluded_tasks);
    }
    
    // process all the entries for a given employee for the given pay period
    public function processEntries($timeentries, $payweekstart){
        $project_id = "project-id";
        $user_id = "user-id";
        $spent_at = "spent-at";
        $taskid = "task-id";
        $wage = "employeeprojectwage";

        // some 'tasks' like *Bereavement or *Holiday don't get counted as regular hours
        $this->excluded_tasks = Department::getExcludedArray();

        $payweekstart_2 = date("Ymd", strtotime("+7 days", strtotime($payweekstart)));
        $week_one = array(); 
        $week_two = array( );  
        $total_raw_hours = 0;
        foreach($timeentries as $timeentry){
            // need to check for exmempt employee, excluded tasks
            $overtimeable_task = $this->overtimeable($timeentry);

            $total_raw_hours += $timeentry->hours;

            // week one
            if( strtotime($payweekstart_2) > strtotime($timeentry->$spent_at)){
                if(empty($week_one[$timeentry->$spent_at])){
                    $week_one[$timeentry->$spent_at]['raw_hours'] = 0;
                }

                if($overtimeable_task){
                    if(empty($week_one[$timeentry->$spent_at]['projects'][$timeentry->$project_id][$timeentry->$taskid]['raw_hours'])){
                        $week_one[$timeentry->$spent_at]['projects'][$timeentry->$project_id][$timeentry->$taskid]['raw_hours'] = 0;
                    }
                    $week_one[$timeentry->$spent_at]['projects'][$timeentry->$project_id][$timeentry->$taskid]['raw_hours'] += $timeentry->hours;

                    if(empty($week_one[$timeentry->$spent_at]['projects'][$timeentry->$project_id][$timeentry->$taskid]['wage'])){
                        $week_one[$timeentry->$spent_at]['projects'][$timeentry->$project_id][$timeentry->$taskid]['wage'] = $timeentry->$wage;
                    }
                    $week_one[$timeentry->$spent_at]['raw_hours'] += $timeentry->hours;
                }else{
                    if(empty($week_one[$timeentry->$spent_at]['special_hours'][$timeentry->$project_id][$timeentry->$taskid]['wage'])){
                        $week_one[$timeentry->$spent_at]['special_hours'][$timeentry->$project_id][$timeentry->$taskid]['wage'] = $timeentry->$wage;
                    }
                    if(empty($week_one[$timeentry->$spent_at]['special_hours'][$timeentry->$project_id][$timeentry->$taskid]['hours'])){
                        $week_one[$timeentry->$spent_at]['special_hours'][$timeentry->$project_id][$timeentry->$taskid]['hours'] = 0;
                    }
                    $week_one[$timeentry->$spent_at]['special_hours'][$timeentry->$project_id][$timeentry->$taskid]['hours'] += $timeentry->hours;
                }
            }else{ // week two
                if(empty($week_two[$timeentry->$spent_at])){
                    $week_two[$timeentry->$spent_at]['raw_hours'] = 0;
                }

                if($overtimeable_task){
                     if(empty($week_two[$timeentry->$spent_at]['projects'][$timeentry->$project_id][$timeentry->$taskid]['raw_hours'])){
                        $week_two[$timeentry->$spent_at]['projects'][$timeentry->$project_id][$timeentry->$taskid]['raw_hours'] = 0;
                    }
                    $week_two[$timeentry->$spent_at]['projects'][$timeentry->$project_id][$timeentry->$taskid]['raw_hours'] += $timeentry->hours;

                    if(empty($week_two[$timeentry->$spent_at]['projects'][$timeentry->$project_id][$timeentry->$taskid]['wage'])){
                        $week_two[$timeentry->$spent_at]['projects'][$timeentry->$project_id][$timeentry->$taskid]['wage'] = $timeentry->$wage;
                    }

                    $week_two[$timeentry->$spent_at]['raw_hours'] += $timeentry->hours;
                }else{
                    if(empty($week_two[$timeentry->$spent_at]['special_hours'][$timeentry->$project_id][$timeentry->$taskid]['wage'])){
                        $week_two[$timeentry->$spent_at]['special_hours'][$timeentry->$project_id][$timeentry->$taskid]['wage'] = $timeentry->$wage;
                    }
                    if(empty($week_two[$timeentry->$spent_at]['special_hours'][$timeentry->$project_id][$timeentry->$taskid]['hours'])){
                        $week_two[$timeentry->$spent_at]['special_hours'][$timeentry->$project_id][$timeentry->$taskid]['hours'] = 0;
                    }
                    $week_two[$timeentry->$spent_at]['special_hours'][$timeentry->$project_id][$timeentry->$taskid]['hours'] += $timeentry->hours;
                }
            }
        }
            
        // Raw hours is total from Harvest and can be more than 80 for exempt employees
        $this->raw_hours = $total_raw_hours;
        $this->save();
        
        if(!empty($week_one)){
            $this->processWeek($week_one);
        }
        if(!empty($week_two)){
            $this->processWeek($week_two);
        }
            
        // payperiod_other_totals is built up on processWeek
        $other_items_total = 0;
        foreach($this->payperiod_other_totals as $project_id => $tasks){
            
            foreach($tasks as $task_id => $taskdata){
//                if($this->exempt && !in_array($task_id, $exempt_unpaid_tasks)){continue;}
                $payroll_item_other_hours = new PayrollItemOtherHours;
                $payroll_item_other_hours->payroll_item_id = $this->id;
                $payroll_item_other_hours->project_harvest_id = $project_id;
                $payroll_item_other_hours->department_harvest_id = $task_id;
                $payroll_item_other_hours->wage = $taskdata['wage'];
                $payroll_item_other_hours->hours = $taskdata['hours'];
                $other_items_total += $taskdata['hours'];
                $payroll_item_other_hours->save();
            }
        }
        
        $exempt_ratio = 1;
        $used_corrected = false;
        $raw_regular_hours = $this->raw_hours - $other_items_total;
        $corrected_regular_hours_total = 0;

        if($this->exempt && ( $this->raw_hours > 80 ) ){
            // we have vacation or sick time to process
            if($other_items_total > 0){ 
                $corrected_regular_hours_total = 80 - $other_items_total;
                $used_corrected = true;
            }else{
                $exempt_ratio = 80/$raw_regular_hours; // get ration of time to 80
            }
        }
        
        $total_task_count = 0; // get total number tasks
        foreach($this->payperiod_totals as $project_id => $tasks){
            foreach($tasks as $task_id => $taskdata){
                $total_task_count ++;
            }
        }
        
        // payperiod_totals is built up on processWeek
        $running_task_count = 0; $running_reguluar_hours = 0; $running_OT_hours = 0;
        foreach($this->payperiod_totals as $project_id => $tasks){
            foreach($tasks as $task_id => $taskdata){
                $running_task_count ++;
                
                $payroll_item_hours = new PayrollItemHours;
                $payroll_item_hours->payroll_item_id = $this->id;
                $payroll_item_hours->project_harvest_id = $project_id;
                $payroll_item_hours->department_harvest_id = $task_id;
                
                if($exempt_ratio < 1){
                   
                    $regular_hours = round($taskdata['regular_hours'] * $exempt_ratio, 2);
                    // correct the last task item so our exempt employess don't come up 
                    // hundreths short of 80 hours after applying our exempt ratio
                    $running_reguluar_hours += $regular_hours;
                    if($running_task_count == $total_task_count){
                        if($running_reguluar_hours < 80){
                            $difference = 80 - $running_reguluar_hours;
                            $regular_hours += $difference;
                        }else{
                            if($running_reguluar_hours > 80){
                                $difference = $running_reguluar_hours - 80;
                                $regular_hours -= $difference;
                            }
                        }
                    }
                }elseif($used_corrected ){
                     
                    $percent_of_original = $taskdata['regular_hours']/$raw_regular_hours; 
                    $regular_hours = round($corrected_regular_hours_total * $percent_of_original, 2);
                    $running_reguluar_hours += $regular_hours;
                    if($running_task_count == $total_task_count){
                        
                        if($running_reguluar_hours < $corrected_regular_hours_total){
                            $difference = $corrected_regular_hours_total - $running_reguluar_hours;
                            $regular_hours += $difference;
                        }else{
                            if($running_reguluar_hours > $corrected_regular_hours_total){
                                $difference = $running_reguluar_hours - $corrected_regular_hours_total;
                                $regular_hours -= $difference;
                            }
                        }
                    }
                    
                }else{
                    // non exempt employees
                    $regular_hours = $taskdata['regular_hours'];
                    $running_reguluar_hours += $regular_hours;
                    $OT_hours = $taskdata['overtime_hours'];
                    $running_OT_hours += $OT_hours;
                    // correct up to 0.05 difference on last task item
                    if($running_task_count == $total_task_count){
//                        var_dump($running_reguluar_hours);
//                        var_dump($this->unprorated_RT);
                        $difference = $this->unprorated_RT - $running_reguluar_hours;
                        
                        if( $running_reguluar_hours < $this->unprorated_RT){
                            $difference = $this->unprorated_RT - $running_reguluar_hours;
//                            var_dump($difference);
                            if($difference < 0.05){
                                $regular_hours += $difference;
                            }
                        }elseif($running_reguluar_hours > $this->unprorated_RT){
                            $difference = $running_reguluar_hours - $this->unprorated_RT;
                            if($difference < 0.05){
                                $regular_hours -= $difference;
                            }
                        }
                        
//                        var_dump($running_OT_hours);
//                        var_dump($this->unprorated_OT);
                        if( $running_OT_hours < $this->unprorated_OT){
                            $difference = $this->unprorated_OT - $running_OT_hours;
                            if($difference < 0.05){
                                $taskdata['overtime_hours'] += $difference;
                            }
                        }elseif($running_OT_hours > $this->unprorated_OT){
                            $difference = $running_OT_hours - $this->unprorated_OT;
                            if($difference < 0.05){
                                $taskdata['overtime_hours'] += $difference;
                            }
                        }
                    }
                }
                
                $payroll_item_hours->hours_regular = $regular_hours;
                $payroll_item_hours->hours_overtime = $taskdata['overtime_hours'];
                $payroll_item_hours->hours_doubletime = $taskdata['doubletime_hours'];
                $payroll_item_hours->wage = $taskdata['wage'];
                $payroll_item_hours->save();
            }
        }
    }
    public function correctSpecialHours($week_data){
        foreach($week_data as $date => $data){
            $special_hours_array = array();
            if(!empty($data['special_hours'])){
               
                foreach($data['special_hours'] as $project_id => $project_data){
                    foreach($project_data as $task_id => $taskdata){
                        if($taskdata['hours'] > 8){
                            $week_data[$date]['special_hours'][$project_id][$task_id]['hours'] = (float) 8;
                        }
                    }
                }
                
            }
        }
        return $week_data;
    }
    public function applyFourHourRule($week_data){
//        return $week_data;
//        echo "<h1>Four Hour Rule</h1>";
        foreach($week_data as $date => $data){
            $four_hour_array = array();
//            echo "<h2>" . $date . "</h2>";
            $dayofweek = date('w', strtotime($date));
            
            // don't apply four hour rules on Sat or Sun
            if($dayofweek == 0 || $dayofweek == 6){ continue; }
            
            if($data['raw_hours'] < 8){
//                echo "<h3>" . $data['raw_hours'] . " Hours</h3>";
                if(empty($data['projects'])){ continue;}
                foreach($data['projects'] as $project_id => $tasks){
                    // get all the task hours from the current day into one place
                    foreach($tasks as $task_id => $task_data){
                        $department = Department::findOne(['harvest_id' => $task_id]);
                        if($department->exclude){continue;}
                        if(!isset($four_hour_array[$date])){
                            $four_hour_array[$date] = $task_data['raw_hours'];
                        }else{
                            $four_hour_array[$date] += $task_data['raw_hours'];
                        }
                    }
                }
            }
            
            // now 
            foreach($four_hour_array as $date => $day_total){
//                echo "<h4>" . $date . "</h4>";                
                if($day_total <= 4){
                    $four_hour_array[$date] = 4;
                }elseif($day_total > 4){
//                    set day's total to eight and reduce special hours to zero
                     $four_hour_array[$date] = 8;
                     $week_data[$date]['special_hours'] = 0;
                }
            }
//            var_dump($four_hour_array);
//            Now that we have our daily totals we need to prorate and changes back 
//            to the project/task
            foreach($four_hour_array as $date => $day_total){
//                 establish the ratio of the new day total to the old raw_hours
                $ratio = $day_total/$week_data[$date]['raw_hours'];
//                var_dump($ratio);
    
                if($ratio != 1){
//                    get the projects for the given date
                    $day_total_added = 0;
                    foreach($week_data[$date]['projects'] as $project_id => $tasks){
//                        var_dump($tasks);
                        $task_count = count($tasks); $count=0; $new_hours_total = 0;
                        foreach($tasks as $task_id => $task){
                            $count++;
                            $new_hours = round($task['raw_hours'] * $ratio, 4);
                            $new_hours_total += $new_hours;
                            if($task_count == $count){
                                $new_hours = $day_total - $new_hours_total;
                            }
                            $week_data[$date]['projects'][$project_id][$task_id]['raw_hours'] = $new_hours;
                        }
                        $day_total_added += $new_hours_total - $task['raw_hours'];
//                        var_dump($week_data[$date]['projects'][$project_id]);
                    }
//                    if they have special hours AND their regular are now at 8 for the day
//                    we need to reduce the special hours by the added hours...
                    if(!empty($week_data[$date]['special_hours']) && ($week_data[$date]['raw_hours'] + $day_total_added) >=8 ){
                        // add up all the special hours
                        foreach($week_data[$date]['special_hours'] as $project_id => $tasks){
                            $special_hours_total = 0;
                            $continue = true;
                            
                            foreach($tasks as $task_id => $task){
                                if(!$continue){continue;}
                                $special = $task['hours'];
                                $special_hours_total += $task['hours'];
                                
                                // reduce the special hour by how much we added
                                $new_special_hours = round($special, 4) - round($day_total_added, 4);
                                
//                                var_dump($new_special_hours);
                                if($new_special_hours <= 0 ){
                                    $week_data[$date]['special_hours'][$project_id][$task_id]['hours'] = 0;
                                    // if we haven't reduced enough look for more special hours
                                    if($special_hours_total < $day_total_added){
                                        $continue = true;
                                    }else{
                                        $continue = false;
                                    }
                                }else{
                                    $week_data[$date]['special_hours'][$project_id][$task_id]['hours'] = $new_special_hours;
                                    $continue = false;
                                }
                            }
                        }
                    }
                }
            }
        }
        return $week_data;
    }

    public function reset(){
        foreach($this->payrollitemhours as $itemhours){
            $itemhours->delete();
        }
        foreach($this->payrollitemotherhours as $itemotherhours){
            $itemotherhours->delete();
        }
        foreach($this->payrolldeductions as $itemdeduction){
            $itemdeduction->delete();
        }
        foreach($this->payrollotherearnings as $itemotherearnings){
            $itemotherearnings->delete();
        }
    }
    
    public function getPayrollItemHours(){
        $payroll_item_hours = PayrollItemHours::find()->where("payroll_item_id = " . $this->id)->all();
        return $payroll_item_hours;
    }
    public function getPayrollItemOtherHours(){
        $payroll_item_other_hours = PayrollItemOtherHours::find()->where("payroll_item_id = " . $this->id)->all();
        return $payroll_item_other_hours;
    }
    public function getPayrollDeductions(){
        $payroll_item_deductions = PayrollItemDeduction::find()->where("payroll_item_id = " . $this->id)->all();
        return $payroll_item_deductions;
    }
    public function getPayrollOtherEarnings(){
        $payroll_item_other_earnings = PayrollItemOtherEarnings::find()->where("payroll_item_id = " . $this->id)->all();
        return $payroll_item_other_earnings;
    }
    public function getPayroll(){
        $payroll = Payroll::find()->where("id = " . $this->payroll_id)->one();
        return $payroll;
    }
}
