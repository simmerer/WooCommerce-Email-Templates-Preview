<?php
global $email; //set $email as global var for using in nrlb_woocommerce_mailer()

// including plugin JS file
function nrlb_load_scripts() {
    wp_enqueue_script('nrlb-script', plugins_url('nrlb_main.js', __FILE__));
}
add_action('admin_head', 'nrlb_load_scripts');


/**
  Returning orders from database.
  @return int
 */
function order_id_from_database() {
    global $wpdb;

    $order_id_query = 'SELECT order_id FROM ' . $wpdb->prefix . 'woocommerce_order_items' . ' GROUP BY order_id ORDER BY order_item_id DESC LIMIT 100';
    $order_id       = $wpdb->get_results($order_id_query);
    return $order_id;
}

/**
  Populating option fields with a order id.
  @param int $wc_email_test_order_id
  @return string - HTML select element
 */
function get_order_id_select_field($wc_email_test_order_id) {

    $order_id = order_id_from_database();

    $order_id_select_options = "<option value='recent'>Most Recent</option>";
    foreach ($order_id as $id) {
        $order_id_select_options .= "<option value='{$id->order_id}'>#{$id->order_id}</option>";
    }

    $order_id_select_options = str_replace("value='{$wc_email_test_order_id}'", "value='{$wc_email_test_order_id}' selected", $order_id_select_options);
    $order_id_select         = "<select style='height:200px; width:100%;' id='wc_email_test_order_id' size='40' name='wc_email_test_order_id'>{$order_id_select_options}</select>";

    return $order_id_select;
}

/**
  Displaying email in a browser.
 */
function nrlb_run_email_script() {
    if ($_GET["order_id"]) {
        $wc_email_test_order_id = $_GET["order_id"];
    } else {
        die;
    }

    if (!$wc_email_test_order_id) {  // get a valid and most recent order_id
        global $wpdb;

        $order_id_query = 'SELECT order_id FROM ' . $wpdb->prefix . 'woocommerce_order_items ORDER BY order_item_id DESC LIMIT 1';
        $order_id       = $wpdb->get_results($order_id_query);

        if (empty($order_id)) {
            return;
        } else {
            $wc_email_test_order_id = $order_id[0]->order_id;
        }
    }

    $email_class = get_query_var('woocommerce_email_test');  // the email type to send

    $for_filter = strtolower(str_replace('WC_Email_', '', $email_class));


    // change email address within order to saved option
    add_filter('woocommerce_email_recipient_' . $for_filter, 'your_email_recipient_filter_function', 10, 2);

    function your_email_recipient_filter_function($recipient, $object) {
        return '';
    }

    // load the email classs
    $wc_emails = new WC_Emails();
    $emails    = $wc_emails->get_emails();
    $new_email = $emails[$email_class]; // select the email

    apply_filters('woocommerce_email_enabled_' . $for_filter, false, $new_email->object);  // make sure email isn't sent


    // passing order_id to WC_Email_Customer_Note class
    if ($for_filter == 'customer_note') {
        $new_email->trigger(array(
            'order_id' => $wc_email_test_order_id
        ));

    } else {
        $new_email->trigger($wc_email_test_order_id); // passing order_id to WC_Email class
    }

    echo $new_email->style_inline($new_email->get_content());  // echo the email content for the browser

} // end nrlb_run_email_script()


// displaying admin email templates
function show_admin_test_email_buttons() {

    global $test_admin_email_class;
    $site_url = site_url();

    foreach ($test_admin_email_class as $class => $name) {
        echo " <a href='{$site_url}/?woocommerce_email_test={$class}' class='button button-primary see-template' style='margin-bottom: 10px;' target='_blank'>{$name}</a> ";
    }
}

// displaying customer email templates
function show_customer_test_email_buttons() {

    global $test_customer_email_class;
    $site_url = site_url();

    foreach ($test_customer_email_class as $class => $name) {
        echo " <a href='{$site_url}/?woocommerce_email_test={$class}' class='button button-primary see-template' style='margin-bottom: 10px;' target='_blank'>{$name}</a> ";
    }
}

// displaying subscription email templates
function show_subscription_test_email_buttons() {

    global $test_subscription_email_class;
    $site_url = site_url();

    foreach ($test_subscription_email_class as $class => $name) {
        echo " <a href='{$site_url}/?woocommerce_email_test={$class}' class='button button-primary see-template' style='margin-bottom: 10px;' target='_blank'>{$name}</a> ";
    }
}

// displaying membership email templates
function show_membership_test_email_buttons() {

    global $test_membership_email_class;
    $site_url = site_url();

    foreach ($test_membership_email_class as $class => $name) {
        echo " <a href='{$site_url}/?woocommerce_email_test={$class}' class='button button-primary see-template' style='margin-bottom: 10px;' target='_blank'>{$name}</a> ";
    }
}

function get_test_email_options() {

    $return = array();
    if (get_option("wc_email_test_order_id", "false")) {
        $return['wc_email_test_order_id'] = get_option("wc_email_test_order_id", "false");
    } else {
        $return['wc_email_test_order_id'] = '';
    }

    return $return;

}

// return $email
function filter_rec() {
    global $email;

    return $email;
}

// populating checkbox fields with customer templates
function nrlb_show_customer_test_email_checkboxes() {

    $mailer                   = WC()->mailer(); // load WooCommerce mailer class
    $mails                    = $mailer->get_emails();
    $customer_templates_array = array_slice($mails, 3, 11);
    echo '<ul>';
    foreach ($customer_templates_array as $mail) {
        echo '<li>';
        echo "<input type='checkbox' name='checkbox' value='$mail->id' />";
        echo "$mail->title";
        echo '</li>';
    }
    echo '</ul>';
}


/**
  Check if e-mail address is well-formated.
  @param string $data
  @return string
 */
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}


// sending email templates to an email client
function nrlb_woocommerce_mailer() {
?>

<?php
    global $email;
    $emailErr = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (!empty($_POST["email"])) {
            $email = test_input($_POST["email"]); // check if e-mail address is well-formated

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $emailErr = "<p class='wp-ui-text-notification'>Invalid email format</p>";
            }
        }
    }
?>

<!-- form for sending templates to an email -->
<form id="woocoommerce_test_email_form" name="woocoommerce_test_email_form" method="POST" action="">
  <input type="text" placeholder="Enter email address" class="regular-text" name="email" value="">
  <input class="button-primary" type="submit" name="Send" value="<?php esc_attr_e('Send!'); ?>" />
  <p class="error"><?php echo $emailErr; ?></p>
</form>

<?php
    $mailer                      = WC()->mailer();
    $mails                       = $mailer->get_emails();
    $selected_checkbox_templates = '';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (!empty($_POST["fields"])) {
            $selected_checkbox_templates = json_decode(stripslashes($_POST['fields']), true);
            $selected_order_id           = json_decode(stripslashes($_POST['order_id']), true);
        }

        if (is_array($selected_checkbox_templates)) {
            foreach ($selected_checkbox_templates as $sctemp) {
                foreach ($mails as $mail) {
                    if ($mail->id == $sctemp) {
                        add_filter('woocommerce_email_recipient_' . $mail->id, 'filter_rec');
                        $mail->trigger($selected_order_id);

                        if ($mail->id == 'customer_note') {
                            $mail->trigger(array(
                                'order_id' => $selected_order_id
                            ));
                        }
                    }
                }
            }
            ?>

            <?php if (empty($selected_checkbox_templates)) { // echo message if there is no checboxes selected ?>
             <p class="wp-ui-notification">Please select checkbox</p>
            <?php } ?>

            <?php if (empty($email)) { // echo message if there is no email entered ?>
             <p class="wp-ui-notification">Please enter email</p>
            <?php } ?>

            <?php if (!empty($selected_checkbox_templates) && !empty($email) && !$emailErr) { // echo message if all is entered  ?>
             <span class="wp-ui-highlight">Email sent to: <?php echo $email; ?> </span>
            <?php }
        }
    }
}
