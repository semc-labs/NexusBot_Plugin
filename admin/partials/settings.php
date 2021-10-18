<?php

// TODO: Update this to be a global array. Shared with class-nexus-aurora-email.php
$settings = [
  'urls' => [
    'nexusbot_url' => 'Bot Url',
    // 'facebook_url' => 'Facebook Url',
    // 'youtube_url' => 'YouTube Url',
    // 'twitter_url' => 'Twitter Url',
    //'discord_url' => 'Discord Url',
  ]
];

$successes = [];
$errors = [];

foreach($settings['urls'] as $url => $title){
  if(! empty($_POST[$url])){

    $clean_url = trim($_POST[$url]);

    if (filter_var($clean_url, FILTER_VALIDATE_URL)) {
      update_option($url, $clean_url, false);
      $successes[] = "URL Updated!";
    }else{
      $errors[] = "Invalid URL";
    }

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
        <?php 
          foreach($settings['urls'] as $url => $title){
            $option_value = get_option($url);
            echo '<tr>
                    <th scope="row"><label for="'.$url.'">'.$title.'</label></th>
                    <td><input name="'.$url.'" type="url" id="'.$url.'" value="'.$option_value.'" class="regular-text"></td>
                  </tr>';
          }
        ?>
      </tbody>
    </table>
    <input name="submit" class="button button-primary" type="submit" value="<?php esc_attr_e( 'Save' ); ?>" />
  </form>
</div>