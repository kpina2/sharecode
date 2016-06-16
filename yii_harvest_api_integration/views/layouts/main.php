<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>

<?php $this->beginBody() ?>
    <div class="wrap">
        <?php
            NavBar::begin([
                'brandLabel' => Html::img('/images/AF_Logo_001_Orange_Black copy_200px.png'),
//                'brandLabel' => Yii::$app->name,
                'brandUrl' => Yii::$app->homeUrl,
                'options' => [
                    'class' => 'navbar-inverse navbar-fixed-top',
                ],
            ]);
            $current_controller = Yii::$app->controller->id;
            $current_action = $this->context->action->id;
            $manage_items = array("user", "project", "company", "employee", "department");
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => [
                    ['label' => 'Home', 'url' => ['/site/index']],
                    ['label' => 'Payroll', 
                        'url' => ['/payroll'], 
                        'options'=>[
                            'class'=>($current_controller == 'payroll' ? "active": "") 
                        ],
                        'items' => [
                            ['label' => 'Run USA Payroll', 'url' => ['/payroll/run_usa'], "options"=>[
                                'class'=>($current_action=='run_usa' ? "active": "")
                            ]],
                            ['label' => 'Run Canada Payroll', 'url' => ['/payroll/run_canada'], "options"=>[
                                'class'=>($current_action=='run_canada' ? "active": "")
                            ]],
                        ]
                    ],
                    ['label' => 'Manage', 
                        'url' => ['/manage'],
                        'options'=>[
                            'class'=>(in_array($current_controller,$manage_items) ? "active": "") 
                        ],
                        'items' => [
                            ['label' => 'Users', 'url' => ['/users'], 'options'=>[
                                'class'=>($current_controller=='user' ? "active": "")
                            ]],
                            ['label' => 'Employees', 'url' => ['/employees'], 'options'=>[
                                'class'=>($current_controller=='employee' ? "active": "")
                            ]],
                            ['label' => 'Projects', 'url' => ['/projects'],'options'=>[
                                'class'=>($current_controller=='project' ? "active": "")
                            ]],
                            ['label' => 'Departments', 'url' => ['/departments'],'options'=>[
                                'class'=>($current_controller=='department' ? "active": "")
                            ]],
                            ['label' => 'Companies', 'url' => ['/companies'],'options'=>[
                                'class'=>($current_controller=='company' ? "active": "")
                            ]],
                            ['label' => 'Earnings Codes', 'url' => ['/earningscode'],'options'=>[
                                'class'=>($current_controller=='payrollotherearningscode' ? "active": "")
                            ]],
                        ]
                    ],
                    Yii::$app->user->isGuest ?
                        ['label' => 'Login', 'url' => ['/site/login']] :
                        ['label' => 'Logout (' . Yii::$app->user->identity->username . ')',
                            'url' => ['/site/logout'],
                            'linkOptions' => ['data-method' => 'post']],
                ],
            ]);
            NavBar::end();
        ?>

        <div class="container">
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <?php if(!Yii::$app->user->isGuest): ?>
                <?php // var_dump(Yii::$app->user->identity->role); ?> 
            <?php endif; ?>
            <?= $content ?>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <p class="pull-left">&copy; <?php echo Yii::$app->name; ?> <?= date('Y') ?></p>
            <p class="pull-right"><?= Yii::powered() ?></p>
        </div>
    </footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
