<?php

namespace app\models;

use Yii;
use yii\base\Model;
use harvest\HarvestAPI;

class HarvestModel extends Model
{
    public $connection;
    
    public function __construct(){
        $harvest = new HarvestAPI;
        $harvest->setUser( "" );
        $harvest->setPassword( "" );
        $harvest->setAccount( "" );
        $harvest->setSSL(true);
        
        $this->connection = $harvest;
        parent::__construct();
    }
    public function attributeNames()
    {
    
    }
}