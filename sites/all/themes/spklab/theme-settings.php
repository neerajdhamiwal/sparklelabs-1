<?php
function spklab_form_system_theme_settings_alter(&$form, &$form_state) {
 
$form ['theme_settings'] ['opacity'] = array(
  '#title' => t('Opacity'),
  '#type' => 'textfield',
  '#description' => t("Provide the opacity for Header Section "),
  '#default_value' => theme_get_setting('opacity'),
  '#weight' => -80,
);
}
