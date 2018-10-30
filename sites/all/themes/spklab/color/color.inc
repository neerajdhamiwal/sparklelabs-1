<?php

// Put the logo path into JavaScript for the live preview.
drupal_add_js(array('color' => array('logo' => theme_get_setting('logo', 'spklab'))), 'setting');

$info = array(
  // Available colors and color labels used in theme.
  'fields' => array(
   
    'bg' => t('Header Section'),
    
  ),
  // Pre-defined color schemes.
  'schemes' => array(
    'default' => array(
      'title' => t('Blue Lagoon (default)'),
      'colors' => array(
        
        'bg' => '#ffffff',
       
      ),
    ),
    'firehouse' => array(
      'title' => t('Firehouse'),
      'colors' => array(
       
        'bg' => '#ffffff',
       
      ),
    ),
    'ice' => array(
      'title' => t('Ice'),
      'colors' => array(
       
        'bg' => '#ffffff',
       
      ),
    ),
    'plum' => array(
      'title' => t('Plum'),
      'colors' => array(
       
        'bg' => '#fffdf7',

      ),
    ),
    'slate' => array(
      'title' => t('Slate'),
      'colors' => array(
       
        'bg' => '#ffffff',
       
      ),
    ),
  ),

  // CSS files (excluding @import) to rewrite with new color scheme.
  'css' => array(
    'css/colors.css',
  ),

  // Files to copy.
  'copy' => array(
    'logo.png',
  ),

  // Gradient definitions.
  'gradients' => array(
    array(
      // (x, y, width, height).
      'dimension' => array(0, 0, 0, 0),
      // Direction of gradient ('vertical' or 'horizontal').
      'direction' => 'vertical',
      // Keys of colors to use for the gradient.
      'colors' => array('top', 'bottom'),
    ),
  ),

  // Color areas to fill (x, y, width, height).
  'fill' => array(),

  // Coordinates of all the theme slices (x, y, width, height)
  // with their filename as used in the stylesheet.
  'slices' => array(),

  // Reference color used for blending. Matches the base.png's colors.
  'blend_target' => '#ffffff',

  // Preview files.
  'preview_css' => 'color/preview.css',
  'preview_js' => 'color/preview.js',
  'preview_html' => 'color/preview.html',

  // Base file for image generation.
  'base_image' => 'color/base.png',
);
