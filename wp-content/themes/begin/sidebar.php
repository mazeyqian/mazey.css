<!-- sidebar.php -->
<div>
<?php
    if(is_dynamic_sidebar()) {
        dynamic_sidebar();
    } else {
        wp_list_cats();
        wp_list_pages();
        get_links();
        wp_register();
        wp_loginout();
    }
?>
</div>