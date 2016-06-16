<?php

namespace app\models;

use Yii;
use harvest\HarvestAPI;

/**
 * This is the model class for table "department".
 *
 * @property integer $id
 * @property integer $harvest_id
 * @property string $harvest_name
 * @property integer $atomic_id
 * @property string $adp_code
 * @property string $created_on
 * @property string $modified_on
 * @property integer $is_deleted
 * @property integer $exclude
 */
class Department extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'department';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['harvest_id', 'harvest_name', 'atomic_id'], 'required'],
            [['harvest_id', 'atomic_id', 'is_deleted', 'exclude'], 'integer'],
            [['created_on', 'modified_on'], 'safe'],
            [['harvest_name', 'adp_code'], 'string', 'max' => 255]
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
            'adp_code' => 'ADP Letter Code',
            'created_on' => 'Created On',
            'modified_on' => 'Modified On',
            'is_deleted' => 'Is Deleted',
            'exclude' => "Exclude from regular hours"
        ];
    }
    
    function getHarvestData(){
        $harvest = new HarvestModel;
        $department = $harvest->connection->getTask($this->harvest_id);
        return $department->data;
    }
    
    static function getHarvestDataById($harvest_id){
        $harvest = new HarvestModel;
        $department = $harvest->connection->getTask($harvest_id);
        return $department->data;
    }
    
    static function harvestFindNew($activeonly=false){
        $harvest = new HarvestModel;
        $tasks = $harvest->connection->getTasks();
        $departments_list = $tasks->data;
        if($activeonly){
            $active = "deactivated";
            foreach($departments_list as $id => $department){
                if($department->$active == 'true'){
                    unset($departments_list[$id]);
                }
            }
        }
        
        $department_ids = array_keys($tasks->data);
        $departments = Department::find()->all();
        foreach($departments as $department){
            if(in_array($department->harvest_id, $department_ids)){
                unset($departments_list[$department->harvest_id]);
            }
        }
        return $departments_list;
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
    
    public static function getExcludedArray(){
         $departments = Department::find()->where("exclude = 1")->all();
         $department_list = array();
         foreach($departments as $department){
            array_push($department_list, $department->harvest_id);
         }
         return $department_list;
    }
    
    public static function getDepartmentArray(){
        $departments = Department::find()->all();
         $department_list = array();
         foreach($departments as $department){
            $department_list[$department->harvest_id] = $department;
         }
         return $department_list;
    }
}
