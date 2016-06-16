<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Company;
use app\components\US_Federal_Holidays;
use app\components\Canada_Holidays;

/**
 * CompanyController implements the CRUD actions for Company model.
 */

    
class Report extends Model
{
    public $lookup;
    public $payweekstart_2;
    public function buildDateArray($data){
        $project_id = "project-id";
        $user_id = "user-id";
        $spent_at = "spent-at";
        $task_id = "task-id";
        $wage = "employeeprojectwage";
        
        $datearray = array();
        foreach($data['time_entries'] as $entry_id => $timeentry){
            if(empty($datearray[$timeentry->$spent_at]['hours'])){
                $datearray[$timeentry->$spent_at]['hours'] = 0;
            }
            $datearray[$timeentry->$spent_at]['hours'] += $timeentry->hours;
        }
        return $datearray;
    }
    // $usa_employees and $canada_employees are composed of three parts 
    // set up in PayrollController->getProjectEmployeeTime():
    // harvest_employee, atomic_employee, and time_entries
    // 
//     'missing', 'eighthour', 'multipleclients', 'timeoff', 'holiday'
    public function missing($usa_employees, $canada_employees)
    {
        $missing_report = array();
        
        foreach($usa_employees as $employee => $data){
            $datearray = $this->buildDateArray($data);
            $week_one = array();
            $week_two = array();
            foreach($datearray as $date => $day){
                // week one
                if( strtotime($this->payweekstart_2) > strtotime($date)){
                    $week_one[$date] = $day['hours'];
                }else{ // week two
                    $week_two[$date] = $day['hours'];
                }
            }
                
            if(count($week_one) > 0 && count($week_one) < 5){
                $missing_report[$employee]['data']['week_one'] = $week_one;
                $missing_report[$employee]['employee'] = $data['atomic_employee'];
            }
            if(count($week_two) > 0 && count($week_two) < 5){
                $missing_report[$employee]['data']['week_two'] = $week_two;
                $missing_report[$employee]['employee'] = $data['atomic_employee'];
            }
            
        }
        foreach($canada_employees as $employee => $data){
            $datearray = $this->buildDateArray($data);
            $week_one = array();
            $week_two = array();
            foreach($datearray as $date => $day){
                // week one
                if( strtotime($this->payweekstart_2) > strtotime($date)){
                    $week_one[$date] = $day['hours'];
                }else{ // week two
                    $week_two[$date] = $day['hours'];
                }
            }
                
            if(count($week_one) > 0 && count($week_one) < 5){
                $missing_report[$employee]['data']['week_one'] = $week_one;
                $missing_report[$employee]['employee'] = $data['atomic_employee'];
            }
            if(count($week_two) > 0 && count($week_two) < 5){
                $missing_report[$employee]['data']['week_two'] = $week_two;
                $missing_report[$employee]['employee'] = $data['atomic_employee'];
            }
            
        }
        return $missing_report;
    }
    public function eighthour($usa_employees, $canada_employees)
    {
        $eighthour_report = array();
        foreach($usa_employees as $employee => $data){
            $datearray = $this->buildDateArray($data);
            foreach($datearray as $date => $day){
                if($day['hours'] < 8){
                    $eighthour_report[$employee]['data'][$date] = $day['hours'];
                    $eighthour_report[$employee]['employee'] = $data['atomic_employee'];
                }
            }
        }
        foreach($canada_employees as $employee => $data){
            $datearray = $this->buildDateArray($data);
            foreach($datearray as $date => $day){
                $dayofweek = date('w', strtotime($date));
               
                if($dayofweek == 0 || $dayofweek == 6){ continue; }
                if($day['hours'] < 8){
                    $eighthour_report[$employee]['data'][$date] = $day['hours'];
                    $eighthour_report[$employee]['employee'] = $data['atomic_employee'];
                }
            }
        }
        return $eighthour_report;
    }
    public function multipleclients($usa_employees, $canada_employees)
    {
        $multipleclients_report = array();
        foreach($usa_employees as $employee => $data){
            $company_id = null;
            foreach($data['time_entries'] as $entry){
                if(!empty($company_id) && ($company_id != $entry->company)){
                    $multipleclients_report[$employee]['data'] = array($entry->company, $company_id);
                    $multipleclients_report[$employee]['employee'] = $data['atomic_employee'];
                }
                $company_id = $entry->company;
            }
        }
        foreach($canada_employees as $employee => $data){
            $company_id = null;
            foreach($data['time_entries'] as $entry){
                if(!empty($company_id) && ($company_id != $entry->company)){
                    $multipleclients_report[$employee]['data'] = array($entry->company, $company_id);
                    $multipleclients_report[$employee]['employee'] = $data['atomic_employee'];
                }
                $company_id = $entry->company;
            }
        }
        return $multipleclients_report;
    }
    public function timeoff($usa_employees, $canada_employees)
    {
        $timeoff_report = array();
        $task_id = "task-id";
        $spent_at = "spent-at";
        $unpaid_time_off_department = Department::find()->where("harvest_name LIKE '%Time Off Unpaid%'")->one();
        foreach($usa_employees as $employee => $data){
            foreach($data['time_entries'] as $entry){
                if($entry->$task_id == $unpaid_time_off_department->harvest_id){
                    $timeoff_report[$employee]['data'][$entry->$spent_at] = $entry;
                    $timeoff_report[$employee]['employee'] = $data['atomic_employee'];
                }
            }
        }
        foreach($canada_employees as $employee => $data){
            foreach($data['time_entries'] as $entry){
                if($entry->$task_id == $unpaid_time_off_department->harvest_id){
                    $timeoff_report[$employee]['data'][$entry->$spent_at] = $entry;
                    $timeoff_report[$employee]['employee'] = $data['atomic_employee'];
                }
            }
        }
        return $timeoff_report;
    }
    public function holiday($usa_employees, $canada_employees)
    {
        $holiday_report = array();
        $us_holiday = new US_Federal_Holidays;
        $canada_holiday = new Canada_Holidays;
        $spent_at = "spent-at";
        $task_id = "task-id";
        $holiday_department = Department::find()->where("harvest_name LIKE '%Holiday%'")->one();
        
        foreach($usa_employees as $employee => $data){
            foreach($data['time_entries'] as $entry){
                $is_holiday = $us_holiday->is_holiday(strtotime($entry->$spent_at));
                if(!$is_holiday){
                    continue;
                }else{
                   
                    if((int)$entry->$task_id != (int)$holiday_department->harvest_id){
                        $holiday_report[$employee]['data'][$entry->$spent_at] = $entry->$task_id;
                        $holiday_report[$employee]['employee'] = $data['atomic_employee'];
                    }
                }
            }
        }
        foreach($canada_employees as $employee => $data){
            foreach($data['time_entries'] as $entry){
                $is_holiday = $canada_holiday->is_holiday(strtotime($entry->$spent_at));
                if(!$is_holiday){
                    continue;
                }else{
                    if((int)$entry->$task_id != (int)$holiday_department->harvest_id){
                        $holiday_report[$employee]['data'][$entry->$spent_at] = $entry->$task_id;
                        $holiday_report[$employee]['employee'] = $data['atomic_employee'];
                    }
                }
            }
         
        }
        
        return $holiday_report;
    }
}