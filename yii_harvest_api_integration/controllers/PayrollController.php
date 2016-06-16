<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use app\models\Payroll;
use app\models\PayrollItem;
use app\models\Company;
use app\models\Employee;
use app\models\Department;
use app\models\Report;
use app\models\HarvestModel;
use app\models\PayrollItemUSA;
use app\models\PayrollItemCanada;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\components\Helpers;

class PayrollController extends \yii\web\Controller
{
    public $lookup;
    
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'history'],
                'rules' => [
                    [
                        'actions' => ['index', 'history'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => [''],
                        'roles' => ['admin'],
                    ]
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex($id=null){
        if(!empty($id)){
           $payroll = $this->findModel($id);
           if(!empty($payroll)){
                // if this payroll data has already been imported from Harvest get view
                if($payroll->status == "completed" || $payroll->status == "exported"){
                    return $this->render('run', [
                         "payroll" => $payroll,
                     ]);
                }else{
                    $this->lookup = $payroll->week_of;
                    return $this->actionImport($payroll->type);
                }
           }
        }
        $companies = Company::find()->all();
        return $this->render('index', ['companies'=>$companies]);
    }
    
    public function actionImport($type=null){
        $payroll_type = $type;
        $lookup = $this->lookup;
        
        $payroll_model = new Payroll;
        if(empty($lookup)){ 
            $current = Helpers::getCurrentPayperiod();
            $payperiodstart = date("Y-m-d", strtotime("-13 days", strtotime($current)));
            $lookup = $payperiodstart;
        }
        
        $payroll = Payroll::find()->where("week_of = '$lookup' AND type = '$payroll_type'")->one();
        if(!empty($payroll)){
            if($payroll->status == 'new'){
                $payroll->reset($payroll);
            }
        }else{
            // create an new entry in the payroll table
            $payroll = $payroll_model->setupNewPayroll($lookup, $payroll_type);
        }
        if($payroll_type == 'usa'){
            $projects = $this->getProjectsUSA();
//            var_dump($projects); exit;
        }elseif($payroll_type == 'canada'){
            $projects = $this->getProjectsCanada();
        }
               
        // build up a model of time entries for the given period for all employess
        //$employee_time_entries[emp_id]['harvest_employee']
        //$employee_time_entries[emp_id]['time_entries']
        //$employee_time_entries[emp_id]['atomic_employee']
        $employee_time_entries = $this->getProjectEmployeeTime($projects, $lookup, $payroll);       
        // This is a new payroll so we need to build and save Payroll Items and hours to the database
        if($payroll->status == "new"){
            $payroll_cost_rate = "cost-rate";
            // for each employee with time logged we want to save a new payroll item
            foreach($employee_time_entries as $employee_id => $data){
                if($payroll->type == 'usa'){
                    $payroll_item = new PayrollItemUSA;
                }else{
                    $payroll_item = new PayrollItemCanada;
                }
               
                $payroll_item->payroll_id = $payroll->id;
                $payroll_item->employee_harvest_id = $employee_id;
                // wage is called "cost rate" in Harvest data
                if(is_object($data['atomic_employee']) && $data['atomic_employee']->is_exempt){
                    $payroll_item->wage = $data['atomic_employee']->getWageByDate();
                }else{
                    $payroll_item->wage = $data['harvest_employee']->$payroll_cost_rate;
                }
                $payroll_item->save();
                
                if(is_array($data['time_entries'])){
                    if(is_object($data['atomic_employee'])){
                        $payroll_item->exempt = ($data['atomic_employee']->is_exempt ? true : false);
                    }else{
                        $payroll_item->exempt = false;
                    }
                    
                     // Now for each employee we need to create PayrollItemHours, 
                     // PayrollItemOtherHours, etc
                    $payroll_item->processEntries($data['time_entries'], $lookup);
                }else{
                    continue;
                }
            }
            $payroll->status = "processing";
            $payroll->save();
        }
        uasort($employee_time_entries, array($this, "entries_name_order"));
        return $this->render('prepayroll', [
            'employee_time_entries'=>$employee_time_entries,
            "payroll" => $payroll,
            "rules"=>"usa",
            "lookup"=>$lookup
        ]);
    }
    
    public function actionImport_lookup(){
        
        $request = Yii::$app->request;
        $lookup = $request->post('lookup');
        $lookup = (empty($lookup) ? $request->get('lookup') : $lookup);
        
        if(empty($lookup)){ return false; }
        $this->lookup = $lookup;
        
        $payroll_type = $request->post('payrolltype');
        if(empty($payroll_type)){
            $payroll_type = ( empty($payroll_type) ? $request->get('payrolltype') : "usa");
        }

//        See if we already have a payroll of this type for the given week
        $payroll = Payroll::find()->where("week_of = '$lookup' AND type = '$payroll_type'")->one();
        if(!empty($payroll)){
            return $this->actionIndex($payroll->id);
        }else{ // if not start a new one
            return $this->actionImport($payroll_type);
        }
    }
    
     public function actionRun($id=null){
        if(!empty($id)){
           $payroll = $this->findModel($id);
           $payroll->status = "completed";
           $payroll->save();
            return $this->render('run', [
                "payroll" => $payroll,
            ]);
        }
    }
    
    public function actionReports($whichreport = "all", $id=null){
        $payroll = $this->findModel($id);
        $lookup = $payroll->week_of;
        if(empty($lookup)){ 
            $current = Helpers::getCurrentPayperiod();
            $lookup = $current;
        }
        
        $projects_usa = $this->getProjectsUSA();
        $usa_employee_time_entries = $this->getProjectEmployeeTime($projects_usa, $lookup);

        $projects_canada = $this->getProjectsCanada();
        $canada_employee_time_entries = $this->getProjectEmployeeTime($projects_canada, $lookup);
        
        $reportModel = new Report;
        $reportModel->lookup = $lookup;
        $reportModel->payweekstart_2 = date("Ymd", strtotime("+7 days", strtotime($lookup)));
        
        if($whichreport == "all"){
            $reports = array(
                "multipleclients" => $reportModel->multipleclients($usa_employee_time_entries, $canada_employee_time_entries, $lookup),
                "missing" => $reportModel->missing($usa_employee_time_entries, $canada_employee_time_entries, $lookup),
                "eighthour" => $reportModel->eighthour($usa_employee_time_entries, $canada_employee_time_entries, $lookup),
                "timeoff" => $reportModel->timeoff($usa_employee_time_entries, $canada_employee_time_entries, $lookup),
                "holiday" => $reportModel->holiday($usa_employee_time_entries, $canada_employee_time_entries, $lookup),
            );
        }else{
            
        }
        
        return $this->render('reports', ["reports"=>$reports, "lookup"=>$lookup]);
    }
    
    public function actionReimport($id=null, $employee_id=null){
        if(!empty($id)){
            $payroll = $this->findModel($id);
            if(!empty($payroll)){
                $payroll->status = "new";
                $payroll->save();
                $this->lookup = $payroll->week_of;
            }
        }
       
       if(!empty($employee_id)){
           $employee = Employee::find()->where("id = $employee_id")->one();
           return $this->actionReimportEmployee($payroll, $employee);
       }
      
       // remove any time entries for projects from cache
       $cachetest = Yii::$app->cache->get("projects-" . $payroll->type);
       if(!empty($cachetest) && is_array($cachetest)){
           foreach($cachetest as $id => $project){
               Yii::$app->cache->delete("project-entries-$id-" . $payroll->week_of);
           }
       }
       return $this->actionImport($payroll->type);
    }
    
    
    
    public function actionUncomplete($id=null){
        $payroll = $this->findModel($id);
        $payroll->status = "processing";
        $this->lookup = $payroll->week_of;
        $payroll->save();
        return $this->actionImport($payroll->type);
    }
    
    public function actionExport($id=null){
        $payroll = $this->findModel($id);
        $departments = Department::getDepartmentArray();
        $payroll->status = "exported";
        $payroll->save();
        $csv_lines = array(
            array("Co Code",
                "Batch Id",
                "File #",
                "Last Name",
                "First Name",
                "Temp Dept",
                "Rate 1",
                "Reg Hours",
                "O/T Hours",
                "Other Hours Code",
                "Other Hours Amount",
                "Other Earnings Code",
                "Other Earnings Amount",
                "Adjust Ded Code",
                "Adjust Ded Amount")
        );
        
        foreach($payroll->payrollitems as $payrollitem){
            $employee = Employee::find()->where("harvest_id = " . $payrollitem->employee_harvest_id)->one();
            $payrollhours = $payrollitem->payrollitemhours;
            $payroll_other_hours = $payrollitem->payrollitemotherhours;
            if(!empty($employee)){
                if($employee->is_deleted){continue;}
                foreach($payrollhours as $itemhours){
                    $row = array();
                    array_push($row, "5HQ");
                    array_push($row, "5HQ15071-01");
                    array_push($row, $employee->atomic_id);
                    array_push($row, $employee->first_name);
                    array_push($row, $employee->last_name);
                    array_push($row, $itemhours->project_harvest_id);
                    
                    if(!empty($itemhours->wage) && $itemhours->wage != '0.00'){
                        array_push($row, $itemhours->wage);
                    }else{
                        array_push($row, $payrollitem->wage);
                    }
                    
                    array_push($row, $itemhours->hours_regular);
                    array_push($row, $itemhours->hours_overtime);

                    if(!empty($itemhours->hours_doubletime) && $itemhours->hours_doubletime != '0.00'){
                        array_push($row, "D");
                        array_push($row, round($itemhours->hours_doubletime, 2));
                    }else{
                        array_push($row, "");
                        array_push($row, "");
                    }
                    array_push($row, "");
                    array_push($row, "");
                    array_push($row, "");
                    array_push($row, "");
                    $csv_lines[] = $row;
                }
                
                foreach($payroll_other_hours as $itemhours){
                    if($departments[$itemhours->department_harvest_id]->adp_code == 'E'){continue;}
                    $row = array();
                    array_push($row, "5HQ");
                    array_push($row, "5HQ15071-01");
                    array_push($row, $employee->atomic_id);
                    array_push($row, $employee->first_name);
                    array_push($row, $employee->last_name);
                    array_push($row, $itemhours->project_harvest_id);
                    
                    if(!empty($itemhours->wage) && $itemhours->wage != '0.00'){
                         array_push($row, $itemhours->wage);
                    }else{
                        array_push($row, $payrollitem->wage);
                    }
                    
                    array_push($row, "");
                    array_push($row, "");

                    array_push($row, $departments[$itemhours->department_harvest_id]->adp_code);
                    array_push($row, $itemhours->hours);
                    
                    array_push($row, "");
                    array_push($row, "");
                    array_push($row, "");
                    array_push($row, "");
                    $csv_lines[] = $row;
                }
            }
        }
        $filepath = Yii::$app->params['root_path'] . "/payroll_exports/";
        $filename = "payroll_export_" . $payroll->week_of . "_" . $payroll->type . ".csv";
        $f = fopen($filepath . $filename, "w");
        foreach ($csv_lines as $line) {
            fputcsv($f, $line);
        }
        
        header('Content-Type: application/csv');
        header('Content-Disposition: attachment; filename="'. $filename);
        
        readfile($filepath . $filename);
    }
    
    public function actionSummary($id=null){
        $payroll = $this->findModel($id);
        $departments = Department::getDepartmentArray();
        $vac_sick_holiday = array("V", "S", "H");
//        $payroll->status = "exported";
//        $payroll->save();
        $csv_lines = array(
                "File #",
                "Last Name",
                "First Name",
                "Rate 1",
                "Reg Hours",
                "O/T Hours",
                "DT Code",
                "DT Hours",
                "Vac Code",
                "Vac Hours",
                "Sick Code",
                "Sick Hours",
                "Holiday Code",
                "Holiday Hours",
                "Other Hours Code",
                "Other Hours Hours",
                "Regular Earnings",
                "Other Earnings Code",
                "Other Earnings Amount",
                "Reimburse Code",
                "Reimburse Amount",
                "Adjust Ded Code",
                "Adjust Ded Amount",
                "Commiss Code",
                "Commiss",
                "Tax Freq"
            );
//        var_dump($csv_lines);
//        var_dump($payroll->payrollitems); exit;
        $employee_rows = array();
        foreach($payroll->payrollitems as $payrollitem){
            // if employee from harvest is not in our system skip it
            $employee = Employee::find()->where("is_deleted = 0 AND harvest_id = " . $payrollitem->employee_harvest_id)->one();
            if(empty($employee)){
                continue;
            }else{
                $employee_rows[$employee->atomic_id]['atomic_employee'] = $employee;
            }
            array(
                "hours_regular" => 0.00,
                "hours_overtime" => 0.00
            );
            $payrollhours = $payrollitem->payrollitemhours;
            foreach($payrollhours as $itemhours){
                if(empty($employee_rows[$employee->atomic_id]['wage'])){
                    if((float) $itemhours->wage > 0){
                        $employee_rows[$employee->atomic_id]['wage'] = $itemhours->wage;
                    }elseif((float) $payrollitem->wage > 0){
                        $employee_rows[$employee->atomic_id]['wage'] = $payrollitem->wage;
                    }else{
                        $employee_rows[$employee->atomic_id]['wage'] = $employee->wage;
                    }
                }
                
                if(empty($employee_rows[$employee->atomic_id]['hours_regular'])){
                    $employee_rows[$employee->atomic_id]['hours_regular'] = (float) $itemhours->hours_regular;
                }else{
                    $employee_rows[$employee->atomic_id]['hours_regular'] += (float) $itemhours->hours_regular;
                }
                
                if(empty($employee_rows[$employee->atomic_id]['hours_overtime'])){
                    $employee_rows[$employee->atomic_id]['hours_overtime'] = (float) $itemhours->hours_overtime;
                }else{
                    $employee_rows[$employee->atomic_id]['hours_overtime'] += (float) $itemhours->hours_overtime;
                }
                
                if(empty($employee_rows[$employee->atomic_id]['hours_doubletime'])){
                    $employee_rows[$employee->atomic_id]['hours_doubletime'] = (float) $itemhours->hours_doubletime;
                }else{
                    $employee_rows[$employee->atomic_id]['hours_doubletime'] += (float) $itemhours->hours_doubletime;
                }
            }
            
            $payroll_other_hours = $payrollitem->payrollitemotherhours;
            foreach($payroll_other_hours as $itemotherhours){
                $which_adp_code = $departments[$itemotherhours->department_harvest_id]->adp_code;
//                if(in_array($which_adp_code, $vac_sick_holiday)){
                if(!empty($which_adp_code)){
                    if(empty($employee_rows[$employee->atomic_id]['coded_hours'][$which_adp_code])){
                        $employee_rows[$employee->atomic_id]['coded_hours'][$which_adp_code] = $itemotherhours->hours;
                    }else{
                        $employee_rows[$employee->atomic_id]['coded_hours'][$which_adp_code] += $itemotherhours->hours;                 
                    }
                }
            }
        }
//        exit;
        uasort($employee_rows, array($this, "entries_name_order"));
        return $this->render('summary', ["employee_rows"=>$employee_rows, "table_header"=>$csv_lines]);
        exit;
        
        foreach($payroll->payrollitems as $payrollitem){
            $employee = Employee::find()->where("harvest_id = " . $payrollitem->employee_harvest_id . " AND is_deleted = 0")->one();
            $payrollhours = $payrollitem->payrollitemhours;
            $payroll_other_hours = $payrollitem->payrollitemotherhours;
            if(!empty($employee)){
                foreach($payrollhours as $itemhours){
                    $row = array();
                    array_push($row, "5HQ");
                    array_push($row, "5HQ15071-01");
                    array_push($row, $employee->atomic_id);
                    array_push($row, $employee->first_name);
                    array_push($row, $employee->last_name);
                    array_push($row, $itemhours->project_harvest_id);
                    
                    if(!empty($itemhours->wage) && $itemhours->wage != '0.00'){
                        array_push($row, $itemhours->wage);
                    }else{
                        array_push($row, $payrollitem->wage);
                    }
                    
                    array_push($row, $itemhours->hours_regular);
                    array_push($row, $itemhours->hours_overtime);

                    if(!empty($itemhours->hours_doubletime) && $itemhours->hours_doubletime != '0.00'){
                        array_push($row, "D");
                        array_push($row, $itemhours->hours_doubletime);
                    }else{
                        array_push($row, "");
                        array_push($row, "");
                    }
                    array_push($row, "");
                    array_push($row, "");
                    array_push($row, "");
                    array_push($row, "");
                    $csv_lines[] = $row;
                }
                
                foreach($payroll_other_hours as $itemhours){
                    $row = array();
                    array_push($row, "5HQ");
                    array_push($row, "5HQ15071-01");
                    array_push($row, $employee->atomic_id);
                    array_push($row, $employee->first_name);
                    array_push($row, $employee->last_name);
                    array_push($row, $itemhours->project_harvest_id);
                    
                    if(!empty($itemhours->wage) && $itemhours->wage != '0.00'){
                         array_push($row, $itemhours->wage);
                    }else{
                        array_push($row, $payrollitem->wage);
                    }
                    
                    array_push($row, "");
                    array_push($row, "");

                    array_push($row, $departments[$itemhours->department_harvest_id]->adp_code);
                    array_push($row, $itemhours->hours);
                    
                    array_push($row, "");
                    array_push($row, "");
                    array_push($row, "");
                    array_push($row, "");
                    $csv_lines[] = $row;
                }
            }
        }
        $filepath = Yii::$app->params['root_path'] . "/payroll_exports/";
        $filename = "payroll_export_" . $payroll->week_of . "_" . $payroll->type . ".csv";
        $f = fopen($filepath . $filename, "w");
        foreach ($csv_lines as $line) {
            fputcsv($f, $line);
        }
        
        header('Content-Type: application/csv');
        header('Content-Disposition: attachment; filename="'. $filename);
        
        readfile($filepath . $filename);
    }
    
    function actionEmployee($id=null, $employee_id=null){
        if(!empty($id)){
            $payroll = $this->findModel($id);
        }
       
        if(!empty($employee_id)){
            $employee = Employee::find()->where("id = $employee_id")->one();
        }
       
        $range = Helpers::getPayperiodRange($payroll->week_of);
        $time_entries = $employee->getTime($range);

        return $this->render('employee', [
            "payroll" => $payroll,
            "employee" => $employee,
            "time_entries" => $time_entries
        ]);
    }
    
    public function actionReimportEmployee($payroll=null, $employee=null){
        $lookup = $payroll->week_of;
        // TODO delete payroll items for this employee
        $old_payroll_item = PayrollItem::find()->where("payroll_id =" . $payroll->id . " AND employee_harvest_id = " . $employee->harvest_id)->one();       
        if(!empty($old_payroll_item)){
            $old_payroll_item->reset();
            $old_payroll_item->delete();
        }

        $range = Helpers::getPayperiodRange($lookup);
        $single_employee_time_entries = $employee::getHarvestEntries($employee->harvest_id, $range);
        $harvest_user = $employee::getHarvestDataById($employee->harvest_id);
      
        if($payroll->type == 'usa'){
            $payroll_item = new PayrollItemUSA;
        }elseif($payroll->type == 'canada'){
            $payroll_item = new PayrollItemCanada;
        }
        
        $payroll_item->payroll_id = $payroll->id;
        $payroll_item->employee_harvest_id = $employee->harvest_id;
        
        // wage is called "cost rate" in Harvest data
        $payroll_cost_rate = "cost-rate";
        if($employee->is_exempt){
            $payroll_item->wage = $employee->getWageByDate();
        }else{
            $payroll_item->wage =$harvest_user->$payroll_cost_rate;
        }
        
        $payroll_item->save();
        
        $payroll_item->exempt = ($employee->is_exempt ? true : false);
        $payroll_item->processEntries($single_employee_time_entries, $lookup);
        
        $total_regular_hours = 0;
        $total_OT_hours = 0;
         foreach($payroll_item->payrollitemhours as $payroll_item_hours){
            $total_regular_hours += $payroll_item_hours['hours_regular'];
            $total_OT_hours += $payroll_item_hours['hours_overtime'];
         }
         
        echo "<div>";
            echo "<h4>" . $employee->getFullname() . " </h4>"; 
            echo "Payroll ID: " . $payroll_item->payroll_id;
            echo "<br>Wage: " . $payroll_item->wage;
            echo "<br>Raw Hours: " . $payroll_item->raw_hours;
            echo "<br>Payroll item ID: " . $payroll_item->id;
            echo "<br>Harvest ID: " . $payroll_item->employee_harvest_id;
            echo "<br>Total Regular Hours: " . $total_regular_hours;
            echo "<br>Total OT Hours: " . $total_OT_hours;
        echo "</div>";
        echo "<h4>Payroll Item Hours</h4>"; 
        var_dump($payroll_item->payrollitemhours);
         echo "<h4>Payroll Item Other Hours</h4>"; 
        var_dump($payroll_item->payrollitemotherhours);
         echo "<h4>Payroll Deductions</h4>"; 
        var_dump($payroll_item->payrolldeductions);
         echo "<h4>Payroll Other Eranings</h4>"; 
        var_dump($payroll_item->payrollotherearnings);
        
    }
    
    public function actionClearcache(){
        Yii::$app->cache->flush();
        echo "done";
    }
    
    public function getProjectsUSA(){
        // Get Company Projects including user assignments 
        // TODO: this probably belongs in Projects Model (as should all cache checks)
        $cachetest = Yii::$app->cache->get("projects-usa");
        if(empty($cachetest)){
            $company = Company::find()->where("id = 2")->one();
            $projects = $company->getProjects();
//            $company = Company::find()->where("id = 1")->one();
//            $projects_2 = $company->getProjects();

//            $projects = $projects_1 + $projects_2;
            Yii::$app->cache->set("projects-usa", $projects, 3600 * 24);
        }else{
            $projects = $cachetest;
        }
        return $projects;
    }
    
    public function getProjectsCanada(){
        $cachetest = Yii::$app->cache->get("projects-canada");
        if(empty($cachetest)){
            $company = Company::find()->where("id = 3")->one();
            $projects = $company->getProjects();
           
            Yii::$app->cache->set("projects-canada", $projects, 3600 * 24);
        }else{
            $projects = $cachetest;
        }
        return $projects;
    }
    
    //$employee_time_entries[emp_id]['harvest_employee']
    //$employee_time_entries[emp_id]['time_entries']
    //$employee_time_entries[emp_id]['atomic_employee']
    public function getProjectEmployeeTime($projects, $lookup, $payroll=null){
        $harvest = new HarvestModel;
        $range = Helpers::getPayperiodRange($lookup);
        $employee_time_entries = array();
        $userid = "user-id"; $hourlyrate = "hourly-rate"; $client_id = "client-id"; $spent_at = "spent-at";
        // use projects time entries to get employees for the current pay period
        foreach($projects as $id => $project){
             // TODO: this cache test probable belongs in the  Project Model too
            $cachetest = Yii::$app->cache->get("project-entries-$id-$lookup");
            if(empty($cachetest)){
                $project_entries = $harvest->connection->getProjectEntries($id, $range);
                $project_entries = $project_entries->data;
                if(!empty($project_entries)){
                    Yii::$app->cache->set("project-entries-$id-$lookup", $project_entries, 3600 * 24);
                }else{
                    Yii::$app->cache->set("project-entries-$id-$lookup", "No entires", 3600 * 24);
                }
            }else{
                $project_entries = $cachetest;
            }
            if(!is_array($project_entries)){continue;}
            foreach($project_entries as $entry_id => $entry){
                $entry->company = $project->$client_id;
                
                if(empty($project->user_assignments[$entry->$userid])){
                    
                }else{
                    // this wage may be set inside harvest on a per project basis and will be used to override 
                    // employees default cost rate which is set on /people/[id] node
                    $entry->employeeprojectwage = $project->user_assignments[$entry->$userid]->$hourlyrate;
                }
                $employee_time_entries[$entry->$userid][$entry_id] = $entry;
            }
        }
        $all_employee_time = array();
        // now we have all the time entries from Harvest lest make an employee centric array
        foreach($employee_time_entries as $employee_id => $entries){
            
            if(empty($all_employee_time[$employee_id]['harvest_employee'])){
                // get Harvest User object (this includes default pay rate)
                // TODO: this cache test probably belongs in Employee model
                $cachetest = Yii::$app->cache->get("harvest-user-$employee_id");
                if(empty($cachetest)){
                    $harvest_user = Employee::getHarvestDataById($employee_id);
                    Yii::$app->cache->set("harvest-user-$employee_id", $harvest_user, 3600 * 24);
                }else{
                    $harvest_user = $cachetest;
                }
                
                // check if employee is contractor; if yes remove from list
                if(Employee::is_harvest_contractor($harvest_user)){
                    unset($employee_time_entries[$employee_id]);
                    continue;
                }else{
                    $all_employee_time[$employee_id]['harvest_employee'] = $harvest_user;
                }
            }
            
            // retrieve atomic employee model
            if(empty($all_employee_time[$employee_id]['atomic_employee'])){
                $atomic_employee = Employee::find()->where("harvest_id = " . $employee_id . " AND is_deleted = 0" )->one();
                if(empty($atomic_employee)){
                    $all_employee_time[$employee_id]['atomic_employee'] = "Missing: $employee_id <a href='/employee/import/$employee_id'>Import</a>";
                }else{
                    $all_employee_time[$employee_id]['atomic_employee'] = $atomic_employee;
                }
            }
            
            // Now add the time entries to our new employee array data structrue
            if(empty($all_employee_time[$employee_id]['time_entries'])){
                $all_employee_time[$employee_id]['time_entries'] = $entries;
            }else{
                $all_employee_time[$employee_id]['time_entries'] = $all_employee_time[$employee_id]['time_entries'] + $entries;
            }
        }
        
        return $all_employee_time;
    }
    
    // function for uasort
    function entries_name_order($a, $b){
        if (is_string($b['atomic_employee']) || is_string($a['atomic_employee'])){
            if(is_string($b['atomic_employee'])){
                return -1;
            }else{
                return 1;
            }
        }
        if(strtolower($a['atomic_employee']->last_name) == strtolower($b['atomic_employee']->last_name)) {
            return 0;
        }
        
        return (strtolower($a['atomic_employee']->last_name) < strtolower($b['atomic_employee']->last_name)) ? -1 : 1;
    }
    
    protected function findModel($id)
    {
        if (($model = Payroll::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
