<?php
$order_id= order_id_from_database();

if( empty( $order_id ) ) { ?>

  <div class="notice notice-error">
    <p>No Orders - This plugin is functional only when there is at least one WooCommerce order</p>
  </div>

  <?php  } else { ?>

  <div class="wrap">
    <h1> WooCommerce Email Test Preview</h1>

    <div id="poststuff">

      <div id="post-body" class="metabox-holder columns-2">

        <!-- main content -->
        <div id="post-body-content">

          <div class="meta-box-sortables ui-sortable">

            <div class="postbox">

              <div class="inside">

                <table class="form-table">

                  <tr>
                    <th class="row-title">Admin templates</th>
                    <td>
                      <?php show_admin_test_email_buttons(); ?>
                    </td>
                  </tr>

                  <tr>
                    <th class="row-title">Customer templates</th>
                    <td>
                      <?php show_customer_test_email_buttons(); ?>
                    </td>
                  </tr>

                  <tr>
                    <th class="row-title">Subscription templates</th>
                    <td>
                      <?php show_subscription_test_email_buttons(); ?>
                    </td>
                  </tr>

                  <tr>
                    <th class="row-title">Membership templates</th>
                    <td>
                      <?php show_membership_test_email_buttons(); ?>
                    </td>
                  </tr>

                </table>

                <hr/>
                </br>

                <p class="wp-ui-text-highlight" style="text-align:center;">The above buttons will open a new tab containing a preview of the test email within your browser - test emails will not get sent to any inbox.</br>You can view specific order by selecting its ID from the ORDER ID box on the right side
                  of the screen.</p>

              </div>
              <!-- .inside -->

            </div>
            <!-- .postbox -->

            <div class="postbox">

              <div class="inside">

                <table class="form-table">

                  <p style="text-align: center;">But, if you want it, you can send an email templates directly to your mail client. Just check which templates you wanna send, enter an email address and voila!</p>
                  <tr valign="top">

                    <td scope="row" id="checkbox_row">
                      <hr/>
                      <p><strong>Customer templates!</strong></p>
                      <?php nrlb_show_customer_test_email_checkboxes(); ?>
                    </td>

                    <td scope="row">
                      <?php nrlb_woocommerce_mailer(); ?>
                    </td>

                  </tr>

                </table>

              </div>
              <!-- .inside -->

            </div>
            <!-- .postbox -->

          </div>
          <!-- .meta-box-sortables .ui-sortable -->

        </div>
        <!-- post-body-content -->

        <div id="postbox-container-1" class="postbox-container">

          <div class="meta-box-sortables">

            <div class="postbox">

              <h2 class="handle"><span>Order ID</span></h2>

              <div class="inside">

                <?php $test_email_options=get_test_email_options(); ?>

                <form method="post" action="">
                  <div class="form-field ">
                    <?php echo $order_id_select=get_order_id_select_field( $test_email_options[ 'wc_email_test_order_id'] ); ?>
                  </div>
                </form>

                <hr/>

              </div>
              <!-- .inside -->

            </div>
            <!-- .postbox -->

          </div>
          <!-- .meta-box-sortables -->

        </div>
        <!-- #postbox-container-1 .postbox-container -->

      </div>
      <!-- #post-body .metabox-holder .columns-2 -->

      <br class="clear">
    </div>
    <!-- #poststuff -->

  </div>
  <!-- .wrap -->
  <?php } ?>
