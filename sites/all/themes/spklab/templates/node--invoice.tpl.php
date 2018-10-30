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
$order_id = $node->title;
?>
<script type="text/javascript">

    jQuery(document).ready(function() {

//            var doc = new jsPDF();
//            var specialElementHandlers = {
//                '#editor': function (element, renderer) {
//                    return true;
//                }
//            };
//        jQuery('#submit').click(function () {
//                doc.fromHTML(jQuery('.node-invoice').html(), 15, 15, {
//                    'width': 190,
//                    'elementHandlers': specialElementHandlers
//                });
//                doc.save('sample-page.pdf');
//            });

        var
            form = jQuery('.node-invoice'),
            cache_width = form.width(),
            a4  =[ 595.28,  841.89];  // for a4 size paper width and height

        jQuery('#submit').on('click',function(){
            jQuery('body').scrollTop(0);
            createPDF();
        });
//create pdf
        function createPDF(){
            getCanvas().then(function(canvas){
                var
                    img = canvas.toDataURL("image/png"),
                    doc = new jsPDF({
                        unit:'px',
                        format:'a4'
                    });
                doc.addImage(img, 'JPEG', 20, 20);
                doc.save('order-<?php echo $order_id; ?>-invoice.pdf');
                form.width(cache_width);
            });
        }

// create canvas object
        function getCanvas(){
            form.width((a4[0]*1.33333) -80).css('max-width','none');
            return html2canvas(form,{
                imageTimeout:2000,
                removeContainer:true
            });
        }



    });



</script>
<button style="float: right;" class="btn btn-info" id="submit">Print</button>
<article id="node-<?php print $node->nid; ?>" class="<?php print $classes; ?> clearfix"<?php print $attributes; ?>>
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
    //print render($content);
  $commerce_shipping_service ='';
  $order = commerce_order_load($order_id);
  $order_wrapper = commerce_order_load($order_id);
  $line_items = $order_wrapper->commerce_line_items['und'];
  foreach ($line_items as $delta => $line_item_wrapper) {
      $line_item_id = $line_item_wrapper['line_item_id'];
      $line_item = commerce_line_item_load($line_item_id);
      if(isset($line_item->commerce_shipping_service)) {
          $commerce_shipping_service = $line_item->line_item_label;
      }
  }
//  echo '<pre>';
//  print_r($order);
      $shipping_id = $order->commerce_customer_shipping['und'][0]['profile_id'];
      $ship_address = commerce_customer_profile_load($shipping_id);
      $billing_id = $order->commerce_customer_billing['und'][0]['profile_id'];
      $bill_address = commerce_customer_profile_load($billing_id);

      $bill_name = (isset($bill_address->field_title['und'][0]['value']))?$bill_address->field_title['und'][0]['value'].' ':'';
      $bill_name .= (isset($bill_address->commerce_customer_address['und'][0]['name_line']))?$bill_address->commerce_customer_address['und'][0]['name_line']:'';
      $bill_street = (isset($bill_address->commerce_customer_address['und'][0]['thoroughfare']))?$bill_address->commerce_customer_address['und'][0]['thoroughfare']:'';
      $bill_city = (isset($bill_address->commerce_customer_address['und'][0]['locality']))?$bill_address->commerce_customer_address['und'][0]['locality'].', ':'';
      $bill_city .= (isset($bill_address->commerce_customer_address['und'][0]['administrative_area']))?$bill_address->commerce_customer_address['und'][0]['administrative_area'].' ':'';
      $bill_city .= (isset($bill_address->commerce_customer_address['und'][0]['postal_code']))?$bill_address->commerce_customer_address['und'][0]['postal_code']:'';
      $bill_country = (isset($bill_address->commerce_customer_address['und'][0]['country']))?$bill_address->commerce_customer_address['und'][0]['country']:'';

      $ship_name = (isset($ship_address->field_title['und'][0]['value']))?$ship_address->field_title['und'][0]['value'].' ':'';
      $ship_name .= (isset($ship_address->commerce_customer_address['und'][0]['name_line']))?$ship_address->commerce_customer_address['und'][0]['name_line']:'';
      $ship_street = (isset($ship_address->commerce_customer_address['und'][0]['thoroughfare']))?$ship_address->commerce_customer_address['und'][0]['thoroughfare']:'';
      $ship_city = (isset($ship_address->commerce_customer_address['und'][0]['locality']))?$ship_address->commerce_customer_address['und'][0]['locality'].', ':'';
      $ship_city .= (isset($ship_address->commerce_customer_address['und'][0]['administrative_area']))?$ship_address->commerce_customer_address['und'][0]['administrative_area'].' ':'';
      $ship_city .= (isset($ship_address->commerce_customer_address['und'][0]['postal_code']))?$ship_address->commerce_customer_address['und'][0]['postal_code']:'';
      $ship_country = (isset($ship_address->commerce_customer_address['und'][0]['country']))?$ship_address->commerce_customer_address['und'][0]['country']:'';

  ?>
    <div id="invoice-header" style="padding-top: 15px; margin-bottom: 80px">
        <h1 style="margin: 0px;">ORDER RECEIPT</h1>
    </div>
    <div class="site-address" style="width:350px;border: 3px groove #000; padding: 10px;">
        <div id="sitename">
            <h2 style="margin: 0 0 12px;font-size: 24px;font-weight: bold;">Sparkle Labs</h2>
        </div>
        <div id="street-address" style="line-height: 16px;padding-top: 5px">
            <p>424 Highland Ave. Montclair, NJ 07043</p>
            <p> info@sparklelabs.com | 212.777.8051</p>
            <p> www.sparklelabs.com</p>
        </div>
    </div>
    <div class="dates" style="width:auto; padding-top: 50px;">
        <div id="invoice-number" style=" color: #00f;font-weight:bold;">
            ORDER# <?php echo $node->title; ?>
        </div>
        <div id="issue-date" style="line-height: 14px;padding-top: 5px; padding-bottom: 20px;font-weight:bold;">
            Date Ordered:  <?php echo date("d/m/y @ H:i a",$node->created); ?>
        </div>
    </div>
    <table border="0"  style="width:100%;margin-top: 30px;margin-bottom: 30px;" nobr="true">
        <tr>
            <td style="width:65%;color:green;float: left">
                <h4>BILL TO :</h4>
                <p style="margin: 0px;"><?php echo $bill_name ?></p>
                <p style="margin: 0px;"><?php echo $bill_street ?></p>
                <p style="margin: 0px;"><?php echo $bill_city ?></p>
                <p style="margin: 0px;"><?php echo $bill_country ?></p>
                <p style="color:blue; margin:10px 0;"><?php echo $order->field_email['und'][0]['value'];?> </p>
            </td>
            <td style="width:35%;color:red;float: left">
                <h4>SHIP TO :</h4>
                <p style="margin: 0px;"><?php echo $ship_name ?></p>
                <p style="margin: 0px;"><?php echo $ship_street ?></p>
                <p style="margin: 0px;"><?php echo $ship_city ?></p>
                <p style="margin: 0px;"><?php echo $ship_country ?></p>
                <h5>Shipping Method:</h5>
                <p>USA Mail Service – <?php echo $commerce_shipping_service;?></p>
            </td>
        </tr>
    </table>
    <div class="order-details">
        <?php
        if(isset($order->field_gift_price['und'][0]['value'])){ ?>
          <h4>GIFT :</h4>
          <p style="margin: 0px;">Its a Gift.</p>
          <p style="margin: 0px;">Gift receipt and message:</p>
          <p class="gift-msg"><?php echo $order->field_gift_message['und'][0]['value'] ?></p>
        <?php } else {
            echo views_embed_view('order_invoice_summery','default',$order_id);

        } ?>
    </div>

  <?php
    // Only display the wrapper div if there are tags or links.
    $field_tags = render($content['field_tags']);
    $links = render($content['links']);
    if ($field_tags || $links):
  ?>
   <footer>
     <?php print $field_tags; ?>
     <?php print $links; ?>
  </footer>
  <?php endif; ?>
  <?php print render($content['comments']); ?>
</article>
