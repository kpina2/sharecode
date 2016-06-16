<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\data\ArrayDataProvider;
use app\models\Project;
use app\models\Department;

/* @var $this yii\web\View */
/* @var $model app\models\Employee */

$this->title = "Payroll";
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="payroll">
    <h1><?php echo $this->title; ?></h1>
    <?php if(!empty($companies)): ?>
        <?php foreach($companies as $company): ?>
            <h3><?php echo $company->harvest_name; ?></h3>
            <?php foreach($company->projects as $project): ?>
                <p>
                    <?php echo $project->name; ?>
                    
                </p>
                    
            <?php endforeach; ?>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
