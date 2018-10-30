<?php
/**
 * @file
 * The primary PHP file for this theme.
 */
function spklab_preprocess_page(&$vars, $hook = null){
    if (isset($vars['node'])) {
        switch ($vars['node']->type) {
            case 'invoice': // machine name content type
                drupal_add_js(drupal_get_path('theme', 'spklab').'/js/jspdf.min.js');
                drupal_add_js(drupal_get_path('theme', 'spklab').'/js/html2canvas.min.js');
                break;
        }
    }
}

function spklab_commerce_cart_empty_page() {
	if(isset($_SERVER['HTTP_REFERER'])) {
	    $referrer = $_SERVER['HTTP_REFERER'];
	}
	//echo $referrer;exit;
	if(empty($referrer)){
		$referrer = '/content/shop';
	}
	$ref_args = explode('/', $referrer);
	//echo $referrer."<pre>";print_r($ref_args);echo "</pre>";exit;
	if(current_path() == end($ref_args)){
		$referrer = '/content/shop';
	}
	//echo current_path();exit;
	//echo $referrer;exit;
    return '<div class="cart-empty-page"><div id="cart-view-main"><div id="cart-view-main-title"><h2>Your Bag (0) items</h2></div><p>Your shopping bag is empty.</p><a class="btn btn-default" href="'.$referrer.'">CONTINUE SHOPPING</a></div><div id="block-block-15" class="col-sm-4 block block-block"><h2 class="block-title">Adorable Gift wrapping</h2><p>Lorem ipsum dolor sit amet. dunt ut labore et dolore magna aliqua. Ut enim ad minim veniam. Incididunt ut labore et dolore magna aliqua</p></div><div id="block-views-cart-view-block">'.views_embed_view('cart_view', $display_id = 'block').'</div>';
}

function spklab_process_html(&$variables) {
  // Hook into color.module.
  if (module_exists('color')) {
    _color_html_alter($variables);
  }
}

function spklab_process_page(&$variables) {
  // Hook into color.module.
  if (module_exists('color')) {
    _color_page_alter($variables);
  }
  // Always print the site name and slogan, but if they are toggled off, we'll
  // just hide them visually.
  $variables['hide_site_name']   = theme_get_setting('toggle_name') ? FALSE : TRUE;
  $variables['hide_site_slogan'] = theme_get_setting('toggle_slogan') ? FALSE : TRUE;
  if ($variables['hide_site_name']) {
    // If toggle_name is FALSE, the site_name will be empty, so we rebuild it.
    $variables['site_name'] = filter_xss_admin(variable_get('site_name', 'Drupal'));
  }
  if ($variables['hide_site_slogan']) {
    // If toggle_site_slogan is FALSE, the site_slogan will be empty, so we rebuild it.
    $variables['site_slogan'] = filter_xss_admin(variable_get('site_slogan', ''));
  }
  // Since the title and the shortcut link are both block level elements,
  // positioning them next to each other is much simpler with a wrapper div.
  if (!empty($variables['title_suffix']['add_or_remove_shortcut']) && $variables['title']) {
    // Add a wrapper div using the title_prefix and title_suffix render elements.
    $variables['title_prefix']['shortcut_wrapper'] = array(
      '#markup' => '<div class="shortcut-wrapper clearfix">',
      '#weight' => 100,
    );
    $variables['title_suffix']['shortcut_wrapper'] = array(
      '#markup' => '</div>',
      '#weight' => -99,
    );
    // Make sure the shortcut link is the first item in title_suffix.
    $variables['title_suffix']['add_or_remove_shortcut']['#weight'] = -100;
  }
}
