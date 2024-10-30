<?php
defined('ABSPATH') or die("You can't access this file directly.");

//Register Meta Box
function kbx_register_meta_box() {

    add_meta_box( 'kbx-knowledgebase-meta', esc_html__( 'Custom Fields', 'kbx-qc' ), 'kbx_meta_box_callback', 'kbx_knowledgebase', 'advanced', 'high' );
}

add_action( 'add_meta_boxes', 'kbx_register_meta_box');
 
//Add field
function kbx_meta_box_callback( $object ) {
 
    $kpm_upvotes = get_post_meta( $object->ID, 'kpm_upvotes', true );
    $kpm_downvotes = get_post_meta( $object->ID, 'kpm_downvotes', true );
    $kpm_ranking = get_post_meta( $object->ID, 'kpm_ranking', true );
    $kpm_views = get_post_meta( $object->ID, 'kpm_views', true );
    $kpm_gterm = get_post_meta( $object->ID, 'kpm_gterm', true );
    $kpm_featured = get_post_meta( $object->ID, 'kpm_featured', true );
    $kpm_more_queries = maybe_unserialize(get_post_meta( $object->ID, 'kpm_more_queries', true ));
    $kpm_article_files = maybe_unserialize(get_post_meta( $object->ID, 'kpm_article_file', true ));
    if($kpm_featured =="yes") { $kpm_featured_checked = 'checked="checked"';}
    else{
        $kpm_featured_checked='';
    }

    $outline = '<div><label for="kpm_featured" style="width:150px; display:inline-block;">'. esc_html__('Sticky Article', 'kbx-qc') .'</label>';

    $outline .= '<input type="checkbox" name="kpm_featured" id="kpm_featured" class="kpm_featured kbx_disabled_input" disabled="disabled"  value="yes" '. $kpm_featured_checked .' />';
    $outline .= '<a class="go-pro-link" href="https://www.quantumcloud.com/products/knowledgebase-helpdesk/" target="_blank"><strong>Coming Soon</strong></a></div>';

    $outline .= '<div><label for="kpm_upvotes" style="width:150px; display:inline-block;">'. esc_html__('Upvote Count', 'kbx-qc') .'</label>';
    $outline .= '<input type="text" name="kpm_upvotes" id="kpm_upvotes" class="kpm_upvotes" value="'. esc_attr($kpm_upvotes) .'" style="width:300px;"/></div>';

    $outline .= '<div><label for="kpm_views" style="width:150px; display:inline-block;">'. esc_html__('Views', 'kbx-qc') .'</label>';
    $outline .= '<input type="text" name="kpm_views" id="kpm_views" class="kpm_views" value="'. esc_attr($kpm_views) .'" style="width:300px;"/></div>';

    $outline .= '<div><label for="kpm_gterm" style="width:150px; display:inline-block;">'. esc_html__('Glossary Term (A to Z)', 'kbx-qc') .'</label>';
    $outline .= '<input type="text" name="kpm_gterm" id="kpm_gterm" class="kpm_gterm" value="'. esc_attr($kpm_gterm) .'" style="width:300px;"/></div>';

    $outline .= '<div><label for="kpm_downvotes" style="width:150px; display:inline-block;">'. esc_html__('Downvote Count', 'kbx-qc') .'</label>';
    $outline .= '<input type="text" name="kpm_downvotes" id="kpm_downvotes" class="kpm_downvotes kbx_disabled_input" disabled="disabled" value="'. esc_attr($kpm_downvotes) .'" style="width:300px;"/>';
    $outline .= '<a class="go-pro-link" href="https://www.quantumcloud.com/products/knowledgebase-helpdesk/" target="_blank"><strong>Coming Soon</strong></a></div>';

    $outline .= '<div><label for="kpm_ranking" style="width:150px; display:inline-block;">'. esc_html__('Ranking', 'kbx-qc') .'</label>';
    
    $outline .= '<input type="text" name="kpm_ranking" id="kpm_ranking" class="kpm_ranking kbx_disabled_input" disabled="disabled" value="'. esc_attr($kpm_ranking) .'" style="width:300px;"/>';
    $outline .= '<a class="go-pro-link" href="https://www.quantumcloud.com/products/knowledgebase-helpdesk/" target="_blank"><strong>Coming Soon</strong></a></div>';

    $outline .= '<div id="kbx-more-queries-container"><label  style="width:150px; display:inline-block;">'. esc_html__('Alternative Titles or Questions that this Article also Answers', 'kbx-qc') .'</label>';
    if(isset($kpm_more_queries) && !empty($kpm_more_queries)){
            $kpm_more_counter=1;
            foreach ($kpm_more_queries as $kpm_more_query){
                $outline .= '<div style="display:inline-block" class="kbx-more-query"><input type="text" name="kpm_more_queries[]"  class="kpm_more_queries kbx_disabled_input" disabled="disabled" value="'. esc_attr($kpm_more_query) .'" style="width:300px;"/> <button type="button" class="danger kbx-more-query-remove kbx_disabled_input" disabled="disabled"> X </button>';
                $outline .= '<a class="go-pro-link" href="https://www.quantumcloud.com/products/knowledgebase-helpdesk/" target="_blank"><strong>Coming Soon</strong></a></div>';
            }
     }else{
        $outline .= '<div style="display: inline-block" class="kbx-more-query"><input type="text" name="kpm_more_queries[]"  class="kpm_more_queries kbx_disabled_input" disabled="disabled" style="width:300px;"/> <button class="danger kbx-more-query-remove kbx_disabled_input" disabled="disabled"> X </button>';
        $outline .= '<a class="go-pro-link" href="https://www.quantumcloud.com/products/knowledgebase-helpdesk/" target="_blank"><strong>Coming Soon</strong></a></div>';
    }
    $outline .= '</div><div style="margin-left:151px;margin-top:5px"> <button type="button" id="kbx-more-query-add kbx_disabled_input" disabled="disabled">+ More</button>';
    $outline .= '<a class="go-pro-link" href="https://www.quantumcloud.com/products/knowledgebase-helpdesk/" target="_blank"><strong>Coming Soon</strong></a></div>';

    $outline .= '<div id="kbx-post-files-wrapper"><label  style="width:150px; display:inline-block;">'. esc_html__('Article Files', 'kbx-qc') .'</label>';//json_encode($kpm_article_files).
    if(isset($kpm_article_files) && !empty($kpm_article_files)){
        $kpm_file_counter=0;
        $kpm_article_labels=$kpm_article_files['file_label'];
        $kpm_article_links=$kpm_article_files['file_link'];
        foreach ($kpm_article_labels as $kpm_article_label){
            $outline .= '<div style="margin-left:150px;" class="kbx-article-file-container"> <a href="'. $kpm_article_links[$kpm_file_counter] .'">'. $kpm_article_label .'</a> <input type="hidden" name="kpm_article_file[file_label][]"  class="kbx-article-file" value="'. $kpm_article_label .'" style="width:300px;"/> <input type="hidden" name="kpm_article_file[file_link][]"  class="kbx-article-file" value="'. $kpm_article_links[$kpm_file_counter] .'" style="width:300px;"/> <button type="button" class="danger kbx-article-file-remove kbx_disabled_input" disabled="disabled">X</button></div>';

        }
    }
    $outline .= '</div><div style="margin-left:151px;margin-top:10px"> <button type="button" id="kbx-post-file-add kbx_disabled_input" disabled="disabled">+ Add File</button> ';
    $outline .= '<a class="go-pro-link" href="https://www.quantumcloud.com/products/knowledgebase-helpdesk/" target="_blank"><strong>Coming Soon</strong></a></div>';


    $outline.='<script>';
        $outline.='
        jQuery(\'#kbx-more-query-add\').click(function (e) {
            var query=\'<div style="margin-left:150px;" class="kbx-more-query"><input type="text" name="kpm_more_queries[]"  class="kpm_more_queries" style="width:300px;"/> <button type="button" class="danger kbx-more-query-remove"> X </button></div>\';
            jQuery(\'#kbx-more-queries-container\').append(query);
        });
        jQuery(document).on(\'click\',\'.kbx-more-query-remove\',function (e) {
        if (confirm(\'Are you sure you want to remove this?\')) {
            jQuery(this).parent().remove();
        } 
    })
        ';
    $outline.=' jQuery("#kbx-post-file-add").click(function(e){e.preventDefault();var t=wp.media({title:"Custom Icon",multiple:!1}).open().on("select",function(e){var l=t.state().get("selection").first();console.log(l.toJSON());var i=l.toJSON().filename,a=l.toJSON().url,n=\'<div style="margin-left:150px;" class="kbx-article-file-container"> <a href="\'+a+\'">\'+i+\'</a> <input type="hidden" name="kpm_article_file[file_label][]"  class="kbx-article-file" value="\'+i+\'" style="width:300px;"/> <input type="hidden" name="kpm_article_file[file_link][]"  class="kbx-article-file" value="\'+a+\'" style="width:300px;"/> <button type="button" class="danger kbx-article-file-remove"> X </button></div>\';jQuery("#kbx-post-files-wrapper").append(n)})});';
    $outline.='jQuery(document).on("click",".kbx-article-file-remove",function(e){e.preventDefault(),confirm("Are you sure you want to remove this file?")&&jQuery(this).parent().remove()});';
    $outline.='</script>';

    echo $outline;
}


if ( ! function_exists( 'kbx_save_custom_meta_box' ) ) {
function kbx_save_custom_meta_box($post_id, $post, $update)
{

    if(!current_user_can("edit_post", $post_id))
        return $post_id;

    if(defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
        return $post_id;

    $slug = "kbx_knowledgebase";

    if($slug != $post->post_type)
        return $post_id;

    $kpm_featured = "";
    $upvotes = "";
    $ranking = "";
    $views = "";
    $gterm = "";



    if(isset($_POST["kpm_upvotes"])){
        $upvotes = sanitize_text_field(( $_POST["kpm_upvotes"] ));
    }
    update_post_meta($post_id, "kpm_upvotes", $upvotes);

    if(isset($_POST["kpm_views"])){
        $views = sanitize_text_field( $_POST["kpm_views"] );
    }
    update_post_meta($post_id, "kpm_views", $views);

    if(isset($_POST["kpm_gterm"])){
        $gterm = sanitize_text_field( $_POST["kpm_gterm"] );
    }
    update_post_meta($post_id, "kpm_gterm", $gterm);

    //Set the detault section as Uncategorized for unassigned section.
    if ( 'publish' === $post->post_status ) {
        $defualt_term       = get_term_by('slug', 'default-section', 'kbx_category');
        $defualt_term_id    = $defualt_term->term_id;
        $all_terms          = get_the_terms($post_id, 'kbx_category');
        if (empty($all_terms)) {
            wp_set_object_terms($post_id, $defualt_term_id, 'kbx_category');
        }
    }
}
}

add_action("save_post", "kbx_save_custom_meta_box", 10, 3);

//add_action( 'post_submitbox_misc_actions', 'kbx_article_as_indent_exporter_button' );
if ( ! function_exists( 'kbx_article_as_indent_exporter_button' ) ) {
    function kbx_article_as_indent_exporter_button($object){
        $html  = '<div id="major-publishing-actions" style="overflow:hidden">';
        $html .= '<div id="publishing-action">';
        $html .= '<input type="button" id="kbx-intent-exporter" post-id="'.$object->ID.'" tabindex="5" value="Export As Intent" class="button-primary" id="custom" name="publish">';
        $html .= '</div>';
        $html .= '</div>';
        echo $html;
    }
}