<h3>Timeoff Check</h3>
<?php $task_id = "task-id"; ?>
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
        <?php foreach($employee['data'] as $date => $entry): ?>
            <td><?php echo $date; ?></td>    
        <?php endforeach; ?>
        </tr>
        <tr>
        <?php foreach($employee['data'] as $date => $entry): ?>
            <td><?php echo $entry->$task_id; ?></td>    
        <?php endforeach; ?>
        </tr>
    </table>
<?php else: ?>
    No Entries marked 'Time Off Unpaid'
<?php endif; ?>