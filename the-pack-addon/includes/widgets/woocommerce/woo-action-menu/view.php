<?php
global $current_user;
$tabti = '';

$heading = is_user_logged_in() ? $settings['wel'] : $settings['sign'];
$sub = is_user_logged_in() ? $current_user->user_login : $settings['signsub'];
$avatar = is_user_logged_in() ? get_avatar( $current_user->user_email, 96, null, null, ['class' => 'img-responsive img-rounded']) : the_pack_render_icon($settings['user'], 'actikn');

foreach ($settings['tabs'] as $a) {

	$url = wc_get_account_endpoint_url( $a['end'] );
	$label = $this->get_endpoint_label_from_slug($a['end']);
	$icon = the_pack_render_icon($a['icon'], 'actikn');
    $tabti .= '<li><a href="'.$url.'">'.$icon.$label.'</a></li>';
}

$links = is_user_logged_in() ? '<ul class="sub-menu">'.$tabti.'</ul>' : '';

?>
<ul class="tp-menu-wrap tp-woo-action-links">
	<li>
		<a href="<?php echo get_permalink( wc_get_page_id( 'myaccount' ) ); ?>">
			<span class="tp-d-flex action-wrap">
				<span class="avatar-icon tp-d-flex no-ovrflow">
					<?php echo $avatar;?>
				</span>
				<span class="content-icon tp-d-flex">
					<?php echo thepack_build_html($heading,'p','heading');?>
					<?php echo thepack_build_html($sub,'span','sub');?>
				</span>
			</span>
		</a>
		<?php echo $links;?>
	</li>
</ul>