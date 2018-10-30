<?php
/**
 * @file views-view-table.tpl.php
 * Template to display a view as a table.
 *
 * - $title : The title of this group of rows.  May be empty.
 * - $header: An array of header labels keyed by field id.
 * - $header_classes: An array of header classes keyed by field id.
 * - $fields: An array of CSS IDs to use for each field id.
 * - $classes: A class or classes to apply to the table, based on settings.
 * - $row_classes: An array of classes to apply to each row, indexed by row
 *   number. This matches the index in $rows.
 * - $rows: An array of row items. Each row is an array of content.
 *   $rows are keyed by row number, fields within rows are keyed by field ID.
 * - $field_classes: An array of classes to apply to each field, indexed by
 *   field id, then row number. This matches the index in $rows.
 * @ingroup views_templates
 */
?>
<div class="pager-main-previous">
<?php
$l = 0;
foreach ($rows as $row_count => $row): ?>

    <?php

    foreach ($row as $field => $content): ?>

        <?php //print $content;
        //$sql = "SELECT etid FROM {og} WHERE gid =".arg(1);
        //$eid = db_query($sql)->fetchField();
        ?>

        <?php
        //print $field;
        if ($field == 'title') {
            $title = $content;
        }
        if ($field == 'body') {
            $body = $content;
        }
        if ($field == 'nid') {
            $nid = $content;
        }
        if ($field == 'path') {
            $path = $content;
        }

        ?>


    <?php endforeach; ?>


    <?php
    $min = arg(1);
    if($min >  $nid){
        $min = $nid;
    }
    ?>
        <?php if($min !=  arg(1) && $l == 0){ $l++; ?>
        <div class="previous">
            <div class="pager-row">
                <div class="pager-title"><?php echo $title; ?></div>
                <div class="pager-body"><?php  echo $body; ?></div>
                <div class="pager-path"><?php  echo $path; ?></div>
            </div>
        </div>
        <?php  }  ?>
<?php endforeach; ?>
</div>