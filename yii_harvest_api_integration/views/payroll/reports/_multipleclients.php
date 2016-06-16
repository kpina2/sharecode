<h3>Multiple Client Check</h3>
<?php if(!empty($data)): ?>
    <?php foreach($data as $employee): ?>
        <?php if(is_object($employee['employee'])): ?>
            <strong><?php echo $employee['employee']->fullname; ?></strong>
        <?php else: ?>
            <?php echo $employee['employee']; ?>
        <?php endif; ?>
        <table>
            <tr>
            <?php foreach($employee['data'] as $company_id): ?>
                <td><?php echo $company_id; ?></td>    
            <?php endforeach; ?>
            </tr>
        </table>
    <?php endforeach; ?>
<?php else: ?>
    No Employees billing to multiple clients
<?php endif; ?>