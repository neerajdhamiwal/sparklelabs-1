<?php

/**
 * @file
 * Default simple view template to all the fields as a row.
 *
 * - $view: The view in use.
 * - $fields: an array of $field objects. Each one contains:
 *   - $field->content: The output of the field.
 *   - $field->raw: The raw data for the field, if it exists. This is NOT output safe.
 *   - $field->class: The safe class id to use.
 *   - $field->handler: The Views field handler object controlling this field. Do not use
 *     var_export to dump this object, as it can't handle the recursion.
 *   - $field->inline: Whether or not the field should be inline.
 *   - $field->inline_html: either div or span based on the above flag.
 *   - $field->wrapper_prefix: A complete wrapper containing the inline_html to use.
 *   - $field->wrapper_suffix: The closing tag for the wrapper.
 *   - $field->separator: an optional separator that may appear before a field.
 *   - $field->label: The wrap label text to use.
 *   - $field->label_html: The full HTML of the label to use including
 *     configured element type.
 * - $row: The raw result object from the query, with all data it fetched.
 *
 * @ingroup views_templates
 */
?>
<?php foreach ($fields as $id => $field): ?>
	<?php if($id == 'commerce_display_path') {
		$path = strip_tags($field->content);
		//echo "<pre>";print_r($field);exit;
	    //echo $path;exit;
	    $nid = str_replace("node/","",$path);
	    $node = node_load($nid);
	    print '<div class="col-md-4 product-img-wrap">';
	    if($node->field_product_type['und'][0]['value'] == 'Kits') {
	    	if(isset($node->field_images_and_videos['und'])) {
		      foreach ($node->field_images_and_videos['und'] as $img_content) {
		          $fid=$img_content['fid'];
		          $file=file_load($fid);
		          if ($file->type == 'image') {
		              print $image_rendered=theme('image_style', array(
		                  'style_name'=>'182_182',
		                  'path'=>$file->uri,
		              ));
		              break;
		          }
		      }
		  }
		  else{
		      $image_rendered = '<img class="img-responsive" src="/sites/default/files/defualt.png" width="182" height="182">';
		      print $image_rendered ;
		  }
		}else{
		    if(isset($node->field_add_ons_accessories_image['und'])) {
			  $fid =  $node->field_add_ons_accessories_image['und'][0]['fid'];
			  $file = file_load($fid);
			  print $image_rendered = theme('image_style', array(
			      'style_name' => '182_182',
			      'path' => $file->uri,
			      'class' => 'pad-left-0',
			  ));
			}else{
			    $image_rendered = '<img class="img-responsive" src="/sites/default/files/defualt.png" width="182" height="182">';
			    print $image_rendered ;
			}
		}
		print '</div>';
	}
	?>

    <?php if($id != 'commerce_display_path'): ?>
	  <?php if (!empty($field->separator)): ?>
	    <?php print $field->separator; ?>
	  <?php endif; ?>

	  <?php print $field->wrapper_prefix; ?>
	    <?php print $field->label_html; ?>
	    <?php print $field->content; ?>
	  <?php print $field->wrapper_suffix; ?>
	<?php endif; ?>
<?php endforeach; ?>