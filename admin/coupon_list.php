<?php
if(!class_exists('coupon_list_class')){
    class coupon_list_class{
        public function __construct(){
            add_action( 'admin_menu', array($this,'coupon_submenu_page') );
        }
        public function coupon_submenu_page() {
            // Add top-level menu page
            add_submenu_page(
                __('list-coupon','wp-ravi-patel'),      
                'Coupons',         
                'Coupons',         
                'manage_options',       
                'list-coupon',    
                array($this,'coupon_list_page')
            );
        }

        public function coupon_list_page(){ 
            global $wpdb;
            $message = '';
            $allCoupons = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'coupon_ravi_patel ORDER BY created_at DESC');
            $totalRec = count($allCoupons);
            if(isset($_GET['msg']) && $_GET['msg']!=''){
                $msg = filter_input(INPUT_GET,'msg');
                if($msg == 'add_success'){
                    $message = 'Coupon added successfully.';
                }
                if($msg == 'edit_success'){
                    $message = 'Coupon edited successfully.';
                }
            }
            ?>
            <div class="wrap">
                <?php if($message!=''){?>
                    <p class="success"><?php echo $message; ?></p>
                <?php }?>
               <h1 class="wp-heading-inline">
                  Coupons
               </h1>
               <a href="<?php echo admin_url('admin.php?page=add-coupon');?>" class="page-title-action">Add New Coupon</a>
               <hr class="wp-header-end">
               <h2 class="screen-reader-text">Filter Coupon list</h2>
               <ul class="subsubsub">
                  <li class="all"><a href="edit.php?post_type=post" class="current" aria-current="page">All <span class="count">(<?php echo $totalRec; ?>)</span></a> |</li>
                  <li class="publish"><a href="edit.php?post_status=publish&amp;post_type=post">Published <span class="count">(<?php echo $totalRec; ?>)</span></a></li>
               </ul>
               <form id="posts-filter" method="get">
                  <p class="search-box">
                     <label class="screen-reader-text" for="post-search-input">Search Coupons:</label>
                     <input type="search" id="post-search-input" name="s" value="">
                     <input type="submit" id="search-submit" class="button" value="Search Coupons">
                  </p>
                  <input type="hidden" name="post_status" class="post_status_page" value="all">
                  <input type="hidden" name="post_type" class="post_type_page" value="post">
                  <input type="hidden" id="_wpnonce" name="_wpnonce" value="530f157199"><input type="hidden" name="_wp_http_referer" value="/wordpress/wp-admin/edit.php">	
                  <div class="tablenav top">
                     <div class="alignleft actions bulkactions">
                        <label for="bulk-action-selector-top" class="screen-reader-text">Select bulk action</label>
                        <select name="action" id="bulk-action-selector-top">
                           <option value="-1">Bulk actions</option>
                           <option value="edit" class="hide-if-no-js">Edit</option>
                           <option value="trash">Move to Trash</option>
                        </select>
                        <input type="submit" id="doaction" class="button action" value="Apply">
                     </div>
                     
                     <div class="tablenav-pages one-page"><span class="displaying-num"><?php echo $totalRec; ?> items</span>
                        <span class="pagination-links"><span class="tablenav-pages-navspan button disabled" aria-hidden="true">«</span>
                        <span class="tablenav-pages-navspan button disabled" aria-hidden="true">‹</span>
                        <span class="paging-input"><label for="current-page-selector" class="screen-reader-text">Current Page</label><input class="current-page" id="current-page-selector" type="text" name="paged" value="1" size="1" aria-describedby="table-paging"><span class="tablenav-paging-text"> of <span class="total-pages">1</span></span></span>
                        <span class="tablenav-pages-navspan button disabled" aria-hidden="true">›</span>
                        <span class="tablenav-pages-navspan button disabled" aria-hidden="true">»</span></span>
                     </div>
                     <br class="clear">
                  </div>
                  <h2 class="screen-reader-text">Posts list</h2>
                  <table class="wp-list-table widefat fixed striped table-view-list posts">
                     <caption class="screen-reader-text">Table ordered by Date. Descending.</caption>
                     <thead>
                        <tr>
                           <td id="cb" class="manage-column column-cb check-column"><input id="cb-select-all-1" type="checkbox">
                              <label for="cb-select-all-1"><span class="screen-reader-text">Select All</span></label>
                           </td>
                           <th scope="col" id="image" class="manage-column column-image">Image</th>
                           <th scope="col" id="title" class="manage-column column-title">Title</th>
                           <th scope="col" id="coupon-amount" class="manage-column column-coupon-amount">Coupon Amount</th>
                           <th scope="col" id="category" class="manage-column column-category">Category</th>
                           <th scope="col" id="availability" class="manage-column column-availability">Availability</th>
                           <th scope="col" id="availability" class="manage-column column-availability">Created At</th>
                        </tr>
                     </thead>
                     <tbody id="the-list">
                        <?php 
                        if(!empty($allCoupons)){
                         foreach($allCoupons as $key => $coupon){?>
                        <tr id="post-<?php echo $coupon->id;?>" class="iedit author-self level-0 post-<?php echo $coupon->id;?> type-post status-publish format-standard hentry category-sports">
                           <th scope="row" class="check-column">
                              <input id="cb-select-110" type="checkbox" name="coupons[]" value="<?php echo $coupon->id;?>">
                           </th>
                           <td class="author column-image" data-colname="Image"><img src="<?php echo $coupon->image; ?>" alt="<?php echo $coupon->title;?>" width="50" /></td>
                           <td class="title column-title has-row-actions column-primary page-title" data-colname="Title">
                              <strong><a class="row-title" href="<?php echo admin_url('admin.php?page=add-coupon&edit_id='.$coupon->id);?>" aria-label="<?php echo $coupon->title;?>"><?php echo $coupon->title;?></a></strong>
                           </td>
                           <td class="author column-image" data-colname="Image"><?php echo $coupon->coupon_amt; ?></td>
                           <td class="author column-image" data-colname="Image"><?php echo $coupon->category; ?></td>
                           <td class="author column-image" data-colname="Image"><?php echo $coupon->availability; ?></td>
                           <td class="author column-image" data-colname="Image"><?php echo $coupon->created_at; ?></td>
                        </tr>
                         <?php }   
                        }else{?>
                            <tr>
                                <td colspan="7"><?php _e('No record found','wp_ravi_patel'); ?></td>
                            <tr>
                        <?php }?>
                        
                       
                     </tbody>
                     <tfoot>
                     <tr>
                           <td id="cb" class="manage-column column-cb check-column"><input id="cb-select-all-1" type="checkbox">
                              <label for="cb-select-all-1"><span class="screen-reader-text">Select All</span></label>
                           </td>
                           <th scope="col" id="image" class="manage-column column-image">Image</th>
                           <th scope="col" id="title" class="manage-column column-title">Title</th>
                           <th scope="col" id="coupon-amount" class="manage-column column-coupon-amount">Coupon Amount</th>
                           <th scope="col" id="category" class="manage-column column-category">Category</th>
                           <th scope="col" id="availability" class="manage-column column-availability">Availability</th>
                           <th scope="col" id="availability" class="manage-column column-availability">Created At</th>
                        </tr>
                     </tfoot>
                  </table>
                  <div class="tablenav bottom">
                     <div class="alignleft actions bulkactions">
                        <label for="bulk-action-selector-bottom" class="screen-reader-text">Select bulk action</label>
                        <select name="action2" id="bulk-action-selector-bottom">
                           <option value="-1">Bulk actions</option>
                           <option value="edit" class="hide-if-no-js">Edit</option>
                           <option value="trash">Move to Trash</option>
                        </select>
                        <input type="submit" id="doaction2" class="button action" value="Apply">
                     </div>
                     <div class="alignleft actions">
                     </div>
                     <div class="tablenav-pages one-page"><span class="displaying-num"><?php echo $totalRec; ?> items</span>
                        <span class="pagination-links"><span class="tablenav-pages-navspan button disabled" aria-hidden="true">«</span>
                        <span class="tablenav-pages-navspan button disabled" aria-hidden="true">‹</span>
                        <span class="screen-reader-text">Current Page</span><span id="table-paging" class="paging-input"><span class="tablenav-paging-text">1 of <span class="total-pages">1</span></span></span>
                        <span class="tablenav-pages-navspan button disabled" aria-hidden="true">›</span>
                        <span class="tablenav-pages-navspan button disabled" aria-hidden="true">»</span></span>
                     </div>
                     <br class="clear">
                  </div>
               </form>
               <div class="clear"></div>
            </div>
        <?php }
    }
    $couponListObj = new coupon_list_class();
}