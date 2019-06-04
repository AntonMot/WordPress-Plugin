<?php
/*
Plugin Name: myplugin
Description: Плагин, который обслуживает шорткод для вывода поста с наибольшим количеством комментариев
Version: 1.0
Author: Anton Momot
*/

add_action('wp_enqueue_scripts', 'register_plugin_styles');

function register_plugin_styles()
{
    wp_register_style('my-plugin', plugins_url('myplugin/my-plugin.css'));
    wp_enqueue_style('my-plugin');
}

function myplugin_showTheMostCommentedPost()
{
    ob_start();
    global $wpdb;
    $result = $wpdb->get_results("SELECT ID, post_title, post_date, post_content, post_excerpt, comment_count 
        FROM {$wpdb->posts}
        WHERE post_status = 'publish' 
        ORDER BY comment_count 
        DESC LIMIT 1 ");
    foreach ($result as $post) {
        setup_postdata($post);

        $postid = $post->ID;
        $title = $post->post_title;
        $date = $post->post_date;
        $content = $post->post_content;
        $excerpt = $post->post_excerpt;
        $commentcount = $post->comment_count;

        if ($commentcount != 0) { ?>

            <div class="my-thumbnail-wrapper my-thumbnail-wrapper-position my-thumbnail-wrapper-trim">
                <figure class="my-figure">
                    <img class="myimage" src="<?php echo get_the_post_thumbnail_url($postid) ?>" alt="image"/>
                </figure>
            </div>
            <br>
            <a href="<?php echo get_permalink($postid); ?>"
               title="<?php echo $title ?>"><?php echo $title ?>
            </a>
            <p><?php echo $excerpt ?></p>
            <p><?php echo $content ?></p>
            <p>Количество комментариев: <?php echo $commentcount ?></p>
            <p>Дата публикации: <?php echo $date ?></p>

        <? }
    }
    $output_string = ob_get_contents();
    ob_end_clean();
    return $output_string;
}

add_shortcode('showTheMostCommentedPost', 'myplugin_showTheMostCommentedPost');
