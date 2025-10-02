<?php
$social = '';
foreach ($settings['items'] as $item) {
    $url = thepack_get_that_link($item['url']);
    $icn = thepack_icon_svg($item['icon']);
    $social.='<a class="tp-flex-center" '.$url.'>'.$icn.'</a>';
}
$link = $settings['url']['url'] ? '<a class="fullink" '.thepack_get_that_link($settings['url']).'></a>' : '';
$more = $settings['url']['url'] ? thepack_icon_svg($settings['plus'],'tp-flex-center more-btn tbtr') : '';
?>
<div class="tp-team-one tp-pos-rel">
    <?php //phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped ?>
    <?php echo $link;?>
    <div class="thumb-wrap tp-no-overflow">
        <?php echo thepack_ft_images($settings['avatar']['id'], 'full','tbtr'); ?>
        <div class="social tbtr tp-d-flex">
            <?php //phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            <?php echo $social;?>
        </div>
    </div>
    <div class="tpinfo tp-d-flex">
        <div class="meta">
            <?php echo thepack_build_html($settings['name'],'h3','name');?>
            <?php echo thepack_build_html($settings['pos'],'span','pos');?>
        </div>
        <?php //phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        <?php echo $more;?>
    </div>
</div>