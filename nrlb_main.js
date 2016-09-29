// get selected value from Order ID option
function get_selected_id() {
    var selected_id = jQuery('#wc_email_test_order_id').val();
    if ((selected_id == 'recent') || (selected_id == null)) {
        selected_id = jQuery('#wc_email_test_order_id option:nth-child(2)').val();
    }
    return selected_id;
}

// monitoring for selected checboxes and appending chosen to hidden input
jQuery("#woocoommerce_test_email_form").submit(function(event) {
    var temp = [];
    jQuery('#checkbox_row').find('input[type=checkbox]:checked').each(function() {
        temp.push(jQuery(this).attr('value'));
    });

    var input = jQuery("<input>")
        .attr("type", "hidden")
        .attr("name", "fields").val(JSON.stringify(temp));
    jQuery('#woocoommerce_test_email_form').append(jQuery(input));

    var selected_id = get_selected_id();

    var input_id = jQuery("<input>")
        .attr("type", "hidden")
        .attr("name", "order_id").val(selected_id);
    jQuery('#woocoommerce_test_email_form').append(jQuery(input_id));

});

// see template mod
jQuery('.see-template').each(function() {
    jQuery(this).attr('oldHref', jQuery(this).attr('href'));
});

jQuery('.see-template').on('click', function() {
    var selected_id = get_selected_id();
    var oldHref = jQuery(this).attr('oldHref');
    var newHref = oldHref + '&order_id=' + selected_id;
    jQuery(this).attr('href', newHref);
});
