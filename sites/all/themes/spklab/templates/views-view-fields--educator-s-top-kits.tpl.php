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
<div class ="educatorkit col-sm-4">
	<h3 class ="edutype"> <?php echo $fields['title']->content; ?> <h3>
	 <div class="edubox">

 <?php echo $fields['field_images_and_videos']->content; ?>

<h4 class=" eduproductname"><?php echo $fields['title_1']->content; ?>  </h4>

<div class= "edusummary">
 <?php echo $fields['field_summary']->content; ?> </div>
	 </div>
	   <?php echo $fields['edit_node']->content; ?>

</div>
 