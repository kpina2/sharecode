<h3>Holiday</h3>
<?php if(!empty($data)): ?>
    <?php foreach($data as $employee): ?>
        <?php if(is_object($employee['employee'])): ?>
            <strong><?php echo $employee['employee']->fullname; ?></strong>
        <?php else: ?>
            <?php echo $employee['employee']; ?>
        <?php endif; ?>
    <?php endforeach; ?>
    
    <table>
        <tr>
        <?php foreach($employee['data'] as $date => $task_id): ?>
            <td><?php echo $date; ?></td>    
        <?php endforeach; ?>
        </tr>
        <tr>
        <?php foreach($employee['data'] as $date => $task_id): ?>
            <td><?php echo $task_id; ?></td>    
        <?php endforeach; ?>
        </tr>
    </table>        
            
<?php else: ?>
    No non-Holiday hours on holidays
<?php endif; ?>