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
<?php foreach ($rows as $row_count => $row): ?>
  <?php foreach ($row as $field => $content): ?>
    <?php $total_qty = 0; ?>
    <?php if($field=='edit_quantity') { $total_qty += $content;  } ?>
    <?php if($total_qty == 1){ $item_string = 'item'; }else{ $item_string = 'items'; } ?>
  <?php endforeach; ?>
<?php endforeach; ?>

<div id='cart-view-main' class="col-sm-8 p-0">
    <div id='cart-view-main-title'>
        <h2>Your Cart</h2>
    </div>
<?php
foreach ($rows as $row_count => $row): ?>

    <?php
    foreach ($row as $field => $content): ?>

        <?php
        //print $field;
        if($field=='commerce_total') { $total = $content;	}
        if($field=='edit_quantity') { $qty = $content;	}
        if($field=='edit_delete') { $delete = $content;	}
        if($field=='commerce_display_path') { $path = $content;	}
        if($field=='commerce_product') { $sku = $content;	}
        if($field=='line_item_id') { $line_item_id = trim(strip_tags($content)); }
         

    ?>
  <?php endforeach; ?>
    <?php
    $path = str_replace("node/","",$path);
    $node = node_load($path);
    ?>
    <div id="main-cart-item" class="main-cart-items col-sm-12">

        <div class="product-img col-sm-3  p-0">
  <?php
  if($node->field_product_type['und'][0]['value'] == 'Kits') {
      if(isset($node->field_images_and_videos['und'])) {
          foreach ($node->field_images_and_videos['und'] as $img_content) {
              $fid=$img_content['fid'];
              $file=file_load($fid);
              if ($file->type == 'image') {
                  echo $image_rendered=theme('image_style', array(
                      'style_name'=>'182_182',
                      'path'=>$file->uri
                  ));
                  break;
              }
          }
      }
      else{
          $image_rendered = '<img src="/sites/default/files/defualt.png" width="182" height="182">';
          echo $image_rendered ;
      }
  }else{
    if(isset($node->field_add_ons_accessories_image['und'])) {
      $fid =  $node->field_add_ons_accessories_image['und'][0]['fid'];
      $file = file_load($fid);
      echo $image_rendered = theme('image_style', array(
          'style_name' => '182_182',
          'path' => $file->uri
      ));
    }
    else{
        $image_rendered = '<img src="/sites/default/files/defualt.png" width="182" height="182">';
        echo $image_rendered ;
    }
  }
  ?>
        </div>
        <div class="col-sm-5">
          <div class="product-title"><?php echo $node->title; ?></div>
          <div class="product-sku"><?php echo 'SKU # '. $sku; ?></div>
          <div class="product-delete delete-<?php print $line_item_id; ?>"><?php print $delete; ?></div>
        </div>
        <div class="col-sm-3 p-0">
            <span class="product-qty-label">Qty:</span>
            <a class="product-qty-dec" lineitemid=<?php print $line_item_id; ?>>-</a>
            <span class="product-qty" qtyitemid=<?php print $line_item_id; ?>><?php print $qty; ?></span>
            <a class="product-qty-inc" lineitemid=<?php print $line_item_id; ?>>+</a>
        </div>
        <div class="col-sm-1 p-0">
            <div class="product-total-price item-<?php print $line_item_id; ?>"><?php print $total; ?></div>
        </div>

  </div>
<?php endforeach; ?>
</div>