<?php
    namespace app\components;
 
    use Yii;
    use yii\base\Component;
    use yii\base\InvalidConfigException;
    use harvest\HarvestAPI;
    use harvest\Model\Range;
    use app\models\Employee;

    class Helpers extends Component{
        static function getPayPeriodDates(){
            $payperiod_start_dates = array();
            $seeddate = date("Y-m-d", strtotime("2014-08-11"));
            $payperiod_start_dates[$seeddate] = $seeddate;
            $count = 1;
            $followingmonday =  date('Y-m-d', strtotime("next monday + $count weeks", strtotime($seeddate)));
            $payperiod_start_dates[$followingmonday] = $followingmonday;
            while($followingmonday < date('Y-m-d')){
                $count += 2;
                $followingmonday =  date('Y-m-d', strtotime("next monday + $count weeks", strtotime($seeddate)));
                $payperiod_start_dates[$followingmonday] = date("Y-m-d", strtotime($followingmonday . " +13 days"));
            }
            array_pop($payperiod_start_dates);
            return $payperiod_start_dates;
        }
        
        static function getProjectNameByHarvestId($harvest_id){
            $criteria = new CDbCriteria;
            $criteria->condition = "harvest_id=:harvest_id";
            $criteria->params = array(":harvest_id"=>$harvest_id);
            $project = Projects::model()->find($criteria);
            return $project->harvest_name;
        }
        
        static function getTaskNameByHarvestId($harvest_id){
            $criteria = new CDbCriteria;
            $criteria->condition = "harvest_id=:harvest_id";
            $criteria->params = array(":harvest_id"=>$harvest_id);
            $department = Department::model()->find($criteria);
            return $department->harvest_name;
        }
        static function getCurrentPayperiod(){
            $payperioddates = self::getPayPeriodDates();
            return array_pop($payperioddates);
        }
   
        static function getPayperiodRange($lookup=null){
            if(empty($lookup)){
                $lookup = self::getCurrentPayperiod();
            }
            $payperiodstart = date("Ymd", strtotime($lookup));
            $payperiodend = date("Ymd", strtotime("+13 days", strtotime($payperiodstart)));
            $range = new Range($payperiodstart, $payperiodend);
    //        var_dump($range); exit;
            return $range;
        }

        static function getPayweekRange($lookup=null){
            if(empty($lookup)){
                $lookup = self::getCurrentPayperiod();
            }
            $payperiodstart = date("Ymd", strtotime($lookup));
            $payperiodend = date("Ymd", strtotime("+6 days", strtotime($payperiodstart)));
            $range = new Range($payperiodstart, $payperiodend);
    //        var_dump($range); exit;
            return $range;
        }
        
        public static function getUSPay($wage, $regular_hours=0, $overtime_hours = 0, $doubletime_hours = 0){
            $pay = 0;
            $pay += $regular_hours * $wage;
            $pay += $overtime_hours * ($wage * 1.5);
            $pay += $doubletime_hours * ($wage * 2);
            return $pay;
        }

        public static function getCanadaPay($wage, $regular_hours=0, $overtime_hours = 0, $doubletime_hours = 0){
            $pay = 0;
            $pay += $regular_hours * $wage;
            $pay += $overtime_hours * ($wage * 1.5);
            $pay += $doubletime_hours * ($wage * 2);
            return $pay;
        }
    }
