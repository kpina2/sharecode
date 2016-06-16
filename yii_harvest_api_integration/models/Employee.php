<?php

namespace app\models;

use Yii;
use harvest\HarvestAPI;
use app\components\Helpers;

/**
 * This is the model class for table "employee".
 *
 * @property integer $id
 * @property integer $harvest_id
 * @property integer $atomic_id
 * @property string $first_name
 * @property string $last_name
 * @property string $wage
 * @property string $created_on
 * @property string $modified_on
 * @property integer $is_deleted
 * @property integer $is_exempt
 */
class Employee extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'employee';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['harvest_id', 'atomic_id', 'is_deleted', 'is_exempt'], 'integer'],
            [['first_name', 'last_name'], 'required'],
            [['wage'], 'number'],
            [['created_on', 'modified_on'], 'safe'],
            [['first_name', 'last_name'], 'string', 'max' => 255]
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
            'atomic_id' => 'Atomic ID',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'wage' => 'Wage/Salary (Exempt only)',
            'created_on' => 'Created On',
            'modified_on' => 'Modified On',
            'is_deleted' => 'Is Deleted',
            'is_exempt' => "Is Exempt"
        ];
    }
    
    function getHarvestData(){
        $harvest = new HarvestModel;
        $employee = $harvest->connection->getUser($this->harvest_id);
        return $employee->data;
    }
    
    static function getHarvestDataById($harvest_id){
        $harvest = new HarvestModel;
        $employee = $harvest->connection->getUser($harvest_id);
        return $employee->data;
    }
    
    static function getHarvestEntries($harvest_id, $range){
        $harvest = new HarvestModel;
        $employee = $harvest->connection->getUserEntries($harvest_id, $range);
        return $employee->data;
    }
    
    static function harvestFindNew($activeonly=false){
        $harvest = new HarvestModel;
        $users = $harvest->connection->getUsers();
        $employees_list = $users->data;
        if($activeonly){
            $active = "is-active";
            foreach($employees_list as $id => $harvest_user){
                if($harvest_user->$active != 'true'){
                    unset($employees_list[$id]);
                }
                if(self::is_harvest_contractor($harvest_user)){
                    unset($employees_list[$id]);
                }
            }
        }
        
        $employee_ids = array_keys($employees_list);
        
        $employees = Employee::find()->all();
        foreach($employees as $employee){
            if(in_array($employee->harvest_id, $employee_ids)){
                unset($employees_list[$employee->harvest_id]);
            }
        }
        return $employees_list;
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
    
    public function afterSave($insert, $changedAttributes) {
        parent::beforeSave($insert);
        if ($insert) {
            
        }else{
            if(!empty($changedAttributes['wage']) && $changedAttributes['wage'] != $this->wage){
                $model = new EmployeeWageHistory;
                $model->employee_id = $this->id;
                $model->wage = $this->wage;
                $model->change_date = date("Y-m-d h:i:s");
                $model->save();
            }
        }
    }
    
    public function getWagehistory(){
        return $this->hasMany(EmployeeWageHistory::className(), ['employee_id' => 'id'])
            ->orderBy('change_date');
    }
    
    public function getWageByDate(){
        if($this->is_exempt){
            return round($this->wage/80 , 4);
        }
    }
    
    public function getTime($range=null){
        if(empty($range)){
            $current = Helpers::getCurrentPayperiod();
            $payperiodstart = date("Ymd", strtotime("-13 days", strtotime($current)));
            $range = Helpers::getPayperiodRange($payperiodstart);
        }
        
        $harvest = new HarvestModel;
        $usertime = $harvest->connection->getUserEntries($this->harvest_id, $range);
        Yii::$app->cache->set('usertime-' . $this->harvest_id, $usertime->data, 3600 * 24);
        
        return $usertime->data;
    }
    
    public function getHours($time){
        $total_hours = 0;
        foreach($time as $entry){
            $total_hours += $entry->hours;
        }
        return $total_hours;
    }
    
    public static function getExemptArray(){
         $employees = Employee::find()->where("is_exempt = 1")->all();
         $employee_list = array();
         foreach($employees as $employee){
            array_push($employee_list, $employee->harvest_id);
         }
         return $employee_list;
    }
    
    public function getFullname(){
        return $this->first_name . " " . $this->last_name;
    }
     public function getFullname_lastname_first(){
        return $this->last_name . ", " . $this->first_name;
    }
    
    public static function is_harvest_contractor($harvest_user){
        $is_contractor = "is-contractor";
        return ($harvest_user->$is_contractor == 'true' ? true : false);
    }
}
