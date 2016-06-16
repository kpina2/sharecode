<?php

use yii\db\Schema;
use yii\db\Migration;

class m150714_175855_updatepayrollhoursother extends Migration
{
    public function up()
    {
        $this->addColumn("payroll_item_other_hours", "project_harvest_id", "integer NOT NULL AFTER payroll_item_id");
        $this->addColumn("payroll_item_other_hours", "department_harvest_id", "integer NOT NULL AFTER project_harvest_id");
        $this->dropColumn("payroll_item_other_hours", "code");
    }

    public function down()
    {
        $this->dropColumn("payroll_item_other_hours", "project_harvest_id");
        $this->dropColumn("payroll_item_other_hours", "department_harvest_id");
        $this->addColumn("payroll_item_other_hours", "code", "string NOT NULL AFTER payroll_item_id");
    }
}
