<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    private $_identity;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
        ];
    }

    /**
    * Declares attribute labels.
    */
    public function attributeLabels()
    {
            return array(
                    'rememberMe'=>'Remember me next time',
            );
    }

    /**
    * Logs in the user using the given username and password in the model.
    * @return boolean whether login is successful
    */
    public function login()
    {
        $user = User::findIdentity(['username' => $this->attributes['username']]);
        
        if($user){
            
            $password =  $this->attributes['password'];
            $pepper = Yii::$app->params['pepper'];
            
            $test_password = hash("sha256", $user->salt . $password . $pepper);
            
            if($user->getAttribute("password") == $test_password){
                
                $duration = ($this->attributes['rememberMe'] ? 3600*24*30 : 0);
                Yii::$app->user->login($user, $duration);
                
                return true;
            }else{
                $this->addError("password", "Login Failed");
            }
        }else{
            $this->addError("password", "Login Failed");
        }
        return false;
    }
}
