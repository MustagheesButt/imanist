<?php

/**
 * Template name: WooCommerce - My Account
 *
 * This templates add My account to the sidebar.
 *
 * @package Flatsome
 */

get_header(); ?>

<?php do_action('flatsome_before_page'); ?>

<?php //wc_get_template( 'myaccount/header.php' ); 
?>

<div class="page-wrapper my-account mb">
	<div class="" role="main">

		<?php if (is_user_logged_in()) { ?>

			<div class="row vertical-tabs" style="max-width: 100%;">
				<div class="large-3 col col-border">
					<?php //wc_get_template( 'myaccount/account-user.php' ); 
					?>

					<?php do_action('woocommerce_before_account_navigation'); ?>

					<div id="my-account-nav-container">
						<?php wc_get_template('myaccount/account-links-customized.php'); ?>
					</div>

					<?php do_action('woocommerce_after_account_navigation'); ?>
				</div>

				<div class="large-9 col">
					<?php while (have_posts()) : the_post(); ?>
						<?php the_content(); ?>
					<?php endwhile; // end of the loop. 
					?>
				</div>
			</div>

		<?php } else { ?>

			<?php while (have_posts()) : the_post(); ?>

				<?php the_content(); ?>

			<?php endwhile; // end of the loop. 
			?>

		<?php } ?>

	</div>
</div>

<?php do_action('flatsome_after_page'); ?>

<?php get_footer(); ?>