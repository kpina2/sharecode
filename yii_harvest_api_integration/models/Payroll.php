<?php

namespace app\models;

use Yii;
use app\components\Helpers;
use harvest\Model\Range;

/**
 * This is the model class for table "payroll".
 *
 * @property integer $id
 * @property integer $run_by_user_id
 * @property string $week_of
 * @property string $status
 * @property string $batch_id
 * @property string $created_on
 * @property string $modified_on
 */
class Payroll extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'payroll';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['run_by_user_id', 'week_of', 'status'], 'required'],
            [['run_by_user_id'], 'integer'],
            [['created_on', 'modified_on'], 'safe'],
            [['week_of', 'status', 'batch_id'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'run_by_user_id' => 'User ID',
            'week_of' => 'Week Of',
            'status' => 'Status',
            'batch_id' => 'Batch ID',
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
        
        public function reset($payroll){
            foreach($payroll->payrollitems as $item){
                $item->reset(); // delete payrollitemhours, otherhours, etc.
                $item->delete();
            }
            $payroll->status = "new";
            $payroll->save();
        }
        
        // creates or retrieves payroll for the given lookup date
        // deletes all related payroll items if payroll is still in "New" status
        public function setupNewPayroll($lookup, $type){
            $payroll = new Payroll;
            $payroll->run_by_user_id = Yii::$app->user->id;
            $payroll->week_of = $lookup;
            $payroll->status = 'new';
            $payroll->type = $type;
            $payroll->save();
            
            return $payroll;
        }
        
        public function getPayrollItems(){
            $payroll_items = PayrollItem::find()->where("payroll_id = " . $this->id)->all();
            return $payroll_items;
        }
        
        public function getWeekEnding(){
            $new_week = date("m-d-Y", strtotime($this->week_of . " +13 days"));
            return $new_week;
        }
}
