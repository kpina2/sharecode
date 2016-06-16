<?php
/* @var $this yii\web\View */
$this->title = 'My Yii Application';
use yii\helpers\Html;
use yii\helpers\Url;
?>
<div class="site-index">
    <div class="body-content">
        <?php if(Yii::$app->user->isGuest): ?>
            <div>
                <?= Html::a('Login', ['site/login'], ['class'=>'btn btn-primary']) ?>
            </div>
        <?php else: ?>
            <div class="row">
                <div class="col-lg-6">
                    <h2>Payroll</h2>
                    <p><?php echo Html::a('View/Import Current U.S. Payroll', ['/payroll/import/usa'], ['class'=>'']); ?></p>
                    <p><?php echo Html::a('View/Import Current Canadian Payroll', ['/payroll/import/canada'], ['class'=>'']); ?></p>
                    <!--<p><?php // Html::a('History', ['/payroll/history'], ['class'=>'']); ?></p>-->
                    <h3>Recent Payroll</h3>
                    <?php if(!empty($recent_payroll)): ?>
                        <table>
                            <tr><th>Week Ending</th><th>Type</th><th>Status</th><th></th></tr>
                        <?php foreach($recent_payroll as $payroll): ?>
                            <tr>
                                <td><?php echo $payroll->weekending; ?></td>
                                <td><?php echo strtoupper($payroll->type); ?></td>
                                <td><?php echo $payroll->status; ?></td>
                                <td><?php echo Html::a('View', ['/payroll/' . $payroll->id], ['class'=>'btn btn-info']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </table>
                    <?php endif; ?>
                    <hr>
                </div>
<!--                <div class="col-lg-4">
                    <h2>Reports</h2>
                </div>-->
                <div class="col-lg-6">
                    <h2>Site Management</h2>
                    <p><?= Html::a('Users', ['/users'], ['class'=>'']) ?></p>
                    <p><?= Html::a('Employees', ['/employees'], ['class'=>'']) ?></p>
                    <p><?= Html::a('Projects', ['/projects'], ['class'=>'']) ?></p>
                    <p><?= Html::a('Departments', ['/departments'], ['class'=>'']) ?></p>
                    <p><?= Html::a('Companies', ['/companies'], ['class'=>'']) ?></p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
