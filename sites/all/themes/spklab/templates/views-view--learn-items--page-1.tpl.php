<?php
$new_node = node_load(arg(1));
/**
 * @file
 * Main view template.
 *
 * Variables available:
 * - $classes_array: An array of classes determined in
 *   template_preprocess_views_view(). Default classes are:
 *     .view
 *     .view-[css_name]
 *     .view-id-[view_name]
 *     .view-display-id-[display_name]
 *     .view-dom-id-[dom_id]
 * - $classes: A string version of $classes_array for use in the class attribute
 * - $css_name: A css-safe version of the view name.
 * - $css_class: The user-specified classes names, if any
 * - $header: The view header
 * - $footer: The view footer
 * - $rows: The results of the view query, if any
 * - $empty: The empty text to display if the view is empty
 * - $pager: The pager next/prev links to display, if any
 * - $exposed: Exposed widget form/info to display
 * - $feed_icon: Feed icon to display, if any
 * - $more: A link to view more, if any
 *
 * @ingroup views_templates
 */
?>
<div id="block-views-item-title-block">
    <div class="learn-item-cart-button">
        <div id="learn-item-cart">
            <?php
            $product = commerce_product_load($new_node->field_product_reference['und'][0]['product_id']);
            $price = commerce_product_calculate_sell_price($product);
            if(isset($price['data']['components'][2]['name']) && $price['data']['components'][2]['name'] == 'discount') {
                $price_display = commerce_currency_format($price['amount'], $price['currency_code'], $product);
            } else {
                $price_display = commerce_currency_format($price['data']['components'][0]['price']['amount'], $price['data']['components'][0]['price']['currency_code'], $product);
            }
            echo '<span class="inline-price" >'.$price_display.'</span>';
            $content = (node_view($new_node, 'full', NULL));
            print drupal_render_children($content['field_product_reference']);
            ?>
        </div>
    </div>
  <div class="view-content">
    <?php print views_embed_view('item_title', 'block', arg(1)); ?>
      <?php
      if($new_node->field_product_type['und'][0]['value'] == 'Kits') {
          ?>
          <div id="learn-item-follow-kits">
              <a id="followKitModal" class="btn section-text">Follow</a>
          </div>

      <?php } ?>
  </div>
</div>

<div id="block-block-9">
        <?php $block = module_invoke('block', 'block_view', '9');
        print render($block['content']); ?>
</div>


<div id="block-views-learn-items-block">


    <div class="<?php print $classes; ?>">
        <?php print render($title_prefix); ?>
        <?php if ($title): ?>
            <?php print $title; ?>
        <?php endif; ?>
        <?php print render($title_suffix); ?>
        <?php if ($header): ?>
            <div class="view-header">
                <?php print $header; ?>
            </div>
        <?php endif; ?>

        <?php if ($exposed): ?>
            <div class="view-filters">
                <?php print $exposed; ?>
            </div>
        <?php endif; ?>

        <?php if ($attachment_before): ?>
            <div class="attachment attachment-before">
                <?php print $attachment_before; ?>
            </div>
        <?php endif; ?>

        <?php if ($rows): ?>
            <div class="view-content">
                <?php print $rows; ?>
            </div>
        <?php elseif ($empty): ?>
            <div class="view-empty">
                <?php print $empty; ?>
            </div>
        <?php endif; ?>

        <?php if ($pager): ?>
            <?php print $pager; ?>
        <?php endif; ?>

        <?php if ($attachment_after): ?>
            <div class="attachment attachment-after">
                <?php print $attachment_after; ?>
            </div>
        <?php endif; ?>

        <?php if ($more): ?>
            <?php print $more; ?>
        <?php endif; ?>

        <?php if ($footer): ?>
            <div class="view-footer">
                <?php print $footer; ?>
            </div>
        <?php endif; ?>

        <?php if ($feed_icon): ?>
            <div class="feed-icon">
                <?php print $feed_icon; ?>
            </div>
        <?php endif; ?>

    </div><?php /* class view */ ?>
</div>
<!--<div id="block-views-projects-view-block">-->
<!--    --><?php
//    $view = views_get_view('projects_view');
//    $print_view = $view->preview('block', array(arg(1)));
//    if (!empty($view->result)) {
//        ?>
<!--        <h2 class="block-title">Additional Projects</h2>-->
<!--        <div class="view-content">-->
<!--            --><?php
//            print views_embed_view('projects_view', 'block', arg(1));
//            ?>
<!--        </div>-->
<!--    --><?php //} ?>
<!--</div>-->
<!--<div id="block-views-teacher-aids-view-block">-->
<!--    --><?php
//    $view = views_get_view('teacher_aids_view');
//    $print_view = $view->preview('block', array(arg(1)));
//    if (!empty($view->result)) {
//        ?>
<!--        <h2 class="block-title">Teaching Aids</h2>-->
<!--        <div class="view-content">-->
<!--            --><?php
//            print views_embed_view('teacher_aids_view', 'block', arg(1));
//            ?>
<!--        </div>-->
<!--    --><?php //} ?>
<!--</div>-->
<div class="modal fade" id="learn-item-follow-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Follow This Kit</h4>
            </div>
            <div class="modal-body">
                <div class="form-errors">

                </div>
                <?php
                global $user;
                $user_email ='';
                if($user->uid){
                    $user_email = $user->mail;
                }
                ?>
                <div class="form-group">
                    <label for="mail-from">Email Address</label>
                    <input class="form-control" id="newsletter-learn-item-follow-email" name="mail-from" type="text" value="<?php echo $user_email;  ?>" />
                </div>
                <div id="learn-item-follow-type" style="display: none;"><?php $term = taxonomy_get_term_by_name($new_node->title, 'newsletter');
                    echo key($term); ?>
                </div>
            </div>
            <div class="modal-footer">
                <button id="newsletter-learn-item-follow-submit" type="button" class="btn btn-primary send-mail-btn">Follow kit</button>
            </div>
        </div>
    </div>
</div>
<script>
    jQuery(function($) {
        $('#followKitModal').click(function(){
            $('#learn-item-follow-modal').modal();
        });
        $('#newsletter-learn-item-follow-submit').click(function(){
            $('#learn-item-follow-modal').modal('hide');
        });
    });
</script>
