<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "projects".
 *
 * @property integer $id
 * @property integer $harvest_id
 * @property string $harvest_name
 * @property integer $atomic_id
 * @property string $name
 * @property string $created_on
 * @property string $modified_on
 * @property integer $is_deleted
 */
class Project extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'projects';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['harvest_id', 'harvest_name', 'atomic_id'], 'required'],
            [['harvest_id', 'atomic_id', 'is_deleted'], 'integer'],
            [['created_on', 'modified_on'], 'safe'],
            [['harvest_name', 'name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'harvest_id' => 'Harvest ID',
            'harvest_name' => 'Harvest Name',
            'atomic_id' => 'Atomic ID',
            'name' => 'Name',
            'created_on' => 'Created On',
            'modified_on' => 'Modified On',
            'is_deleted' => 'Is Deleted',
        ];
    }
    
    function getHarvestData(){
        $harvest = new HarvestModel;
        $project = $harvest->connection->getProject($this->harvest_id);
        return $project->data;
    }
    
    static function getHarvestDataById($harvest_id){
        $harvest = new HarvestModel;
        $project = $harvest->connection->getProject($harvest_id);
        return $project->data;
    }
    
    static function harvestFindNew($activeonly=false){
        $harvest = new HarvestModel;
        $projects_h = $harvest->connection->getProjects();
        $projects_list = $projects_h->data;
        if($activeonly){
            $active = "active";
            foreach($projects_list as $id => $project){
                if($project->$active != 'true'){
                    unset($projects_list[$id]);
                }
            }
        }
        
        $project_ids = array_keys($projects_h->data);
        $projects = Project::find()->all();
        foreach($projects as $project){
            if(in_array($project->harvest_id, $project_ids)){
                unset($projects_list[$project->harvest_id]);
            }
        }
        return $projects_list;
    }
    
    public function beforeSave($insert)
    {
        parent::beforeSave($insert);
        if ($insert) {
            $this->created_on = date("Y-m-d h:i:s");
        }else{
            $this->modified_on = date("Y-m-d h:i:s");
        }
        return true;
    }
}
