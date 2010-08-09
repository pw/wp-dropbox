<?php
  if (isset($_POST['allow_access']))
  {
    //modify include path to work w/ PEAR on Dreamhost
    ini_set(
	    'include_path',
	    ini_get( 'include_path' ) . PATH_SEPARATOR . "/home/kshrine/.pear/home/kshrine/pear/php"
	    );
    
    $consumerKey = '';
    $consumerSecret = '';
    
    include 'Dropbox/autoload.php';

    $oauth = new Dropbox_OAuth_PEAR($consumerKey, $consumerSecret);
    $dropbox = new Dropbox_API($oauth);

    $tokens = $dropbox->getToken($_POST['email_address'], $_POST['password']);
    update_option('wpdp_token', $tokens);

    $stored_tokens = get_option('wpdp_token');
    $oauth->setToken($stored_tokens);
    $acct_info = $dropbox->getAccountInfo();
    update_option('wpdp_acct_name', $acct_info['display_name']);
    
  }
?>
<div class="wrap">
  <h2>WP-Dropbox</h2>
  <?php
    $name_on_authorized_acct = get_option('wpdp_acct_name');
    if (empty($name_on_authorized_acct))
      {
	echo "<h3>No Dropbox in use.</h3>";
      }
    else
      {
	echo "<h3>Using $name_on_authorized_acct's Dropbox.</h3>";
      }
	?>



<form method="post" action="options.php">
  <?php settings_fields('wpdp_options');  ?>
Directory to use: <input type="text" size="60" name="wpdp_dir" value="<?php echo get_option('wpdp_dir'); ?>" />

<p class="submit">
<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
</p>
</form>

<h3>Allow Access to Your Dropbox</h3>
<p>You'll only have to do this once, and we never store your password or email address.</p>
<form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>" >
  <input type="hidden" name="allow_access" id="allow_access" value="true" />
  Email address: <input type="text" name="email_address" id="email_address" /><br/>
  Password: <input type="password" name="password" id="password" /><br/>
  <div class="submit">
    <input type="submit" name="allow_access" class="button-primary" value="<?php _e('Allow'); ?>" />  
  </div>
</form>
</div>