<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "payroll_other_earnings_code".
 *
 * @property integer $id
 * @property string $code_id
 * @property string $code
 * @property string $description
 */
class PayrollOtherEarningsCode extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'payroll_other_earnings_code';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code_id', 'code'], 'required'],
            [['code_id', 'code', 'description'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code_id' => 'Code ID',
            'code' => 'Code',
            'description' => 'Description',
        ];
    }
}
