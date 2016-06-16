<?php
    $this->title = "Pre-payroll Reports";
    $this->params['breadcrumbs'][] = $this->title;
?>
<div class='reports'>
    <h1><?php echo $this->title . " " . $lookup; ?></h1>
    <?php   foreach($reports as $type => $report): ?>
        <div class='report-section'>
            <?php echo $this->render('reports/_' . $type, ['data' => $report]); ?>
        </div>  
    <?php   endforeach; ?>
</div>
 <style>
     .report-section{
         border-radius: 5px;
         padding: 20px;
         background: #eeeeee;
         margin: 15px;
     }
table tr td,
table tr th{
    font-size: 10px;
    min-width: 100px;
}
</style>
