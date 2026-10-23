<?php
$wishlist_template = the_pack_get_global_settings('tpwoowish');
$url = get_permalink($wishlist_template);
$wishlist = new Dev_Ash_Wishlist();
$id = $wishlist->get_wishlist_items(); 
?>

<a href="<?php echo $url;?>" class="tp-wishlist-wrap tp-d-flex tp-al-center">
    <?php echo the_pack_render_icon($settings['ikn'], 'tp-wish-icon tp-d-flex tp-aljust-center');?>
    <span class="tp-wish-count tp-pos-rel"><?php echo count($id);?></span>
</a> 