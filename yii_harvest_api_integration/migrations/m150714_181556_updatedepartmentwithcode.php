<?php

use yii\db\Schema;
use yii\db\Migration;

class m150714_181556_updatedepartmentwithcode extends Migration
{
    public function up()
    {
        $this->addColumn("department", "adp_code", "string AFTER atomic_id");
    }

    public function down()
    {
        $this->dropColumn("department", "adp_code");
    }
}
