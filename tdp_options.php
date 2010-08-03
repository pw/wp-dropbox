<?php
  if (isset($_POST['allow_access']))
  {
  echo "jdsfk";
  }
?>
<div class="wrap">
<h2>WP-Dropbox</h2>

<form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>" >
  <input type="hidden" name="allow_access" id="allow_access" value="true" />
  <div class="submit">
    <input type="submit" name="allow_access" class="button-primary" value="<?php _e('Allow access to your Dropbox'); ?>" />  
  </div>
</form>

<form method="post" action="options.php">
<?php settings_fields('tdp-opt');  ?>
<table class="form-table">

<tr valign="top">
<th scope="row">Consumer Key (from Dropbox API registration)</th>
<td><input type="text" name="tdp_consumer_key" value="<?php echo get_option('tdp_consumer_key'); ?>" /></td>
</tr>

<tr valign="top">
<th scope="row">Consumer Secret (from Dropbox API registration)</th>
<td><input type="password" name="tdp_consumer_secret" value="<?php echo get_option('tdp_consumer_secret'); ?>" /></td>
</tr>

<tr valign="top">
<th scope="row">Starting directory:</th>
<td><input type="text" name="tdp_dir" value="<?php echo get_option('tdp_dir'); ?>" /></td>
</tr>
</table>

<p class="submit">
<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
</p>
</form>
</div>