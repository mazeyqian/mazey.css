<?php
/* 填充页面信息 */
class Class_Show {
    public $current_page = 0;
    /* 判断页面标题 */
    private function return_page_title() {
        $title = 'unknown';
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

    /* 文章标题链接 */
    public function print_post_title_link() {
        //the_post();
        echo '<a href="';
        the_permalink();
        echo '" title="';
        the_title();
        echo '" target="_blank">';
        the_title();
        echo '</a>';
    }
    /* public function print_post_title_link() {
        echo 'link';
    } */

    /* 文章标签 */
    /* private function return_post_detail() {
        $detail = 'unknown';
        $detail = '时间：' . the_date('Y年m月d日', '', '', false);
        $detail .= ' - 作者：' . get_the_author();
        $detail .= ' - 分类：' . get_the_category();
        return $detail;
    }
    public function print_post_detail() {
        echo $this->return_post_detail();


    } */
}