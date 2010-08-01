<div class="wrap">
<h2>The Dropbox Plugin</h2>

<form method="post" action="options.php">
<?php settings_fields('tdp-opt');  ?>
<table class="form-table">
<tr valign="top">
<th scope="row">Dropbox email</th>
<td><input type="text" name="tdp_mail" value="<?php echo get_option('tdp_mail'); ?>" /></td>
</tr>

<tr valign="top">
<th scope="row">Dropbox password</th>
<td><input type="password" name="tdp_pass" value="<?php echo get_option('tdp_pass'); ?>" /></td>
</tr>

<tr valign="top">
<th scope="row">Starting directory:</th>
<td><input type="text" name="tdp_dir" value="<?php echo get_option('tdp_dir'); ?>" /></td>
</tr>
<tr valign="top">
<td><input type="checkbox" name="tdp_size" value="1" <?php if( get_option('tdp_size')=="1") echo "checked=\"1\"" ?> /> Do not show file size
</td>
</tr>
<tr valign="top">
<td><input type="checkbox" name="tdp_date" value="1" <?php if( get_option('tdp_date')=="1") echo "checked=\"1\"" ?> /> Do not show when last modified
</td>
</tr>
<tr valign="top">
<td><input type="checkbox" name="tdp_cred" value="1" <?php if( get_option('tdp_cred')=="1") echo "checked=\"1\"" ?> /> Do not include backlink(bottom of page)
</td>
</tr>
</table>

<p class="submit">
<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
</p>
</form>
<?php tdp_upload(); ?>

</div>