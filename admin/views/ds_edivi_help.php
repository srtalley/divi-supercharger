<?php
//vars
$ds_support_uri = $this->main_settings['support_uri'];
$ds_support_email = $this->main_settings['support_email'];
?>

<div>
  
  <h2>Changing appearance options</h2>
  <P>Simply check out the <a href="<?php echo site_url();?>/wp-admin/customize.php">Theme Customizer (Appearance > Customize)</a> and then open the section named <strong>Divi SUPERCHARGER Options</strong> to see the various options available to you.</p>

  <h2>Licensing</h2>
  <p>Make sure to check out the License tab and enter your license.</P>

  <h2>How options are saved (per theme or globally)</h2>
  <p>You can also choose whether the options you choose are stored with your theme/child theme or are stored globally on the General tab. This means you could have multiple child themes each with different saved options under <strong>Divi SUPERCHARGER Options</strong>. If you want all child themes to use the same Divi SUPERCHARGER options (and you probably only have one, anyway), choose <strong>Global</strong>.

  <h2>Video options</h2>
  <p>On the general tab, you can choose to automatically strip icons, tiles, and author bylines from your YouTube and Vimeo videos (if they are added via the standard WordPress process).</p>
  

  <h4>Need help? <a href="<?php echo $ds_support_uri; ?>">Contact us!</a> You can also send us an email at <a href="mailto:<?php echo $ds_support_email; ?>"><?php echo $ds_support_email; ?></a></h4>

</div>
