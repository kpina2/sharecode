<?php

namespace app\models;

use Yii;
use harvest\HarvestAPI;
use app\components\Helpers;
use app\models\Employee;

/**
 * This is the model class for table "company".
 *
 * @property integer $id
 * @property integer $harvest_id
 * @property string $harvest_name
 * @property integer $atomic_id
 * @property string $created_on
 * @property string $modified_on
 * @property integer $is_deleted
 */
class Company extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'company';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['harvest_id', 'harvest_name', 'atomic_id'], 'required'],
            [['harvest_id', 'atomic_id', 'is_deleted'], 'integer'],
            [['created_on', 'modified_on'], 'safe'],
            [['harvest_name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'harvest_id' => 'Harvest ID',
            'harvest_name' => 'Harvest Name',
            'atomic_id' => 'Atomic ID',
            'created_on' => 'Created On',
            'modified_on' => 'Modified On',
            'is_deleted' => 'Is Deleted',
        ];
    }
    
    function getHarvestData(){
        $harvest = new HarvestModel;
        $client = $harvest->connection->getClient($this->harvest_id);
        return $client->data;
    }
    
    static function getHarvestDataById($harvest_id){
        $harvest = new HarvestModel;
        $client = $harvest->connection->getClient($harvest_id);
        return $client->data;
    }
    
    static function harvestFindNew($activeonly=false){
        $harvest = new HarvestModel;
        $clients = $harvest->connection->getClients();
        $clients_list = $clients->data;
        if($activeonly){
            $active = "active";
            foreach($clients_list as $id => $client){
                if($client->$active != 'true'){
                    unset($clients_list[$id]);
                }
            }
        }
        
        $client_ids = array_keys($clients->data);
        $companies = Company::find()->all();
        foreach($companies as $company){
            if(in_array($company->harvest_id, $client_ids)){
                unset($clients_list[$company->harvest_id]);
            }
        }
        return $clients_list;
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
    
    public function getProjects(){
        $harvest = new HarvestModel;
        $client_projects = $harvest->connection->getClientProjects($this->harvest_id);
        
        $client_projects_list = $client_projects->data;
        $active = "active";
        foreach($client_projects_list as $id => $project){
            if($project->$active != 'true'){
//                unset($client_projects_list[$id]);
            }else{
                $atomic_project = Project::find()->where("harvest_id = " . $id)->one();
                if(!empty($atomic_project)){
                    $client_projects_list[$id]->project = $atomic_project;
                }
                
                // we need to get user assignments for each project because wage can
                // change by project and this is the only place the Harvest API library
                // retrieves that data
                $user_assignments = $harvest->connection->getProjectUserAssignments($id);

                $user_assignments_list = array();
                $userid = "user-id";
                foreach($user_assignments->data as $assignment_id => $assigment){
                    $user_assignments_list[$assigment->$userid] = $assigment;
                }
                $client_projects_list[$id]->user_assignments = $user_assignments_list;
            }
        }
        return $client_projects_list;
    }
    public function getProjectsTime($lookup=null){
        $projects = $this->getProjects();
        $harvest = new HarvestModel;
        $range = Payroll::getPayperiodRange($lookup);
        
        foreach($projects as $id => $project){
            $project_entries = $harvest->connection->getProjectEntries($id, $range);
            $project_entry_list[$id] = $project_entries->data;
        }
        return $project_entry_list;
    }
    
    public function getEmployeeList(){
        $projects = $this->getProjects();
        $harvest = new HarvestModel;
        
        $employees_list = array();
        $userid = "user-id";
        foreach($projects as $id => $project){
            $employees = $harvest->connection->getProjectUserAssignments($id);
            foreach($employees->data as $employee){
                $employees_list[$employee->$userid] = $employee;
            }
        }
        return $employees_list;
    }
    
    public function getEmployeeTimeSingle(){
        
    }
    
    public function getEmployeeTime($lookup=null, $payroll_id){
        $harvest = new HarvestModel;
        $range = Helpers::getPayperiodRange($lookup);
        $employees_list = $this->getEmployeeList();
        
        $employee_time_entries = array();
        $hourlyrate = "hourly-rate";
        foreach($employees_list as $id => $employee){
            $cachetest = Yii::$app->cache->get("payroll-user-" . $id . "-" . $lookup . "-" . $payroll_id);
            
            if(empty($cachetest)){
                $employees_entries = $harvest->connection->getUserEntries($id, $range);
                $time_entries = $employees_entries->data;
                if(empty($time_entries)){$time_entries = "No Entries";};
                Yii::$app->cache->set("payroll-user-" . $id . "-" . $lookup . "-" . $payroll_id, $time_entries, 3600 * 24);
                $employee_time_entries[$id]['cache'] = false;
            }else{
                $time_entries = $cachetest;
                $employee_time_entries[$id]['cache'] = true;
            }
            
            $employee_time_entries[$id]['harvest_employee'] = $employee;
            $employee_time_entries[$id]['time_entries'] = $time_entries;
            $atomic_employee = Employee::find()->where("harvest_id = " . $id)->one();
            if(empty($atomic_employee)){
                $employee_time_entries[$id]['atomic_employee'] = "Missing: $id <a href='/employee/import/$id'>Import</a>";
            }else{
                $employee_time_entries[$id]['atomic_employee'] = Employee::find()->where("harvest_id = " . $id)->one();
            }
            
        }
        return $employee_time_entries;
    }
}
