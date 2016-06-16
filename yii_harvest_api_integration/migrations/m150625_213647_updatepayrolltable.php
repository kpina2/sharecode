<?php

use yii\db\Schema;
use yii\db\Migration;

class m150625_213647_updatepayrolltable extends Migration
{
    public function up()
    {
        $this->addColumn("payroll_item", "raw_hours", 'decimal(4,2) AFTER wage');
    }

    public function down()
    {
        $this->dropColumn("payroll_item", "raw_hours");
    }
}
