<?php
/* mip类 */
class Class_MIP {
    /* 获取当前地址 */
    private function return_current_url() {
        $current_url = get_bloginfo('url');
        if(is_home()) {
            $current_url = get_bloginfo('url');
        } elseif(is_tax() || is_tag() || is_category()) {
            $query_object = get_queried_object();
            $current_url = get_term_link($query_object, $query_object->taxonomy);
        } elseif(is_page() || is_single) {
            $current_url = get_permalink();
        }
        return $current_url;
    }

    public function print_current_url() {
        echo $this->return_current_url();
    }
}