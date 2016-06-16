<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use app\models\User;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
    /**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class RbacController extends Controller  {
    
    function actionInit(){
//        return;
        $auth = Yii::$app->authManager;

//        $user=$auth->createRole('user', "Basic Access User");
//        $auth->add($user);
//        
//        $admin=$auth->createRole('admin', "Website Owner Access");
//        $auth->add($admin);
//        $auth->addChild($admin, $user);
//        
//        $super=$auth->createRole('super', "Web Developer Access");
//        $auth->add($super);
//        $auth->addChild($super,$admin);
         $user = $auth->getRole('user');
        $admin = $auth->getRole('admin');
        $super = $auth->getRole('super');
       
        $auth->assign($user, 21);
        $auth->assign($admin, 20);
        $auth->assign($super, 23);
    }
} 
