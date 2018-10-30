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

        if($field=='nid') { $nid = $content;	}

        ?>


    <?php endforeach; ?>


    <?php
    //echo '<pre>';
    //print_r(node_load($nid));

    $new_node = node_load($nid);
    $alias = drupal_get_path_alias('node/'.$nid);
    $counter = 0;
   ?>
        <div class="main-goes-well ">
            <div class="product-det2  p-0">
                <div class="product-img col-sm-12 p-0">
                    <div class="product-img-content col-sm-12 p-0">
                        <?php
                        if(isset($new_node->field_images_and_videos['und'])) {
                            foreach ($new_node->field_images_and_videos['und'] as $img_content) {
                                $fid=$img_content['fid'];
                                $file=file_load($fid);
                                if ($file->type == 'image') {
                                    echo $image_rendered=theme('image_style', array(
                                        'style_name'=>'375_375',
                                        'path'=>$file->uri
                                    ));
                                    break;
                                }
                            }
                        }
                        else{
                            $image_rendered = '<img src="/sites/default/files/defualt.png" width="375" height="375">';
                            echo $image_rendered ;
                        }
                        ?>
                    </div>
                    <div class="product-cart col-sm-12 p-0">
                        <?php
                            $content = (node_view($new_node, 'full', NULL));
                            print drupal_render_children($content['field_product_reference']);
                        ?>
                    </div>
                </div>
                <div class="product-main col-sm-10 p-0">
                    <div class="product-title col-sm-12 p-0">
                        <a href="/node/<?php echo $new_node->nid; ?>" ><?php echo $new_node->title; ?></a>
                    </div>
                </div>
                <div class="product-price col-sm-2 p-0">
                 <?php
                 $product = commerce_product_load($new_node->field_product_reference['und'][0]['product_id']);
                 $price = commerce_product_calculate_sell_price($product);
                 if(isset($price['data']['components'][2]['name']) && $price['data']['components'][2]['name'] == 'discount') {
                     $price_display = commerce_currency_format($price['amount'], $price['currency_code'], $product);
                 } else {
                     $price_display = commerce_currency_format($price['data']['components'][0]['price']['amount'], $price['data']['components'][0]['price']['currency_code'], $product);
                 }
                    echo  $price_display;
                 ?>
                </div>
            </div>
        </div>
    <?php
endforeach; ?>
