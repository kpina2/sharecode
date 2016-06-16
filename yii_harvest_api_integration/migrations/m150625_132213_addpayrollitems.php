<?php

use yii\db\Schema;
use yii\db\Migration;

class m150625_132213_addpayrollitems extends Migration
{
    public function up()
    {
        $this->createTable('payroll', array(
            'id' => 'pk',
            'run_by_user_id' => "integer NOT NULL",
            'week_of' => 'string NOT NULL',
            'status' => 'string NOT NULL',
            'batch_id' => 'string',
            'created_on' => 'DATETIME',
            'modified_on' => 'DATETIME',
        ));
        $this->createTable('payroll_item', array(
            'id' => 'pk',
            'payroll_id' => "integer NOT NULL",
            'employee_harvest_id' => 'integer NOT NULL',
            'wage' => 'decimal(5,2)',
            'created_on' => 'DATETIME',
            'modified_on' => 'DATETIME',
        ));
        $this->createTable('payroll_item_hours', array(
            'id' => 'pk',
            'payroll_item_id' => "integer NOT NULL",
            'project_harvest_id' => 'integer NOT NULL',
            'department_harvest_id' => 'integer NOT NULL',
            'hours_regular' => 'decimal(4,2)',
            'hours_overtime' => 'decimal(4,2)',
            'hours_doubletime' => 'decimal(4,2)',
            'created_on' => 'DATETIME',
            'modified_on' => 'DATETIME',
        ));
        $this->createTable('payroll_item_other_hours', array(
            'id' => 'pk',
            'payroll_item_id' => "integer NOT NULL",
            'code' => 'string NOT NULL',
            'hours' => 'decimal(4,2) NOT NULL',
            'created_on' => 'DATETIME',
            'modified_on' => 'DATETIME',
        ));
        $this->createTable('payroll_item_other_earnings', array(
            'id' => 'pk',
            'payroll_item_id' => "integer NOT NULL",
            'project_harvest_id' => 'integer NOT NULL',
            'department_harvest_id' => 'integer NOT NULL',
            'code' => 'string NOT NULL',
            'amount' => 'decimal(4,2) NOT NULL',
            'created_on' => 'DATETIME',
            'modified_on' => 'DATETIME',
        ));
        $this->createTable('payroll_item_deduction', array(
            'id' => 'pk',
            'payroll_item_id' => "integer NOT NULL",
            'project_harvest_id' => 'integer NOT NULL',
            'department_harvest_id' => 'integer NOT NULL',
            'code' => 'string NOT NULL',
            'amount' => 'decimal(4,2) NOT NULL',
            'created_on' => 'DATETIME',
            'modified_on' => 'DATETIME',
        ));
        
        $this->createTable('payroll_deduction_code', array(
            'id' => 'pk',
            'code_id' => 'string NOT NULL',
            'code' => 'string NOT NULL',
            'description' => 'string'
        ));
        
        $this->createTable('payroll_other_hours_code', array(
            'id' => 'pk',
            'code_id' => 'string NOT NULL',
            'code' => 'string NOT NULL',
            'description' => 'string'
        ));
        
        $this->createTable('payroll_other_earnings_code', array(
            'id' => 'pk',
            'code_id' => 'string NOT NULL',
            'code' => 'string NOT NULL',
            'description' => 'string'
        ));
    }

    public function down()
    {
       $this->dropTable("payroll");
       $this->dropTable("payroll_item");
       $this->dropTable("payroll_item_hours");
       $this->dropTable("payroll_item_other_hours");
       $this->dropTable("payroll_item_other_earnings");
       $this->dropTable("payroll_item_deduction");
       $this->dropTable("payroll_deduction_code");
       $this->dropTable("payroll_other_hours_code");
       $this->dropTable("payroll_other_earnings_code");
    }
   
}
