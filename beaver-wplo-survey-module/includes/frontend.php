<?php
if( isset($settings->my_select_field) && !empty($settings->my_select_field) && (strlen($settings->my_select_field>0)) ){
    print_r(do_shortcode('[wplosurvey_insert_post ids='.$settings->my_select_field.']'));
}
else{
    echo("Choose a WPLO Survey Form in settings popup.<br>Note: Only one survey can be placed per page.");
}
