<?php
/**
 * Plugin class
 **/
if ( ! class_exists( 'KBX_USER_ROLES' ) ) {

class KBX_USER_ROLES {

  public function __construct() {
    //
  }
 
 /*
  * Initialize the class and start calling our hooks and filters
  * @since 1.0.0
 */
 public function init() {
   add_action( 'kbx_category_add_form_fields', array ( $this, 'add_kbx_article_categories' ), 10, 2 );
   add_action( 'created_kbx_category', array ( $this, 'save_kbx_article_categories' ), 10, 2 );
   add_action( 'kbx_category_edit_form_fields', array ( $this, 'edit_kbx_article_categories' ), 10, 2 );
   add_action( 'edited_kbx_category', array ( $this, 'update_kbx_article_categories' ), 10, 2 );
 }


 /*
  * Add a form field in the new category page
  * @since 1.0.0
 */
 public function add_kbx_article_categories ( $taxonomy ) {
     global $wp_roles;
     $roles = $wp_roles->get_names();
     $roles['visitor'] = 'Visitor';
     $html="<strong>User Roles </strong><br>";
     if ( ! empty( $roles ) ) {
         foreach ($roles as $key => $val) {

             $html .= '<input name="kbx_cats_user_roles[]"  type="checkbox" value="'.$key.'" checked/>';

             $html .= '<span style="margin-right:5px">' . $val . '</span>';
         }
     }
     $html .= '</br></br>';
     echo $html;
 }
 
 /*
  * Save the form field
  * @since 1.0.0
 */
 public function save_kbx_article_categories ( $term_id, $tt_id ) {
   if( isset( $_POST['kbx_cats_user_roles'] ) && !empty($_POST['kbx_cats_user_roles'])){
     $roles = $_POST['kbx_cats_user_roles'];
     add_term_meta( $term_id, 'kbx_cats_user_roles', $roles, true );
   }
 }
 
 /*
  * Edit the form field
  * @since 1.0.0
 */
 public function edit_kbx_article_categories ( $term, $taxonomy ) {
     global $wp_roles;
     $cat_roles= get_term_meta ( $term -> term_id, 'kbx_cats_user_roles', true );
     $roles = $wp_roles->get_names();
     $roles['visitor'] = 'Visitor';
     $html="<tr><th>User Roles </th><td>";
     if ( ! empty( $cat_roles ) ) {
         foreach ($roles as $key => $val) {
              if(in_array($key,$cat_roles)){
                  $flag="checked";
              }else{
                  $flag="";
              }
             $html .= '<input name="kbx_cats_user_roles[]"  type="checkbox" value="'.$key.'"  '.$flag.'/>';

             $html .= '<span style="margin-right:5px">' . $val . '</span>';
         }
     }else{
         foreach ($roles as $key => $val) {

             $html .= '<input name="kbx_cats_user_roles[]"  type="checkbox" value="'.$key.'" />';

             $html .= '<span style="margin-right:5px">' . $val . '</span>';
         }
     }
     $html .= '</td></tr></br>';
     echo $html;
 }

/*
 * Update the form field value
 * @since 1.0.0
 */
  public function update_kbx_article_categories ( $term_id, $tt_id ) {
    if( isset( $_POST['kbx_cats_user_roles'] ) && !empty( $_POST['kbx_cats_user_roles'] )){
      $cat_roles = $_POST['kbx_cats_user_roles'];
      update_term_meta ( $term_id, 'kbx_cats_user_roles', $cat_roles );
    } else {
      update_term_meta ( $term_id, 'kbx_cats_user_roles', '' );
    }
  }

}
 
$KBX_USER_ROLES = new KBX_USER_ROLES();
$KBX_USER_ROLES -> init();
 
}