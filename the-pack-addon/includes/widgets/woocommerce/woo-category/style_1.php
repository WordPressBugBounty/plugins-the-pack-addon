<?php
$media = $settings['tmpl'] == 'icon' ? the_pack_render_icon($settings['icon'], 'inactikn') : thepack_ft_images($settings['img']['id'],'full','tbtr');
$term_m = get_term_by( 'slug', $settings['cat'], $settings['tax'] );

$count = $term_m->count ? thepack_build_html($term_m->count.' Products','span','count'): '';
$title = $term_m->name ? thepack_build_html($term_m->name,'h3','title'): '';
?>
<div class="tp-woo-category-box tp-d-flex">
    <?php echo '<a class="fullink" href="'.get_term_link( $term_m->term_id ).'"></a>';?>
    <div class="media tp-flex-center tp-no-body-overflow">
        <?php echo $media;?>
    </div>
    <div class="cat-content">
        <?php echo $title;?>
        <?php echo $count;?>
    </div>
</div>