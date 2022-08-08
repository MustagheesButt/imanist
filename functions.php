<?php
// Add custom Theme Functions here
// 
// 
function lsp_rename_image($filename)
{
	$info = pathinfo($filename);
	$ext  = empty($info['extension']) ? '' : '.' . $info['extension'];
	$name = basename($filename, $ext);
	return $name . "-imanistudio.com" . $ext;
}
add_filter('sanitize_file_name', 'lsp_rename_image', 10);

add_image_size('singleproduct-thumb', 297, 443); //products carousals
add_image_size('archiveproduct-thumb', 318, 476); //archive product thumbnails
add_image_size('homepage-thumb size', 220, 180);
add_image_size('products-thumb size', 254, 381);
add_image_size('mobile-menu-thumbs', 120, 75);


// add_filter( 'woocommerce_breadcrumb_defaults', 'wcc_change_breadcrumb_home_text' );
// function wcc_change_breadcrumb_home_text( $defaults ) {
//     // Change the breadcrumb home text from 'Home' to 'Apartment'
// 	$defaults['Shopping Cart'] = 'Cart';
// 	return $defaults;
// }
add_action('woocommerce_checkout_before_customer_details', 'loginpage');
function loginpage()
{
	$html = '<div class="layout-flex layout-flex--tight-vertical layout-flex--loose-horizontal layout-flex--wrap">
				  <h2 class="section__title layout-flex__item layout-flex__item--stretch" id="main-header" tabindex="-1">
					Contact information
				  </h2>
				<p class="layout-flex__item">
				  <span aria-hidden="true">Already have an account?</span>
				  <a class="xoo-el-login-tgr" href="/my-account/">
					Log in
					</a>
				</p>
			</div>';
	echo $html;
}


add_action('wp_head', 'pop_up_js');
function pop_up_js()
{
	if (!is_user_logged_in()) { ?>
		<script>
			jQuery(document).ready(function() {
				jQuery(".wishlist-icon .wishlist-button").click(function() {
					jQuery(this).addClass("xoo-el-login-tgr");
					e.preventDefault();
				});

				jQuery(".xoo-el-password_cont").append('<div id="nsl-custom-login-form-main"><div class="nsl-container nsl-container-block" data-align="left"><div class="nsl-container-buttons"><a href="/wp-login.php?loginSocial=facebook&amp;redirect=<?= get_site_url() ?>%2Fwp-admin%2F" rel="nofollow" aria-label="Continue with <b>Facebook</b>" data-plugin="nsl" data-action="connect" data-provider="facebook" data-popupwidth="600" data-popupheight="679"><div class="nsl-button nsl-button-default nsl-button-facebook" data-skin="dark" style="background-color:#1877F2;"><div class="nsl-button-svg-container"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1365.3 1365.3" height="1365.3" width="1365.3"><path d="M1365.3 682.7A682.7 682.7 0 10576 1357V880H402.7V682.7H576V532.3c0-171.1 102-265.6 257.9-265.6 74.6 0 152.8 13.3 152.8 13.3v168h-86.1c-84.8 0-111.3 52.6-111.3 106.6v128h189.4L948.4 880h-159v477a682.8 682.8 0 00576-674.3" fill="#fff"></path></svg></div><div class="nsl-button-label-container">Continue with <b>Facebook</b></div></div></a></div></div></div>');
			});
		</script>
	<?php } ?>
	<style>
		.xoo-el-login-tgr {
			display: block !important;
		}
	</style>
	<script>
		jQuery(document).ready(function() {
			jQuery(".account-item").addClass('xoo-el-login-tgr');
		});
	</script>

<?php }

function imani_custom_variable_price_html($price, $product)
{
	$prices = $product->get_variation_prices(true);

	if (!empty($prices['price'])) {
		$min_price     = current($prices['price']);
		$max_price     = end($prices['price']);
		$min_reg_price = current($prices['regular_price']);
		$max_reg_price = end($prices['regular_price']);

		if ($min_price !== $max_price) {
			// $price = wc_format_price_range( $min_price, $max_price );
			$price = "From " . wc_price($min_price);
			if ($min_price !== $min_reg_price) {
				$price = "<del>" . wc_price($min_reg_price) . "</del> " . $price;
			}
			// $price = wc_format_sale_price(wc_price($min_reg_price), wc_price($min_price));
		}
	}

	return $price;
}
add_filter('woocommerce_variable_price_html', 'imani_custom_variable_price_html', 100, 2);

add_action('wp_footer', 'custom_styling_scripts');
function custom_styling_scripts()
{ ?>
	<script>
		const accordion = document.getElementsByClassName('container');

		for (i = 0; i < accordion.length; i++) {
			accordion[i].addEventListener('click', function() {
				this.classList.toggle('active')
			})
		}
	</script>
<?php }

add_filter('woocommerce_sale_flash', 'add_percentage_to_sale_badge', 20, 3);
function add_percentage_to_sale_badge($html, $post, $product)
{

	if ($product->is_type('variable')) {
		$percentages = array();

		// Get all variation prices
		$prices = $product->get_variation_prices();

		// Loop through variation prices
		foreach ($prices['price'] as $key => $price) {
			// Only on sale variations
			if ($prices['regular_price'][$key] !== $price) {
				// Calculate and set in the array the percentage for each variation on sale
				$percentages[] = round(100 - ($prices['sale_price'][$key] / $prices['regular_price'][$key] * 100));
			}
		}
		$percentage = max($percentages) . '%';
	} else {
		$regular_price = (float) $product->get_regular_price();
		$sale_price    = (float) $product->get_sale_price();

		$percentage    = round(100 - ($sale_price / $regular_price * 100)) . '%';
	}
	$html = '<span class="onsale">' . esc_html__('-', 'woocommerce') . '' . $percentage . '</span>';
	return $html;
}

add_action('woocommerce_before_add_to_cart_form', 'discounted_price_on_single');
function discounted_price_on_single()
{
}
add_filter('template_redirect', 'log_out_customer');
function log_out_customer()
{
	global $wp;

	if (isset($wp->query_vars['customer-logout'])) {
		wp_redirect(str_replace('&amp;', '&', wp_logout_url(wc_get_page_permalink('myaccount'))));
		exit;
	}
}

/*add_action('init', 'custom_taxonomy_Item');
function custom_taxonomy_Item()
{
	$labels = array(
		'name'                       => 'Collections',
		'singular_name'              => 'Collections',
		'menu_name'                  => 'Collection',
		'all_items'                  => 'All Collections',
		'parent_item'                => 'Parent Collection',
		'parent_item_colon'          => 'Parent Collection:',
		'new_item_name'              => 'New Collection Name',
		'add_new_item'               => 'Add New Collection',
		'edit_item'                  => 'Edit Collection',
		'update_item'                => 'Update Collection',
		'separate_items_with_commas' => 'Separate Collection with commas',
		'search_items'               => 'Search Collection',
		'add_or_remove_items'        => 'Add or remove Collection',
		'choose_from_most_used'      => 'Choose from the most used Collection',
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
	);
	register_taxonomy('Collection', 'product', $args);
	register_taxonomy_for_object_type('Collection', 'product');
}*/
//remove_action( 'woocommerce_shop_loop_item_title','woocommerce_template_loop_product_title', 10 );

function customize_shop_page_product_title()
{
	$result = do_shortcode('[yith_wcwl_add_to_wishlist]');
	echo $result;
}
add_action('woocommerce_shop_loop_item_title', 'customize_shop_page_product_title');

function floating_social_links()
{
?>
	<div id="fixed-social-links">
		<!-- <a href="https://facebook.com/ImaniStudio/" target="_blank" class="icon button circle facebook">
			<i class="icon-facebook"></i>
		</a> -->
		<a href="https://messenger.com/ImaniStudio/" target="_blank" class="icon button fb-messenger">
			<svg style="width: 20px; height: 20px;">
				<use href="#fb-messenger" />
			</svg>
		</a>
		<a href="https://www.instagram.com/imani.studio/" target="_blank" class="icon button instagram">
			<i class="icon-instagram"></i>
		</a>
		<a href="https://wa.me/447734982915" target="_blank" class="icon button whatsapp">
			<i class="icon-whatsapp"></i>
		</a>
	</div>
<?php
}
add_action('wp_footer', 'floating_social_links');

// add_action( 'woocommerce_checkout_after_terms_and_conditions', 'example_required_checkbox' );

// function example_required_checkbox() {
// 	woocommerce_form_field(
// 		'required_checkbox',
// 		array(
// 			'type'     => 'checkbox',
// 			'value'    => 'Yes',
// 			'required' => true,
// 			'label'    => __( 'This is a required checkbox.' ),
// 			'columns'  => 12,
// 		),
// 		WC()->checkout()->get_value( 'required_checkbox' )
// 	);
// }

// add_action( 'woocommerce_after_checkout_validation', 'example_validate_required_checkbox_field', 10, 2 );

// function example_validate_required_checkbox_field( &$data, &$errors ) {
// 	if ( ! isset( $_POST['required_checkbox'] ) ) {
// 		$errors->add( 'terms', 'You must check the required checkbox!' );
// 	}
// }

// /**
//  * Update value of field
//  */
// add_action( 'woocommerce_checkout_update_order_meta', 'example_required_checkbox_checkout_field_update_order_meta' );

// function example_required_checkbox_checkout_field_update_order_meta( $order_id ) {
// 	if ( ! empty( $_POST['required_checkbox'] ) ) {
// 		update_post_meta( $order_id, 'required_checkbox', sanitize_text_field( $_POST['required_checkbox'] ) );
// 	}
// }

// /**
//  * Display field value on the order edit page
//  */
// add_action( 'woocommerce_admin_order_data_after_billing_address', 'example_required_checkbox_display_admin_order_meta', 10, 1 );

// function example_required_checkbox_display_admin_order_meta( $order ) {
// 	$value = get_post_meta( $order->get_id(), 'required_checkbox', true );

// 	if ( '1' === $value ) {
// 		$value = 'Yes';
// 	} else {
// 		$value = 'No';
// 	}

// 	echo '<p><strong>' . __( 'Required Checkbox' ) . ':</strong> <br/>' . $value . '</p>';
// }
// 

function imani_product_meta_links()
{
	global $product;

	$product_id = $product->get_id(); // The product ID

	$brands = get_the_terms($product_id->ID, 'product_brand');
	$brand = array_values(array_filter($brands, function ($b) {
		return $b->parent == 0;
	}))[0];
	$sub_brands = array_values(array_filter($brands, function ($b) use ($brand) {
		return $b->parent == $brand->term_id;
	}));
	$category_id = get_post_meta($product_id, 'rank_math_primary_product_cat', true);
	if (!empty($category_id))
		$category = get_term($category_id, 'product_cat');
	else
		$category = get_the_terms($product->ID, 'product_cat')[0];

	// Displaying your custom field under the title
	if (!empty($brand)) {
		$brand_link = get_term_link($brand);
		echo "<div class='brand_name'>Brand: <a href='{$brand_link}'><strong>{$brand->name}</strong></a></div>";
	}
	if (!empty($sub_brands)) {
		echo "<div class='collection_name'>Collections: ";
		for ($i = 0; $i < sizeof($sub_brands); $i++) {
			$sb_link = get_term_link($sub_brands[$i]);
			echo "<a href='{$sb_link}'><strong>{$sub_brands[$i]->name}</strong></a>";
			if ($i < sizeof($sub_brands) - 1) echo " | ";
		}
		echo "</div>";
	}
	if (!empty($category)) {
		$cat_link = get_term_link($category);
		echo "<div class='category_name'>Category: <a href='{$cat_link}'><strong>{$category->name}</strong></a></div>";
	}
}
add_action('woocommerce_after_add_to_cart_form', 'imani_product_meta_links', 20);

function wishlist_and_share_single_prod()
{
	// display wishlist button and share button
?>
	<div id="product-actions" class="flex items-center">
		<?= do_shortcode('[yith_wcwl_add_to_wishlist]') ?>

		<?php if (get_theme_mod('product_info_share', 1)) { ?>
			<div class="custom-share-btn">
				<svg style="width: 25px; height: 25px;">
					<use href="#share" />
				</svg>

				<?php woocommerce_template_single_sharing() ?>
			</div>
		<?php } ?>
	</div>
<?php
}
add_action('woocommerce_single_product_summary', 'wishlist_and_share_single_prod');

add_shortcode('product_description', 'display_product_description');
function display_product_description($atts)
{
	$atts = shortcode_atts(array(
		'id' => get_the_id(),
	), $atts, 'product_description');

	global $product;

	if (!is_a($product, 'WC_Product'))
		$product = wc_get_product($atts['id']);

	return $product->get_description();
}

function accordin_add_after_social()
{
	global $product;
	$product_name = $product->get_title();
	$url = get_permalink($product->ID);
	get_template_part('template-parts/single-product-accordian');
}
add_action('woocommerce_after_add_to_cart_form', 'accordin_add_after_social');

/* New Additions */

function remove_actions()
{
	remove_action('flatsome_product_image_tools_top', 'flatsome_product_wishlist_button', 2);
	remove_action('flatsome_product_box_tools_top', 'flatsome_product_wishlist_button', 2);

	remove_action('flatsome_products_after', 'flatsome_products_footer_content');
	remove_action('flatsome_category_title', 'flatsome_add_category_filter_button', 20);

	remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50);

	//remove_action('woocommerce_simple_add_to_cart', array('CWG_Instock_Notifier_Product', 'display_in_simple_product'), 31);
}
add_action('wp_loaded', 'remove_actions');

//add_action('woocommerce_after_add_to_cart_form', array('CWG_Instock_Notifier_Product', 'display_in_simple_product'), 31);

add_action('flatsome_category_title_alt', 'flatsome_add_category_filter_button', 25);

if (!function_exists('yith_wcwl_custom_remove_from_wishlist_label')) {
	function yith_wcwl_custom_remove_from_wishlist_label($label)
	{
		return '';
	}
	add_filter('yith_wcwl_remove_from_wishlist_label', 'yith_wcwl_custom_remove_from_wishlist_label');
}

function buy_now_button()
{
	wp_enqueue_script('buy-now-js', get_stylesheet_directory_uri() . '/assets/js/buyNow.js', [], '0.0', true);

	$checkout_url = wc_get_checkout_url();
	$product_id = get_the_ID();

	echo "<a href='{$checkout_url}?add-to-cart={$product_id}' class='buy_now button alt'>Buy Now</a>";
}
add_action('woocommerce_after_add_to_cart_button', 'buy_now_button');

// this is a pluggable function
function flatsome_category_header()
{
	global $wp_query;

	do_action('imani_before_category_title');
	// Set Custom Shop Header.
	if (get_theme_mod('html_shop_page') && is_shop() && !$wp_query->is_search() && $wp_query->query_vars['paged'] < 1) {
		echo do_shortcode('<div class="custom-page-title">' . get_theme_mod('html_shop_page') . '</div>');
		wc_get_template_part('layouts/headers/category-title');
	} // Set Category headers.
	elseif (is_product_category() || is_shop() || is_product_tag() || is_product_taxonomy()) {
		wp_enqueue_script('product-archive-js', get_stylesheet_directory_uri() . '/assets/js/productArchive.js', [], '0.0', true);
		// Get Custom Header Content.
		$cat_header_style = get_theme_mod('category_title_style');

		// Fix Transparent header.
		if (get_theme_mod('category_header_transparent', 0) && !$cat_header_style) {
			$cat_header_style = 'featured';
		}

		$queried_object = get_queried_object();
		if (!is_shop() && get_term_meta($queried_object->term_id, 'cat_meta')) {
			$content = get_term_meta($queried_object->term_id, 'cat_meta');
			if (!empty($content[0]['cat_header'])) {
				if (!$cat_header_style) {
					//echo do_shortcode($content[0]['cat_header']);
					wc_get_template_part('layouts/headers/category-title');
				} else {
					wc_get_template_part('layouts/headers/category-title', $cat_header_style);
					//echo '<div class="custom-category-header">' . do_shortcode($content[0]['cat_header']) . '</div>';
				}
			} else {
				// Get default header title.
				wc_get_template_part('layouts/headers/category-title', $cat_header_style);
			}
		} else {
			// Get default header title.
			wc_get_template_part('layouts/headers/category-title', $cat_header_style);
		}
	}
}

function top_seo_content()
{
	if (is_search()) {
		return;
	}

	if (is_shop() || is_product_category() || is_product_tag() || is_product_taxonomy()) {
		$queried_object = get_queried_object();
		$content = get_term_meta($queried_object->term_id, 'cat_meta');

		if (empty($content[0]['cat_header'])) {
			$uncat = get_term_by('slug', 'uncategorized', 'product_cat');
			$content = get_term_meta($uncat->term_id, 'cat_meta');
		}

		echo do_shortcode($content[0]['cat_header']);
	}
}
add_action('imani_top_seo_content', 'top_seo_content');

function child_flatsome_products_footer_content()
{
	if (is_shop() || is_product_category() || is_product_tag() || is_product_taxonomy()) {
		$queried_object = get_queried_object();
		$content = get_term_meta($queried_object->term_id, 'cat_meta');
		if (!empty($content[0]['cat_footer'])) {
			echo '<hr/>';
			echo do_shortcode($content[0]['cat_footer']);
		} else {
			$uncat = get_term_by('slug', 'uncategorized', 'product_cat');
			$content = get_term_meta($uncat->term_id, 'cat_meta');
			echo '<hr/>';
			echo do_shortcode($content[0]['cat_footer']);
		}
	}
}
add_action('flatsome_products_after', 'child_flatsome_products_footer_content');

function mobile_bot_bar()
{
	echo do_shortcode('[block id="mobile-bottom-bar"]');
}

add_action('wp_footer', 'mobile_bot_bar');

function breadcrumbs()
{
	if (!(is_product_category() || is_product_taxonomy())) return;
?>
	<div class="container" style="margin-top: 15px;">
		<?= do_shortcode('[rank_math_breadcrumb]') ?>
	</div>
<?php
}
//add_action('flatsome_before_page', 'breadcrumbs');
add_action('imani_before_category_title', 'breadcrumbs', 30);
//add_action('woocommerce_before_single_product', 'breadcrumbs');

function spit_category_buttons()
{
	$brand = get_query_var('product_brand');
	if (!empty($brand)) {
		$categories = wp_get_post_terms(get_the_ID(), 'product_brand', ["fields" => "all"]);
	} else {
		$categories = wp_get_post_terms(get_the_ID(), 'product_cat', ["fields" => "all"]);
	}

	foreach ($categories as $cat) {
		$link = get_term_link($cat);
		echo "<a href='{$link}' class='button'>{$cat->name}</a>";
	}
}
add_action('imani_brand_or_category_buttons', 'spit_category_buttons');

// Pluggable function - for category/shop page title
function flatsome_category_title()
{
	if (!get_theme_mod('category_show_title', 0)) {
		return;
	} ?>
	<h1 class="shop-page-title is-xxxlarge text-center uppercase"><?php woocommerce_page_title(); ?></h1>
<?php
}

function estimated_shipping_date()
{
	// $product = wc_get_product(get_the_ID());
	$delivery = $product->get_attribute('delivery');
	if (str_contains($delivery, "Same Day Shipment")) {
		$days = "2 days";
	} else {
		$days = "10 days";
	}

	$d = date_create();
	date_add($d, date_interval_create_from_date_string($days));
	$d = date_format($d, "l, d M Y");

	echo "<div style='padding: 10px; background-color: #e7e7e7;margin-bottom: 10px;'>Estimated Shipping Date: <strong>{$d}</strong></div>";
}
add_action('woocommerce_before_add_to_cart_button', 'estimated_shipping_date');

// Change out of stock Text to Something Else
function imani_change_soldout($text, $product)
{
	if (!$product->is_in_stock()) {
		$text = '<div class="">Sold out.</div>';
	}
	return $text;
}
add_filter('woocommerce_get_availability_text', 'imani_change_soldout', 10, 2);

function imani_tab_titles($tabs)
{
	$tabs['description']['title'] = __('Dress Details', 'woocommerce');
	$tabs['additional_information']['title'] = __('Weight & Size', 'woocommerce');

	$tabs['returns'] = [
		'title' => __('Returns & Exchange', 'woocommerce'),
		'priority' => 60,
		'callback' => function () {
			echo 'Lorem ipsum consit';
		}
	];

	return $tabs;
}
add_filter('woocommerce_product_tabs', 'imani_tab_titles', 10, 2);

// remove query strings so style.css gets loaded correctly
function remove_query_strings()
{
	if (!is_admin()) {
		add_filter('script_loader_src', 'remove_query_strings_split', 15);
		add_filter('style_loader_src', 'remove_query_strings_split', 15);
	}
}

function remove_query_strings_split($src)
{
	$output = preg_split("/(&ver|\?ver)/", $src);
	return $output[0];
}
add_action('init', 'remove_query_strings');

function add_svg_defs()
{
	get_template_part("template-parts/svg-defs");

	// also gonna preload fonts
	/*
	?>
	 	<link rel="preload" href="/wp-content/themes/imanistudio/assets/fonts/Futura.woff2" as="font" type="font/woff2">
	 	<link rel="preload" href="/wp-content/themes/imanistudio/assets/fonts/Futura2.woff2" as="font" type="font/woff2">
	<?php
	*/
}
add_action('wp_head', 'add_svg_defs');

function promo_slider_before_every_page()
{
	echo do_shortcode('[block id="promo-slider"]');
}
add_action('imani_before_category_title', 'promo_slider_before_every_page', 20);

// Allow login with phone number
function imani_custom_login($user, $username, $password)
{
	//  Try logging in via their billing phone number
	if (is_numeric($username)) {

		//  The passed username is numeric - that's a start
		//  Now let's grab all matching users with the same phone number:
		$matchingUsers = get_users(array(
			'meta_key'     => 'billing_phone',
			'meta_value'   => $username,
			'meta_compare' => 'LIKE'
		));

		//  Let's save time and assume there's only one.
		if (is_array($matchingUsers) && !empty($matchingUsers)) {
			$username = $matchingUsers[0]->user_login;
		}
	} elseif (is_email($username)) {
		//  The passed username is email- that's a start
		//  Now let's grab all matching users with the same email:
		$matchingUsers = get_user_by_email($username);

		//  Let's save time and assume there's only one.
		if (isset($matchingUsers->user_login)) {
			$username = $matchingUsers->user_login;
		}
	}

	return wp_authenticate_username_password(null, $username, $password);
}
add_filter('authenticate', 'imani_custom_login', 20, 3);

// make billing_email optional
function adjust_requirement_of_checkout_contact_fields($fields)
{
	// $fields['billing_phone']['required']    = false;
	$fields['billing_email']['required']    = false;

	return $fields;
}
add_filter('woocommerce_billing_fields', 'adjust_requirement_of_checkout_contact_fields');

// remove company field from checkout
function remove_company_checkout_fields($fields)
{
	unset($fields['shipping_company']);

	return $fields;
}
add_filter('cfw_get_shipping_checkout_fields', 'remove_company_checkout_fields', 100, 3);

function imani_billing_phone_field()
{
	get_template_part('template-parts/checkout-billing_phone-field');
}
//add_action('cfw_checkout_before_shipping_address', 'imani_billing_phone_field');

function custom_registration_errors( $errors )
{
	unset($errors->errors['empty_email']);
}
add_filter( 'registration_errors', 'custom_registration_errors' );
add_action( 'register_new_user', 'custom_registration_errors' );

//Adding Prefix to order

add_filter( 'woocommerce_order_number', 'change_woocommerce_order_number' );

function change_woocommerce_order_number( $order_id ) {
    $prefix = '#IM-';
    $suffix = '-ST';
    $new_order_id = $prefix . $order_id . $suffix;
    return $new_order_id;
}

function imani_shipping_rates( $rates ) {
	// do maths in default currency
	global $WOOCS;
	$default_currency_rate = $WOOCS->get_currencies()[$WOOCS->current_currency]['rate'];

	$cart_total_price = floatval( WC()->cart->get_cart_contents_total() );
	$cart_total_price = $WOOCS->back_convert($cart_total_price, $default_currency_rate, 2);
	$cart_total_weight = WC()->cart->get_cart_contents_weight();

	foreach ( $rates as $rate_id => $rate ) {
		// Domestic Standard
		if ( 'flat_rate:46' === $rate_id ) {
			if ($cart_total_price > 50) {
				$rates[$rate_id]->cost = 0;
			} elseif ($cart_total_weight <= 1) {
				$rates[$rate_id]->cost = 5.5;
			}
			// else {
			// 	$rates[$rate_id]->cost = 10;
			// }
			// break;
		}

		// Domestic Express
		if ( 'flat_rate:43' === $rate_id ) {
			// if ($cart_total_weight > 1.5) {
			// 	$rates[$rate_id]->cost = 15;
			// }
			if ($cart_total_weight <= 1.5) {
				$rates[$rate_id]->cost = 11;
			}
		}
	}

	return $rates;
}
add_filter( 'woocommerce_package_rates', 'imani_shipping_rates', 100 );
