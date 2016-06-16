<?php

namespace app\models;

use yii;

/**
 * This is the model class for table "users".
 *
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $new_password
 * @property string $salt
 * @property string $role
 * @property string $created_on
 * @property string $modified_on
 * @property integer $is_deleted
 */
class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
//    public $id; 
//    public $username;
//    public $password;
//    public $authKey;
//    public $accessToken;
    public $new_password;
    
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }
    public function getId()
    {
        return $this->id;
    }
    public function getAuthKey()
    {
        return $this->salt;
    }
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username',  'role'], 'required'],
            [['password'], 'required',  'on' => 'create'],
            [['created_on', 'modified_on'], 'safe'],
            [['is_deleted'], 'integer'],
            [['username', 'password', 'salt', 'role', 'new_password'], 'string', 'max' => 255],
            [['username'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'password' => 'Password',
            'new_password' => 'New Password',
            'salt' => 'Salt',
            'role' => 'Role',
            'created_on' => 'Created On',
            'modified_on' => 'Modified On',
            'is_deleted' => 'Is Suspended',
        ];
    }
    
    private function randomSalt($len = 12) {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789`~!@#$%^&*()-=_+';
        $l = strlen($chars) - 1;
        $str = '';
        for ($i = 0; $i<$len; ++$i) {
            $str .= $chars[rand(0, $l)];
        }
        return $str;
    }
    
    public function beforeSave($insert)
    {
        parent::beforeSave($insert);
        if ($insert) { // for insert
            $salt = $this->randomSalt(12);
            $pepper = Yii::$app->params['pepper'];
            $new_password =  hash("sha256", $salt . $this->password . $pepper);
            $this->password = $new_password;
            $this->salt = $salt;
            $this->created_on = date("Y-m-d h:i:s");
            
            return true;
        } else { // for updates
           
            if(empty($this->new_password)){
                
            }else{
                $salt = $this->randomSalt(12);
                $pepper = Yii::$app->params['pepper'];
                $new_password =  hash("sha256", $salt . $this->new_password . $pepper);
                $this->password = $new_password;
                $this->salt = $salt;
            }
            $this->modified_on = date("Y-m-d h:i:s");
            
            return true;
        }
    }
    public function afterSave($insert, $changedAttributes) {
        if ($insert){
           $auth = Yii::$app->authManager;
           $role = $auth->getRole($this->role);
           $auth->assign($role, $this->id);
        }else{
           $auth=Yii::$app->authManager;
           $auth->revokeAll($this->id);
           $role = $auth->getRole($this->role);
           $auth->assign($role, $this->id);
        }
        parent::afterSave($insert, $changedAttributes);
   }
   
   public function afterDelete(){
        $auth=Yii::$app->authManager;
        $auth->revokeAll($this->id);
        parent::afterDelete();
   }
}
