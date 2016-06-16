<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Companies';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="company-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php // Html::a('Create Company', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    
    <?php if(!empty($new_companies)): ?>
        <p>
            <?php echo (count($new_companies) > 1 ? "There are new ".count($new_companies)." companies ready to import" : "There is a new company ready to import"); ?>
        </p>
        <?php echo Html::a('Import New Companies', ['import'], ['class' => 'btn btn-success']) ?>
    <?php endif; ?>
        
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],

//            'id',
            'harvest_id',
            'harvest_name',
            'atomic_id',
            'created_on',
            // 'modified_on',
             'is_deleted',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    
    
</div>
