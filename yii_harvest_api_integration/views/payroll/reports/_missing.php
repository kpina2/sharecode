<h3>Missing Entries</h3>
<?php if(!empty($data)): ?>
    <?php foreach($data as $employee): ?>
        <?php if(is_object($employee['employee'])): ?>
            <strong><?php echo $employee['employee']->fullname; ?></strong>
        <?php else: ?>
            <?php echo $employee['employee']; ?>
        <?php endif; ?>

        <table>
        <?php if(!empty($employee['data']["week_one"])): ?>
            <tr><td>Week One</td></tr>
            <tr>
            <?php foreach($employee['data']["week_one"] as $date => $hours): ?>
                <td><?php echo $date; ?></td>    
            <?php endforeach; ?>
            </tr>
            <tr>
            <?php foreach($employee['data']["week_one"] as $date => $hours): ?>
                <td><?php echo $hours; ?></td>    
            <?php endforeach; ?>
            </tr>
        <?php endif; ?>
        <?php if(!empty($employee['data']["week_two"])): ?>
            <tr><td>Week One</td></tr>
            <tr>
            <?php foreach($employee['data']["week_two"] as $date => $hours): ?>
                <td><?php echo $date; ?></td>    
            <?php endforeach; ?>
            </tr>
            <tr>
            <?php foreach($employee['data']["week_two"] as $date => $hours): ?>
                <td><?php echo $hours; ?></td>    
            <?php endforeach; ?>
            </tr>
        <?php endif; ?>
        </table>
    <?php endforeach; ?>
<?php else: ?>
    No Missing entries
<?php endif; ?>