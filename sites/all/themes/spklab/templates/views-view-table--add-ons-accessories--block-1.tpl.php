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

<?php
$r = 0;
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
        if ($field == 'field_product_tags') {
            $tags = $content;
        }
        if ($field == 'field_add_ons_accessories_image') {
            $img = $content;
        }
        if ($field == 'field_tag_colorr') {
            $color = $content;
        }
        ?>


    <?php endforeach; ?>


    <?php
    //echo '<pre>';
    //print_r(node_load($nid));

    $node = node_load($nid);
    ?>
    <div class="main-item2 col-sm-8  p-0">
        <div class="slider-main2 col-sm-12">
            <div class="product-det2 col-sm-12 p-0">
                <div class="product-img col-sm-12 p-0">
                    <?php echo $img; ?>
                </div>
                <div class="product-main col-sm-12  p-0">
                    <div class="product-title col-sm-9 p-0">
                        <?php echo $title; ?>
                    </div>
                    <div class="product-price col-sm-3  p-0">
                        <?php if ($node->field_product_reference['und'][0]['product_id']) {
                            if (commerce_product_load($node->field_product_reference['und'][0]['product_id'])->commerce_price['und'][0]['amount']) {
                                $product = commerce_product_load($node->field_product_reference['und'][0]['product_id']);
                                $price = commerce_product_calculate_sell_price($product);
                                $price_display = commerce_currency_format($price['data']['components'][0]['price']['amount'], $price['data']['components'][0]['price']['currency_code'], $product);
                                echo $price_display;
                            }
                        }
                        ?>
                    </div>
                    <div class="product-body col-sm-9 p-0">
                        <?php echo $body; ?>
                    </div>
                    <div class="product-tags col-sm-9 p-0" >
                        <span class="tags"><?php echo $tags; ?></span>
                <span class="product-color" style="display: none">
                    <?php echo $color; ?>
                </span>
                    </div>
                </div>


            </div>
        </div>
    </div>
<?php endforeach; ?>
