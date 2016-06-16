<?php
    function displaytotal($employee){
        if($employee['atomic_employee']->is_exempt){
            $vacation_pay=0; $sick_pay=0; $holiday_pay=0; $paid_time_off=0;
            $regular_pay=0; $overtime_pay=0; $doubletime_pay=0;
            $rate = (float) $employee['wage'];
            
//            $regular_pay = $employee['atomic_employee']->wage;
//            if($employee['hours_regular'] < 80){
//                $regular_pay = (float) $rate * $employee['hours_regular'];
//            }
//            
            $regular_pay = (float) $rate * $employee['hours_regular'];
            $overtime_pay = (float)($rate * 1.5) * $employee['hours_overtime'];
            $doubletime_pay = (float) ($rate * 2)  * $employee['hours_doubletime'];

            if(!empty($employee['coded_hours'])){
                $vacation_pay = (float) (!empty($employee['coded_hours']["V"]) ? $rate * $employee['coded_hours']["V"] : 0);
                $sick_pay = (float) (!empty($employee['coded_hours']["S"]) ? $rate * $employee['coded_hours']["S"] : 0);
                $holiday_pay = (float) (!empty($employee['coded_hours']["H"]) ? $rate * $employee['coded_hours']["H"] : 0);
                $paid_time_off = (float) (!empty($employee['coded_hours']["P"]) ? $rate * $employee['coded_hours']["P"] : 0);
            }
            $total_pay = $regular_pay + $overtime_pay + $doubletime_pay + $vacation_pay + $sick_pay + $holiday_pay + $paid_time_off;
            return number_format(round($total_pay, 2), 2, '.', '');
        }
    }
?>

<table id="payroll-summary-table">
    <tr>
        <?php foreach($table_header as $th): ?>
            <th><?php echo $th; ?></th>
        <?php endforeach; ?>
    </tr>
    <?php foreach($employee_rows as $employee_id => $employee): ?>
        <?php if(empty($employee['wage']) || $employee['atomic_employee']->is_deleted ): ?>
            <?php continue; ?>
        <?php endif; ?>
        <?php $employee_css = ""; ?>
        <?php if($employee['atomic_employee']->is_exempt): ?>
            <?php $employee_css .= " is_exempt"; ?>
        <?php endif; ?>
    <tr class='<?php echo $employee_css; ?>' >
        <td><?php echo str_pad($employee_id, 5, '0', STR_PAD_LEFT);; ?></td>
        <td><?php echo $employee['atomic_employee']->last_name; ?></td>
        <td><?php echo $employee['atomic_employee']->first_name; ?></td>
        <td><?php echo  number_format(round($employee['wage'], 2), 2); ?></td>
        <td><?php echo $employee['hours_regular']; ?></td>
        <td><?php echo $employee['hours_overtime']; ?></td>
        <td>D</td>
        <td><?php echo $employee['hours_doubletime']; ?></td>
        <td><?php echo (empty($employee['coded_hours']["V"]) ? "" : "V" ); ?></td>
        <td><?php echo (empty($employee['coded_hours']["V"]) ? "" : $employee['coded_hours']["V"]); ?></td>
        <td><?php echo (empty($employee['coded_hours']["S"]) ? "" : "S" ); ?></td>
        <td><?php echo (empty($employee['coded_hours']["S"]) ? "" : $employee['coded_hours']["S"]); ?></td>
        <td><?php echo (empty($employee['coded_hours']["H"]) ? "" : "H" ); ?></td>
        <td><?php echo (empty($employee['coded_hours']["H"]) ? "" : $employee['coded_hours']["H"]); ?></td>
        <td>
            <?php 
                $remaining_special_values = array();
                if(!empty($employee['coded_hours']))
                { 
                    $tempcodes = $employee['coded_hours'];
                    unset($tempcodes["V"]);
                    unset($tempcodes["S"]);
                    unset($tempcodes["H"]);
                    unset($tempcodes["E"]);
                    
                    $keys = array_keys($tempcodes);
                    $keys_string = implode(", ", $keys);
                    echo $keys_string;
                    
                    $remaining_special_values = array();
                    foreach($tempcodes as $code_id => $value){
                        array_push($remaining_special_values, $value);
                    }
                }; 
            ?>
        </td>
        <td>
            <?php echo (!empty($remaining_special_values) ? implode(", ", $remaining_special_values) : ""); ?>
        </td>
        <td>
            <?php echo displaytotal($employee); ?>
        </td>
        <td></td>
        <td></td>
        <td>39</td>
        <td></td>
        <td></td>
        <td></td>
        <td>C</td>
        <td></td>
        <td></td>
    </tr>
    <?php endforeach; ?>
</table>