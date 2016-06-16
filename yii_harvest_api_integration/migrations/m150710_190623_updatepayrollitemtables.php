<?php

use yii\db\Schema;
use yii\db\Migration;

class m150710_190623_updatepayrollitemtables extends Migration
{
    public function up()
    {
        $this->addColumn("payroll_item_hours", "wage", "decimal(5,2) AFTER department_harvest_id");
        $this->addColumn("payroll_item_other_hours", "wage", "decimal(5,2) AFTER code");
    }

    public function down()
    {
        $this->dropColumn("payroll_item_hours", "wage");
        $this->dropColumn("payroll_item_other_hours", "wage");
    }
}
