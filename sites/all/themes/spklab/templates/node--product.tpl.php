<?php
/**
 * @file
 * Default theme implementation to display a node.
 *
 * Available variables:
 * - $title: the (sanitized) title of the node.
 * - $content: An array of node items. Use render($content) to print them all,
 *   or print a subset such as render($content['field_example']). Use
 *   hide($content['field_example']) to temporarily suppress the printing of a
 *   given element.
 * - $user_picture: The node author's picture from user-picture.tpl.php.
 * - $date: Formatted creation date. Preprocess functions can reformat it by
 *   calling format_date() with the desired parameters on the $created variable.
 * - $name: Themed username of node author output from theme_username().
 * - $node_url: Direct URL of the current node.
 * - $display_submitted: Whether submission information should be displayed.
 * - $submitted: Submission information created from $name and $date during
 *   template_preprocess_node().
 * - $classes: String of classes that can be used to style contextually through
 *   CSS. It can be manipulated through the variable $classes_array from
 *   preprocess functions. The default values can be one or more of the
 *   following:
 *   - node: The current template type; for example, "theming hook".
 *   - node-[type]: The current node type. For example, if the node is a
 *     "Blog entry" it would result in "node-blog". Note that the machine
 *     name will often be in a short form of the human readable label.
 *   - node-teaser: Nodes in teaser form.
 *   - node-preview: Nodes in preview mode.
 *   The following are controlled through the node publishing options.
 *   - node-promoted: Nodes promoted to the front page.
 *   - node-sticky: Nodes ordered above other non-sticky nodes in teaser
 *     listings.
 *   - node-unpublished: Unpublished nodes visible only to administrators.
 * - $title_prefix (array): An array containing additional output populated by
 *   modules, intended to be displayed in front of the main title tag that
 *   appears in the template.
 * - $title_suffix (array): An array containing additional output populated by
 *   modules, intended to be displayed after the main title tag that appears in
 *   the template.
 *
 * Other variables:
 * - $node: Full node object. Contains data that may not be safe.
 * - $type: Node type; for example, story, page, blog, etc.
 * - $comment_count: Number of comments attached to the node.
 * - $uid: User ID of the node author.
 * - $created: Time the node was published formatted in Unix timestamp.
 * - $classes_array: Array of html class attribute values. It is flattened
 *   into a string within the variable $classes.
 * - $zebra: Outputs either "even" or "odd". Useful for zebra striping in
 *   teaser listings.
 * - $id: Position of the node. Increments each time it's output.
 *
 * Node status variables:
 * - $view_mode: View mode; for example, "full", "teaser".
 * - $teaser: Flag for the teaser state (shortcut for $view_mode == 'teaser').
 * - $page: Flag for the full page state.
 * - $promote: Flag for front page promotion state.
 * - $sticky: Flags for sticky post setting.
 * - $status: Flag for published status.
 * - $comment: State of comment settings for the node.
 * - $readmore: Flags true if the teaser content of the node cannot hold the
 *   main body content.
 * - $is_front: Flags true when presented in the front page.
 * - $logged_in: Flags true when the current user is a logged-in member.
 * - $is_admin: Flags true when the current user is an administrator.
 *
 * Field variables: for each field instance attached to the node a corresponding
 * variable is defined; for example, $node->body becomes $body. When needing to
 * access a field's raw values, developers/themers are strongly encouraged to
 * use these variables. Otherwise they will have to explicitly specify the
 * desired field language; for example, $node->body['en'], thus overriding any
 * language negotiation rule that was previously applied.
 *
 * @see template_preprocess()
 * @see template_preprocess_node()
 * @see template_process()
 *
 * @ingroup templates
 */

?>
<article id="node-<?php print $node->nid; ?>" class="<?php print $classes; ?> clearfix"<?php print $attributes; ?>
         xmlns="http://www.w3.org/1999/html">
  <?php if ((!$page && !empty($title)) || !empty($title_prefix) || !empty($title_suffix) || $display_submitted): ?>
  <header>
    <?php print render($title_prefix); ?>
    <?php if (!$page && !empty($title)): ?>
    <h2<?php print $title_attributes; ?>><a href="<?php print $node_url; ?>"><?php print $title; ?></a></h2>
    <?php endif; ?>
    <?php print render($title_suffix); ?>
    <?php if ($display_submitted): ?>
    <span class="submitted">
      <?php print $user_picture; ?>
      <?php print $submitted; ?>
    </span>
    <?php endif; ?>
  </header>
  <?php endif; ?>
  <?php
    // Hide comments, tags, and links now so that we can render them later.
    hide($content['comments']);
    hide($content['links']);
    hide($content['field_tags']);
  ?>
<div class="region-inner-fix">
    <div class="main-page-slider col-sm-12">
        <?php
        if($node->field_product_type['und'][0]['value'] == 'Kits') {
        ?>

        <div class="tab-content">
            <?php
            $counter = 0;
            if (isset($node->field_images_and_videos['und'])) {
                foreach ($node->field_images_and_videos['und'] as $img_content) {
                   
                    $fid = $img_content['fid'];
                    $file = file_load($fid);
                    if ($counter == 0) {
                        echo '<div id="image-area2_' . $counter . '" class="tab-pane fade in active">';
                    } else {
                        echo '<div id="image-area2_' . $counter . '" class="tab-pane fade">';
                    }
                    if ($file->type == 'image') {

                        $image_rendered = theme('image_style', array(
                            'style_name' => '2400_1150',
                            'path' => $file->uri
                        ));

                        echo "<span class='zoom'>" . $image_rendered . "</span>";
                    } else {
                        $key = 'media_' . substr($file->filemime, 6) . '_video';
                        $formatter_info = file_info_formatter_types($key);
                        $content = array();
                        $content['#theme'] = $key;
                        $content['#uri'] = $file->uri;
                        if (isset($formatter_info['default settings'])) {
                            $content['#options'] = $formatter_info['default settings'];
                        }
                        print $rendered = drupal_render($content);
                    }
                    echo '</div>';
                    $counter++;
                }
            }else{
                $image_rendered = '<img src="/sites/default/files/defualt.png" width="2400" height="450">';
                echo  $image_rendered ;
            }
                ?>
            </div>
            <div class="main-thumnil">
                <ul class="nav  new-slider">
                    <?php
                    $counter = 0;
                    if(isset($node->field_images_and_videos['und'])) {
                        foreach ($node->field_images_and_videos['und'] as $img_content) {
                            $fid = $img_content['fid'];
                            $file = file_load($fid);

                            if ($counter == 0) {
                                echo '<li class="active">';
                            } else {
                                echo '<li>';
                            }
                            if ($file->type == 'image') {
                                echo '<a data-toggle="tab" href="#image-area2_' . $counter . '">';
                                echo $image_rendered = theme('image_style', array(
                                    'style_name' => '100_100',
                                    'path' => $file->uri
                                ));

                                echo '</a>';
                            } else {
                                $file_tname = '/sites/default/files/styles/media_thumbnail/public/media-vimeo/';
                                $file_tname .= str_replace("vimeo://v/", "", $file->uri);
                                $file_tname .= '.jpg';
                                echo '<a class="video" data-toggle="tab" href="#image-area2_' . $counter . '">';
                                echo '<img src="' . $file_tname . '" height="100" width="100">';
                                echo '</a>';
                            }
                            echo '</li>';
                            $counter++;
                        }
                    }
                    ?>
                </ul>
            </div>
        <?php }
        else{
            $fid =  $node->field_add_ons_accessories_image['und'][0]['fid'];
            $file = file_load($fid);
            $image_rendered = theme('image_style', array(
                'style_name' => '2400_1150',
                'path' => $file->uri
            ));
            echo "<span class='zoom' >".$image_rendered."</span>";
        }
        ?>
    </div>
  <div id="tags_colors">

      <?php

      if($node->field_tag_colorr['und']) {
          $tags_color = '';
          foreach ($node->field_tag_colorr['und'] as $tag_content) {
              $tags_color .= $tag_content['value'] . ',';
          }
          $tags_color = rtrim($tags_color, ",");
          echo $tags_color;
      }

      ?>

  </div>
    <div class="main-content col-sm-12">
      <div class="main-tags col-sm-2 pleft-0">
        <div class="tags-label tag">
          Good For
        </div>
        <div class="tags-container">
        <?php
        if(isset($node->field_product_tags['und'])) {
            foreach ($node->field_product_tags['und'] as $tags) {
                echo '<div class="tags-content tag">' . $tags['taxonomy_term']->name . '</div>';
            }
        }
        ?>
        </div>
      </div>
      <div class="main-content-body col-sm-6 pleft-0">
        <div class="summary-content col-sm-12 pleft-0">
          <q><?php print $node->field_summary['und'][0]['value']; ?></q>
        </div>
        <div class="body-content col-sm-12 pleft-0">
          <?php
             $my_value = field_view_field('node', $node, 'body', array('label'=>'hidden'));
               print render($my_value);
            ?>
          </div>
        <div class="tools">
          <?php
            if(isset($node->field_tools['und'])) {
              foreach ($node->field_tools['und'] as $tools) {
                echo '<span class="tool-area '.$tools['value'].'"></span>';
              }
            }
          ?>
          </div>
      </div>
      <div class="main-content-cart col-sm-4">
        <div class="sidebar2-content col-sm-12 p-0">
        <div class="sidebar-sections-price col-sm-12 p-0">
          <div class="price-content new-price col-sm-6 p-0">
              <?php
              if(isset($price_sale)){
                  echo $price_sale;

              }else{
                  echo $price_original;
              }
              ?>

          </div>
          <div class="price-content old-price col-sm-6 p-0">
              <?php
              if(isset($price_sale)) {
                  echo $price_original;
              }
              ?>
          </div>
        </div>
        <div class="cart-form-content col-sm-12 p-0">
          <?php
           print drupal_render_children($cart);
          ?>
        </div>
        <div class="sidebar-sections col-sm-12 p-0">
            <a id="shipingmodel" class="section-text" href="#">Shipping & Returns</a>
        </div>
        <div class=" sidebar-sections col-sm-12 p-0">
          <a id="EmailModal" class="send_mail section-text">Send to a Friend</a>
        </div>
        <div class="sidebar-sections col-sm-12 p-0">
            <span class="section-text">Share</span> <div class="share-section"><?php // print_r($content['sharethis']); ?></div>
        </div>
       </div>
      </div>
    </div>
  </div>
<div class="region-inner-full glowing-papertronics-region">
  <div id="block-views-glowing-papertronics-block">
  <?php
     echo views_embed_view('glowing_papertronics', 'block');
  ?>
</div>
</div>
<div class="region-inner-fix goes-well-item">
  <?php
 if(isset($node->field_goes_well_with['und'])){
  ?>
  <h2 class="block-title goes-well-with-title">Goes Well With</h2>
    <div class="view-goes-well-with">
      <?php
      if(isset($node->field_goes_well_with['und'])) {
          foreach ($node->field_goes_well_with['und'] as $goes_well_product) {
              $new_id = $goes_well_product['nid'];
              $new_node = node_load($new_id);
              ?>
              <div class="main-goes-well ">
                  <div class="product-det2  p-0">
                      <div class="product-img col-sm-12 p-0">
                          <div class="product-img-content col-sm-12 p-0">
                              <?php
                              if ($new_node->field_product_type['und'][0]['value'] == 'Kits') {
                                  if(isset($new_node->field_images_and_videos['und'])){
                                      foreach ($new_node->field_images_and_videos['und'] as $img_content) {
                                          $fid = $img_content['fid'];
                                          $file = file_load($fid);
                                          if ($file->type == 'image') {
                                              echo $image_rendered = theme('image_style', array(
                                                  'style_name' => '375_375',
                                                  'path' => $file->uri
                                              ));
                                              break;
                                          }
                                      }
                                  }
                                  else{
                                      $image_rendered = '<img src="/sites/default/files/defualt.png" width="375" height="375">';
                                      echo $image_rendered ;
                                  }
                              } else {
                                  if(isset($new_node->field_add_ons_accessories_image['und'])) {
                                      $fid = $new_node->field_add_ons_accessories_image['und'][0]['fid'];
                                      $file = file_load($fid);
                                      echo $image_rendered = theme('image_style', array(
                                          'style_name' => '375_375',
                                          'path' => $file->uri
                                      ));
                                  }
                              }
                              ?>
                          </div>
                          <div class="product-cart col-sm-12 p-0">
                              <?php
                              if ($new_node->field_product_type['und'][0]['value'] == 'Kits') {
                                  $content = (node_view($new_node, 'full', NULL));
                                  print drupal_render_children($content['field_product_reference']);
                              } else {
                                  $content = (node_view($node, 'full', NULL));
                                  print drupal_render_children($content['field_add_ons_accessories_refere']);
                              }
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
                          if ($new_node->field_product_type['und'][0]['value'] == 'Kits') {
                              $pid = $new_node->field_product_reference['und'][0]['product_id'];
                          } else {
                              $pid = $new_node->field_add_ons_accessories_refere['und'][0]['product_id'];
                          }
                          $product = commerce_product_load($pid);
                          $price = commerce_product_calculate_sell_price($product);

                          if(isset($price['data']['components'][2]['name']) && $price['data']['components'][2]['name'] == 'discount') {
                              $price_display = commerce_currency_format($price['amount'], $price['currency_code'], $product);
                          } else {
                              $price_display = commerce_currency_format($price['data']['components'][0]['price']['amount'], $price['data']['components'][0]['price']['currency_code'], $product);
                          }
                          echo $price_display;
                          ?>
                      </div>
                  </div>
              </div>

          <?php }

      }
      ?>
    </div>
  <?php } ?>
</div>

<div class="region-inner-fix similar-products">
  <?php
 if(isset($node->field_similar_items['und'])){
  ?>
  <h2 class="block-title goes-well-with-title">Similar Items</h2>
    <div class="view-goes-well-with">
      <?php
      if(isset($node->field_similar_items['und'])) {
          foreach ($node->field_similar_items['und'] as $goes_well_product) {
              $new_id = $goes_well_product['nid'];
              $new_node = node_load($new_id);
              ?>
              <div class="main-goes-well ">
                  <div class="product-det2  p-0">
                      <div class="product-img col-sm-12 p-0">
                          <div class="product-img-content col-sm-12 p-0">
                              <?php
                              if ($new_node->field_product_type['und'][0]['value'] == 'Kits') {
                                  if(isset($new_node->field_images_and_videos['und'])){
                                      foreach ($new_node->field_images_and_videos['und'] as $img_content) {
                                          $fid = $img_content['fid'];
                                          $file = file_load($fid);
                                          if ($file->type == 'image') {
                                              echo $image_rendered = theme('image_style', array(
                                                  'style_name' => '375_375',
                                                  'path' => $file->uri
                                              ));
                                              break;
                                          }
                                      }
                                  }
                                  else{
                                      $image_rendered = '<img src="/sites/default/files/defualt.png" width="375" height="375">';
                                      echo $image_rendered ;
                                  }
                              } else {
                                  if(isset($new_node->field_add_ons_accessories_image['und'])) {
                                      $fid = $new_node->field_add_ons_accessories_image['und'][0]['fid'];
                                      $file = file_load($fid);
                                      echo $image_rendered = theme('image_style', array(
                                          'style_name' => '375_375',
                                          'path' => $file->uri
                                      ));
                                  }
                              }
                              ?>
                          </div>
                          <div class="product-cart col-sm-12 p-0">
                              <?php
                              if ($new_node->field_product_type['und'][0]['value'] == 'Kits') {
                                  $content = (node_view($new_node, 'full', NULL));
                                  print drupal_render_children($content['field_product_reference']);
                              } else {
                                  $content = (node_view($node, 'full', NULL));
                                  print drupal_render_children($content['field_add_ons_accessories_refere']);
                              }
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
                          if ($new_node->field_product_type['und'][0]['value'] == 'Kits') {
                              $pid = $new_node->field_product_reference['und'][0]['product_id'];
                          } else {
                              $pid = $new_node->field_add_ons_accessories_refere['und'][0]['product_id'];
                          }
                          $product = commerce_product_load($pid);
                          $price = commerce_product_calculate_sell_price($product);
                      

                          if(isset($price['data']['components'][2]['name']) && $price['data']['components'][2]['name'] == 'discount') {
                              $price_display = commerce_currency_format($price['amount'], $price['currency_code'], $product);
                          } else {
                              $price_display = commerce_currency_format($price['data']['components'][0]['price']['amount'], $price['data']['components'][0]['price']['currency_code'], $product);
                          }
                          echo $price_display;
                          ?>
                      </div>
                  </div>
              </div>

          <?php }

      }
      ?>
    </div>
  <?php } ?>
</div>


<div class="similar_product"> <?php print render($content['field_similar_items']); ?>  </div>

<div class="container region-inner-fix example-project-region">
  <?php
    $view = views_get_view('projects_view');
    $print_view = $view->preview('block_1', array($node->nid));
    if (!empty($view->result)) {
      ?>
      <h2 class="block-title">Example Projects & Videos</h2>
      <div class="example-projects">
        <?php
        print views_embed_view('projects_view', 'block_1', $node->nid);
        ?>
      </div>
  <div class="example-project-link col-sm-12 p-0">
      <a href="<?php echo '/learn_items/'.$node->nid; ?>" class="btn example-project-btn-link">See All Projects & Videos</a>
    </div>
    <?php } ?>
  </div>
  <div class="region-inner-fix reviews-region">
   <?php
   $view = views_get_view('reviews');
   $print_view = $view->preview('block', array($node->nid));
   if(!empty($view->result)){
 ?>
    <h2 class="block-title">Reviews for <?php echo $node->title; ?></h2>
    <div class="reviews">
      <?php
      print views_embed_view('reviews', 'block', $node->nid);
      ?>
    </div>
      <?php } ?>
    </div>
  <?php if (!empty($content['field_tags']) || !empty($content['links'])): ?>
  <footer>
    <?php print render($content['field_tags']); ?>
    <?php print render($content['links']); ?>
  </footer>
  <?php endif; ?>
  <?php print render($content['comments']); ?>
</article>

<!------------------------- Pop up box -------------------------------->
<div class="modal fade" id="myEmailModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Send Mail</h4>
      </div>
      <div class="validation-error" style="display:none;"></div>
      <div class="modal-body">
        <div class="form-errors">
          <ul>
            <li class="from-error" style="display:none; color:#a51f18;" >Enter A valid Email in From field.</li>
            <li class="sendto-error" style="display:none; color:#a51f18;" >Enter A valid Email in Send to field.</li>
          </ul>
        </div>
        <div class="form-group">
          <div class ="row">
     <div class="col-sm-3">
           <?php 
              $fid = $node->field_images_and_videos['und'][0]['fid'];
              $file = file_load($fid);      
              $image_rendered = theme('image_style', array(
                  'style_name' => 'media_thumbnail',
                  'path' => $file->uri
                 ));

                 echo "<span >" . $image_rendered . "</span>";
          ?> </div>
          <div class="col-sm-9">
        <div class="product-title"><label><?php echo $title; ?></label></div>
         <div class="product-desc"><label><?php echo $node->field_summary['und'][0]['value']; ?></label></div> 
          <div class="page-url"><label>Product URL : </label><div class="myurl"><?php echo $_SERVER['HTTP_HOST'] . request_uri(); ?></div></div>

       </div> </div>
        
          <!-- <div class="page-url"><label>Product URL : </label><div class="myurl"><?php echo $_SERVER['HTTP_HOST'] . request_uri(); ?></div></div> -->
          <div class="form-group">
          <label for="mail-subject">Subject</label>
          <input class="form-control" id="subject" name="subject" value="<?php echo $title; ?>" />
          </div>
          <div class="form-group">
          <div><label for="mail-from">From</label></div>
          <input class="form-control" id="mail-from" name="mail-from" type="text" value="<?php echo $site_email = variable_get('site_mail', ''); ?>" />
        </div>
        </div>
        <div class="productmailform">
        <div class="form-group">
          <label for="mail-to">Send To</label>
          <input class="form-control" id="mail-to" name="mail-to" type="text" />
        </div>
        <div class="form-group">
          
           <label for="agent_title">Message</label>
          <textarea class="form-control" id="message" name="message"  style="min-height: 90px;"></textarea>
        </div>
        
        <div class="form-group">
          <input class="form-control" id="site_email" name="site_email" type="hidden" value="<?php echo $site_email = variable_get('site_mail', ''); ?>" />
        </div>
        <div class="form-group">
          <input class="form-control" id="page-title" name="mail-title" type="hidden" value="<?php echo $node->title; ?>" />
        </div>
      
      <div class="modal-footer">
        <button type="button" class="btn btn-primary send-mail-btn">Send</button>
      </div>
    </div>
    </div>
  </div>
  </div>
</div>
<div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Send Mail</h4>
      </div>
      <p>Email has been send successfully.</p>
    </div>
  </div>
</div>
<div class="modal fade" id="shipinggmodel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      <h4 class="modal-title" id="myModalLabel">Shipping & Returns</h4>
    </div>
    <div class="modal-body">
      <?php
      if(isset($node->field_shipping_and_returns['und'])) {
          print $node->field_shipping_and_returns['und'][0]['value'];
      }
      ?>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div>
  </div>
</div>
</div>

<script>
  jQuery(function($) {
       var mode = 'vertical';
      
            if ( ! window.matchMedia('(max-width: 768px)').matches) {
           $('.new-slider').bxSlider({
               mode: mode,
               slideWidth: 100,
               minSlides: 5,
              slideMargin: 0
          });
       }
     
    
    //   if ($(window).width() < 767) {
    //      var mode = 'horizontal';
    //  $('.main-ver-slider').bxSlider({
    //             mode: mode,
    //          slideWidth: 85,
    //             minSlides: 2,
    //             maxSlides: 4,
    //             slideMargin: 5
    //       });
    // }
      if (window.matchMedia('(max-width: 720px)').matches) {
            $('.new-slider').bxSlider({
                slideWidth: 85,
                minSlides: 2,
                maxSlides: 4,
                slideMargin: 9
            });
        }
    $('#EmailModal').click(function(){
      $(".from-error").css("display","none");
      $(".sendto-error").css("display","none");
      $('#myEmailModal').modal();
    });
    $('#shipingmodel').click(function(){
      $('#shipinggmodel').modal();
    });
    $("#myEmailModal .send-mail-btn").click(function( event ) {
      if($('#mail-from').val() == "" || $('#mail-to').val() == "" ){
        if($('#mail-from').val() == "" ){
          $(".from-error").css("display","block");
        }
        else{
          $(".from-error").css("display","none");
        }
        if($('#mail-to').val() == "" ){
          $(".sendto-error").css("display","block");
        }
        else{
          $(".sendto-error").css("display","none");
        }
      }
      else{
        event.preventDefault();
        $(".from-error").css("display","none");
        $(".sendto-error").css("display","none");
        $('#myEmailModal').modal('hide');
        var from = $('#mail-from').val();
        var to = $('#mail-to').val();
        var message = $('#message').val();
        var site_email = $('#site_email').val();
        var page_title = $('#page-title').val();
        var subject = $('#subject').val();
        var desc = $('.product-desc').text();
	var img = $('.main-page-slider img:first').attr('src');
        var page_url = $('.page-url .myurl').text();
        var page_title_enc = encodeURIComponent(page_title);
        var subject_enc = encodeURIComponent(subject);
        var message_enc = encodeURIComponent(message);
        var desc_enc = encodeURIComponent(desc);
        var data = "from="+from+"&to="+to+"&subject="+subject_enc+"&message="+message_enc+"&page_title="+page_title_enc+"&site_email="+site_email+"&page_url="+page_url+"&img_url="+img+"&page_desc="+desc_enc;

        $.ajax
        ({
          type: "POST",
          url: "/sendmail",
          data: data,
          success: function(comment)
          {
            $('#successModal').modal();
            $('#mail-to, #message').val('');
          }
        });

      }
    });
    setTimeout(function()
    {
      $('#successModal').modal('hide');

    }, 1000);
    function validateEmail(emailField){
      var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;

      if (reg.test(emailField.value) == false)
      {
        return false;
      }

      return true;

    }
    $('.reviews-region .view-footer .show-all-reviews').click(function(e){
      e.preventDefault();
      $('.reviews-region .view-content').toggleClass('full-height');
    });
   
    
    if ($(window).width() < 381) {
       
        $("#block-system-main .main-thumnil").css({"position":"relative","margin-left": "67px","height": "50px","overflow":"hidden","width":"291px"});
        $("#block-system-main .main-thumnil .bx-wrapper").css("max-width","100% !important");
        $("#block-system-main .main-thumnil .bx-wrapper .bx-viewport").css({"max-width":"86% ","height": "50px", "overflow": "hidden"});
        $("#block-system-main .main-thumnil .bx-wrapper ul").css({"max-width":"375px","width": "375px","position":"relative"});
        $("#block-system-main .main-thumnil .bx-wrapper ul li").css({"position": "unset !important", "float": "left"});
        }
  });
//  if ($(window).width() < 381) {
//    $("#block-views-items-block .view-content .bx-wrapper").css("max-width","185px !important");
//  }
</script>
<!--<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-585b8e5f904b1790"></script>-->

