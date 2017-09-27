<?php
/* mip类 */
class Class_MIP {
    /* 获取当前地址 */
    private function return_current_url() {
        if(is_home()) {
            return bloginfo('url');
        } else {
            return the_permalink();
        }
    }
    public function print_current_url() {
        echo $this->return_current_url();
    }
}