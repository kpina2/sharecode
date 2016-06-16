<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Projects';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="project-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php //  Html::a('Create Project', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php if(!empty($new_projects)): ?>
        <p>
            <?php echo (count($new_projects) > 1 ? "There are new ".count($new_projects)." projects ready to import" : "There is a new project ready to import"); ?>
        </p>
        <?php echo Html::a('Import New Projects', ['import'], ['class' => 'btn btn-success']) ?>
    <?php endif; ?>
        
        
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'harvest_id',
            'harvest_name',
            'atomic_id',
//            'name',
            // 'created_on',
            // 'modified_on',
            // 'is_deleted',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
