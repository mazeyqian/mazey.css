<?php
/* 填充页面信息 */
class Class_Show {
    public $current_page = 0;
    /* 判断页面标题 */
    private function return_page_title() {
        $title = '未知';
        $this->current_page = get_query_var('paged');
        /* 是否主页 */
        if(is_home()) {
            $title = get_bloginfo('description');
        } else {
            $title = wp_title('', false);
        }
        /* 是否分页 */
        if($this->current_page > 0) {
            $title .= " - 第{$this->current_page}页";
        }
        $title .= ' - ' . get_bloginfo('name');
        return $title;
    }
    public function print_page_title() {
        echo $this->return_page_title();
    }
}