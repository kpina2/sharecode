<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "employee_wage_history".
 *
 * @property integer $id
 * @property integer $employee_id
 * @property string $wage
 * @property string $change_date
 */
class EmployeeWageHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'employee_wage_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['employee_id'], 'required'],
            [['employee_id'], 'integer'],
            [['wage'], 'number'],
            [['change_date'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'employee_id' => 'Employee ID',
            'wage' => 'Wage',
            'change_date' => 'Change Date',
        ];
    }
}
