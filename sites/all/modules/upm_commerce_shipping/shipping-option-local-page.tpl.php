<?php
    $count = count($output);

    ?>
    <p>Define Shipping options for USA</p>
    <ul class="action-links">
        <li>
            <a href="/admin/commerce/config/shipping-rates/<?php echo $type;?>/add">Add Local Shipping option</a>
        </li>
    </ul>

<?php  if($count > 0 ) { ?>
    <table class="sticky-table">
        <thead><tr><th>Label</th><th>Rate</th><th>Edit</th><th>Delete</th> </tr></thead>
        <tbody><?php
        foreach ($output as $result) {
            $edit = l('Edit', 'admin/commerce/config/shipping-rates/'.$result->id.'/edit' , array('attributes' => array('class' => 'edit')));
            $delete = l('Delete', 'admin/commerce/config/shipping-rates/'.$result->id.'/delete', array('attributes' => array('class' => 'delete')));
            echo " <tr ><td>$result->label</td><td>$$result->rate</td><td>$edit</td><td>$delete</td></tr>";
        }
        ?>
        </tbody>
    </table>
<?php
    }else{
        echo ' <p>No Rates Define yet. </p>';
    }
    ?>

