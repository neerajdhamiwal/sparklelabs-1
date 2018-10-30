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
        if($field=='title') { $title = $content;	}
        if($field=='body') { $body = $content;	}
        if($field=='nid') { $nid = $content;	}
        if($field=='field_product_tags') { $tags = $content;	}
        if ($field == 'field_tag_colorr') {
            $color = $content;
        }
        ?>


    <?php endforeach; ?>


    <?php
    //echo '<pre>';
    //print_r(node_load($nid));

    $node = node_load($nid);
    $alias = drupal_get_path_alias('node/'.$nid);
    $counter = 0;
    echo'<div class="main-item col-sm-8  p-0"><div class="slider-main col-sm-12">';
    echo '<div class="tab-content">';
    $counter =0;
    if(isset($node->field_images_and_videos['und'])) {
        foreach ($node->field_images_and_videos['und'] as $img_content) {
            $fid=$img_content['fid'];
            $file=file_load($fid);
            if ($counter == 0) {
                echo '<div id="image-area2_' . $r . '_' . $counter . '" class="tab-pane fade in active">';
            } else {
                echo '<div id="image-area2_' . $r . '_' . $counter . '" class="tab-pane fade">';
            }
            if ($file->type == 'image') {

                $image_rendered=theme('image_style', array(
                    'style_name'=>'800_380',
                    'path'=>$file->uri
                ));
                echo '<a href="/' . $alias . '">' . $image_rendered . '</a>';

            } else {
                $key='media_' . substr($file->filemime, 6) . '_video';
                $formatter_info=file_info_formatter_types($key);
                $content=array();
                $content['#theme']=$key;
                $content['#uri']=$file->uri;
                if (isset($formatter_info['default settings'])) {
                    $content['#options']=$formatter_info['default settings'];
                }

                print $rendered=drupal_render($content);
            }
            echo '</div>';
            $counter++;
        }
    }
    else{
        $image_rendered = '<img src="/sites/default/files/defualt.png" width="800" height="380">';
        echo '<a href="/' . $alias . '">' . $image_rendered . '</a>';
    }
    echo '</div>';
    echo '<ul class="nav slider2">';
    $counter = 0;
    if(isset($node->field_images_and_videos['und'])) {
        foreach ($node->field_images_and_videos['und'] as $img_content) {
            $fid=$img_content['fid'];
            $file=file_load($fid);

            if ($counter == 0) {
                echo '<li class="active">';
            } else {
                echo '<li>';
            }
            if ($file->type == 'image') {
                echo '<a data-toggle="tab" href="#image-area2_' . $r . '_' . $counter . '">';
                echo $image_rendered=theme('image_style', array(
                    'style_name'=>'82_82',
                    'path'=>$file->uri
                ));

                echo '</a>';
            } else {
                $file_tname='/sites/default/files/styles/media_thumbnail/public/media-vimeo/';
                $file_tname.=str_replace("vimeo://v/", "", $file->uri);
                $file_tname.='.jpg';
                echo '<a class="video"  data-toggle="tab" href="#image-area2_' . $r . '_' . $counter . '">';
                echo '<img src="' . $file_tname . '" height="82" width="82">';
                echo '</a>';
            }
            echo '</li>';
            $counter++;
        }
    }
    else{
        $image_rendered = '<img src="/sites/default/files/defualt.png" width="82" height="82">';
        echo '<a data-toggle="tab">' . $image_rendered . '</a>';
    }
    echo '</ul>';
    ?>
    <div class="product-det col-sm-12 p-0" >
        <div class="product-main col-sm-12 p-0" >
            <div class="product-title col-sm-9 p-0" >
                <?php echo $title; ?>
            </div>
            <div class="product-price col-sm-3 p-0" >
                <?php  if($node->field_product_reference['und'][0]['product_id']){
            
                    $pid = $node->field_product_reference['und'][0]['product_id'];}

                   $product = commerce_product_load($pid);

                          $price = commerce_product_calculate_sell_price($product);
                        
                        
                        $price_display = commerce_currency_format($price['amount'], $price['currency_code'], $product);
                              
                          echo $price_display;
                          

                          ?>


            </div>
            <div class="product-body col-sm-9 p-0" >
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
    <?php
    $r++;
endforeach; ?>
<script>
    jQuery(function($) {
        if (window.matchMedia('(max-width: 720px)').matches) {
            $('.slider2').bxSlider({
                slideWidth: 85,
                minSlides: 2,
                maxSlides: 4,
                slideMargin: 9
            });
        }
    });
</script>