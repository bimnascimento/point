<?php
/**
 * Dokan Seller registration form
 *
 * @since 2.4
 *
 * @package dokan
 */
?>

<p class="form-row form-group user-role">
    <label class="radio">
        <input type="radio" name="role" value="customer"<?php checked( $role, 'customer' ); ?>>
        <?php _e( 'I am a customer', 'dokan' ); ?>
    </label>

    <label class="radio">
        <input type="radio" name="role" value="seller"<?php checked( $role, 'seller' ); ?>>
        <?php _e( 'I am a seller', 'dokan' ); ?>
    </label>
    <?php do_action( 'dokan_registration_form_role', $role ); ?>
</p>
<div class="show_if_seller"<?php echo $role_style; ?>>

    <?php /* ?>

    <div class="split-row form-row-wide">
        <p class="form-row form-group">
            <label for="first-name"><?php _e( 'First Name', 'dokan' ); ?> <span class="required">*</span></label>
            <input type="text" class="input-text form-control" name="fname" id="first-name" value="<?php if ( ! empty( $postdata['fname'] ) ) echo esc_attr($postdata['fname']); ?>" required="required" />
        </p>

        <p class="form-row form-group">
            <label for="last-name"><?php _e( 'Last Name', 'dokan' ); ?> <span class="required">*</span></label>
            <input type="text" class="input-text form-control" name="lname" id="last-name" value="<?php if ( ! empty( $postdata['lname'] ) ) echo esc_attr($postdata['lname']); ?>" required="required" />
        </p>
    </div>

    <?php */ ?>

    <h4><i class="fa fa-handshake-o" aria-hidden="true"></i> <?php _e( 'Informações da Lavanderia', 'porto' ); ?></h4>

    <p class="form-row form-group form-row-wide">
        <label for="company-name"><?php _e( 'Shop Name', 'dokan' ); ?> <span class="required">*</span></label>
        <input placeholder="<?php _e( 'Shop Name', 'dokan' ); ?>" type="text" class="input-text form-control" name="shopname" id="company-name" value="<?php if ( ! empty( $postdata['shopname'] ) ) echo esc_attr($postdata['shopname']); ?>" required="required" />
    </p>

    <p class="form-row form-group form-row-wide">
        <label for="seller-url" class="pull-left"><?php _e( 'Shop URL', 'dokan' ); ?> <span class="required">*</span></label>
        <strong id="url-alart-mgs" class="pull-right"></strong>
        <input placeholder="<?php _e( 'Shop URL', 'dokan' ); ?>" type="text" class="input-text form-control" name="shopurl" id="seller-url" value="<?php if ( ! empty( $postdata['shopurl'] ) ) echo esc_attr($postdata['shopurl']); ?>" required="required" />
        <small><?php
        //echo home_url() . '/' . dokan_get_option( 'custom_store_url', 'dokan_general', 'store' );
        echo home_url() . '/';
        ?><strong id="url-alart"></strong></small>
    </p>

    <p class="form-row form-group form-row-wide">
        <label for="shop-phone"><?php _e( 'Phone Number', 'dokan' ); ?><span class="required">*</span></label>
        <input placeholder="<?php _e( 'Phone Number', 'dokan' ); ?>" type="text" class="input-text form-control" name="phone" id="shop-phone" value="<?php if ( ! empty( $postdata['phone'] ) ) echo esc_attr($postdata['phone']); ?>" required="required" />
    </p>
    <?php

        $show_toc = dokan_get_option( 'enable_tc_on_reg', 'dokan_general' );
        $show_toc = 'on';
        if ( $show_toc == 'on' ) {
            $toc_page_id = dokan_get_option( 'reg_tc_page', 'dokan_pages' );
            if ( $toc_page_id != -1 ) {
                $toc_page_url = get_permalink( $toc_page_id );
            }else{
                $toc_page_url = home_url('../termo-de-uso');
            ?>
            <p class="form-row form-group form-row-wide">
                <input class="tc_check_box" type="checkbox" id="tc_agree" name="tc_agree" required="required">
                <label style="display: inline" for="tc_agree"><?php echo sprintf( __( 'I have read and agree to the <a target="_blank" href="%s">Terms &amp; Conditions</a>.', 'dokan' ), $toc_page_url ); ?></label>
            </p>
      <?php } ?>
  <?php } ?>
  <?php  do_action( 'dokan_seller_registration_field_after' ); ?>

</div>

<?php do_action( 'dokan_reg_form_field' ); ?>
