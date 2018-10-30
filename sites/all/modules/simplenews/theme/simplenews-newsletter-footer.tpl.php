<?php

/**
 * @file
 * Default theme implementation to format the simplenews newsletter footer.
 *
 * Copy this file in your theme directory to create a custom themed footer.
 * Rename it to simplenews-newsletter-footer--[tid].tpl.php to override it for a
 * newsletter using the newsletter term's id.
 *
 * @todo Update the available variables.
 * Available variables:
 * - $build: Array as expected by render()
 * - $build['#node']: The $node object
 * - $language: language code
 * - $key: email key [node|test]
 * - $format: newsletter format [plain|html]
 * - $unsubscribe_text: unsubscribe text
 * - $test_message: test message warning message
 * - $simplenews_theme: path to the configured simplenews theme
 *
 * Available tokens:
 * - [simplenews-subscriber:unsubscribe-url]: unsubscribe url to be used as link
 *
 * Other available tokens can be found on the node edit form when token.module
 * is installed.
 *
 * @see template_preprocess_simplenews_newsletter_footer()
 */
?>
<footer style="font-size: .6rem;font-weight: 500;color: #7f7f7f;margin-top: 48px; max-width: 800px;margin: auto;">
<table align="right" border="0" cellpadding="0" cellspacing="0"  style=" margin:50px 0 0; width:100%; text-align: right;  padding: 0px 0px 20px;">
  <tr>
    <th>
      <a href="#" style="display: inline-block; margin-left: 12px; text-decoration: none;"><img style="width:50px; background: #34C8B6;" src="http://sparklelabs.opensenselabs.com/sites/all/themes/spklab/images/socialicon_fb_knockout_white.png"></a>
      <a href="#" style="display: inline-block;  margin-left: 12px; text-decoration: none;"><img style="width:50px; background: #34C8B6;" src="http://sparklelabs.opensenselabs.com/sites/all/themes/spklab/images/socialicon_in_knockout_white.png"></a>
      <a href="#" style="display: inline-block;  margin-left: 12px; text-decoration: none;"><img style="width:50px; background: #34C8B6;" src="http://sparklelabs.opensenselabs.com/sites/all/themes/spklab/images/socialicon_pn_knockout_white.png"></a>
      <a href="#" style="display: inline-block;  margin-left: 12px; text-decoration: none;"><img style="width:50px; background: #34C8B6;" src="http://sparklelabs.opensenselabs.com/sites/all/themes/spklab/images/socialicon_tw_knockout_white.png"></a>
    </th>
  </tr>
  <tr>
    <th>

      <span style="float:left;">© 2018 Sparkle Labs.
      <a style="color: #2E9EF5;text-decoration:none;" href="[simplenews-subscriber:unsubscribe-url]">unsubscribe</a>
  </span>

  </th>
  </tr>
</table>
</footer>



<?php if ($key == 'test'): ?>
- - - <?php print $test_message ?> - - -
<?php endif ?>
