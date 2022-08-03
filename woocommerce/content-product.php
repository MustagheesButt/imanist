<?php

/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.6.0
 */

defined('ABSPATH') || exit;

global $product;

// Ensure visibility.
if (empty($product) || false === wc_get_loop_product_visibility($product->get_id()) || !$product->is_visible()) {
	return;
}

// Check stock status.
$out_of_stock = !$product->is_in_stock();

// Extra post classes.
$classes   = array();
$classes[] = 'product-small';
$classes[] = 'col';
$classes[] = 'has-hover';

if ($out_of_stock) $classes[] = 'out-of-stock';

?><div <?php wc_product_class($classes, $product); ?>>
	<div class="col-inner">
		<?php do_action('woocommerce_before_shop_loop_item'); ?>
		<div class="product-small box <?php echo flatsome_product_box_class(); ?>">
			<div class="box-image">
				<div class="<?php echo flatsome_product_box_image_class(); ?>">
					<a href="<?php echo get_the_permalink(); ?>" aria-label="<?php echo esc_attr($product->get_title()); ?>">
						<?php
						/**
						 *
						 * @hooked woocommerce_get_alt_product_thumbnail - 11
						 * @hooked woocommerce_template_loop_product_thumbnail - 10
						 */
						do_action('flatsome_woocommerce_shop_loop_images');
						?>
					</a>
				</div>
				<div class="image-tools is-small top right show-on-hover">
					<?php do_action('flatsome_product_box_tools_top'); ?>
				</div>
				<div class="image-tools is-small hide-for-small bottom left show-on-hover">
					<?php do_action('flatsome_product_box_tools_bottom'); ?>
				</div>
				<div class="image-tools <?php echo flatsome_product_box_actions_class(); ?>">
					<?php do_action('flatsome_product_box_actions'); ?>
				</div>
				<?php if ($out_of_stock) { ?><div class="out-of-stock-label"><?php _e('Out of stock', 'woocommerce'); ?></div><?php } ?>
			</div>

			<div class="box-tags">
				<?php if ($out_of_stock) { ?>
					<div class="coming-soon">
						<a href="#">Coming Soon</a>
						<label data-security="bdc0e353e3" data-variation_id="<?= $product->get_id() ?>" data-product_id="<?= $product->get_id() ?>" class="cwg_popup_submit ">Notify <i class="fas fa-bell"></i></label>
					</div>
				<?php } elseif (str_contains($product->get_attribute('delivery'), 'Same Day Shipment')) { ?>
					<a href="<?= get_the_permalink(); ?>" style="display: block;">
						<div class="eid-badge-div ready-to-ship">
							<svg xmlns="http://www.w3.org/2000/svg" stroke="white" width="23" height="13" viewBox="0 0 23 13" fill="none">
								<path d="M22.3224 7.06755L22.3223 7.06769L22.3324 7.06882C22.5229 7.09026 22.6699 7.25065 22.6126 7.52643L22.6125 7.52639L22.6104 7.53819L22.1018 10.4541L22.0998 10.4658L22.0985 10.4777C22.0979 10.4826 22.0968 10.485 22.096 10.4864C22.0951 10.4881 22.0931 10.491 22.0887 10.4946C22.0793 10.5022 22.0609 10.511 22.0354 10.511H20.8973H20.6053L20.5338 10.7941C20.3067 11.6925 19.431 12.3978 18.5485 12.3978C17.7405 12.3978 17.1733 11.7669 17.2511 10.9203L17.2887 10.511H16.8776H6.34416H6.05216L5.9806 10.7941C5.75404 11.6903 4.85564 12.3978 3.99532 12.3978C3.1873 12.3978 2.6201 11.7669 2.69788 10.9203L2.73548 10.511H2.32445H1.38007C1.37683 10.511 1.35747 10.5086 1.33577 10.4817C1.31422 10.4551 1.31063 10.4285 1.31337 10.4133L1.31377 10.411L1.55599 9.01425L1.558 9.00268L1.55928 8.991C1.55982 8.98606 1.56093 8.98368 1.56169 8.98232C1.56263 8.98063 1.56467 8.97769 1.56904 8.97412C1.57844 8.96644 1.59679 8.9577 1.62229 8.9577H13.197C13.6082 8.9577 13.9166 8.67078 13.999 8.29575L14.0006 8.28852L14.0019 8.28122L14.8737 3.40515L14.8759 3.39263L14.8773 3.37998C14.8778 3.37504 14.8789 3.37266 14.8797 3.3713C14.8806 3.36961 14.8827 3.36667 14.887 3.3631C14.8964 3.35543 14.9148 3.34668 14.9403 3.34668H17.5797C18.1938 3.34668 18.647 3.65972 18.8274 4.13408L18.8282 4.13619L19.7484 6.51297L19.8279 6.71842L20.0461 6.74896L22.3224 7.06755ZM15.0554 6.23896L14.9777 6.67912L15.4247 6.67912L18.5968 6.67912L19.1468 6.67912L18.9459 6.16713L18.292 4.50102L18.2918 4.50029C18.1317 4.09502 17.7388 3.87093 17.3376 3.87093H15.7878H15.4732L15.4185 4.18077L15.0554 6.23896ZM20.3471 10.7755L19.9771 10.7146L20.3471 10.7755C20.4104 10.3907 20.3264 10.0116 20.0991 9.723C19.869 9.43077 19.5152 9.26147 19.1053 9.26147C18.3135 9.26147 17.6064 9.88587 17.4773 10.6463C17.3987 11.0381 17.4855 11.4206 17.7172 11.7091C17.9509 12.0002 18.31 12.1678 18.7179 12.1678C19.5123 12.1678 20.2214 11.5394 20.3471 10.7755ZM4.16472 12.1678C4.95663 12.1678 5.66372 11.5434 5.79274 10.7829C5.95681 9.9638 5.36686 9.26147 4.5522 9.26147C3.7603 9.26147 3.05323 9.88589 2.92419 10.6464C2.75988 11.4656 3.35003 12.1678 4.16472 12.1678Z" stroke-width="0.75"></path>
								<path d="M12.9274 7.52369L12.7748 7.51529L12.7368 7.51321C12.7456 7.50756 12.7574 7.50288 12.7702 7.50148C12.7746 7.50101 12.7781 7.50102 12.7805 7.50117C12.7832 7.50132 12.7847 7.50164 12.7849 7.50168C12.7852 7.50173 12.7839 7.50144 12.7809 7.50023L12.7715 7.49643L12.9274 7.52369ZM13.9177 0.700223L13.9174 0.702024L12.7324 7.48057L12.7132 7.4728H12.6401H0.750618C0.550274 7.4728 0.375 7.30185 0.375 7.08825C0.375 6.87466 0.550246 6.70373 0.750587 6.70371C0.750597 6.70371 0.750608 6.70371 0.750618 6.70371L2.78459 6.70388L2.79582 6.70388L2.80703 6.70321C3.40777 6.66726 3.91024 6.16846 3.91024 5.5447C3.91024 4.92529 3.40756 4.41018 2.78468 4.41015C2.78466 4.41015 2.78464 4.41015 2.78462 4.41015L1.52563 4.40998H1.52558C1.32524 4.40998 1.14996 4.23904 1.14996 4.02544C1.14996 3.81183 1.32523 3.64089 1.52558 3.64089H1.52561L3.55976 3.64072C3.55977 3.64072 3.55978 3.64072 3.55979 3.64072C4.18267 3.6407 4.68538 3.12561 4.68538 2.50618C4.68538 1.88675 4.18267 1.37163 3.55976 1.37163H2.30054C2.1002 1.37163 1.92493 1.20069 1.92493 0.987084C1.92493 0.773481 2.10019 0.602539 2.30054 0.602539H13.851C13.8543 0.602539 13.8736 0.604955 13.8953 0.631784C13.9169 0.658436 13.9205 0.685 13.9177 0.700223Z" stroke-width="0.75"></path>
							</svg>
							<span class="eid-badge-text">READY TO SHIP</span>
						</div>
					</a>
				<?php } ?>
			</div>

			<div class="box-text <?php echo flatsome_product_box_text_class(); ?>">
				<?php
				do_action('woocommerce_before_shop_loop_item_title');

				echo '<div class="title-wrapper">';
				do_action('woocommerce_shop_loop_item_title');
				echo '</div>';


				echo '<div class="price-wrapper">';
				do_action('woocommerce_after_shop_loop_item_title');
				echo '</div>';

				do_action('flatsome_product_box_after');

				?>
			</div>
		</div>
		<?php do_action('woocommerce_after_shop_loop_item'); ?>
	</div>
</div><?php /* empty PHP to avoid whitespace */ ?>