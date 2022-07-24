<div class="account-user circle" style="width: fit-content; margin: 0 auto; text-align: center;">
  <span class="image mr-half inline-block">
    <?php
    $current_user = wp_get_current_user();
    $user_id = $current_user->ID;
    echo get_avatar($user_id, 150);
    ?>
  </span>
  <div class="user-name">
    <h2><?= $current_user->display_name ?></h2>
    <p><?= $current_user->user_email ?></p>
    <!-- <em class="user-id op-5"><?php //echo '#'.$user_id;
                                  ?></em> -->
  </div>

  <?php do_action('flatsome_after_account_user'); ?>
</div>