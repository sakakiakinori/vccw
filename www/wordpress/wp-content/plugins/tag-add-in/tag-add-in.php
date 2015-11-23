<?php

/*
Plugin Name: Tag Add In
Author: Test Author
Plugin URI:
Description: Tag Add In Description
Version: 1.0
Author URI:
*/

class MyManageTag
{
    public static function manage_tags_columns($columns)
    {
        $columns['test'] = 'テスト';

        echo '<div>';
        echo 'myManageTag';
        var_dump(func_get_args());
        echo '</div>';

        return $columns;
    }

    public function add_columns()
    {
        add_filter('get_term', array($this, 'add_get_term'));
        add_filter('manage_edit-post_tag_columns', array($this, 'add_header_columns'));
        add_action('manage_post_tag_custom_column', array($this, 'add_columns_data'), 10, 5);
        add_filter('request', array($this, 'custom_orderby_columns'));
        add_filter('manage_edit-post_tag_sortable_columns', array($this, 'add_sortable_columns'));
    }
    /**
     * Filter the column headers for a list table on a specific screen.
     *
     * The dynamic portion of the hook name, `$screen->id`, refers to the
     * ID of a specific screen. For example, the screen ID for the Posts
     * list table is edit-post, so the filter for that screen would be
     * manage_edit-post_columns.
     *
     * @since 3.0.0
     *
     * @param array $columns An array of column headers. Default empty.
     */
    public function add_header_columns($columns)
    {
        $columns['update_dt'] = '更新日時';
        $columns['create_dt'] = '作成日時';

        return $columns;
    }

/**
 * Filter a term.
 *
 * @since 2.3.0
 *
 * @param int|object $_term    Term object or ID.
 * @param string     $taxonomy The taxonomy slug.
 */
    // $_term = apply_filters( 'get_term', $_term, $taxonomy );
    public function add_get_term($_term)
    {
        // echo '<pre>';
        // var_dump($_term);
        // echo '</pre>';
        global $wpdb;
        $row = $wpdb->get_row($wpdb->prepare("SELECT update_dt, create_dt FROM wp_term_date WHERE term_id = %d LIMIT 1", $_term->term_id));
        // echo '<pre>';
        // var_dump($row);
        // echo '</pre>';
        if ($row) {
            $_term->update_dt = $row->update_dt;
            $_term->create_dt = $row->create_dt;
        } else {
            $_term->update_dt = null;
            $_term->create_dt = null;
        }
        return $_term;
    }

    /**
     * Filter the displayed columns in the terms list table.
     *
     * The dynamic portion of the hook name, `$this->screen->taxonomy`,
     * refers to the slug of the current taxonomy.
     *
     * @since 2.8.0
     *
     * @param string $string      Blank string.
     * @param string $column_name Name of the column.
     * @param int    $term_id     Term ID.
     */
    public function add_columns_data($string, $column_name, $term_id)
    {
        // global $wpdb;
        // echo '<pre>';
        // var_dump($wpdb->last_result);
        // echo '</pre>';
        $tag = get_tag($term_id);
        echo $tag->{$column_name};
    }

    public function custom_orderby_columns($vars)
    {
        // if (isset($vars['orderby']) && 'location' == $vars['orderby']) {
        //     $vars = array_merge($vars, array(
        //         'meta_key' => 'location',
        //         'orderby' => 'meta_value',
        //     ));
        // }

        // if (isset($vars['orderby']) && 'update_dt' == $vars['orderby']) {
            echo '<pre>';
            echo 'myorder';
            var_dump($vars);
            echo '</pre>';
        // }

        return $vars;
    }
    /**
     * Filter the list table sortable columns for a specific screen.
     *
     * The dynamic portion of the hook name, `$this->screen->id`, refers
     * to the ID of the current screen, usually a string.
     *
     * @since 3.5.0
     *
     * @param array $sortable_columns An array of sortable columns.
     */
    public function add_sortable_columns($sortable_column)
    {
        $sortable_column['update_dt'] = 'update_dt';
        $sortable_column['create_dt'] = 'create_dt';

        return $sortable_column;
    }
}
// global $pagenow;
// global $post_type;//投稿タイプで切り分けたいときに使う
// if (is_admin() && ($pagenow=='post-new.php' || $pagenow=='post.php') && $post_type=="news"){

global $pagenow;
if (is_admin() && $pagenow == 'edit-tags.php') {
    // global $wpdb;
    // var_dump($wpdb);
    $myManageTag = new myManageTag();
    // add_filter('manage_edit-tags_columns', array($myManageTag, 'manage_tags_columns'));
    // add_filter('manage_edit-post_columns', array('MyManageTag', 'manage_tags_columns'));
    // add_filter('manage_edit-post_tag_columns', array('MyManageTag', 'manage_tags_columns'));
    // add_filter('manage_edit-post_tag_sortable_columns', array('MyManageTag', 'manage_tags_columns'));
    $myManageTag->add_columns();
}
