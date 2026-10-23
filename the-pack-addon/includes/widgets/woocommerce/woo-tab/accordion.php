<?php
$closed = $settings['closed'] ? null : 'open="open"';
$product_tabs = apply_filters( 'woocommerce_product_tabs', array() );

if ( ! empty( $product_tabs ) ) : ?>

	<div class="woocommerce-tabs wc-tabs-wrapper tpwooaccordion">
			<?php $open_status = $closed; ?>
			<?php foreach ( $product_tabs as $key => $product_tab ) : ?>
				<details <?php echo $open_status; ?> name="single-product" class="<?php echo esc_attr( $key ); ?>_tab" id="tab-title-<?php echo esc_attr( $key ); ?>" role="tab" aria-controls="tab-<?php echo esc_attr( $key ); ?>">
					<summary class="tab-name"><?php echo wp_kses_post( apply_filters( 'woocommerce_product_' . $key . '_tab_title', $product_tab['title'], $key ) ); ?></summary>
					<div class="tp-woo-tab-content">
					<?php
					if ( isset( $product_tab['callback'] ) ) {
						call_user_func( $product_tab['callback'], $key, $product_tab );
					}
					?>
					</div>
				</details>
				<?php $open_status = null;  // Default to closed. ?>
			<?php endforeach; ?>

		<?php do_action( 'woocommerce_product_after_tabs' ); ?>
	</div>

<?php endif; ?>

<style>
.tab-name:before,.tab-name:after {
    border-top: 2px solid currentColor;
    content: "";
    display: inline-block;
    height: 1px;
    position: absolute;
    right: 0;
    top: 50%;
    transition: all .2s ease;
    width: 12px;
}
.tab-name:after {
    transform: rotate(90deg);
}
[open] .tab-name:after {
    transform: none;
}
</style>