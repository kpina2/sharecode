<?php

namespace app\models;

use Yii;
use yii\base\Model;
use harvest\HarvestAPI;
use harvest\Model\Range;
use app\models\Employee;

class Payroll extends Model
{
    
    public function attributeNames()
    {
    
    }
    
    static function getPayPeriodDates(){
        $payperiod_start_dates = array();
        $seeddate = date("Y-m-d", strtotime("2014-08-11"));
        $payperiod_start_dates[$seeddate] = $seeddate;
        $count = 1;
        $followingmonday =  date('Y-m-d', strtotime("next monday + $count weeks", strtotime($seeddate)));
        $payperiod_start_dates[$followingmonday] = $followingmonday;
        while($followingmonday < date('Y-m-d')){
            $count ++;
            $followingmonday =  date('Y-m-d', strtotime("next monday + $count weeks", strtotime($seeddate)));
            $payperiod_start_dates[$followingmonday] = $followingmonday;
        }
        array_pop($payperiod_start_dates);
        return $payperiod_start_dates;
    }
    
   
    static function getPayperiodRange($lookup=null){
        if(empty($lookup)){
            $lookup = date("Ymd", strtotime('last monday'));
        }
        $payperiodstart = date("Ymd", strtotime($lookup));
        $payperiodend = date("Ymd", strtotime("+6 days", strtotime($payperiodstart)));
        $range = new Range($payperiodstart, $payperiodend);
//        var_dump($range); exit;
        return $range;
    }
    
    static function getPayrollData($lookup=null){
        $cachetest = Yii::$app->cache->get("payroll-$lookup");
        $range = self::getPayperiodRange($lookup);
        $employee_time_list = array();
        if(!empty($cachetest)){ // && 1==0
            $employee_time_list = $cachetest;
        }else{
            $count = 0;
            $harvest = new HarvestModel;
            $employees = Employee::find()->all();
            foreach($employees as $employee){
                $count ++;
                if($count > 20){continue;} // for controlling number of results during development;
                
                $entries = $harvest->connection->getUserEntries($employee->harvest_id, $range);
                $employee_time_list[$employee->harvest_id]['employee_data'] = $employee;
                $employee_time_list[$employee->harvest_id]['time_entries'] = $entries->data;
            }

            Yii::$app->cache->set("payroll-$lookup", $employee_time_list);
        }
        return $employee_time_list;
    }
    public static function getUSPay($wage, $regular_hours=0, $overtime_hours = 0, $doubletime_hours = 0){
        $pay = 0;
        $pay += $regular_hours * $wage;
        $pay += $overtime_hours * ($wage * 1.5);
        $pay += $doubletime_hours * ($wage * 2);
        return $pay;
    }
    
    public static function getCanadaPay($wage, $regular_hours=0, $overtime_hours = 0, $doubletime_hours = 0){
        $pay = 0;
        $pay += $regular_hours * $wage;
        $pay += $overtime_hours * ($wage * 1.5);
        $pay += $doubletime_hours * ($wage * 2);
        return $pay;
    }
    
    public function getTableData($payroll_entries){
        $table_data = array();
        $allentries = array(); 
        $headerdates=array();
        
        $project_id = "project-id";
        $user_id = "user-id";
        $spent_at = "spent-at";
        $taskid = "task-id";
        foreach($payroll_entries as $project_id => $project_entries){
            
            if(is_array($project_entries)){
                foreach($project_entries as $entry_id => $entry){
                    if(empty($allentries[$entry->$user_id][$entry->$spent_at]['detail'][$project_id])){
                        $allentries[$entry->$user_id][$entry->$spent_at]['detail'][$project_id] = array();
                    }
                    if(empty($allentries[$entry->$user_id][$entry->$spent_at]['detail'][$project_id][$entry->$taskid])){
                        $allentries[$entry->$user_id][$entry->$spent_at]['detail'][$project_id][$entry->$taskid] = 0;
                    }
                    
                    if(empty($allentries[$entry->$user_id][$entry->$spent_at]['hours'])){
                        $allentries[$entry->$user_id][$entry->$spent_at]['hours'] = 0;
                    } 
                    if(empty($employee_project_detail[$entry->$user_id][$project_id]['hours'])){
                        $employee_project_detail[$entry->$user_id][$project_id]['hours'] = 0;
                    }
                    $employee_project_detail[$entry->$user_id][$project_id]['hours'] += $entry->hours; 
                    $allentries[$entry->$user_id][$entry->$spent_at]['detail'][$project_id][$entry->$taskid] += $entry->hours;
                    $allentries[$entry->$user_id][$entry->$spent_at]['hours'] += $entry->hours; 
                    $headerdates[$entry->$spent_at] = $entry->$spent_at;
                }
            }
        }
        $table_data['headerdates'] = $headerdates;
        $table_data['allentries'] = $allentries;
        return $table_data;
    }
}