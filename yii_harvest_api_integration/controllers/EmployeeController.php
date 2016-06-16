<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use app\models\Employee;
use yii\data\ActiveDataProvider;
use yii\data\Sort;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * EmployeeController implements the CRUD actions for Employee model.
 */
class EmployeeController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'view', 'create', 'update', 'delete', 'import'],
                'rules' => [
                    [
                        'actions' => ['index', 'view'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create', 'update', 'delete', 'import'],
                        'roles' => ['admin'],
                    ]
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Employee models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Employee::find(),
            'sort' => new Sort(
                ['defaultOrder' => ['last_name'=>SORT_ASC]]
            )
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'new_employees' => Employee::harvestFindNew(true)
        ]);
    }

    function actionImport($id=null){
        $session = Yii::$app->session;
        $lastpage = $session['lastpage'];
       
        if(!empty($id)){
            $harvest_user = Employee::getHarvestDataById($id);
            if(!is_object($harvest_user)){
                 return $this->render('import', [
                    'new_employees' => array(),
                ]);
            }
            $model = new Employee;
            $model->harvest_id = $harvest_user->id;
            $model->first_name = $harvest_user->first_name;
            $model->last_name = $harvest_user->last_name;
            $model->load(Yii::$app->request->post());
          
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                
                $model->save();
                if(!empty($lastpage)){
                    $session['lastpage'] = "";
                     return $this->redirect($lastpage);
                }else{
                    return $this->redirect(['/employee']);
                }
                
            } else {
                return $this->render('importform', [
                    'model' => $model,
                ]);
            }
        }else{
            $new_employees = Employee::harvestFindNew(true);
            return $this->render('import', [
                'new_employees' => $new_employees
            ]);
        }
    }
    
    /**
     * Displays a single Employee model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        return $this->render('view', [
            'model' => $model,
            'time' => $model->getTime()
        ]);
    }

    /**
     * Creates a new Employee model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Employee();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Employee model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Employee model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->is_deleted = 1;
        $model->save();
        return $this->redirect(['index']);
    }

    /**
     * Finds the Employee model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Employee the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Employee::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
