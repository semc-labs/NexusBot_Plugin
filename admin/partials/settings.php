<?php

$error = '';
if(! empty($_POST['nexusbot_url'])){
  $clean_email = trim($_POST['nexusbot_url']);
  if (filter_var($clean_email, FILTER_VALIDATE_URL)) {
    update_option('nexusbot_url', $clean_email, false);
    $success = "URL Updated!";
  }else{
    $error = "Invalid URL";
  }
}

$nexusbot_url = get_option('nexusbot_url');

?>
<div class="wrap">
  <h1>NexusBot Settings</h1>
  <?php 
    if($error) echo '<div class="error notice"><p>'.$error.'</p></div>';
    if($success) echo '<div class="updated notice"><p>'.$success.'</p></div>';
  ?>
  <form action="" method="post">
    <table class="form-table" role="presentation">
      <tbody>
        <tr>
          <th scope="row"><label for="nexusbot_url">Discord Bot URL</label></th>
          <td><input name="nexusbot_url" type="url" id="nexusbot_url" value="<?php echo $nexusbot_url; ?>" class="regular-text"></td>
        </tr>
      </tbody>
    </table>
    <input name="submit" class="button button-primary" type="submit" value="<?php esc_attr_e( 'Save' ); ?>" />
  </form>
</div>