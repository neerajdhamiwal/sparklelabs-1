<div class="shareitblock">
<div class="page-top-bar">
    <h2 class="thankyou-title block-title">Thank You!</h2>
    <p class="text-center my-order-no">Your order # <?php echo $order_id;?></p>
</div>
<!-- share your purchase code starts -->
<div class="share-your-purchase">
    <div class = "sharemenu">
    <!-- <h2>Share your purchase</h2> -->
    <div class= "shareflex">
    <div id="fbshare" class="tabcontent active">  <a href="#"> <i class="fa fa-facebook-official" aria-hidden="true"></i>
</a></div>
        <div id="tweetshare" class="tabcontent"> <a href="#"> <i class="fa fa-twitter" aria-hidden="true"></i>
</a></div>
            <div id="pinshare" class="tabcontent">  <a href="#"><i class="fa fa-pinterest-square" aria-hidden="true"></i>
 </a></div>
                <div id="emailshare" class="tabcontent">  <a href="#"> <i class="fa fa-envelope-o" aria-hidden="true"></i>
 </a> </div>
            </div>
        </div>
<!-- <div data-network="facebook" class="st-custom-button">Share on Facebook</div>
    <div data-network="twitter" class="st-custom-button">Share on Twitter</div>
    <div data-network="pinterest" class="st-custom-button">Share on Pinterest</div>
    <div data-network="email" class="st-custom-button">Share on Email</div>  -->
<div>
    <?php echo order_share_block(); ?>
</div>
</div>
</div>
<!-- share your purchase code ends -->
<div class="related-lern-items">
    <h3>See the learn pages for the kits you just bought:</h3>
<?php
$lern_item = array();
$order = commerce_order_load($order_id);
$order_wrapper = entity_metadata_wrapper('commerce_order', $order);
foreach($order->commerce_line_items['und'] as $items ){
    $line_item = $items['line_item_id'];
    $line_item_wrapper = entity_metadata_wrapper('commerce_line_item', $line_item);
    if ($line_item_wrapper->type->value() == 'product') {
        $product = $line_item_wrapper->commerce_product->raw();

        $query = new EntityFieldQuery;
        $query->entityCondition('entity_type', 'node', '=')
            ->propertyCondition('type', 'product')
            ->fieldCondition('field_product_reference', 'product_id', $product, '=')
            ->range(0, 1);

        if ($result = $query->execute()) {
            foreach($result['node'] as $node){
                $node = node_load($node->nid);
                $lern_item[$node->nid]['title'] = $node->title;
                $alias = drupal_get_path_alias('node/'.$node->nid);
                $lern_item[$node->nid]['alias'] = $alias;
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
                            $linked_image =  "<a href='/learn_items/".$node->nid."'>".$image_rendered."</a>";
                            $flag = 1;
                            break;
                        }
                    }
                    if(!$flag){
                        $image_rendered = '<img src="/sites/default/files/defualt.png" width="580" height="410">';
                        $linked_image =    "<a href='/learn_items/".$node->nid."'>".$image_rendered."</a>";
                    }
                }else{
                    $image_rendered = '<img src="/sites/default/files/defualt.png" width="580" height="410">';
                    $linked_image =    "<a href='/learn_items/".$node->nid."'>".$image_rendered."</a>";
                }
                $lern_item[$node->nid]['image'] = $linked_image;
            }
        }
    }
}
?>
<div class="view-content">
    <?php //echo '<pre>'; print_r($lern_item); //echo $item['image']; ?>
    <?php foreach ($lern_item as $item): ?>
    <div class="views-row">
        <div class="views-field views-field-image">
            <?php echo $item['image']; ?>
        </div>
        <div class="views-field views-field-title">
            <?php echo "<a href='/learn_items/".$node->nid."'>".$item['title']."</a>"; ?>
        </div>
       <div class="new-div-button"> <a href='/learn_items/ .$node->nid.;' class="btn btn-default thanks-button">Learn Page</a> </div>


    </div>
    <?php endforeach; ?>

</div>
</div>
</div>
