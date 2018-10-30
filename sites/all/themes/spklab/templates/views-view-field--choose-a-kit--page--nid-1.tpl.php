<?php

/**
 * @file
 * This template is used to print a single field in a view.
 *
 * It is not actually used in default Views, as this is registered as a theme
 * function which has better performance. For single overrides, the template is
 * perfectly okay.
 *
 * Variables available:
 * - $view: The view object
 * - $field: The field handler object that can process the input
 * - $row: The raw SQL result that can be used
 * - $output: The processed output that will normally be used.
 *
 * When fetching output from the $row, this construct should be used:
 * $data = $row->{$field->field_alias}
 *
 * The above will guarantee that you'll always get the correct data,
 * regardless of any changes in the aliasing that might happen if
 * the view is modified.
 */
?>
<?php //print $output;
    $node = node_load($output);
    $alias = drupal_get_path_alias('node/'.$output);
    $flag = 0;
    if(isset($node->field_images_and_videos['und'])){
        foreach ($node->field_images_and_videos['und'] as $img_content) {
            $fid = $img_content['fid'];
            $file = file_load($fid);
            if ($file->type == 'image') {
                $image_rendered = theme('image_style', array(
                    'style_name' => '580x416',
                    'path' => $file->uri
                ));
                echo  "<a href='/learn_items/".$node->nid."'>".$image_rendered."</a>";
                $flag = 1;
                break;
            }
        }
        if(!$flag){
            $image_rendered = '<img src="/sites/default/files/defualt.png" width="580" height="410">';
            echo  "<a href='/learn_items/".$node->nid."'>".$image_rendered."</a>";
        }
    }else{
        $image_rendered = '<img src="/sites/default/files/defualt.png" width="580" height="410">';
        echo  "<a href='/learn_items/".$node->nid."'>".$image_rendered."</a>";
    }
