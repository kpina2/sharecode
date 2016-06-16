<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "payroll_item_other_hours".
 *
 * @property integer $id
 * @property integer $payroll_item_id
 * @property integer $project_harvest_id
 * @property integer $department_harvest_id
 * @property string $hours
 * @property string $created_on
 * @property string $modified_on
 */
class PayrollItemOtherHours extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'payroll_item_other_hours';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['payroll_item_id', 'project_harvest_id', 'department_harvest_id', 'hours'], 'required'],
            [['payroll_item_id'], 'integer'],
            [['hours', 'wage'], 'number'],
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
            'payroll_item_id' => 'Payroll Item ID',
            'project_harvest_id' => 'Project Harvest ID',
            'department_harvest_id' => 'Department Harvest ID',
            'hours' => 'Hours',
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
    
}
