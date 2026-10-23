<?php
$cls = $settings['absmnu'].' '.$settings['subop'];
?>

<div class="tp-toggle-cat-wrap">
    <?php if(!$settings['hydlbl']){ ?>
        <a href="#" class="category-toggle tp-d-flex">
            <span class="label-wrap tp-d-flex"><?php echo the_pack_render_icon($settings['btik'], 'tp-icon');?><?php echo esc_attr($settings['btl']);?></span>
            <svg class="drop-icon" width="10" height="10" viewBox="0 0 10 10" fill="#575757" xmlns="http://www.w3.org/2000/svg"><path d="M9.78571 2.21429C9.5 1.92857 9.07143 1.92857 8.78571 2.21429L5 6L1.21429 2.21429C0.928571 1.92857 0.5 1.92857 0.214286 2.21429C-0.0714286 2.5 -0.0714286 2.92857 0.214286 3.21429L4.5 7.5C4.64286 7.64286 4.85714 7.71429 5 7.71429C5.14286 7.71429 5.35714 7.64286 5.5 7.5L9.78571 3.21429C10.0714 2.92857 10.0714 2.5 9.78571 2.21429Z"></path></svg>
        </a>
    <?php } ?>
    <nav class="site-categories <?php echo esc_attr($cls);?>">
        <?php rendor_custom_nav_menu($settings['menus']);?>
    </nav>
</div>
