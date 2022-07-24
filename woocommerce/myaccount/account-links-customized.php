<?php if (has_nav_menu('my_account')) { ?>

  <?php
  /*echo wp_nav_menu(array(
    'theme_location' => 'my_account',
    'container' => false,
    'items_wrap' => '%3$s',
    'depth' => 0,
    'walker' => new FlatsomeNavSidebar
  ));*/
  ?>
<?php } else if (!get_theme_mod('wc_account_links', 1)) { ?>
  <li>Define your My Account dropdown menu in <b>Appearance > Menus</b> or enable default WooCommerce Account Endpoints.</li>
<?php } ?>

<?php if (function_exists('wc_get_account_menu_items') && get_theme_mod('wc_account_links', 1)) { ?>
  <div class="accordion">
    <div class="container">
      <div class="label">
        <svg class="icon">
          <use href="#user" />
        </svg>
        Personal Information
      </div>
      <ul id="my-account-nav" class="content account-nav nav nav-line nav-uppercase nav-vertical">
        <?php
          foreach (wc_get_account_menu_items() as $endpoint => $label) :
            if ($endpoint == 'orders') continue;
            if ($endpoint == 'dashboard') $label = "My Profile";
            if ($endpoint == 'edit-account') $label = "Edit Info";
        ?>
          <li class="<?php echo wc_get_account_menu_item_classes($endpoint); ?>">
            <?php if ($endpoint == 'dashboard') { ?>
              <a href="<?php echo esc_url(wc_get_account_endpoint_url($endpoint)); ?>"><?php echo esc_html($label); ?></a>
              <!-- empty -->
            <?php } else { ?>
              <a href="<?php echo esc_url(wc_get_endpoint_url($endpoint, '', wc_get_page_permalink('myaccount'))); ?>"><?php echo esc_html($label); ?></a>
            <?php } ?>
          </li>
        <?php endforeach; ?>
        <?php do_action('flatsome_account_links'); ?>
      </ul>
    </div>
  </div>

    <div class="">
      <a class="side-link" href="<?= esc_url(wc_get_endpoint_url('orders', '', wc_get_page_permalink('myaccount'))); ?>">
        <svg class="icon">
          <use href="#orders" />
        </svg>
        <span>Order History</span>
      </a>
    </div>

    <!-- <div class="">
      <a class="side-link" href="<?= esc_url(wc_get_endpoint_url('returns', '', wc_get_page_permalink('myaccount'))); ?>">
        <svg class="icon">
          <use href="#returns" />
        </svg>
        <span>Return & Exchange Policy</span>
      </a>
    </div> -->

    <div class="">
      <a class="side-link" href="/delivery-policy/">
        <svg class="icon">
          <use href="#returns" />
        </svg>
        <span>Delivery Policy</span>
      </a>
    </div>

    <div class="">
      <a class="side-link" href="/refund-policy/">
        <svg class="icon">
          <use href="#refund" />
        </svg>
        <span>Refund Policy</span>
      </a>
    </div>

    <div>
      <a class="side-link" href="<?= esc_url(wc_get_endpoint_url('customer-logout', '', wc_get_page_permalink('myaccount'))); ?>">
        <svg class="icon">
          <use href="#logout" />
        </svg>
        <span>Logout</span>
      </a>
    </div>
<?php } ?>
<style>
  .my-account .accordion .label::before {
    right: 15px !important;
  }
  .my-account .accordion .label {
    padding: 0;
  }
  .container.active ul {
    margin-bottom: 20px;
  }
  .accordion .container {
    margin: 0 !important;
  }

  .side-link {
    display: flex;
    align-items: center;
    margin: 10px 0;
  }

  .side-link .icon, .icon {
    width: 20px;
    height: 20px;
  }

  .side-link span {
    margin: 0;
    margin-left: 10px;
    font-size: 16px;
  }
</style>