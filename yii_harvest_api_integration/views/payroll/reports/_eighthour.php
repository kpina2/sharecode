<h3>Eight Hour Check</h3>
<?php if(!empty($data)): ?>
    <?php foreach($data as $employee): ?>
        <?php if(is_object($employee['employee'])): ?>
            <strong><?php echo $employee['employee']->fullname; ?></strong>
        <?php else: ?>
            <?php echo $employee['employee']; ?>
        <?php endif; ?>

        <table>
            <tr>
            <?php foreach($employee['data'] as $date => $hours): ?>
                <td><?php echo $date; ?></td>    
            <?php endforeach; ?>
            </tr>
            <tr>
            <?php foreach($employee['data'] as $date => $hours): ?>
                <td><?php echo $hours; ?></td>    
            <?php endforeach; ?>
            </tr>
        </table>
    <?php endforeach; ?>
<?php else: ?>
    No employee days below eight hours 
<?php endif; ?>