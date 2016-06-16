<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "payroll_item_hours".
 *
 * @property integer $id
 * @property integer $payroll_item_id
 * @property integer $project_harvest_id
 * @property integer $department_harvest_id
 * @property string $hours_regular
 * @property string $hours_overtime
 * @property string $hours_doubletime
 * @property string $created_on
 * @property string $modified_on
 */
class PayrollItemHours extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'payroll_item_hours';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['payroll_item_id', 'project_harvest_id', 'department_harvest_id'], 'required'],
            [['payroll_item_id', 'project_harvest_id', 'department_harvest_id'], 'integer'],
            [['hours_regular', 'hours_overtime', 'hours_doubletime', 'wage'], 'number'],
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
            'wage' => 'Wage',
            'hours_regular' => 'Hours Regular',
            'hours_overtime' => 'Hours Overtime',
            'hours_doubletime' => 'Hours Doubletime',
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
