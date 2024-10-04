<?php
if(!class_exists('coupon_add_edit_class')){
    class coupon_add_edit_class{
        public function __construct(){
            add_action( 'admin_menu', array($this,'coupon_submenu_page') );
        }
        public function coupon_submenu_page() {
            add_submenu_page(
                __('list-coupon','wp-ravi-patel'),      
                'Add Coupon',         
                'Add Coupon',         
                'manage_options',       
                'add-coupon',    
                array($this,'add_coupon_template_page')
            );
        }

        public function add_coupon_template_page(){
            global $wpdb;
            $showError = false;
            $title = $description = $couponamount = $image = $category = $featured = ''; 
            $availability = [];
            $nonceField = 'add_coupon_nonce_field';
            $nonceAction = 'add_coupon_nonce_action';
            if(isset($_GET['edit_id']) && $_GET['edit_id'] > 0){
                $nonceField = 'edit_coupon_nonce_field';
                $nonceAction = 'edit_coupon_nonce_action';
                $couponId = filter_input(INPUT_GET,'edit_id');
                $couponData = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}coupon_ravi_patel WHERE id = %d", $couponId ) );
                if(isset($couponData->title)){
                    $title = $couponData->title;
                    $description = $couponData->description;
                    $couponamount = $couponData->coupon_amt;
                    $image = $couponData->image;
                    $category = $couponData->category;
                    $availability = $couponData->availability;
                    $availability = explode(',',$availability);
                    $featured = $couponData->featured;
                }else{
                    $showError = true;
                    $errorMessage = 'No record exists with this id.';
                }
            }
            if(isset($_POST) && !empty($_POST)){
                    $title = filter_input(INPUT_POST,'title') ?? '';
                    $description = filter_input(INPUT_POST,'description') ?? '';
                    $couponamount = filter_input(INPUT_POST,'couponamount') ?? '';
                    $image = filter_input(INPUT_POST,'coupon_image') ?? '';
                    $category = filter_input(INPUT_POST,'category') ?? '';
                    @$availability = $_POST['avail'] ?? [];
                    $featured = filter_input(INPUT_POST,'featured') ?? 0;
                    if($title == '' || $description == '' || $couponamount == '' || $image == '' || $category == '' || empty($availability) ){
                        $showError = true;
                        $errorMessage = 'Please select all the mendetory fields.';
                    }else{
                        $availabilityStr = implode(',',$availability);
                        if ( isset( $_POST['add_coupon_nonce_field'] ) && wp_verify_nonce( $_POST['add_coupon_nonce_field'], 'add_coupon_nonce_action' )){
                            $id = $wpdb->insert(
                                $wpdb->prefix . 'coupon_ravi_patel',
                                array(
                                    'title' => $title,
                                    'description' => $description,
                                    'coupon_amt'=>$couponamount,
                                    'image' => $image,
                                    'category' => $category,
                                    'availability' => $availabilityStr,
                                    'featured' => $featured,
                                ),
                                array(
                                    '%s',
                                    '%s',
                                    '%d',
                                    '%s',
                                    '%s',
                                    '%s',
                                    '%s',
                                )
                            );
                            $headerMsg="add_success";
                        }
                        if ( isset( $_POST['edit_coupon_nonce_field'] ) && wp_verify_nonce( $_POST['edit_coupon_nonce_field'], 'edit_coupon_nonce_action' )){
                            $couponId = filter_input(INPUT_GET,'edit_id');
                            $wpdb->update(
                                $wpdb->prefix . 'coupon_ravi_patel',
                                array(
                                    'title' => $title,
                                    'description' => $description,
                                    'coupon_amt'=>$couponamount,
                                    'image' => $image,
                                    'category' => $category,
                                    'availability' => $availabilityStr,
                                    'featured' => $featured,
                                ),
                                array(
                                    'id' => $couponId,
                                ),
                                array(
                                    '%s',
                                    '%s',
                                    '%d',
                                    '%s',
                                    '%s',
                                    '%s',
                                    '%s',
                                ),
                                array(
                                    '%d',
                                )
                            );
                            $headerMsg="edit_success";
                        }
                    } ?>
                    <script>
                     window.location.href = '<?php echo admin_url('admin.php?page=list-coupon&msg='.$headerMsg);?>';
                     </script>
                <?php }   
            ?>
            <div class="wrap">
                <h1 class="wp-heading-inline"><?php if(isset($_GET['edit_id'])){
                    _e('Edit coupon','wp-ravi-patel');
                }else{
                    _e('Add coupon','wp-ravi-patel');
                }?></h1>
                <a href="<?php echo admin_url('admin.php?page=list-coupon')?>" class="page-title-action"><?php _e('Back to list','wp-ravi-patel'); ?></a>
                <div class="white-bg">
                    <table width="100%" cellspacing="3" cellpadding="10">
                    <form name="addCouponFrm" id="addCouponFrm" method="post" action="">
                    <input type="hidden" name="action" value="create" />
                    <?php wp_nonce_field($nonceAction, $nonceField ); ?>
                    <?php if($showError){ ?>
                        <p class="error"><?php echo $errorMessage; ?></p>
                    <?php }?>
                    
                    <h2><?php if(isset($_GET['edit_id'])){
                    _e('Edit coupon','wp-ravi-patel');
                    }else{
                        _e('Add coupon','wp-ravi-patel');
                    }?></h2>
                    <p>(*)&nbsp;<?php _e('Required fields','wp-ravi-patel')?></p>
                        <tr>
                            <td class="left"><?php _e('Title:','wp-ravi-patel'); ?><sup>*</sup></td>
                            <td class="right"><input type="text" name="title" id="title" value="<?php echo $title;?>" ><br><span><?php _e('Enter the coupon title','wp-ravi-patel');?></span></td>
                        </tr>
                        <tr>
                            <td class="left"><?php _e('Description:','wp-ravi-patel'); ?><sup>*</sup></td>
                            <td class="right"><textarea rows="5" name="description" id="description"><?php echo $description;?></textarea><br><span><?php _e('Enter the coupon description','wp-ravi-patel');?></span></td>
                        </tr>
                        <tr>
                            <td class="left"><?php _e('Coupon Amount:','wp-ravi-patel'); ?><sup>*</sup></td>
                            <td class="right"><input type="number" name="couponamount" id="couponamount" value="<?php echo $couponamount; ?>" ></td>
                        </tr>
                        <tr>
                            <td class="left"><?php _e('image:','wp-ravi-patel'); ?><sup>*</sup></td>
                            <td class="right"><div class="image-preview">
                                                <img src="<?php echo $image; ?>" style="max-width: 15%; height: auto;" />
                                            </div>
                                            <input type="hidden" name="coupon_image" id="coupon_image" value="<?php echo $image; ?>" />
                                            <button class="button button-secondary" id="coupon_image_button"><?php _e('Select Image','wp-ravi-patel');?></button></td>
                        </tr>
                        <tr>
                            <td class="left"><?php _e('Category:','wp-ravi-patel'); ?><sup>*</sup></td>
                            <td class="right"><select name="category" id="category">
                                                <option value=""><?php _e('Select category');?></option>
                                                <option value="category1" <?php if($category == 'category1'){ echo 'selected'; } ?>>Category1</option>
                                                <option value="category2" <?php if($category == 'category2'){ echo 'selected'; } ?>>Category2</option>
                                                <option value="category3" <?php if($category == 'category3'){ echo 'selected'; } ?>>Category3</option> 
                                            </select><br><span><?php _e('Select the coupon category','wp-ravi-patel');?></span>
                            </td>
                        </tr>
                        <tr>
                            <td class="left"><?php _e('Availability:','wp-ravi-patel'); ?><sup>*</sup></td>
                            <td class="right"><input type="checkbox" name="avail[]" value="client" <?php if(in_array('client',$availability)){ echo 'checked'; }?>>&nbsp;Client&nbsp;<input type="checkbox" name="avail[]" value="distributor" <?php if(in_array('distributor',$availability)){ echo 'checked'; }?>>&nbsp;Distributor<br><span><?php _e('Choose the coupon availability','wp-ravi-patel');?></span>
                            </td>
                        </tr>
                        <tr>
                            <td class="left"><?php _e('Featured Coupon:','wp-ravi-patel'); ?></td>
                            <td class="right"><input type="checkbox" name="featured" value="1" <?php if($featured == '1'){ echo 'checked'; }?>>&nbsp;<br><span><?php _e('Select the featured coupon','wp-ravi-patel');?></span>
                            </td>
                        </tr>
                        <tr>
                            <td><input type="submit" class="button action" value="Save" /></td>
                        </tr>
                        </form>
                    </table>
                </div>
            </div>
        <?php

        }
    }
    $couponAddEditObj = new coupon_add_edit_class();
}
?>