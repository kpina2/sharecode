<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "payroll_item_deduction".
 *
 * @property integer $id
 * @property integer $payroll_item_id
 * @property integer $project_harvest_id
 * @property integer $department_harvest_id
 * @property string $code
 * @property string $amount
 * @property string $created_on
 * @property string $modified_on
 */
class PayrollItemDeduction extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'payroll_item_deduction';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['payroll_item_id', 'project_harvest_id', 'department_harvest_id', 'code', 'amount'], 'required'],
            [['payroll_item_id', 'project_harvest_id', 'department_harvest_id'], 'integer'],
            [['amount'], 'number'],
            [['created_on', 'modified_on'], 'safe'],
            [['code'], 'string', 'max' => 255]
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
            'code' => 'Code',
            'amount' => 'Amount',
            'created_on' => 'Created On',
            'modified_on' => 'Modified On',
        ];
    }
}
