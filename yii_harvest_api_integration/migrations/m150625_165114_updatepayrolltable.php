<?php

use yii\db\Schema;
use yii\db\Migration;

class m150625_165114_updatepayrolltable extends Migration
{
    public function up()
    {
        $this->addColumn("payroll", "type", "string NOT NULL AFTER batch_id");
    }

    public function down()
    {
        $this->dropColumn("payroll", "type");
    }
}
