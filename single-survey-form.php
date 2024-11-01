<?php
/**
 * WP-LO Survey Form CPT Template
 */
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if (isset($shortcodeID)){
    //turn off header and footer
    $HF_off = "off";

    $dir = plugin_dir_url(__FILE__);
    $dir2 = plugin_dir_path(__FILE__);

    wp_enqueue_style( 'wplo-svy-bootstrap4css', $dir . 'assets/css/bootstrapcustom.css', array(), filemtime( $dir2 . 'assets/css/bootstrapcustom.css' ));
    wp_enqueue_style( 'wplodf-font-awesome-free', $dir . 'assets/fonts/css/all.min.css', array(), filemtime( $dir2 . 'assets/fonts/css/all.min.css' ));
    wp_enqueue_style( 'wplo_style1_survey', $dir . 'assets/css/wp-lo-survey-style.css', array(), filemtime( $dir2 . 'assets/css/wp-lo-survey-style.css' ));
    wp_enqueue_style( 'jquery_ui_css', $dir . 'assets/css/jquery-ui.css', array(), filemtime( $dir2 . 'assets/css/jquery-ui.css' ));

}
else {
    $shortcodeID = get_the_ID();
}


if (!isset($HF_off)){
    get_header();
}

$meta_survey_fa_status = get_post_meta( $shortcodeID, 'meta-survey-radio-fa-btn', true );
if ($meta_survey_fa_status == "on"){
    wp_enqueue_style( 'font-awesome-free', 'https://use.fontawesome.com/releases/v5.6.3/css/all.css' );
}


//Don't load luminateExtend.init if WPLO Donate has already done so
if ( !post_type_exists( 'donations' ) ) {
    require_once(plugin_dir_path(__FILE__) . "cnct-set.php");
}
require_once(plugin_dir_path( __FILE__ ) . "svprocess-inc.php");



?>

<?php

$meta_font_size = get_post_meta( $shortcodeID, 'meta-survey-font-size', true );
$df_paragraph_font_size = $meta_font_size;
$df_input_fields_font_size = (floatval($meta_font_size) * 1);
$df_font_size_2_2 = (floatval($meta_font_size) * 2.2);
$df_font_size_1_8 = (floatval($meta_font_size) * 1.8);
$df_font_size_1_6 = (floatval($meta_font_size) * 1.6);
$df_font_size_1_5 = (floatval($meta_font_size) * 1.5);
$df_font_size_1_4 = (floatval($meta_font_size) * 1.4);
$df_font_size_1_3 = (floatval($meta_font_size) * 1.3);
$df_font_size_1_2 = (floatval($meta_font_size) * 1.2);
$df_font_size_1_1 = (floatval($meta_font_size) * 1.1);
$df_font_size_1 = (floatval($meta_font_size) * 1);
$df_font_size_0_9 = (floatval($meta_font_size) * 0.9);
$df_font_size_0_6 = (floatval($meta_font_size) * 0.6);
$wplo_input_padding_x = (floatval($meta_font_size) * 0.5);
$wplo_input_padding_y = (floatval($meta_font_size) * 0.35);

$meta_header_bgimage = get_post_meta( $shortcodeID, 'meta-survey-header-bgimage', true );
$meta_header_bgcolour = get_post_meta( $shortcodeID, 'meta-survey-header-bgcolour', true );

?>
<!-- Load font sizing -->
<style type="text/css">

    :root {
        --wplo-survey-input-padding-x: <?php  if( !empty( $wplo_input_padding_x ) ) {echo esc_attr($wplo_input_padding_x);}?>px!important;
        --wplo-survey-input-padding-y: <?php  if( !empty( $wplo_input_padding_y ) ) {echo esc_attr($wplo_input_padding_y);}?>px!important; }
</style>


<?php $meta_value = get_post_meta( $shortcodeID, 'meta-survey-color', true );
if( !empty( $meta_value ) ) : ?>
<style type="text/css">

    .introTitle, .topFloatTitle{
        color: <?php echo esc_attr($meta_value) ?>!important;
    }
    #svy-wrapper form.wp-svy-form-style .option_levels div ul li input[type=radio] + label {
        color: <?php echo esc_attr($meta_value) ?>;
        border-color: <?php echo esc_attr($meta_value) ?>;
    }
    #svy-wrapper form.wp-svy-form-style .form-control{
        border-color: <?php echo esc_attr($meta_value) ?>;
    }
    #svy-wrapper form.wp-svy-form-style .selectdiv:after{
        color: <?php echo esc_attr($meta_value) ?>;
        font-size: <?php  if( !empty( $df_input_fields_font_size ) ) {echo esc_attr($df_input_fields_font_size);}?>px;
    }
    #svy-wrapper form.wp-svy-form-style input[type=checkbox] + label:before{
        color: <?php echo esc_attr($meta_value) ?>;
        font-size: <?php  if( !empty( $df_font_size_1 ) ) {echo esc_attr($df_font_size_1);}?>px;
    }
    #svy-wrapper form.wp-svy-form-style .input-group .input-group-prepend .input-group-text{
        background-color: <?php echo esc_attr($meta_value) ?>;
        border-color: <?php echo esc_attr($meta_value) ?>;
    }
    #svy-wrapper form.wp-svy-form-style .option_levels div ul li input[type=radio]:checked + label{
        background-color: <?php echo esc_attr($meta_value) ?>;
    }
    #svy-wrapper form.wp-svy-form-style .option_levels div ul li input[type=radio] + label:hover, #svy-wrapper form.wp-svy-form-style .option_levels div ul li input[type=radio] + label:active, #svy-wrapper form.wp-svy-form-style .option_levels div ul li input[type=radio] + label:focus{
        background-color: <?php echo esc_attr($meta_value) ?>;
    }
    #svy-wrapper form.wp-svy-form-style .btn-wp-lo-form{
        background-color: <?php echo esc_attr($meta_value) ?>;
    }

    #svy-wrapper form.wp-svy-form-style #ecard_type_div ul li input[type=radio]:checked + label{
        border-color: <?php echo esc_attr($meta_value) ?>;
    }
    #svy-wrapper form.wp-svy-form-style #ecard_type_div ul li input[type=radio] + label:hover, #svy-wrapper form.wp-svy-form-style #ecard_type_div ul li input[type=radio] + label:active, #svy-wrapper form.wp-svy-form-style #ecard_type_div ul li input[type=radio] + label:focus{
        border-color: <?php echo esc_attr($meta_value) ?>;
    }
    #svy-wrapper form.wp-svy-form-style .btn-wp-lo-form-outline{
        color: <?php echo esc_attr($meta_value) ?>;
        border-color: <?php echo esc_attr($meta_value) ?>;
    }
    #svy-wrapper form.wp-svy-form-style .btn-wp-lo-form-outline:active, #svy-wrapper form.wp-svy-form-style .btn-wp-lo-form-outline:hover, #svy-wrapper form.wp-svy-form-style .btn-wp-lo-form-outline:focus{
        background-color: <?php echo esc_attr($meta_value) ?>;
    }
    #svy-wrapper a{

    }

    @media (max-width:767.98px){
        #svy-wrapper p{
            padding-left:0;
            padding-right:0;
        }
    }

    #svy-wrapper #ecard_message{
        border:2px solid <?php echo esc_attr($meta_value) ?>;
    }

    <?php if (!isset($HF_off)):?>
    .svy-content{
        padding:30px;
    }
    .svy-content  #svy-wrapper{
        margin: 0 auto;
    }
    <?php endif?>


    <?php $meta_value_hover = get_post_meta( $shortcodeID, 'meta-survey-color-hover', true );
    if( !empty( $meta_value_hover ) ) : ?>
            #svy-wrapper form.wp-svy-form-style .btn-wp-lo-form:active, #svy-wrapper form.wp-svy-form-style .btn-wp-lo-form:hover, #svy-wrapper form.wp-svy-form-style .btn-wp-lo-form:focus{
                background-color: <?php echo esc_attr($meta_value_hover) ?>;
            }
        <?php endif ?>

</style>
<?php endif ?>

<style type="text/css">

<?php $meta_value_header_align = get_post_meta( $shortcodeID, 'meta-survey-select-header-title-align', true );
if( !empty( $meta_value_header_align ) ) : ?>
.introTitle, .topTitle{
    text-align: <?php echo esc_attr($meta_value_header_align) ?>;
}
<?php endif ?>


<?php $meta_value = get_post_meta( $shortcodeID, 'meta-survey-select-panel-align', true );
if( !empty( $meta_value ) ) : ?>
    .panel-heading{
        text-align: <?php echo esc_attr($meta_value) ?>;
    }
<?php endif ?>

</style>

<!-- Common Styles and Custom CSS-->
<style type="text/css">
    #svy-wrapper{

    }

    #svy-wrapper form.wp-svy-form-style .form-label-group-float input:not(:placeholder-shown) ~ label{
        letter-spacing: normal;
    }


    /** Longer final submit button text, reduce ls at <350 **/
    @media only screen and (max-width:350px){
        #survey-submit{
            letter-spacing:-1px;
        }
    }

    /** Changeable fonts and colours **/

    <?php

    $meta_font_header = get_post_meta( $shortcodeID, 'meta-survey-font-header', true );
    //Convert double quotes to single if used
    $meta_font_header = str_replace("\"","'",$meta_font_header);

    $meta_font_paragraph = get_post_meta( $shortcodeID, 'meta-survey-font-paragraph', true );
    //Convert double quotes to single if used
    $meta_font_paragraph = str_replace("\"","'",$meta_font_paragraph);

    $meta_primary_colour = get_post_meta( $shortcodeID, 'meta-survey-color', true );
    $meta_primary_colour_hover = get_post_meta( $shortcodeID, 'meta-survey-color-hover', true );

    $meta_button_colour = get_post_meta( $shortcodeID, 'meta-survey-button-color', true );
    $meta_button_colour_hover = get_post_meta( $shortcodeID, 'meta-survey-button-color-hover', true );
    $meta_outline_button_colour = get_post_meta( $shortcodeID, 'meta-survey-outline-button-color', true );
    $meta_header_colour = get_post_meta( $shortcodeID, 'meta-survey-header-color', true );
    $meta_button_radius = get_post_meta( $shortcodeID, 'meta-survey-button-radius', true );


    ?>


    .bootstrapsvyiso h2, .bootstrapsvyiso .h2, .bootstrapsvyiso h1, .bootstrapsvyiso .h1{
        font-size: <?php  if( !empty( $df_font_size_1_8 ) ) {echo esc_attr($df_font_size_1_8);}?>px;
    }


    #svy-wrapper form.wp-svy-form-style input, #svy-wrapper form.wp-svy-form-style select, #svy-wrapper form.wp-svy-form-style textarea, #svy-wrapper form.wp-svy-form-style label{
        font-size: <?php  if( !empty( $df_font_size_1 ) ) {echo esc_attr($df_font_size_1);}?>px!important;
    }

    #svy-wrapper form.wp-svy-form-style label.checkbox-label{
        font-size: <?php  if( !empty( $df_font_size_1 ) ) {echo esc_attr($df_font_size_1);}?>px!important;
    }

    #svy-wrapper form.wp-svy-form-style .form-label-group-float > label, #svy-wrapper form.wp-svy-form-style .form-label-group-float-search > label{
        /*line-height:1.65;*/
    }
    #svy-wrapper form.wp-svy-form-style .input-group input{

    }
    #svy-wrapper form.wp-svy-form-style .form-label-group-float input:not(:placeholder-shown) ~ label{
        font-size:11px;
    }

    #svy-wrapper form.wp-svy-form-style .btn-wp-lo-form{
        font-size: <?php  if( !empty( $df_font_size_1 ) ) {echo esc_attr($df_font_size_1);}?>px;
    }

    .bootstrapsvyiso{
        font-size: <?php  if( !empty( $meta_font_size ) ) {echo esc_attr($meta_font_size);}?>px;
    }
    .bootstrapsvyiso h1, .bootstrapsvyiso h2, .bootstrapsvyiso h3, .bootstrapsvyiso h4, .bootstrapsvyiso h5{
        font-family:<?php  if( !empty( $meta_font_header ) ) {echo _wp_specialchars($meta_font_header,ENT_COMPAT);}?>;
        text-transform:none;
        letter-spacing:normal;
    }
    .introParagraph{
        font-family:<?php  if( !empty( $meta_font_paragraph ) ) {echo _wp_specialchars($meta_font_paragraph,ENT_COMPAT);}?>!important;
        font-size: <?php  if( !empty( $df_font_size_1 ) ) {echo esc_attr($df_font_size_1);}?>px!important;
    }
    .bootstrapsvyiso p {
        font-family:<?php  if( !empty( $meta_font_paragraph ) ) {echo _wp_specialchars($meta_font_paragraph,ENT_COMPAT);}?>!important;
        font-size: <?php  if( !empty( $df_font_size_1 ) ) {echo esc_attr($df_font_size_1);}?>px!important;
        position: relative;
        padding-left:15px;
        padding-right:15px;
    }

    .bootstrapsvyiso .wplo-survey-question::before{
        font-family:<?php  if( !empty( $meta_font_paragraph ) ) {echo _wp_specialchars($meta_font_paragraph,ENT_COMPAT);}?>!important;
        font-size: <?php  if( !empty( $df_font_size_1 ) ) {echo esc_attr($df_font_size_1);}?>px!important;
    }

    .bootstrapsvyiso input, .bootstrapsvyiso select, .bootstrapsvyiso textarea, .bootstrapsvyiso label, .bootstrapsvyiso .form-control{
        font-family:<?php  if( !empty( $meta_font_paragraph ) ) {echo _wp_specialchars($meta_font_paragraph,ENT_COMPAT);}?>!important;
        font-size: <?php  if( !empty( $df_input_fields_font_size ) ) {echo esc_attr($df_input_fields_font_size);}?>px;
    }

    .bootstrapsvyiso .HelpLink {
        font-family:<?php  if( !empty( $meta_font_paragraph ) ) {echo _wp_specialchars($meta_font_paragraph,ENT_COMPAT);}?>;
        font-size: <?php  if( !empty( $meta_font_size ) ) {echo esc_attr($meta_font_size);}?>px;
        letter-spacing:-1.5px;
    }

    .bootstrapsvyiso .panel-heading{
        color: <?php echo($meta_header_colour) ?>!important;
    }

    .bootstrapsvyiso .wplo-header-font{
        font-family:<?php  if( !empty( $meta_font_header ) ) {echo _wp_specialchars($meta_font_header,ENT_COMPAT);}?>;
        text-transform:none;
        letter-spacing:normal;
    }

    .bootstrapsvyiso .wplo-paragraph-font{
        font-family:<?php  if( !empty( $meta_font_paragraph ) ) {echo _wp_specialchars($meta_font_paragraph,ENT_COMPAT);}?>;
        text-transform:none;
        letter-spacing:normal;
    }

    .bootstrapsvyiso i.fa{
        font-size: 28px;
    }

    <?php
    $meta_input_border_width = get_post_meta( $shortcodeID, 'meta-survey-input-border-width', true );
    $meta_input_border_radius = get_post_meta( $shortcodeID, 'meta-survey-input-radius', true );
    ?>

    .bootstrapsvyiso input, .bootstrapsvyiso select, .bootstrapsvyiso textarea{
        border-radius:<?php  if( !empty( $meta_input_border_radius ) ) {echo esc_attr($meta_input_border_radius);}?>!important;
        border-width:<?php  if( !empty( $meta_input_border_width ) ) {echo esc_attr($meta_input_border_width);}?>!important;
    }
    @media only screen and (max-width:767px){
        .bootstrapsvyiso .input-margins-forms{
            padding:0!important;
        }
    }


    /** Buttons **/

    #svy-wrapper form.wp-svy-form-style .btn-wp-lo-form, #svy-wrapper .btn-wp-lo-form-ty {
        padding: 5px 10px;
        border: 2px solid <?php  if( !empty( $meta_button_colour ) ) {echo esc_attr($meta_button_colour);}?>;
        border-radius: <?php  if( !empty( $meta_button_radius ) ) {echo esc_attr($meta_button_radius);}?>;
        color: #fff;
        background: <?php  if( !empty( $meta_button_colour ) ) {echo esc_attr($meta_button_colour);}?>;
        font-weight: normal;
        font-family:<?php  if( !empty( $meta_font_paragraph ) ) {echo _wp_specialchars($meta_font_paragraph,ENT_COMPAT);}?>;
        font-size: <?php  if( !empty( $df_font_size_1_3 ) ) {echo esc_attr($df_font_size_1_3);}?>px;
        white-space:nowrap;
    }
    #svy-wrapper .btn-wp-lo-form-ty{
        padding:10px 15px;
    }
    #svy-wrapper form.wp-svy-form-style .btn-wp-lo-form:active, #svy-wrapper form.wp-svy-form-style .btn-wp-lo-form:hover, #svy-wrapper form.wp-svy-form-style .btn-wp-lo-form:focus, #svy-wrapper .btn-wp-lo-form-ty:active, #svy-wrapper .btn-wp-lo-form-ty:focus, #svy-wrapper .btn-wp-lo-form-ty:hover {
        -webkit-transition: background-color 0.3s, border 0.3s;
        -moz-transition: background-color 0.3s, border 0.3s;
        -ms-transition: background-color 0.3s, border 0.3s;
        -o-transition: background-color 0.3s, border 0.3s;
        transition: background-color 0.3s, border 0.3s;
        background-color: <?php  if( !empty( $meta_button_colour_hover ) ) {echo esc_attr($meta_button_colour_hover);}?>;
        border: 2px solid <?php  if( !empty( $meta_button_colour_hover ) ) {echo esc_attr($meta_button_colour_hover);}?>; }

     #svy-wrapper form.wp-svy-form-style .btn-wp-lo-form-outline {
        color: <?php  if( !empty( $meta_outline_button_colour ) ) {echo esc_attr($meta_outline_button_colour);}?>;
        border: 2px solid <?php  if( !empty( $meta_outline_button_colour ) ) {echo esc_attr($meta_outline_button_colour);}?>;
        border-radius: <?php  if( !empty( $meta_button_radius ) ) {echo esc_attr($meta_button_radius);}?>;
        font-family:<?php  if( !empty( $meta_font_paragraph ) ) {echo _wp_specialchars($meta_font_paragraph,ENT_COMPAT);}?>;
        font-size: <?php  if( !empty( $df_font_size_1_3 ) ) {echo esc_attr($df_font_size_1_3);}?>px;
        background: none;
        padding: 0.50rem 0.7rem; }
    #svy-wrapper form.wp-svy-form-style .btn-wp-lo-form-outline:active, #svy-wrapper form.wp-svy-form-style .btn-wp-lo-form-outline:hover, #svy-wrapper form.wp-svy-form-style .btn-wp-lo-form-outline:focus {
        color: white;
        -webkit-transition: background-color 0.3s, border 0.3s;
        -moz-transition: background-color 0.3s, border 0.3s;
        -ms-transition: background-color 0.3s, border 0.3s;
        -o-transition: background-color 0.3s, border 0.3s;
        transition: background-color 0.3s, border 0.3s;
        border-color: <?php  if( !empty( $meta_outline_button_colur ) ) {echo esc_attr($meta_outline_button_colur);}?>;
        background-color: <?php  if( !empty( $meta_outline_button_colur ) ) {echo esc_attr($meta_outline_button_colur);}?>; }
    #svy-wrapper form.wp-svy-form-style .btn-goback {
        background: none;
        font-weight: normal;
        color: <?php  if( !empty( $meta_primary_colour ) ) {echo esc_attr($meta_primary_colour);}?>;
        border: 2px solid <?php  if( !empty( $meta_primary_colour ) ) {echo esc_attr($meta_primary_colour);}?>;
        border-radius: <?php  if( !empty( $meta_button_radius ) ) {echo esc_attr($meta_button_radius);}?>;
        font-family:<?php  if( !empty( $meta_font_paragraph ) ) {echo _wp_specialchars($meta_font_paragraph,ENT_COMPAT);}?>;
        font-size: <?php  if( !empty( $df_font_size_1_3 ) ) {echo esc_attr($df_font_size_1_3);}?>px;
        padding: 0.50rem 0.6rem; }
    #svy-wrapper form.wp-svy-form-style .btn-goback:active, #svy-wrapper form.wp-svy-form-style .btn-goback:hover, #svy-wrapper form.wp-svy-form-style .btn-goback:focus {
        color: white;
        -webkit-transition: background-color 0.3s, border 0.3s;
        -moz-transition: background-color 0.3s, border 0.3s;
        -ms-transition: background-color 0.3s, border 0.3s;
        -o-transition: background-color 0.3s, border 0.3s;
        transition: background-color 0.3s, border 0.3s;
        border-color: <?php  if( !empty( $meta_primary_colour ) ) {echo esc_attr($meta_primary_colour);}?>;
        background-color: <?php  if( !empty( $meta_primary_colour ) ) {echo esc_attr($meta_primary_colour);}?>; }

    .bootstrapsvyiso .btn-wp-lo-form-outline-sm {
        color: <?php  if( !empty( $meta_outline_button_colour ) ) {echo esc_attr($meta_outline_button_colour);}?>;
        border: 2px solid <?php  if( !empty( $meta_outline_button_colour ) ) {echo esc_attr($meta_outline_button_colour);}?>;
        border-radius: <?php  if( !empty( $meta_button_radius ) ) {echo esc_attr($meta_button_radius);}?>;
        font-family:<?php  if( !empty( $meta_font_header ) ) {echo _wp_specialchars($meta_font_header,ENT_COMPAT);}?>;
        font-size: <?php  if( !empty( $df_font_size_1_1 ) ) {echo esc_attr($df_font_size_1_1);}?>px;
        font-weight:normal;
        padding: 5px;
        text-decoration: none !important; }
    .bootstrapsvyiso .btn-wp-lo-form-outline-sm:active, .bootstrapsvyiso .btn-wp-lo-form-outline-sm:hover, .bootstrapsvyiso .btn-wp-lo-form-outline-sm:focus {
        color: white !important;
        text-decoration: none !important;
        -webkit-transition: background-color 0.3s, border 0.3s;
        -moz-transition: background-color 0.3s, border 0.3s;
        -ms-transition: background-color 0.3s, border 0.3s;
        -o-transition: background-color 0.3s, border 0.3s;
        transition: background-color 0.3s, border 0.3s;
        background-color: <?php  if( !empty( $meta_outline_button_colour ) ) {echo esc_attr($meta_outline_button_colour);}?>;
    }

    /** BS4 Overrides **/

    .bootstrapsvyiso .modal {
        text-align: center;
    }

    .bootstrapsvyiso .modal:before {
        display: inline-block;
        vertical-align: middle;
        content: " ";
        height: 100%;
    }

    .bootstrapsvyiso .modal-dialog {
        display: inline-block;
        text-align: left;
        vertical-align: middle;
    }

    .bootstrapsvyiso .progress{
        height:16px;
        margin-bottom:0;
    }


    /*.bootstrapsvyiso .modal-dialog-centered:before{*/
        /*height:auto;*/
        /*display:inherit;*/
    /*}*/

    /*.modal-backdrop.fade, .modal-backdrop.fade.in {*/
        /*opacity: 0.7 !important;*/
    /*}*/


    /** Floats overrides **/

    #svy-wrapper form.wp-svy-form-style .form-label-group-float input:not(:placeholder-shown) ~ label {
        padding-top: calc(var(--wplo-survey-input-padding-y) / 3);
        padding-bottom: calc(var(--wplo-survey-input-padding-y) / 3);
        font-size: <?php  if( !empty( $df_font_size_0_6 ) ) {echo esc_attr($df_font_size_0_6);}?>px!important;
        line-height:1.2;
        color: #777; }

    #svy-wrapper form.wp-svy-form-style .form-label-group input{
        padding:0.25rem;
    }

    /** FA styling **/
    <?php

    if ($meta_survey_fa_status == "on") :?>

    #svy-wrapper form.wp-svy-form-style input[type=checkbox], #svy-wrapper form.wp-svy-form-style input[type=radio] {
        opacity: 0;
        filter: alpha(opacity=0);
        margin: 0;
        width: 0 !important;
        height: 0 !important; }
    #svy-wrapper form.wp-svy-form-style input[type=checkbox] + label, #svy-wrapper form.wp-svy-form-style input[type=radio] + label {
        font-size: 1.3rem; }
    #svy-wrapper form.wp-svy-form-style input[type=checkbox] + label:hover, #svy-wrapper form.wp-svy-form-style input[type=radio] + label:hover {
        cursor: pointer; }
    #svy-wrapper form.wp-svy-form-style input[type=checkbox] + label:before, #svy-wrapper form.wp-svy-form-style input[type=radio] + label:before {
        font-family: "Font Awesome 5 Free";
        font-weight: 400;
        position: relative;
        color: #6b4890;
        letter-spacing: 0.5em; }
    #svy-wrapper form.wp-svy-form-style input[type=checkbox] + label:before {
        content: "\f0c8";
        letter-spacing: 0.35em; }
    #svy-wrapper form.wp-svy-form-style input[type=checkbox]:checked + label:before {
        content: "\f14a";
        letter-spacing: 0.35em; }

    #svy-wrapper form.wp-svy-form-style input[type=radio] + label:before {
        content: "\f111";
        letter-spacing: 0.35em; }
    #svy-wrapper form.wp-svy-form-style input[type=radio]:checked + label:before{
        content: "\f192";
        letter-spacing: 0.35em; }



    <?php else : ?>

    #svy-wrapper form.wp-svy-form-style input[type=checkbox]{
        width:inherit!important;
        margin-left:0;
        position:relative;
    }
    <?php endif ?>


    /** Question Counter **/
<?php
$meta_survey_counter_status = get_post_meta( $shortcodeID, 'meta-survey-sv-numbered-label', true );
if ($meta_survey_counter_status == "true") :?>
    #svy-wrapper form.wp-svy-form-style {
        counter-reset: wplo-survey-counter;
    }

    #svy-wrapper form.wp-svy-form-style .wplo-survey-question::before{
        counter-increment: wplo-survey-counter;
        content: "" counter(wplo-survey-counter) ". ";
    }

    #svy-wrapper form.wp-svy-form-style .wplo-survey-question label, #svy-wrapper form.wp-svy-form-style .wplo-survey-question .form-label-group-float, #svy-wrapper form.wp-svy-form-style .wplo-survey-question .form-label-group-float .selectdiv{
        display:inline;
    }

<?php endif ?>

    /** Nice looking selectboxes **/
    #svy-wrapper .selectdiv select{
        -webkit-appearance: none;
        -moz-appearance: none;
        -o-appearance: none;
        appearance: none;
    }
    #svy-wrapper .selectdiv select::-ms-expand {
        display: none;
    }
    #svy-wrapper .selectdiv select {
        background-image:
                linear-gradient(45deg, transparent 50%, gray 50%),
                linear-gradient(135deg, gray 50%, transparent 50%),
                linear-gradient(to right, #ccc, #ccc);
        background-position:
                calc(100% - 18px) calc(1em),
                calc(100% - 13px) calc(1em),
                calc(100% - 2em) 0;
        background-size:
                5px 5px,
                5px 5px,
                1px 100%;
        background-repeat: no-repeat;
    }

    #svy-wrapper .selectdiv select:focus {
        background-image:
                linear-gradient(45deg, green 50%, transparent 50%),
                linear-gradient(135deg, transparent 50%, green 50%),
                linear-gradient(to right, #ccc, #ccc);
        background-position:
                calc(100% - 13px) 1em,
                calc(100% - 18px) 1em,
                calc(100% - 2em) 0;
        background-size:
                5px 5px,
                5px 5px,
                1px 100%;
        background-repeat: no-repeat;
        border-color: green;
        outline: 0;
    }

    /** ConsRegInfo styling **/

    .consRegInfoLast{
        margin-bottom: 1.5rem !important;
    }


    /** Datepicker styling **/

    #svy-wrapper .ui-datepicker select.ui-datepicker-month, #svy-wrapper .ui-datepicker select.ui-datepicker-year{
        font-weight: normal;
    }


    /** Firefox general fixes **/
    #svy-wrapper input:required {
        box-shadow: none;
    }

    /** IE11 fixes **/

    @media screen and (-ms-high-contrast: active), screen and (-ms-high-contrast: none) {
        #svy-wrapper div.selectdiv select.form-control:not([size]):not([multiple]), #svy-wrapper select#cons_state{
            /*height:54px!important;*/

        }
        #svy-wrapper form.wp-svy-form-style .form-label-group-float input{
            height:auto!important;
        }
    }

    /** Safari fixes **/

    @media not all and (min-resolution:.001dpcm) { @media {
        #svy-wrapper .row:before,
        #svy-wrapper .row:after {
            content: normal;
        }
    }}

    <?php $meta_custom_css = get_post_meta( $shortcodeID, 'meta-survey-custom-css', true ); if( !empty( $meta_custom_css ) ) {echo esc_attr($meta_custom_css);} ?>

</style>



<div class="svy-content d-flex">





    <div class="bootstrapsvyiso <?php $meta_value = get_post_meta( $shortcodeID, 'meta-survey-form-shadow', true ); if( !empty( $meta_value ) ) {echo esc_attr($meta_value);} ?>" id="svy-wrapper">

        <!-- Intro Paragraph Wrapper -->

        <?php
        if (!isset($HF_off)){

            $meta_survey_lo_title = get_post_meta( $shortcodeID, 'meta-survey-sv-surveyname-label', true );
            echo('<h1 class="entry-title mt-5 font-weight-bold">' . $meta_survey_lo_title . '</h1>');
        }
        ?>

        <div id="introSection" class="mb-0">
            <div class="container-fluid p-0">
                <div class="row justify-content-center w-100">
                    <div class="w-100">

                        <div class="introParagraph">
                            <?php $meta_value = get_post_meta( $shortcodeID, '_post_note', true );
                            if( !empty( $meta_value ) ) {echo esc_attr($meta_value);} ?>
                        </div>

                    </div>
                </div>
            </div>
        </div>


        <div id="feedbackContainer" class="mb-1" style="visibility:hidden;position:absolute;left:-30000px;">
            <div class="container-fluid p-0">
                <div class="row justify-content-center w-100">
                    <div class="w-100">
                        <h1 id="feedbackTitle" class="d-flex justify-content-center align-items-center flex-column">

                        </h1>

                    </div>
                </div>
            </div>
        </div>


        <!-- /Intro Paragraph Wrapper -->


        <div class="container-fluid mt-0 mb-3 donation-form-content">


            <!-- Alert Wrapper -->

            <div id="surveyAlertWrapper" style="margin-top:2rem; margin-bottom:2rem; display:none;"></div>

            <!-- /Alert Wrapper -->


            <!-- Survey Form -->

            <div class="wp-lo-svy-wrapper">

                <!-- Spinning Modal -->


                <div class="modal" tabindex="-1" role="dialog" id="wploSvy_ajaxModal">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-body text-center" style="color:<?php $meta_ajax_colour = get_post_meta( $shortcodeID, 'meta-survey-color', true ); echo esc_attr($meta_ajax_colour); ?>">
                                <i class="fa fa-spinner fa-pulse"></i>
                            </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->



                <?php

                $key = "POSqAny0+Glb/51sqmn6gM11A4lrJUzEJ9sYTGFKu94=";

                function my_encrypt($data, $key) {
                    // Remove the base64 encoding from our key
                    $encryption_key = base64_decode($key);
                    // Generate an initialization vector
                    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
                    // Encrypt the data using AES 256 encryption in CBC mode using our encryption key and initialization vector.
                    $encrypted = openssl_encrypt($data, 'aes-256-cbc', $encryption_key, OPENSSL_ZERO_PADDING, $iv);
                    // The $iv is just as important as the key for decrypting, so save it with our encrypted data using a unique separator (::)
                    return $encrypted . '::' . base64_encode($iv);
                }

                function my_decrypt($data, $key) {
                    // Remove the base64 encoding from our key
                    $encryption_key = base64_decode($key);
                    // To decrypt, split the encrypted data from our IV - our unique separator used was "::"
                    list($encrypted_data, $iv) = explode('::', $data, 2);
                    return openssl_decrypt($encrypted_data, 'aes-256-cbc', $encryption_key, 0, base64_decode($iv));
                }

                ?>

                <form class="wp-svy-form-style" id="wp-svy-form" onSubmit="return WPLOSURVEY_validate_survey(event)">

                    <input type="hidden" name="formLoaded9lb4TkY0" value="<?php echo my_encrypt(time(),$key) ?>">

                    <input type="hidden" name="method" value="submitSurvey">

                    <input type="hidden" name="survey_id" id="survey_id" value="<?php

                    // Retrieves stored value from database
                    $meta_value_svy_setup = get_post_meta( $shortcodeID, 'meta-survey-svy-setup', true );
                    // Checks and displays the retrieved value
                    if( !empty( $meta_value_svy_setup ) ) {echo esc_attr($meta_value_svy_setup);}

                    ?>">



                        <label class="checkthis">Leave this field empty:</label>
                        <input class="checkthis" type="text" name="phone_number_95231er" autocomplete="off">


                        <?php
                        $meta_survey_progress_bar = get_post_meta( $shortcodeID, 'meta-survey-radio-progress-bar', true );
                        $meta_survey_progress_bar_goal = get_post_meta( $shortcodeID, 'meta-survey-progress-bar-goal', true );
                        $meta_survey_progress_bar_question_id = get_post_meta( $shortcodeID, 'meta-survey-sv-question-0-id', true );
                        $meta_survey_progress_bar_color = get_post_meta( $shortcodeID, 'meta-survey-progress-bar-color', true );


                        $meta_sv_intro_label = get_post_meta( $shortcodeID, 'meta-survey-sv-introduction-label', true );
                        ?>

                        <?php if (!empty($meta_sv_intro_label)) : ?>

                        <div class="container-fluid">
                            <div class="row justify-content-center">
                                <div class="col-md-12">
                                    <div class="introParagraph text-left mb-3">
                                        <?php if( !empty( $meta_sv_intro_label ) ) {echo $meta_sv_intro_label;} ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php endif ?>

                        <?php if ($meta_survey_progress_bar == "on") : ?>

                            <script type="text/javascript">

                                ready(function() {


                                    window.getSurveyTotalCallback = {
                                        error: function(data) {
                                            // console.log(data);
                                        },
                                        success: function(data) {
                                            // console.log(data);
                                            var responseString = jQuery(data.getTagInfoResponse.preview).text();
                                            var cleanResponseString = responseString.replace(/\D/g,'');
                                            console.log(cleanResponseString);

                                            var goalResponses = <?php echo esc_js($meta_survey_progress_bar_goal)?>;

                                            var widthPercent = cleanResponseString/goalResponses *100;
                                            console.log(widthPercent);

                                            jQuery('#currentResultsID').text(cleanResponseString);
                                            jQuery('#WPLOsurveyProgressBar').width(widthPercent + '%');

                                        }

                                    };

                                    luminateExtend.api.request({
                                        api: 'CRContentAPI',
                                        callback: getSurveyTotalCallback,
                                        data: 'method=getTagInfo&content=[[S28:REPORTING:<?php echo esc_js($meta_value_svy_setup) ?>:<?php echo esc_js($meta_survey_progress_bar_question_id) ?>]]',
                                        requiresAuth: "true"
                                    });


                                });


                            </script>


                        <div class="progress">
                            <div class="progress-bar" id="WPLOsurveyProgressBar" role="progressbar" style="width: 0;background-color:<?php echo esc_attr($meta_survey_progress_bar_color)?>;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="d-flex justify-content-between mt-2 mb-5"><div><span class="font-weight-bold" id="currentResultsID"></span> Supporters</div><div><span class="font-weight-bold" id="goalResultsID"><?php echo esc_attr($meta_survey_progress_bar_goal) ?></span> Goal</div></div>

                        <?php endif ?>

                        <?php
                        $meta_value_q1 = get_post_meta( $shortcodeID, 'meta-survey-sv-question-0-type', true );
                        // Checks and displays the retrieved value
//                        if( !empty( $meta_value_q1 ) ) {echo $meta_value_q1;}

                        $meta_sv_count_label = get_post_meta( $shortcodeID, 'meta-survey-sv-count-label', true );

                        $meta_survey_input_single_line = get_post_meta( $shortcodeID, 'meta-survey-input-single-line', true );
                        $meta_survey_input_justify = get_post_meta( $shortcodeID, 'meta-survey-input-justify', true );

                        $x = 0;


                        //Open row for input items
                        echo('<div ' . (get_post_meta( $shortcodeID, 'meta-survey-radio-email-top-btn', true ) == "on" ? 'style="display:flex;flex-direction:column;align-items:center;"' : '' ) . ' class="row '. ($meta_survey_input_justify == "center" ? 'justify-content-center' : '') . '">');

                        while($x <= $meta_sv_count_label) {

                            $i = $x;

                            $question = [];
                            $subquestion = [];
                            //Iterate registration questions (ConsQuestion)
                            if (!empty(get_post_meta( $shortcodeID, 'meta-survey-sv-question-' . $i . '-type', true ))) {
//                                print_r(get_post_meta( $shortcodeID, 'meta-survey-sv-question-' . $i . '-type'));
                                $question[$i]['type'] = get_post_meta( $shortcodeID, 'meta-survey-sv-question-' . $i . '-type', true );
                                $question[$i]['text'] = get_post_meta( $shortcodeID, 'meta-survey-sv-question-' . $i . '-text', true );
                                $question[$i]['id'] = get_post_meta( $shortcodeID, 'meta-survey-sv-question-' . $i . '-id', true );
                                $question[$i]['required'] = get_post_meta( $shortcodeID, 'meta-survey-sv-question-' . $i . '-required', true );

                                if ($question[$i]['type'] !== null || $question[$i]['text'] !== null || $question[$i]['id'] !== null || $question[$i]['required'] !== null) {
    //                                echo($question[$i]['type']);
                                    echo('<p class="mb-3 wplo-survey-question w-100" id="question-sentence-' . $i . '">' . $question[$i]['text'] . '</p>');
    //                                echo($question[$i]['id']);
    //                                echo($question[$i]['required']);
                                }

                                $i_2 = 0;


                                //Open row for input items
//                                echo('<div ' . (get_post_meta( $shortcodeID, 'meta-survey-radio-email-top-btn', true ) == "on" ? 'style="display:flex;flex-direction:column;align-items:center;"' : '' ) . ' class="mb-4 row '. ($meta_survey_input_justify == "center" ? 'justify-content-center' : '') . '">');
                                while (!empty(get_post_meta( $shortcodeID, 'meta-survey-sv-question-' . $i . '-fieldName-' . $i_2, true ))) {

                                    $consRegInfoLast = null;
                                    if (empty(get_post_meta( $shortcodeID, 'meta-survey-sv-question-' . $i . '-fieldName-' . ($i_2 + 1), true ))){
                                        $consRegInfoLast = "consRegInfoLast";
                                    }
                                    else{
                                        $consRegInfoLast = "";
                                    }


                                    // Add a new array for each iteration
                                    $subquestion[$i]['label-' . $i_2] = get_post_meta( $shortcodeID, 'meta-survey-sv-question-' . $i . '-label-' . $i_2, true );
                                    $subquestion[$i]['fieldName-' . $i_2] = get_post_meta( $shortcodeID, 'meta-survey-sv-question-' . $i . '-fieldName-' . $i_2, true );
                                    $subquestion[$i]['fieldStatus-' . $i_2] = get_post_meta( $shortcodeID, 'meta-survey-sv-question-' . $i . '-fieldStatus-' . $i_2, true );
                                    $subquestion[$i]['fieldValues-' . $i_2] = get_post_meta( $shortcodeID, 'meta-survey-sv-question-' . $i . '-fieldValues-' . $i_2, true );
                                    $cur_field_status = $subquestion[$i]['fieldStatus-' . $i_2];

//                                    print_r($subquestion[$i]['fieldName-' . $i_2] = get_post_meta( $shortcodeID, 'meta-survey-sv-question-' . $i . '-fieldName-' . $i_2, true );

                                    if ((($subquestion[$i]['label-' . $i_2] !== null ) || $subquestion[$i]['fieldName-' . $i_2] !== null || $subquestion[$i]['fieldStatus-' . $i_2] !== null) && (empty($subquestion[$i]['fieldValues-' . $i_2]))){


                                        //If for checkbox items
                                        if(($subquestion[$i]['fieldName-' . $i_2] === "cons_postal_opt_in") || ($subquestion[$i]['fieldName-' . $i_2] === "cons_email_opt_in")){
                                            echo('
                                            <div class="input-margins-forms ' . $consRegInfoLast . ' mb-3 col-sm-12 ' . ($meta_survey_input_single_line == "single" ? '' : 'col-md-12') . ' ">
                                                <div class="checkbox"><input id="' . ($subquestion[$i]['fieldName-' . $i_2]) . '" name="' . ($subquestion[$i]['fieldName-' . $i_2]) . '" type="checkbox" ' .  ($cur_field_status == "DEFAULT" ? 'checked="checked"' : '') .  ' value="true">
                                                    <label for="' . ($subquestion[$i]['fieldName-' . $i_2]) . '">' . ($subquestion[$i]['label-' . $i_2]) . '</label></div>
                                            </div>
                                        ');
                                        }
                                        //Otherwise normal input
                                        else{
                                            echo('
                                            <div ' . (get_post_meta( $shortcodeID, 'meta-survey-radio-email-top-btn', true ) == "on" ? ($subquestion[$i]['fieldName-' . $i_2] == "cons_email" ? 'style="order:0;"' : 'style="order:2"' ) : '' ) . 'class="input-margins-forms ' . $consRegInfoLast . ' col-sm-12 ' . ($meta_survey_input_single_line == "single" ? '' : 'col-md-6') . ' ">
                                                <div class="form-label-group-float"><input id="' . ($subquestion[$i]['fieldName-' . $i_2]) . '" class="form-control" ' . (($subquestion[$i]['fieldName-' . $i_2] == "cons_zip_code") ? 'pattern="\d{5}(?:-\d{4})?|[a-zA-Z]\d[a-zA-Z] ?\d[a-zA-Z]\d"' : '' ) .' name="' . ($subquestion[$i]['fieldName-' . $i_2]) . '" '. ($subquestion[$i]['fieldName-' . $i_2] == "cons_email" ? 'type="email"' : 'type="text"') . ' placeholder="' . ($subquestion[$i]['label-' . $i_2]) .'" ' . ($cur_field_status == "REQUIRED" ? 'required="required"' : '') . '>
                                                    <label class="control-label" for="' . ($subquestion[$i]['fieldName-' . $i_2]) . '">' . ($subquestion[$i]['label-' . $i_2]) . ($cur_field_status == "REQUIRED" ? ' *' : '') . '</label></div>
                                            </div>
                                        ');
                                        }
    //                                    echo($subquestion[$i]['label-' . $i_2]);
    //                                    echo($subquestion[$i]['fieldName-' . $i_2]);
    //                                    echo($subquestion[$i]['fieldStatus-' . $i_2]);
    //                                    echo($subquestion[$i]['fieldValues-' . $i_2]);
                                    }
                                    elseif (!empty($subquestion[$i]['fieldValues-' . $i_2])){


                                        $optionsElementsHtml = "";
                                        $lines = explode(PHP_EOL, $subquestion[$i]['fieldValues-' . $i_2]);
                                        $options_csv_array = array();
                                        foreach ($lines as $line) {
                                            $options_csv_array = str_getcsv($line);
                                        }

                                        if ($subquestion[$i]['fieldName-' . $i_2] === "cons_country"){

                                        }
                                        else {
                                            $optionsElementsHtml .= '<option disabled="disabled" selected="selected" value="">Select ' . $subquestion[$i]['label-' . $i_2] . ($cur_field_status == "REQUIRED" ? ' *' : '') . '</option>';
                                        }

                                        //Email format path
                                        if ($subquestion[$i]['fieldName-' . $i_2] === "cons_email_format"){
                                            $i_emailFormat = 1;
                                            foreach ($options_csv_array as $optionElement ){
                                                $optionsElementsHtml .= '<option value="' . $i_emailFormat . '">'. $optionElement .'</option>';
                                                $i_emailFormat++;
                                            }
                                        }
                                        //Normal path
                                        else {
                                            foreach ($options_csv_array as $i_c => $optionElement ){
                                                if ($subquestion[$i]['fieldName-' . $i_2] === "cons_country"){
                                                    if ($i_c < 1){
                                                        $optionsElementsHtml .= '<option value="' . $optionElement . '" selected="selected">'. $optionElement .'</option>';
                                                    }
                                                    else{
                                                        $optionsElementsHtml .= '<option value="' . $optionElement . '">'. $optionElement .'</option>';
                                                    }
                                                }
                                                else{
                                                    $optionsElementsHtml .= '<option value="' . $optionElement . '">'. $optionElement .'</option>';
                                                }
                                            }
                                        }


                                        echo('
                                            <div class="input-margins-forms ' . $consRegInfoLast . ' col-sm-12 ' . ($meta_survey_input_single_line == "single" ? '' : 'col-md-6') . ' ">
                                                <div class="form-label-group-float">
                                                <select id="' . ($subquestion[$i]['fieldName-' . $i_2]) . '" class="form-control" name="' . ($subquestion[$i]['fieldName-' . $i_2]) . '" ' . ($cur_field_status == "REQUIRED" ? 'required="required"' : '') . '>'
                                                . $optionsElementsHtml .
                                                '</select>
                                                </div>
                                            </div>
                                        ');
                                    }


                                    //Check for cons_phone to run cleave after input is created
                                    if(($subquestion[$i]['fieldName-' . $i_2] === "cons_phone")){
                                        ?>
                                        <script type="text/javascript">
                                        jQuery(document).ready(function(){
                                            (function(){function l(l,n){var u,t=l.split("."),e=P;t[0]in e||!e.execScript||e.execScript("var "+t[0]);for(;t.length&&(u=t.shift());)t.length||void 0===n?e=e[u]?e[u]:e[u]={}:e[u]=n}function n(l,n){function u(){}u.prototype=n.prototype,l.M=n.prototype,l.prototype=new u,l.prototype.constructor=l,l.N=function(l,u,t){for(var e=Array(arguments.length-2),r=2;r<arguments.length;r++)e[r-2]=arguments[r];return n.prototype[u].apply(l,e)}}function u(l,n){null!=l&&this.a.apply(this,arguments)}function t(l){l.b=""}function e(l,n){return l>n?1:l<n?-1:0}function r(l,n){this.b=l,this.a={};for(var u=0;u<n.length;u++){var t=n[u];this.a[t.b]=t}}function i(l){return function(l,n){l.sort(n||e)}(l=function(l){var n,u=[],t=0;for(n in l)u[t++]=l[n];return u}(l.a),function(l,n){return l.b-n.b}),l}function d(l,n){switch(this.b=l,this.g=!!n.v,this.a=n.c,this.i=n.type,this.h=!1,this.a){case K:case Y:case J:case L:case O:case U:case F:this.h=!0}this.f=n.defaultValue}function a(){this.a={},this.f=this.j().a,this.b=this.g=null}function o(l,n){var u=l.a[n];if(null==u)return null;if(l.g){if(!(n in l.b)){var t=l.g,e=l.f[n];if(null!=u)if(e.g){for(var r=[],i=0;i<u.length;i++)r[i]=t.b(e,u[i]);u=r}else u=t.b(e,u);return l.b[n]=u}return l.b[n]}return u}function s(l,n,u){var t=o(l,n);return l.f[n].g?t[u||0]:t}function f(l,n){var u;if(null!=l.a[n])u=s(l,n,void 0);else l:{if(void 0===(u=l.f[n]).f){var t=u.i;if(t===Boolean)u.f=!1;else if(t===Number)u.f=0;else{if(t!==String){u=new t;break l}u.f=u.h?"0":""}}u=u.f}return u}function p(l,n){return l.f[n].g?null!=l.a[n]?l.a[n].length:0:null!=l.a[n]?1:0}function c(l,n,u){l.a[n]=u,l.b&&(l.b[n]=u)}function h(l,n){var u,t=[];for(u in n)0!=u&&t.push(new d(u,n[u]));return new r(l,t)}function g(){a.call(this)}function m(){a.call(this)}function b(){a.call(this)}function y(){}function v(){}function S(){}function _(){this.a={}}function w(l){return 0==l.length||nl.test(l)}function A(l,n){if(null==n)return null;n=n.toUpperCase();var u=l.a[n];if(null==u){if(null==(u=z[n]))return null;u=(new S).a(b.j(),u),l.a[n]=u}return u}function x(l){return null==(l=Z[l])?"ZZ":l[0]}function B(l){this.H=RegExp(" "),this.C="",this.m=new u,this.w="",this.i=new u,this.u=new u,this.l=!0,this.A=this.o=this.F=!1,this.G=_.b(),this.s=0,this.b=new u,this.B=!1,this.h="",this.a=new u,this.f=[],this.D=l,this.J=this.g=C(this,this.D)}function C(l,n){var u;if(null!=n&&isNaN(n)&&n.toUpperCase()in z){if(null==(u=A(l.G,n)))throw Error("Invalid region code: "+n);u=f(u,10)}else u=0;return null!=(u=A(l.G,x(u)))?u:ul}function M(l){for(var n=l.f.length,u=0;u<n;++u){var e,r=l.f[u],i=f(r,1);if(l.w==i)return!1;e=l;var d=f(o=r,1);if(-1!=d.indexOf("|"))e=!1;else{var a;d=(d=d.replace(tl,"\\d")).replace(el,"\\d"),t(e.m),a=e;var o=f(o,2),p="999999999999999".match(d)[0];p.length<a.a.b.length?a="":a=(a=p.replace(new RegExp(d,"g"),o)).replace(RegExp("9","g")," "),0<a.length?(e.m.a(a),e=!0):e=!1}if(e)return l.w=i,l.B=il.test(s(r,4)),l.s=0,!0}return l.l=!1}function N(l,n){for(var u=[],t=n.length-3,e=l.f.length,r=0;r<e;++r){var i=l.f[r];0==p(i,3)?u.push(l.f[r]):(i=s(i,3,Math.min(t,p(i,3)-1)),0==n.search(i)&&u.push(l.f[r]))}l.f=u}function D(l){return l.l=!0,l.A=!1,l.f=[],l.s=0,t(l.m),l.w="",I(l)}function G(l){for(var n=l.a.toString(),u=l.f.length,t=0;t<u;++t){var e=l.f[t],r=f(e,1);if(new RegExp("^(?:"+r+")$").test(n))return l.B=il.test(s(e,4)),j(l,n=n.replace(new RegExp(r,"g"),s(e,2)))}return""}function j(l,n){var u=l.b.b.length;return l.B&&0<u&&" "!=l.b.toString().charAt(u-1)?l.b+" "+n:l.b+n}function I(l){var n=l.a.toString();if(3<=n.length){for(var u=l.o&&0==l.h.length&&0<p(l.g,20)?o(l.g,20)||[]:o(l.g,19)||[],t=u.length,e=0;e<t;++e){var r=u[e];0<l.h.length&&w(f(r,4))&&!s(r,6)&&null==r.a[5]||(0!=l.h.length||l.o||w(f(r,4))||s(r,6))&&rl.test(f(r,2))&&l.f.push(r)}return N(l,n),0<(n=G(l)).length?n:M(l)?V(l):l.i.toString()}return j(l,n)}function V(l){var n=l.a.toString(),u=n.length;if(0<u){for(var t="",e=0;e<u;e++)t=T(l,n.charAt(e));return l.l?j(l,t):l.i.toString()}return l.b.toString()}function $(l){var n,u=l.a.toString(),e=0;return 1!=s(l.g,10)?n=!1:n="1"==(n=l.a.toString()).charAt(0)&&"0"!=n.charAt(1)&&"1"!=n.charAt(1),n?(e=1,l.b.a("1").a(" "),l.o=!0):null!=l.g.a[15]&&(n=new RegExp("^(?:"+s(l.g,15)+")"),null!=(n=u.match(n))&&null!=n[0]&&0<n[0].length&&(l.o=!0,e=n[0].length,l.b.a(u.substring(0,e)))),t(l.a),l.a.a(u.substring(e)),u.substring(0,e)}function R(l){var n=l.u.toString(),u=new RegExp("^(?:\\+|"+s(l.g,11)+")");return null!=(u=n.match(u))&&null!=u[0]&&0<u[0].length&&(l.o=!0,u=u[0].length,t(l.a),l.a.a(n.substring(u)),t(l.b),l.b.a(n.substring(0,u)),"+"!=n.charAt(0)&&l.b.a(" "),!0)}function E(l){if(0==l.a.b.length)return!1;var n,e=new u;l:{if(0!=(n=l.a.toString()).length&&"0"!=n.charAt(0))for(var r,i=n.length,d=1;3>=d&&d<=i;++d)if((r=parseInt(n.substring(0,d),10))in Z){e.a(n.substring(d)),n=r;break l}n=0}return 0!=n&&(t(l.a),l.a.a(e.toString()),"001"==(e=x(n))?l.g=A(l.G,""+n):e!=l.D&&(l.g=C(l,e)),l.b.a(""+n).a(" "),l.h="",!0)}function T(l,n){if(0<=(e=l.m.toString()).substring(l.s).search(l.H)){var u=e.search(l.H),e=e.replace(l.H,n);return t(l.m),l.m.a(e),l.s=u,e.substring(0,l.s+1)}return 1==l.f.length&&(l.l=!1),l.w="",l.i.toString()}var P=this;u.prototype.b="",u.prototype.set=function(l){this.b=""+l},u.prototype.a=function(l,n,u){if(this.b+=String(l),null!=n)for(var t=1;t<arguments.length;t++)this.b+=arguments[t];return this},u.prototype.toString=function(){return this.b};var F=1,U=2,K=3,Y=4,J=6,L=16,O=18;a.prototype.set=function(l,n){c(this,l.b,n)},a.prototype.clone=function(){var l=new this.constructor;return l!=this&&(l.a={},l.b&&(l.b={}),function l(n,u){for(var t=i(n.j()),e=0;e<t.length;e++){var r=(a=t[e]).b;if(null!=u.a[r]){n.b&&delete n.b[a.b];var d=11==a.a||10==a.a;if(a.g)for(var a=o(u,r)||[],s=0;s<a.length;s++){var f=n,p=r,h=d?a[s].clone():a[s];f.a[p]||(f.a[p]=[]),f.a[p].push(h),f.b&&delete f.b[p]}else a=o(u,r),d?(d=o(n,r))?l(d,a):c(n,r,a.clone()):c(n,r,a)}}}(l,this)),l},n(g,a);var H=null;n(m,a);var q=null;n(b,a);var X=null;g.prototype.j=function(){var l=H;return l||(H=l=h(g,{0:{name:"NumberFormat",I:"i18n.phonenumbers.NumberFormat"},1:{name:"pattern",required:!0,c:9,type:String},2:{name:"format",required:!0,c:9,type:String},3:{name:"leading_digits_pattern",v:!0,c:9,type:String},4:{name:"national_prefix_formatting_rule",c:9,type:String},6:{name:"national_prefix_optional_when_formatting",c:8,defaultValue:!1,type:Boolean},5:{name:"domestic_carrier_code_formatting_rule",c:9,type:String}})),l},g.j=g.prototype.j,m.prototype.j=function(){var l=q;return l||(q=l=h(m,{0:{name:"PhoneNumberDesc",I:"i18n.phonenumbers.PhoneNumberDesc"},2:{name:"national_number_pattern",c:9,type:String},9:{name:"possible_length",v:!0,c:5,type:Number},10:{name:"possible_length_local_only",v:!0,c:5,type:Number},6:{name:"example_number",c:9,type:String}})),l},m.j=m.prototype.j,b.prototype.j=function(){var l=X;return l||(X=l=h(b,{0:{name:"PhoneMetadata",I:"i18n.phonenumbers.PhoneMetadata"},1:{name:"general_desc",c:11,type:m},2:{name:"fixed_line",c:11,type:m},3:{name:"mobile",c:11,type:m},4:{name:"toll_free",c:11,type:m},5:{name:"premium_rate",c:11,type:m},6:{name:"shared_cost",c:11,type:m},7:{name:"personal_number",c:11,type:m},8:{name:"voip",c:11,type:m},21:{name:"pager",c:11,type:m},25:{name:"uan",c:11,type:m},27:{name:"emergency",c:11,type:m},28:{name:"voicemail",c:11,type:m},29:{name:"short_code",c:11,type:m},30:{name:"standard_rate",c:11,type:m},31:{name:"carrier_specific",c:11,type:m},33:{name:"sms_services",c:11,type:m},24:{name:"no_international_dialling",c:11,type:m},9:{name:"id",required:!0,c:9,type:String},10:{name:"country_code",c:5,type:Number},11:{name:"international_prefix",c:9,type:String},17:{name:"preferred_international_prefix",c:9,type:String},12:{name:"national_prefix",c:9,type:String},13:{name:"preferred_extn_prefix",c:9,type:String},15:{name:"national_prefix_for_parsing",c:9,type:String},16:{name:"national_prefix_transform_rule",c:9,type:String},18:{name:"same_mobile_and_fixed_line_pattern",c:8,defaultValue:!1,type:Boolean},19:{name:"number_format",v:!0,c:11,type:g},20:{name:"intl_number_format",v:!0,c:11,type:g},22:{name:"main_country_for_code",c:8,defaultValue:!1,type:Boolean},23:{name:"leading_digits",c:9,type:String},26:{name:"leading_zero_possible",c:8,defaultValue:!1,type:Boolean}})),l},b.j=b.prototype.j,y.prototype.a=function(l){throw new l.b,Error("Unimplemented")},y.prototype.b=function(l,n){if(11==l.a||10==l.a)return n instanceof a?n:this.a(l.i.prototype.j(),n);if(14==l.a){if("string"==typeof n&&k.test(n)){var u=Number(n);if(0<u)return u}return n}if(!l.h)return n;if((u=l.i)===String){if("number"==typeof n)return String(n)}else if(u===Number&&"string"==typeof n&&("Infinity"===n||"-Infinity"===n||"NaN"===n||k.test(n)))return Number(n);return n};var k=/^-?[0-9]+$/;n(v,y),v.prototype.a=function(l,n){var u=new l.b;return u.g=this,u.a=n,u.b={},u},n(S,v),S.prototype.b=function(l,n){return 8==l.a?!!n:y.prototype.b.apply(this,arguments)},S.prototype.a=function(l,n){return S.M.a.call(this,l,n)};var Z={1:"US AG AI AS BB BM BS CA DM DO GD GU JM KN KY LC MP MS PR SX TC TT VC VG VI".split(" ")},z={AG:[null,[null,null,"(?:268|[58]\\d\\d|900)\\d{7}",null,null,null,null,null,null,[10],[7]],[null,null,"268(?:4(?:6[0-38]|84)|56[0-2])\\d{4}",null,null,null,"2684601234",null,null,null,[7]],[null,null,"268(?:464|7(?:1[3-9]|2\\d|3[246]|64|[78][0-689]))\\d{4}",null,null,null,"2684641234",null,null,null,[7]],[null,null,"8(?:00|33|44|55|66|77|88)[2-9]\\d{6}",null,null,null,"8002123456"],[null,null,"900[2-9]\\d{6}",null,null,null,"9002123456"],[null,null,null,null,null,null,null,null,null,[-1]],[null,null,"5(?:00|2[12]|33|44|66|77|88)[2-9]\\d{6}",null,null,null,"5002345678"],[null,null,"26848[01]\\d{4}",null,null,null,"2684801234",null,null,null,[7]],"AG",1,"011","1",null,null,"1",null,null,null,null,null,[null,null,"26840[69]\\d{4}",null,null,null,"2684061234",null,null,null,[7]],null,"268",[null,null,null,null,null,null,null,null,null,[-1]],[null,null,null,null,null,null,null,null,null,[-1]],null,null,[null,null,null,null,null,null,null,null,null,[-1]]],AI:[null,[null,null,"(?:264|[58]\\d\\d|900)\\d{7}",null,null,null,null,null,null,[10],[7]],[null,null,"2644(?:6[12]|9[78])\\d{4}",null,null,null,"2644612345",null,null,null,[7]],[null,null,"264(?:235|476|5(?:3[6-9]|8[1-4])|7(?:29|72))\\d{4}",null,null,null,"2642351234",null,null,null,[7]],[null,null,"8(?:00|33|44|55|66|77|88)[2-9]\\d{6}",null,null,null,"8002123456"],[null,null,"900[2-9]\\d{6}",null,null,null,"9002123456"],[null,null,null,null,null,null,null,null,null,[-1]],[null,null,"5(?:00|2[12]|33|44|66|77|88)[2-9]\\d{6}",null,null,null,"5002345678"],[null,null,null,null,null,null,null,null,null,[-1]],"AI",1,"011","1",null,null,"1",null,null,null,null,null,[null,null,null,null,null,null,null,null,null,[-1]],null,"264",[null,null,null,null,null,null,null,null,null,[-1]],[null,null,null,null,null,null,null,null,null,[-1]],null,null,[null,null,null,null,null,null,null,null,null,[-1]]],AS:[null,[null,null,"(?:[58]\\d\\d|684|900)\\d{7}",null,null,null,null,null,null,[10],[7]],[null,null,"6846(?:22|33|44|55|77|88|9[19])\\d{4}",null,null,null,"6846221234",null,null,null,[7]],[null,null,"684(?:2(?:5[2468]|72)|7(?:3[13]|70))\\d{4}",null,null,null,"6847331234",null,null,null,[7]],[null,null,"8(?:00|33|44|55|66|77|88)[2-9]\\d{6}",null,null,null,"8002123456"],[null,null,"900[2-9]\\d{6}",null,null,null,"9002123456"],[null,null,null,null,null,null,null,null,null,[-1]],[null,null,"5(?:00|2[12]|33|44|66|77|88)[2-9]\\d{6}",null,null,null,"5002345678"],[null,null,null,null,null,null,null,null,null,[-1]],"AS",1,"011","1",null,null,"1",null,null,null,null,null,[null,null,null,null,null,null,null,null,null,[-1]],null,"684",[null,null,null,null,null,null,null,null,null,[-1]],[null,null,null,null,null,null,null,null,null,[-1]],null,null,[null,null,null,null,null,null,null,null,null,[-1]]],BB:[null,[null,null,"(?:246|[58]\\d\\d|900)\\d{7}",null,null,null,null,null,null,[10],[7]],[null,null,"246(?:2(?:2[78]|7[0-4])|4(?:1[024-6]|2\\d|3[2-9])|5(?:20|[34]\\d|54|7[1-3])|6(?:2\\d|38)|7[35]7|9(?:1[89]|63))\\d{4}",null,null,null,"2464123456",null,null,null,[7]],[null,null,"246(?:2(?:[356]\\d|4[0-57-9]|8[0-79])|45\\d|69[5-7]|8(?:[2-5]\\d|83))\\d{4}",null,null,null,"2462501234",null,null,null,[7]],[null,null,"8(?:00|33|44|55|66|77|88)[2-9]\\d{6}",null,null,null,"8002123456"],[null,null,"(?:246976|900[2-9]\\d\\d)\\d{4}",null,null,null,"9002123456",null,null,null,[7]],[null,null,null,null,null,null,null,null,null,[-1]],[null,null,"5(?:00|2[12]|33|44|66|77|88)[2-9]\\d{6}",null,null,null,"5002345678"],[null,null,"24631\\d{5}",null,null,null,"2463101234",null,null,null,[7]],"BB",1,"011","1",null,null,"1",null,null,null,null,null,[null,null,null,null,null,null,null,null,null,[-1]],null,"246",[null,null,null,null,null,null,null,null,null,[-1]],[null,null,"246(?:292|367|4(?:1[7-9]|3[01]|44|67)|7(?:36|53))\\d{4}",null,null,null,"2464301234",null,null,null,[7]],null,null,[null,null,null,null,null,null,null,null,null,[-1]]],BM:[null,[null,null,"(?:441|[58]\\d\\d|900)\\d{7}",null,null,null,null,null,null,[10],[7]],[null,null,"441(?:2(?:02|23|[3479]\\d|61)|[46]\\d\\d|5(?:4\\d|60|89)|824)\\d{4}",null,null,null,"4412345678",null,null,null,[7]],[null,null,"441(?:[37]\\d|5[0-39])\\d{5}",null,null,null,"4413701234",null,null,null,[7]],[null,null,"8(?:00|33|44|55|66|77|88)[2-9]\\d{6}",null,null,null,"8002123456"],[null,null,"900[2-9]\\d{6}",null,null,null,"9002123456"],[null,null,null,null,null,null,null,null,null,[-1]],[null,null,"5(?:00|2[12]|33|44|66|77|88)[2-9]\\d{6}",null,null,null,"5002345678"],[null,null,null,null,null,null,null,null,null,[-1]],"BM",1,"011","1",null,null,"1",null,null,null,null,null,[null,null,null,null,null,null,null,null,null,[-1]],null,"441",[null,null,null,null,null,null,null,null,null,[-1]],[null,null,null,null,null,null,null,null,null,[-1]],null,null,[null,null,null,null,null,null,null,null,null,[-1]]],BS:[null,[null,null,"(?:242|[58]\\d\\d|900)\\d{7}",null,null,null,null,null,null,[10],[7]],[null,null,"242(?:3(?:02|[236][1-9]|4[0-24-9]|5[0-68]|7[347]|8[0-4]|9[2-467])|461|502|6(?:0[1-4]|12|2[013]|[45]0|7[67]|8[78]|9[89])|7(?:02|88))\\d{4}",null,null,null,"2423456789",null,null,null,[7]],[null,null,"242(?:3(?:5[79]|7[56]|95)|4(?:[23][1-9]|4[1-35-9]|5[1-8]|6[2-8]|7\\d|81)|5(?:2[45]|3[35]|44|5[1-46-9]|65|77)|6[34]6|7(?:27|38)|8(?:0[1-9]|1[02-9]|2\\d|[89]9))\\d{4}",null,null,null,"2423591234",null,null,null,[7]],[null,null,"(?:242300|8(?:00|33|44|55|66|77|88)[2-9]\\d\\d)\\d{4}",null,null,null,"8002123456",null,null,null,[7]],[null,null,"900[2-9]\\d{6}",null,null,null,"9002123456"],[null,null,null,null,null,null,null,null,null,[-1]],[null,null,"5(?:00|2[12]|33|44|66|77|88)[2-9]\\d{6}",null,null,null,"5002345678"],[null,null,null,null,null,null,null,null,null,[-1]],"BS",1,"011","1",null,null,"1",null,null,null,null,null,[null,null,null,null,null,null,null,null,null,[-1]],null,"242",[null,null,null,null,null,null,null,null,null,[-1]],[null,null,"242225[0-46-9]\\d{3}",null,null,null,"2422250123"],null,null,[null,null,null,null,null,null,null,null,null,[-1]]],CA:[null,[null,null,"(?:[2-8]\\d|90)\\d{8}",null,null,null,null,null,null,[10],[7]],[null,null,"(?:2(?:04|[23]6|[48]9|50)|3(?:06|43|65)|4(?:03|1[68]|3[178]|50)|5(?:06|1[49]|48|79|8[17])|6(?:04|13|39|47)|7(?:0[59]|78|8[02])|8(?:[06]7|19|25|73)|90[25])[2-9]\\d{6}",null,null,null,"5062345678",null,null,null,[7]],[null,null,"(?:2(?:04|[23]6|[48]9|50)|3(?:06|43|65)|4(?:03|1[68]|3[178]|50)|5(?:06|1[49]|48|79|8[17])|6(?:04|13|39|47)|7(?:0[59]|78|8[02])|8(?:[06]7|19|25|73)|90[25])[2-9]\\d{6}",null,null,null,"5062345678",null,null,null,[7]],[null,null,"8(?:00|33|44|55|66|77|88)[2-9]\\d{6}",null,null,null,"8002123456"],[null,null,"900[2-9]\\d{6}",null,null,null,"9002123456"],[null,null,null,null,null,null,null,null,null,[-1]],[null,null,"(?:5(?:00|2[12]|33|44|66|77|88)|622)[2-9]\\d{6}",null,null,null,"5002345678"],[null,null,"600[2-9]\\d{6}",null,null,null,"6002012345"],"CA",1,"011","1",null,null,"1",null,null,1,null,null,[null,null,null,null,null,null,null,null,null,[-1]],null,null,[null,null,null,null,null,null,null,null,null,[-1]],[null,null,null,null,null,null,null,null,null,[-1]],null,null,[null,null,null,null,null,null,null,null,null,[-1]]],DM:[null,[null,null,"(?:[58]\\d\\d|767|900)\\d{7}",null,null,null,null,null,null,[10],[7]],[null,null,"767(?:2(?:55|66)|4(?:2[01]|4[0-25-9])|50[0-4]|70[1-3])\\d{4}",null,null,null,"7674201234",null,null,null,[7]],[null,null,"767(?:2(?:[2-4689]5|7[5-7])|31[5-7]|61[1-7])\\d{4}",null,null,null,"7672251234",null,null,null,[7]],[null,null,"8(?:00|33|44|55|66|77|88)[2-9]\\d{6}",null,null,null,"8002123456"],[null,null,"900[2-9]\\d{6}",null,null,null,"9002123456"],[null,null,null,null,null,null,null,null,null,[-1]],[null,null,"5(?:00|2[12]|33|44|66|77|88)[2-9]\\d{6}",null,null,null,"5002345678"],[null,null,null,null,null,null,null,null,null,[-1]],"DM",1,"011","1",null,null,"1",null,null,null,null,null,[null,null,null,null,null,null,null,null,null,[-1]],null,"767",[null,null,null,null,null,null,null,null,null,[-1]],[null,null,null,null,null,null,null,null,null,[-1]],null,null,[null,null,null,null,null,null,null,null,null,[-1]]],DO:[null,[null,null,"(?:[58]\\d\\d|900)\\d{7}",null,null,null,null,null,null,[10],[7]],[null,null,"8(?:[04]9[2-9]\\d\\d|29(?:2(?:[0-59]\\d|6[04-9]|7[0-27]|8[0237-9])|3(?:[0-35-9]\\d|4[7-9])|[45]\\d\\d|6(?:[0-27-9]\\d|[3-5][1-9]|6[0135-8])|7(?:0[013-9]|[1-37]\\d|4[1-35689]|5[1-4689]|6[1-57-9]|8[1-79]|9[1-8])|8(?:0[146-9]|1[0-48]|[248]\\d|3[1-79]|5[01589]|6[013-68]|7[124-8]|9[0-8])|9(?:[0-24]\\d|3[02-46-9]|5[0-79]|60|7[0169]|8[57-9]|9[02-9])))\\d{4}",null,null,null,"8092345678",null,null,null,[7]],[null,null,"8[024]9[2-9]\\d{6}",null,null,null,"8092345678",null,null,null,[7]],[null,null,"8(?:00|33|44|55|66|77|88)[2-9]\\d{6}",null,null,null,"8002123456"],[null,null,"900[2-9]\\d{6}",null,null,null,"9002123456"],[null,null,null,null,null,null,null,null,null,[-1]],[null,null,"5(?:00|2[12]|33|44|66|77|88)[2-9]\\d{6}",null,null,null,"5002345678"],[null,null,null,null,null,null,null,null,null,[-1]],"DO",1,"011","1",null,null,"1",null,null,null,null,null,[null,null,null,null,null,null,null,null,null,[-1]],null,"8[024]9",[null,null,null,null,null,null,null,null,null,[-1]],[null,null,null,null,null,null,null,null,null,[-1]],null,null,[null,null,null,null,null,null,null,null,null,[-1]]],GD:[null,[null,null,"(?:473|[58]\\d\\d|900)\\d{7}",null,null,null,null,null,null,[10],[7]],[null,null,"473(?:2(?:3[0-2]|69)|3(?:2[89]|86)|4(?:[06]8|3[5-9]|4[0-49]|5[5-79]|73|90)|63[68]|7(?:58|84)|800|938)\\d{4}",null,null,null,"4732691234",null,null,null,[7]],[null,null,"473(?:4(?:0[2-79]|1[04-9]|2[0-5]|58)|5(?:2[01]|3[3-8])|901)\\d{4}",null,null,null,"4734031234",null,null,null,[7]],[null,null,"8(?:00|33|44|55|66|77|88)[2-9]\\d{6}",null,null,null,"8002123456"],[null,null,"900[2-9]\\d{6}",null,null,null,"9002123456"],[null,null,null,null,null,null,null,null,null,[-1]],[null,null,"5(?:00|2[12]|33|44|66|77|88)[2-9]\\d{6}",null,null,null,"5002345678"],[null,null,null,null,null,null,null,null,null,[-1]],"GD",1,"011","1",null,null,"1",null,null,null,null,null,[null,null,null,null,null,null,null,null,null,[-1]],null,"473",[null,null,null,null,null,null,null,null,null,[-1]],[null,null,null,null,null,null,null,null,null,[-1]],null,null,[null,null,null,null,null,null,null,null,null,[-1]]],GU:[null,[null,null,"(?:[58]\\d\\d|671|900)\\d{7}",null,null,null,null,null,null,[10],[7]],[null,null,"671(?:3(?:00|3[39]|4[349]|55|6[26])|4(?:00|56|7[1-9]|8[0236-9])|5(?:55|6[2-5]|88)|6(?:3[2-578]|4[24-9]|5[34]|78|8[235-9])|7(?:[0479]7|2[0167]|3[45]|8[7-9])|8(?:[2-57-9]8|6[48])|9(?:2[29]|6[79]|7[1279]|8[7-9]|9[78]))\\d{4}",null,null,null,"6713001234",null,null,null,[7]],[null,null,"671(?:3(?:00|3[39]|4[349]|55|6[26])|4(?:00|56|7[1-9]|8[0236-9])|5(?:55|6[2-5]|88)|6(?:3[2-578]|4[24-9]|5[34]|78|8[235-9])|7(?:[0479]7|2[0167]|3[45]|8[7-9])|8(?:[2-57-9]8|6[48])|9(?:2[29]|6[79]|7[1279]|8[7-9]|9[78]))\\d{4}",null,null,null,"6713001234",null,null,null,[7]],[null,null,"8(?:00|33|44|55|66|77|88)[2-9]\\d{6}",null,null,null,"8002123456"],[null,null,"900[2-9]\\d{6}",null,null,null,"9002123456"],[null,null,null,null,null,null,null,null,null,[-1]],[null,null,"5(?:00|2[12]|33|44|66|77|88)[2-9]\\d{6}",null,null,null,"5002345678"],[null,null,null,null,null,null,null,null,null,[-1]],"GU",1,"011","1",null,null,"1",null,null,1,null,null,[null,null,null,null,null,null,null,null,null,[-1]],null,"671",[null,null,null,null,null,null,null,null,null,[-1]],[null,null,null,null,null,null,null,null,null,[-1]],null,null,[null,null,null,null,null,null,null,null,null,[-1]]],JM:[null,[null,null,"(?:[58]\\d\\d|658|900)\\d{7}",null,null,null,null,null,null,[10],[7]],[null,null,"(?:658[2-9]\\d\\d|876(?:5(?:0[12]|1[0-468]|2[35]|63)|6(?:0[1-3579]|1[0237-9]|[23]\\d|40|5[06]|6[2-589]|7[05]|8[04]|9[4-9])|7(?:0[2-689]|[1-6]\\d|8[056]|9[45])|9(?:0[1-8]|1[02378]|[2-8]\\d|9[2-468])))\\d{4}",null,null,null,"8765230123",null,null,null,[7]],[null,null,"876(?:(?:2[14-9]|[348]\\d)\\d|5(?:0[3-9]|[2-57-9]\\d|6[0-24-9])|7(?:0[07]|7\\d|8[1-47-9]|9[0-36-9])|9(?:[01]9|9[0579]))\\d{4}",null,null,null,"8762101234",null,null,null,[7]],[null,null,"8(?:00|33|44|55|66|77|88)[2-9]\\d{6}",null,null,null,"8002123456"],[null,null,"900[2-9]\\d{6}",null,null,null,"9002123456"],[null,null,null,null,null,null,null,null,null,[-1]],[null,null,"5(?:00|2[12]|33|44|66|77|88)[2-9]\\d{6}",null,null,null,"5002345678"],[null,null,null,null,null,null,null,null,null,[-1]],"JM",1,"011","1",null,null,"1",null,null,null,null,null,[null,null,null,null,null,null,null,null,null,[-1]],null,"658|876",[null,null,null,null,null,null,null,null,null,[-1]],[null,null,null,null,null,null,null,null,null,[-1]],null,null,[null,null,null,null,null,null,null,null,null,[-1]]],KN:[null,[null,null,"(?:[58]\\d\\d|900)\\d{7}",null,null,null,null,null,null,[10],[7]],[null,null,"869(?:2(?:29|36)|302|4(?:6[015-9]|70))\\d{4}",null,null,null,"8692361234",null,null,null,[7]],[null,null,"869(?:5(?:5[6-8]|6[5-7])|66\\d|76[02-7])\\d{4}",null,null,null,"8697652917",null,null,null,[7]],[null,null,"8(?:00|33|44|55|66|77|88)[2-9]\\d{6}",null,null,null,"8002123456"],[null,null,"900[2-9]\\d{6}",null,null,null,"9002123456"],[null,null,null,null,null,null,null,null,null,[-1]],[null,null,"5(?:00|2[12]|33|44|66|77|88)[2-9]\\d{6}",null,null,null,"5002345678"],[null,null,null,null,null,null,null,null,null,[-1]],"KN",1,"011","1",null,null,"1",null,null,null,null,null,[null,null,null,null,null,null,null,null,null,[-1]],null,"869",[null,null,null,null,null,null,null,null,null,[-1]],[null,null,null,null,null,null,null,null,null,[-1]],null,null,[null,null,null,null,null,null,null,null,null,[-1]]],KY:[null,[null,null,"(?:345|[58]\\d\\d|900)\\d{7}",null,null,null,null,null,null,[10],[7]],[null,null,"345(?:2(?:22|44)|444|6(?:23|38|40)|7(?:4[35-79]|6[6-9]|77)|8(?:00|1[45]|25|[48]8)|9(?:14|4[035-9]))\\d{4}",null,null,null,"3452221234",null,null,null,[7]],[null,null,"345(?:32[1-9]|5(?:1[67]|2[5-79]|4[6-9]|50|76)|649|9(?:1[67]|2[2-9]|3[689]))\\d{4}",null,null,null,"3453231234",null,null,null,[7]],[null,null,"8(?:00|33|44|55|66|77|88)[2-9]\\d{6}",null,null,null,"8002345678"],[null,null,"(?:345976|900[2-9]\\d\\d)\\d{4}",null,null,null,"9002345678"],[null,null,null,null,null,null,null,null,null,[-1]],[null,null,"5(?:00|2[12]|33|44|66|77|88)[2-9]\\d{6}",null,null,null,"5002345678"],[null,null,null,null,null,null,null,null,null,[-1]],"KY",1,"011","1",null,null,"1",null,null,null,null,null,[null,null,"345849\\d{4}",null,null,null,"3458491234"],null,"345",[null,null,null,null,null,null,null,null,null,[-1]],[null,null,null,null,null,null,null,null,null,[-1]],null,null,[null,null,null,null,null,null,null,null,null,[-1]]],LC:[null,[null,null,"(?:[58]\\d\\d|758|900)\\d{7}",null,null,null,null,null,null,[10],[7]],[null,null,"758(?:4(?:30|5\\d|6[2-9]|8[0-2])|57[0-2]|638)\\d{4}",null,null,null,"7584305678",null,null,null,[7]],[null,null,"758(?:28[4-7]|384|4(?:6[01]|8[4-9])|5(?:1[89]|20|84)|7(?:1[2-9]|2\\d|3[01]))\\d{4}",null,null,null,"7582845678",null,null,null,[7]],[null,null,"8(?:00|33|44|55|66|77|88)[2-9]\\d{6}",null,null,null,"8002123456"],[null,null,"900[2-9]\\d{6}",null,null,null,"9002123456"],[null,null,null,null,null,null,null,null,null,[-1]],[null,null,"5(?:00|2[12]|33|44|66|77|88)[2-9]\\d{6}",null,null,null,"5002345678"],[null,null,null,null,null,null,null,null,null,[-1]],"LC",1,"011","1",null,null,"1",null,null,null,null,null,[null,null,null,null,null,null,null,null,null,[-1]],null,"758",[null,null,null,null,null,null,null,null,null,[-1]],[null,null,null,null,null,null,null,null,null,[-1]],null,null,[null,null,null,null,null,null,null,null,null,[-1]]],MP:[null,[null,null,"(?:[58]\\d\\d|(?:67|90)0)\\d{7}",null,null,null,null,null,null,[10],[7]],[null,null,"670(?:2(?:3[3-7]|56|8[5-8])|32[1-38]|4(?:33|8[348])|5(?:32|55|88)|6(?:64|70|82)|78[3589]|8[3-9]8|989)\\d{4}",null,null,null,"6702345678",null,null,null,[7]],[null,null,"670(?:2(?:3[3-7]|56|8[5-8])|32[1-38]|4(?:33|8[348])|5(?:32|55|88)|6(?:64|70|82)|78[3589]|8[3-9]8|989)\\d{4}",null,null,null,"6702345678",null,null,null,[7]],[null,null,"8(?:00|33|44|55|66|77|88)[2-9]\\d{6}",null,null,null,"8002123456"],[null,null,"900[2-9]\\d{6}",null,null,null,"9002123456"],[null,null,null,null,null,null,null,null,null,[-1]],[null,null,"5(?:00|2[12]|33|44|66|77|88)[2-9]\\d{6}",null,null,null,"5002345678"],[null,null,null,null,null,null,null,null,null,[-1]],"MP",1,"011","1",null,null,"1",null,null,1,null,null,[null,null,null,null,null,null,null,null,null,[-1]],null,"670",[null,null,null,null,null,null,null,null,null,[-1]],[null,null,null,null,null,null,null,null,null,[-1]],null,null,[null,null,null,null,null,null,null,null,null,[-1]]],MS:[null,[null,null,"(?:(?:[58]\\d\\d|900)\\d\\d|66449)\\d{5}",null,null,null,null,null,null,[10],[7]],[null,null,"664491\\d{4}",null,null,null,"6644912345",null,null,null,[7]],[null,null,"66449[2-6]\\d{4}",null,null,null,"6644923456",null,null,null,[7]],[null,null,"8(?:00|33|44|55|66|77|88)[2-9]\\d{6}",null,null,null,"8002123456"],[null,null,"900[2-9]\\d{6}",null,null,null,"9002123456"],[null,null,null,null,null,null,null,null,null,[-1]],[null,null,"5(?:00|2[12]|33|44|66|77|88)[2-9]\\d{6}",null,null,null,"5002345678"],[null,null,null,null,null,null,null,null,null,[-1]],"MS",1,"011","1",null,null,"1",null,null,null,null,null,[null,null,null,null,null,null,null,null,null,[-1]],null,"664",[null,null,null,null,null,null,null,null,null,[-1]],[null,null,null,null,null,null,null,null,null,[-1]],null,null,[null,null,null,null,null,null,null,null,null,[-1]]],PR:[null,[null,null,"(?:[589]\\d\\d|787)\\d{7}",null,null,null,null,null,null,[10],[7]],[null,null,"(?:787|939)[2-9]\\d{6}",null,null,null,"7872345678",null,null,null,[7]],[null,null,"(?:787|939)[2-9]\\d{6}",null,null,null,"7872345678",null,null,null,[7]],[null,null,"8(?:00|33|44|55|66|77|88)[2-9]\\d{6}",null,null,null,"8002345678"],[null,null,"900[2-9]\\d{6}",null,null,null,"9002345678"],[null,null,null,null,null,null,null,null,null,[-1]],[null,null,"5(?:00|2[12]|33|44|66|77|88)[2-9]\\d{6}",null,null,null,"5002345678"],[null,null,null,null,null,null,null,null,null,[-1]],"PR",1,"011","1",null,null,"1",null,null,1,null,null,[null,null,null,null,null,null,null,null,null,[-1]],null,"787|939",[null,null,null,null,null,null,null,null,null,[-1]],[null,null,null,null,null,null,null,null,null,[-1]],null,null,[null,null,null,null,null,null,null,null,null,[-1]]],SX:[null,[null,null,"(?:(?:[58]\\d\\d|900)\\d|7215)\\d{6}",null,null,null,null,null,null,[10],[7]],[null,null,"7215(?:4[2-8]|8[239]|9[056])\\d{4}",null,null,null,"7215425678",null,null,null,[7]],[null,null,"7215(?:1[02]|2\\d|5[034679]|8[014-8])\\d{4}",null,null,null,"7215205678",null,null,null,[7]],[null,null,"8(?:00|33|44|55|66|77|88)[2-9]\\d{6}",null,null,null,"8002123456"],[null,null,"900[2-9]\\d{6}",null,null,null,"9002123456"],[null,null,null,null,null,null,null,null,null,[-1]],[null,null,"5(?:00|2[12]|33|44|66|77|88)[2-9]\\d{6}",null,null,null,"5002345678"],[null,null,null,null,null,null,null,null,null,[-1]],"SX",1,"011","1",null,null,"1",null,null,null,null,null,[null,null,null,null,null,null,null,null,null,[-1]],null,"721",[null,null,null,null,null,null,null,null,null,[-1]],[null,null,null,null,null,null,null,null,null,[-1]],null,null,[null,null,null,null,null,null,null,null,null,[-1]]],TC:[null,[null,null,"(?:[58]\\d\\d|649|900)\\d{7}",null,null,null,null,null,null,[10],[7]],[null,null,"649(?:712|9(?:4\\d|50))\\d{4}",null,null,null,"6497121234",null,null,null,[7]],[null,null,"649(?:2(?:3[129]|4[1-7])|3(?:3[1-389]|4[1-8])|4[34][1-3])\\d{4}",null,null,null,"6492311234",null,null,null,[7]],[null,null,"8(?:00|33|44|55|66|77|88)[2-9]\\d{6}",null,null,null,"8002345678"],[null,null,"900[2-9]\\d{6}",null,null,null,"9002345678"],[null,null,null,null,null,null,null,null,null,[-1]],[null,null,"5(?:00|2[12]|33|44|66|77|88)[2-9]\\d{6}",null,null,null,"5002345678"],[null,null,"64971[01]\\d{4}",null,null,null,"6497101234",null,null,null,[7]],"TC",1,"011","1",null,null,"1",null,null,null,null,null,[null,null,null,null,null,null,null,null,null,[-1]],null,"649",[null,null,null,null,null,null,null,null,null,[-1]],[null,null,null,null,null,null,null,null,null,[-1]],null,null,[null,null,null,null,null,null,null,null,null,[-1]]],TT:[null,[null,null,"(?:[58]\\d\\d|900)\\d{7}",null,null,null,null,null,null,[10],[7]],[null,null,"868(?:2(?:01|[23]\\d)|6(?:0[7-9]|1[02-8]|2[1-9]|[3-69]\\d|7[0-79])|82[124])\\d{4}",null,null,null,"8682211234",null,null,null,[7]],[null,null,"868(?:2(?:6[6-9]|[7-9]\\d)|[37](?:0[1-9]|1[02-9]|[2-9]\\d)|4[6-9]\\d|6(?:20|78|8\\d))\\d{4}",null,null,null,"8682911234",null,null,null,[7]],[null,null,"8(?:00|33|44|55|66|77|88)[2-9]\\d{6}",null,null,null,"8002345678"],[null,null,"900[2-9]\\d{6}",null,null,null,"9002345678"],[null,null,null,null,null,null,null,null,null,[-1]],[null,null,"5(?:00|2[12]|33|44|66|77|88)[2-9]\\d{6}",null,null,null,"5002345678"],[null,null,null,null,null,null,null,null,null,[-1]],"TT",1,"011","1",null,null,"1",null,null,null,null,null,[null,null,null,null,null,null,null,null,null,[-1]],null,"868",[null,null,null,null,null,null,null,null,null,[-1]],[null,null,null,null,null,null,null,null,null,[-1]],null,null,[null,null,"868619\\d{4}",null,null,null,"8686191234",null,null,null,[7]]],US:[null,[null,null,"[2-9]\\d{9}",null,null,null,null,null,null,[10],[7]],[null,null,"(?:2(?:0[1-35-9]|1[02-9]|2[03-589]|3[149]|4[08]|5[1-46]|6[0279]|7[0269]|8[13])|3(?:0[1-57-9]|1[02-9]|2[0135]|3[0-24679]|4[67]|5[12]|6[014]|8[056])|4(?:0[124-9]|1[02-579]|2[3-5]|3[0245]|4[0235]|58|6[39]|7[0589]|8[04])|5(?:0[1-57-9]|1[0235-8]|20|3[0149]|4[01]|5[19]|6[1-47]|7[013-5]|8[056])|6(?:0[1-35-9]|1[024-9]|2[03689]|[34][016]|5[017]|6[0-279]|78|8[0-2])|7(?:0[1-46-8]|1[2-9]|2[04-7]|3[1247]|4[037]|5[47]|6[02359]|7[02-59]|8[156])|8(?:0[1-68]|1[02-8]|2[08]|3[0-28]|4[3578]|5[046-9]|6[02-5]|7[028])|9(?:0[1346-9]|1[02-9]|2[0589]|3[0146-8]|4[0179]|5[12469]|7[0-389]|8[04-69]))[2-9]\\d{6}",null,null,null,"2015550123",null,null,null,[7]],[null,null,"(?:2(?:0[1-35-9]|1[02-9]|2[03-589]|3[149]|4[08]|5[1-46]|6[0279]|7[0269]|8[13])|3(?:0[1-57-9]|1[02-9]|2[0135]|3[0-24679]|4[67]|5[12]|6[014]|8[056])|4(?:0[124-9]|1[02-579]|2[3-5]|3[0245]|4[0235]|58|6[39]|7[0589]|8[04])|5(?:0[1-57-9]|1[0235-8]|20|3[0149]|4[01]|5[19]|6[1-47]|7[013-5]|8[056])|6(?:0[1-35-9]|1[024-9]|2[03689]|[34][016]|5[017]|6[0-279]|78|8[0-2])|7(?:0[1-46-8]|1[2-9]|2[04-7]|3[1247]|4[037]|5[47]|6[02359]|7[02-59]|8[156])|8(?:0[1-68]|1[02-8]|2[08]|3[0-28]|4[3578]|5[046-9]|6[02-5]|7[028])|9(?:0[1346-9]|1[02-9]|2[0589]|3[0146-8]|4[0179]|5[12469]|7[0-389]|8[04-69]))[2-9]\\d{6}",null,null,null,"2015550123",null,null,null,[7]],[null,null,"8(?:00|33|44|55|66|77|88)[2-9]\\d{6}",null,null,null,"8002345678"],[null,null,"900[2-9]\\d{6}",null,null,null,"9002345678"],[null,null,null,null,null,null,null,null,null,[-1]],[null,null,"5(?:00|2[12]|33|44|66|77|88)[2-9]\\d{6}",null,null,null,"5002345678"],[null,null,null,null,null,null,null,null,null,[-1]],"US",1,"011","1",null,null,"1",null,null,1,[[null,"(\\d{3})(\\d{4})","$1-$2",["[2-9]"]],[null,"(\\d{3})(\\d{3})(\\d{4})","($1) $2-$3",["[2-9]"],null,null,1]],[[null,"(\\d{3})(\\d{3})(\\d{4})","$1-$2-$3",["[2-9]"]]],[null,null,null,null,null,null,null,null,null,[-1]],1,null,[null,null,null,null,null,null,null,null,null,[-1]],[null,null,"710[2-9]\\d{6}",null,null,null,"7102123456"],null,null,[null,null,null,null,null,null,null,null,null,[-1]]],VC:[null,[null,null,"(?:[58]\\d\\d|784|900)\\d{7}",null,null,null,null,null,null,[10],[7]],[null,null,"784(?:266|3(?:6[6-9]|7\\d|8[0-24-6])|4(?:38|5[0-36-8]|8[0-8])|5(?:55|7[0-2]|93)|638|784)\\d{4}",null,null,null,"7842661234",null,null,null,[7]],[null,null,"784(?:4(?:3[0-5]|5[45]|89|9[0-8])|5(?:2[6-9]|3[0-4]))\\d{4}",null,null,null,"7844301234",null,null,null,[7]],[null,null,"8(?:00|33|44|55|66|77|88)[2-9]\\d{6}",null,null,null,"8002345678"],[null,null,"900[2-9]\\d{6}",null,null,null,"9002345678"],[null,null,null,null,null,null,null,null,null,[-1]],[null,null,"5(?:00|2[12]|33|44|66|77|88)[2-9]\\d{6}",null,null,null,"5002345678"],[null,null,null,null,null,null,null,null,null,[-1]],"VC",1,"011","1",null,null,"1",null,null,null,null,null,[null,null,null,null,null,null,null,null,null,[-1]],null,"784",[null,null,null,null,null,null,null,null,null,[-1]],[null,null,null,null,null,null,null,null,null,[-1]],null,null,[null,null,null,null,null,null,null,null,null,[-1]]],VG:[null,[null,null,"(?:284|[58]\\d\\d|900)\\d{7}",null,null,null,null,null,null,[10],[7]],[null,null,"284(?:(?:229|774|8(?:52|6[459]))\\d|4(?:22\\d|9(?:[45]\\d|6[0-5])))\\d{3}",null,null,null,"2842291234",null,null,null,[7]],[null,null,"284(?:(?:3(?:0[0-3]|4[0-7]|68|9[34])|54[0-57])\\d|4(?:(?:4[0-6]|68)\\d|9(?:6[6-9]|9\\d)))\\d{3}",null,null,null,"2843001234",null,null,null,[7]],[null,null,"8(?:00|33|44|55|66|77|88)[2-9]\\d{6}",null,null,null,"8002345678"],[null,null,"900[2-9]\\d{6}",null,null,null,"9002345678"],[null,null,null,null,null,null,null,null,null,[-1]],[null,null,"5(?:00|2[12]|33|44|66|77|88)[2-9]\\d{6}",null,null,null,"5002345678"],[null,null,null,null,null,null,null,null,null,[-1]],"VG",1,"011","1",null,null,"1",null,null,null,null,null,[null,null,null,null,null,null,null,null,null,[-1]],null,"284",[null,null,null,null,null,null,null,null,null,[-1]],[null,null,null,null,null,null,null,null,null,[-1]],null,null,[null,null,null,null,null,null,null,null,null,[-1]]],VI:[null,[null,null,"(?:(?:34|90)0|[58]\\d\\d)\\d{7}",null,null,null,null,null,null,[10],[7]],[null,null,"340(?:2(?:01|2[06-8]|44|77)|3(?:32|44)|4(?:22|7[34])|5(?:1[34]|55)|6(?:26|4[23]|77|9[023])|7(?:1[2-57-9]|27|7\\d)|884|998)\\d{4}",null,null,null,"3406421234",null,null,null,[7]],[null,null,"340(?:2(?:01|2[06-8]|44|77)|3(?:32|44)|4(?:22|7[34])|5(?:1[34]|55)|6(?:26|4[23]|77|9[023])|7(?:1[2-57-9]|27|7\\d)|884|998)\\d{4}",null,null,null,"3406421234",null,null,null,[7]],[null,null,"8(?:00|33|44|55|66|77|88)[2-9]\\d{6}",null,null,null,"8002345678"],[null,null,"900[2-9]\\d{6}",null,null,null,"9002345678"],[null,null,null,null,null,null,null,null,null,[-1]],[null,null,"5(?:00|2[12]|33|44|66|77|88)[2-9]\\d{6}",null,null,null,"5002345678"],[null,null,null,null,null,null,null,null,null,[-1]],"VI",1,"011","1",null,null,"1",null,null,1,null,null,[null,null,null,null,null,null,null,null,null,[-1]],null,"340",[null,null,null,null,null,null,null,null,null,[-1]],[null,null,null,null,null,null,null,null,null,[-1]],null,null,[null,null,null,null,null,null,null,null,null,[-1]]]};_.b=function(){return _.a?_.a:_.a=new _};var Q={0:"0",1:"1",2:"2",3:"3",4:"4",5:"5",6:"6",7:"7",8:"8",9:"9","０":"0","１":"1","２":"2","３":"3","４":"4","５":"5","６":"6","７":"7","８":"8","９":"9","٠":"0","١":"1","٢":"2","٣":"3","٤":"4","٥":"5","٦":"6","٧":"7","٨":"8","٩":"9","۰":"0","۱":"1","۲":"2","۳":"3","۴":"4","۵":"5","۶":"6","۷":"7","۸":"8","۹":"9"},W=RegExp("[+＋]+"),ll=RegExp("([0-9０-９٠-٩۰-۹])"),nl=/^\(?\$1\)?$/,ul=new b;c(ul,11,"NA");var tl=/\[([^\[\]])*\]/g,el=/\d(?=[^,}][^,}])/g,rl=RegExp("^[-x‐-―−ー－-／  ­​⁠　()（）［］.\\[\\]/~⁓∼～]*(\\$\\d[-x‐-―−ー－-／  ­​⁠　()（）［］.\\[\\]/~⁓∼～]*)+$"),il=/[- ]/;B.prototype.K=function(){this.C="",t(this.i),t(this.u),t(this.m),this.s=0,this.w="",t(this.b),this.h="",t(this.a),this.l=!0,this.A=this.o=this.F=!1,this.f=[],this.B=!1,this.g!=this.J&&(this.g=C(this,this.D))},B.prototype.L=function(l){return this.C=function(l,n){l.i.a(n);var u,e=n;if(ll.test(e)||1==l.i.b.length&&W.test(e)?("+"==(e=n)?(u=e,l.u.a(e)):(u=Q[e],l.u.a(u),l.a.a(u)),n=u):(l.l=!1,l.F=!0),!l.l){if(!l.F)if(R(l)){if(E(l))return D(l)}else if(0<l.h.length&&(e=l.a.toString(),t(l.a),l.a.a(l.h),l.a.a(e),u=(e=l.b.toString()).lastIndexOf(l.h),t(l.b),l.b.a(e.substring(0,u))),l.h!=$(l))return l.b.a(" "),D(l);return l.i.toString()}switch(l.u.b.length){case 0:case 1:case 2:return l.i.toString();case 3:if(!R(l))return l.h=$(l),I(l);l.A=!0;default:return l.A?(E(l)&&(l.A=!1),l.b.toString()+l.a.toString()):0<l.f.length?(e=T(l,n),0<(u=G(l)).length?u:(N(l,l.a.toString()),M(l)?V(l):l.l?j(l,e):l.i.toString())):I(l)}}(this,l)},l("Cleave.AsYouTypeFormatter",B),l("Cleave.AsYouTypeFormatter.prototype.inputDigit",B.prototype.L),l("Cleave.AsYouTypeFormatter.prototype.clear",B.prototype.K)}).call("object"==typeof global&&global?global:window);

                                            var cleavePhone = new Cleave('#cons_phone', {
                                                phone: true,
                                                phoneRegionCode: 'CA'
                                            });
                                        });
                                    </script>
                                        </script>
                                        <?php
                                    }

                                    $i_2++;
                                }
                                //Close row for input items
//                                echo('</div>');

                            };



                            //MultiMulti checkbox
                            $multiQuestion = [];
                            $multiSubquestion = [];

                            //Iterate custom questions (MultiMulti)
                            if (!empty(get_post_meta( $shortcodeID, 'meta-survey-sv-multimulti-' . $i . '-type', true ))) {
                                $multiQuestion[$i]['type'] = get_post_meta( $shortcodeID, 'meta-survey-sv-multimulti-' . $i . '-type', true );
                                $multiQuestion[$i]['text'] = get_post_meta( $shortcodeID, 'meta-survey-sv-multimulti-' . $i . '-text', true );
                                $multiQuestion[$i]['id'] = get_post_meta( $shortcodeID, 'meta-survey-sv-multimulti-' . $i . '-id', true );
                                $multiQuestion[$i]['required'] = get_post_meta( $shortcodeID, 'meta-survey-sv-multimulti-' . $i . '-required', true );
                                $multiQuestion[$i]['max'] = get_post_meta( $shortcodeID, 'meta-survey-sv-multimulti-' . $i . '-max', true );
                                $cur_field_status = $multiQuestion[$i]['required'];
                                if (!empty($multiQuestion[$i]['max'])) :?>
                                    <script type="text/javascript">
                                        jQuery(document).ready(function(){
                                            jQuery('input[name="question_<?php echo($multiQuestion[$i]['id']) ?>"]').on('change', function() {
                                                if(jQuery('input[name="question_<?php echo($multiQuestion[$i]['id']) ?>"]:checked').length > <?php echo($multiQuestion[$i]['max'])?>) {
                                                    this.checked = false;
                                                }
                                            });
                                        });
                                    </script>
                                <?php endif;?>

                                    <script type="text/javascript">
                                        jQuery(document).ready(function(){
                                            var requiredCheckboxes = jQuery('input[name="question_<?php echo($multiQuestion[$i]['id']) ?>"]:checkbox[required]');
                                            requiredCheckboxes.change(function(){
                                                if(requiredCheckboxes.is(':checked')) {
                                                    requiredCheckboxes.removeAttr('required');
                                                    requiredCheckboxes.removeClass('invalid');
                                                } else {
                                                    requiredCheckboxes.attr('required', 'required');
                                                }
                                            });
                                        });
                                    </script>

                                <?php



                                if ($multiQuestion[$i]['type'] !== null || $multiQuestion[$i]['text'] !== null || $multiQuestion[$i]['id'] !== null || $multiQuestion[$i]['required'] !== null || $multiQuestion[$i]['max'] !== null) {
    //                                echo($multiQuestion[$i]['type']);
                                    echo('<p class="mb-2 wplo-survey-question w-100" id="question-sentence-' . $i . '">' . $multiQuestion[$i]['text'] . ( (!empty($multiQuestion[$i]['max'])) ? ' Max ' . $multiQuestion[$i]['max'] : '') . ($cur_field_status == "true" ? ' <span class="wplosvy-alert-asterisk">*</span>' : '') .'</p>');
    //                                echo($multiQuestion[$i]['id']);
    //                                echo($multiQuestion[$i]['required']);
                                }

                                $i_2 = 0;

                                //Open row for input items
                                echo('<div ' . (get_post_meta( $shortcodeID, 'meta-survey-radio-email-top-btn', true ) == "on" ? 'style="display:flex;flex-direction:column;align-items:center;"' : '' ) . ' class="row w-100 mb-4' . '">');
                                while (!empty(get_post_meta( $shortcodeID, 'meta-survey-sv-multimulti-' . $i . '-label-' . $i_2, true ))) {
                                    // Add a new array for each iteration
                                    $multiSubquestion[$i]['label-' . $i_2] = get_post_meta( $shortcodeID, 'meta-survey-sv-multimulti-' . $i . '-label-' . $i_2, true );
                                    $multiSubquestion[$i]['fieldValues-' . $i_2] = get_post_meta( $shortcodeID, 'meta-survey-sv-multimulti-' . $i . '-value-' . $i_2, true );

                                    if (($multiSubquestion[$i]['label-' . $i_2] !== null )){
                                        //Checkbox items
                                        echo('
                                        <div ' . (get_post_meta( $shortcodeID, 'meta-survey-radio-email-top-btn', true ) == "on" ? ($multiSubquestion[$i]['fieldName-' . $i_2] == "cons_email" ? 'style="order:0;"' : 'style="order:2"' ) : '' ) . 'class="input-margins-forms col-sm-12 ' . ($meta_survey_input_single_line == "single" ? '' : 'col-md-6') . ' ">
                                            <div class="form-label d-flex align-items-center ml-4 mb-2 '. '"><input id="question_' . ($multiQuestion[$i]['id']) . '_' . $i_2  . '" class="wplo-survey-form-control-checkbox mt-0 w-25" name="question_' . ($multiQuestion[$i]['id']) . '" '. 'type="checkbox"' . ' value="' . ($multiSubquestion[$i]['label-' . $i_2]) .'" ' . ($cur_field_status == "true" ? 'required="required"' : '') . '>
                                                <label class="control-label checkbox-label ml-3 w-75" style="margin-bottom:0!important" for="question_' . ($multiQuestion[$i]['id']) . '_' . $i_2  . '">' . ($multiSubquestion[$i]['label-' . $i_2]) . '</label></div>
                                        </div>
                                        ');

                                    }
                                    $i_2++;
                                }
                                //Close row for input items
                                echo('</div>');
                            };




                            //MultiSingle begins
                            $multiSingleQuestion = [];
                            $multiSingleSubquestion = [];
                            //Iterate custom questions (MultiSingle)
                            if (!empty(get_post_meta( $shortcodeID, 'meta-survey-sv-multisingle-' . $i . '-type', true ))) {

                                $multiSingleQuestion[$i]['type'] = get_post_meta( $shortcodeID, 'meta-survey-sv-multisingle-' . $i . '-type', true );
                                $multiSingleQuestion[$i]['text'] = get_post_meta( $shortcodeID, 'meta-survey-sv-multisingle-' . $i . '-text', true );
                                $multiSingleQuestion[$i]['id'] = get_post_meta( $shortcodeID, 'meta-survey-sv-multisingle-' . $i . '-id', true );
                                $multiSingleQuestion[$i]['required'] = get_post_meta( $shortcodeID, 'meta-survey-sv-multisingle-' . $i . '-required', true );
                                $multiSingleQuestion[$i]['max'] = get_post_meta( $shortcodeID, 'meta-survey-sv-multisingle-' . $i . '-max', true );
                                $cur_field_status = $multiSingleQuestion[$i]['required'];


                                echo('<div ' . (get_post_meta( $shortcodeID, 'meta-survey-radio-email-top-btn', true ) == "on" ? ($multiSingleQuestion[$i]['text'] == "Email" ? 'style="order:0;"' : 'style="order:2"' ) : '' ) . 'class="mb-4 input-margins-forms col-sm-12 wplo-survey-question ' . ($meta_survey_input_single_line == "single" ? '' : 'col-md-6') . ' ">
    <div class="form-label-group-float">
    <div class="selectdiv">
    <label class="control-label pb-2" for="question_' . ($multiSingleQuestion[$i]['id']) . '">' . ($multiSingleQuestion[$i]['text']) . ($cur_field_status == "true" ? ' <span class="wplosvy-alert-asterisk">*</span>' : '') . '</label>
    <select id="question_' . ($multiSingleQuestion[$i]['id']) . '" class="form-control" name="question_' . ($multiSingleQuestion[$i]['id']) . '" '. ($multiSingleQuestion[$i]['text'] == "Email" ? 'type="email"' : 'type="text"') . ' placeholder="' . ($multiSingleQuestion[$i]['text']) .'" ' . ($cur_field_status == "true" ? 'required="required"' : '') . '>
    <option value="" disabled="disabled" selected="selected">Please select response</option>
    ');

                                $i_2 = 0;

                                //Open row for input items
                                while (!empty(get_post_meta( $shortcodeID, 'meta-survey-sv-multisingle-' . $i . '-label-' . $i_2, true ))) {
    //                                // Add a new array for each iteration
                                    $multiSingleSubquestion[$i]['label-' . $i_2] = get_post_meta( $shortcodeID, 'meta-survey-sv-multisingle-' . $i . '-label-' . $i_2, true );
                                    $multiSingleSubquestion[$i]['fieldValues-' . $i_2] = get_post_meta( $shortcodeID, 'meta-survey-sv-multisingle-' . $i . '-value-' . $i_2, true );

                                    if (($multiSingleSubquestion[$i]['label-' . $i_2] !== null )){
                                        //Select items
                                        echo('
                                        <option id="question_' . ($multiSingleQuestion[$i]['id']) . '_' . $i_2  . '" name="question_' . ($multiSingleQuestion[$i]['id']) . '" value="' . ($multiSingleSubquestion[$i]['fieldValues-' . $i_2]) . '">
                                        ' . $multiSingleSubquestion[$i]['label-' . $i_2] . '
                                        </option>
                                        ');

                                    }
                                    $i_2++;
                                }
                                //Close row for input items

                                echo('</select></div></div></div>');
                            };






                            //YesNo begins
                            $YesNoQuestion = [];
                            $YesNoSubquestion = [];
                            //Iterate custom questions (YesNo)
                            if (!empty(get_post_meta( $shortcodeID, 'meta-survey-sv-yesno-' . $i . '-type', true ))) {

                                $YesNoQuestion[$i]['type'] = get_post_meta( $shortcodeID, 'meta-survey-sv-yesno-' . $i . '-type', true );
                                $YesNoQuestion[$i]['text'] = get_post_meta( $shortcodeID, 'meta-survey-sv-yesno-' . $i . '-text', true );
                                $YesNoQuestion[$i]['id'] = get_post_meta( $shortcodeID, 'meta-survey-sv-yesno-' . $i . '-id', true );
                                $YesNoQuestion[$i]['required'] = get_post_meta( $shortcodeID, 'meta-survey-sv-yesno-' . $i . '-required', true );
                                $YesNoQuestion[$i]['max'] = get_post_meta( $shortcodeID, 'meta-survey-sv-yesno-' . $i . '-max', true );
                                $cur_field_status = $YesNoQuestion[$i]['required'];


                                echo('<div ' . (get_post_meta( $shortcodeID, 'meta-survey-radio-email-top-btn', true ) == "on" ? ($YesNoQuestion[$i]['text'] == "Email" ? 'style="order:0;"' : 'style="order:2"' ) : '' ) . 'class="mb-4 input-margins-forms col-sm-12 wplo-survey-question ' . ($meta_survey_input_single_line == "single" ? '' : 'col-md-6') . ' ">
    <div class="form-label-group-float">
    <div class="selectdiv">
    <label class="control-label pb-2" for="question_' . ($YesNoQuestion[$i]['id']) . '">' . ($YesNoQuestion[$i]['text']) . ($cur_field_status == "true" ? ' <span class="wplosvy-alert-asterisk">*</span>' : '') . '</label>
    <select id="question_' . ($YesNoQuestion[$i]['id']) . '" class="form-control" name="question_' . ($YesNoQuestion[$i]['id']) . '" '. ($YesNoQuestion[$i]['text'] == "Email" ? 'type="email"' : 'type="text"') . ' placeholder="' . ($YesNoQuestion[$i]['text']) .'" ' . ($cur_field_status == "true" ? 'required="required"' : '') . '>
    <option value="" disabled="disabled" selected="selected">Please select response</option>
    ');

                                $i_2 = 0;

                                //Open row for input items
                                while (!empty(get_post_meta( $shortcodeID, 'meta-survey-sv-yesno-' . $i . '-label-' . $i_2, true ))) {
    //                                // Add a new array for each iteration
                                    $YesNoSubquestion[$i]['label-' . $i_2] = get_post_meta( $shortcodeID, 'meta-survey-sv-yesno-' . $i . '-label-' . $i_2, true );
                                    $YesNoSubquestion[$i]['fieldValues-' . $i_2] = get_post_meta( $shortcodeID, 'meta-survey-sv-yesno-' . $i . '-value-' . $i_2, true );

                                    if (($YesNoSubquestion[$i]['label-' . $i_2] !== null )){
                                        //Select items
                                        echo('
                                        <option id="question_' . ($YesNoQuestion[$i]['id']) . '_' . $i_2  . '" name="question_' . ($YesNoQuestion[$i]['id']) . '" value="' . ($YesNoSubquestion[$i]['fieldValues-' . $i_2]) . '">
                                        ' . $YesNoSubquestion[$i]['label-' . $i_2] . '
                                        </option>
                                        ');

                                    }
                                    $i_2++;
                                }
                                //Close row for input items

                                echo('</select></div></div></div>');

                            };




                            //shortText begins
                            $shortText = [];
                            //Iterate shorttext questions (shortTextValue)
                            if (!empty(get_post_meta( $shortcodeID, 'meta-survey-sv-shorttext-' . $i . '-type', true ))) {
                                $shortText[$i]['type'] = get_post_meta( $shortcodeID, 'meta-survey-sv-shorttext-' . $i . '-type', true );  //Just the type, aka short text
                                $shortText[$i]['text'] = get_post_meta( $shortcodeID, 'meta-survey-sv-shorttext-' . $i . '-text', true ); //Just the field label, aka what the human description is from LO
                                $shortText[$i]['id'] = get_post_meta( $shortcodeID, 'meta-survey-sv-shorttext-' . $i . '-id', true ); //Just the id, for submitting an answer to
                                $shortText[$i]['required'] = get_post_meta( $shortcodeID, 'meta-survey-sv-shorttext-' . $i . '-required', true ); //Just the required field, to set it required if true
                                $cur_field_status = $shortText[$i]['required'];

                                echo('<div ' . (get_post_meta( $shortcodeID, 'meta-survey-radio-email-top-btn', true ) == "on" ? ($shortText[$i]['text'] == "Email" ? 'style="order:0;"' : 'style="order:2"' ) : '' ) . 'class="mb-4 input-margins-forms col-sm-12 wplo-survey-question ' . ($meta_survey_input_single_line == "single" ? '' : 'col-md-6') . ' ">
    <label class="control-label pb-2" for="question_' . ($shortText[$i]['id']) . '">' . ($shortText[$i]['text']) . ($cur_field_status == "true" ? ' <span class="wplosvy-alert-asterisk">*</span>' : '') . '</label>
    <div class="form-label-group"><input id="question_' . ($shortText[$i]['id']) . '" class="form-control" name="question_' . ($shortText[$i]['id']) . '" '. ($shortText[$i]['text'] == "Email" ? 'type="email"' : 'type="text"') . ($cur_field_status == "true" ? 'required="required"' : '') . ' maxlength="40">
    </div></div>');

                            };
                            //shortText ends




                            //dateQuestion begins
                            $dateQuestion = [];
                            //Iterate dateQuestion questions (dateQuestionValue)

                            if (!empty(get_post_meta( $shortcodeID, 'meta-survey-sv-datequestion-' . $i . '-type', true ))) {
                                $dateQuestion[$i]['type'] = get_post_meta( $shortcodeID, 'meta-survey-sv-datequestion-' . $i . '-type', true );  //Just the type, aka short text
                                $dateQuestion[$i]['text'] = get_post_meta( $shortcodeID, 'meta-survey-sv-datequestion-' . $i . '-text', true ); //Just the field label, aka what the human description is from LO
                                $dateQuestion[$i]['id'] = get_post_meta( $shortcodeID, 'meta-survey-sv-datequestion-' . $i . '-id', true ); //Just the id, for submitting an answer to
                                $dateQuestion[$i]['required'] = get_post_meta( $shortcodeID, 'meta-survey-sv-datequestion-' . $i . '-required', true ); //Just the required field, to set it required if true
                                $cur_field_status = $dateQuestion[$i]['required'];

                                echo('<div ' . (get_post_meta( $shortcodeID, 'meta-survey-radio-email-top-btn', true ) == "on" ? ($dateQuestion[$i]['text'] == "Email" ? 'style="order:0;"' : 'style="order:2"' ) : '' ) . 'class="mb-4 input-margins-forms col-sm-12 wplo-survey-question ' . ($meta_survey_input_single_line == "single" ? '' : 'col-md-6') . ' ">
    <label class="control-label pb-2" for="question_' . ($dateQuestion[$i]['id']) . '">' . ($dateQuestion[$i]['text']) . ($cur_field_status == "true" ? ' <span class="wplosvy-alert-asterisk">*</span>' : '') . '</label>
    <div class="form-label-group">
    <input id="question_' . ($dateQuestion[$i]['id']) . '" class="form-control datequestion" name="question_' . ($dateQuestion[$i]['id']) . '" '. ($dateQuestion[$i]['text'] == "Email" ? 'type="email"' : 'type="text"') .'" ' . ($cur_field_status == "true" ? 'required="required"' : '') . '>
    </div>
    </div>');

                            };

                            //dateQuestion ends





                            //hidden interest begins
                            $hiddenInterest = [];
                            $hiddenInterestOption = [];
                            $h = $i;

                            //Iterate hidden interests
                            if (!empty(get_post_meta( $shortcodeID, 'meta-survey-sv-hiddenInterest-' . $h . '-type', true ))) {
                                $hiddenInterest[$h]['type'] = get_post_meta( $shortcodeID, 'meta-survey-sv-hiddenInterest-' . $h . '-type', true );
                                $hiddenInterest[$h]['text'] = get_post_meta( $shortcodeID, 'meta-survey-sv-hiddenInterest-' . $h . '-text', true );
                                $hiddenInterest[$h]['id'] = get_post_meta( $shortcodeID, 'meta-survey-sv-hiddenInterest-' . $h . '-id', true );
                                $hiddenInterest[$h]['required'] = get_post_meta( $shortcodeID, 'meta-survey-sv-hiddenInterest-' . $h . '-required', true );

    //                            if ($hiddenInterest[$h]['type'] !== null || $hiddenInterest[$h]['text'] !== null || $hiddenInterest[$h]['id'] !== null || $hiddenInterest[$h]['required'] !== null) {
    //                                echo($hiddenInterest[$h]['type']);
    //                                echo($hiddenInterest[$h]['text']);
    //                                echo($hiddenInterest[$h]['id']);
    //                                echo($hiddenInterest[$h]['required']);
    //                            }

                                $h_2 = 0;
                                while (!empty(get_post_meta( $shortcodeID, 'meta-survey-sv-hiddenInterest-' . $h . '-label-' . $h_2, true ))) {
                                    // Add a new array for each iteration
                                    $hiddenInterestOption[$h]['label-' . $h_2] = get_post_meta( $shortcodeID, 'meta-survey-sv-hiddenInterest-' . $h . '-label-' . $h_2, true );
                                    $hiddenInterestOption[$h]['value-' . $h_2] = get_post_meta( $shortcodeID, 'meta-survey-sv-hiddenInterest-' . $h . '-value-' . $h_2, true );

                                    if ($hiddenInterestOption[$h]['label-' . $h_2] !== null || $hiddenInterestOption[$h]['value-' . $h_2] !== null){
                                        echo('
                                        <input id="question_' . ($hiddenInterest[$h]['id']) . '_' . $h_2 . '" name="question_' . ($hiddenInterest[$h]['id']) . '" type="hidden" value="' . $hiddenInterestOption[$h]['value-' . $h_2] .'">                                    
                                        ');
                                        //echo($hiddenInterestOption[$h]['label-' . $h_2]);

                                    }
                                    $h_2++;
                                }

                            };




                            //HiddenTextValue begins
                            $hiddenTextValue = [];
                            //Iterate hiddentextvalue questions (HiddenTextValue)
                            if (!empty(get_post_meta( $shortcodeID, 'meta-survey-sv-hiddentextvalue-' . $i . '-type', true ))) {
                                $hiddenTextValue[$i]['type'] = get_post_meta( $shortcodeID, 'meta-survey-sv-hiddentextvalue-' . $i . '-type', true );  //Just the type, aka short text
                                $hiddenTextValue[$i]['text'] = get_post_meta( $shortcodeID, 'meta-survey-sv-hiddentextvalue-' . $i . '-text', true ); //Just the field label, aka what the human description is from LO
                                $hiddenTextValue[$i]['id'] = get_post_meta( $shortcodeID, 'meta-survey-sv-hiddentextvalue-' . $i . '-id', true ); //Just the id, for submitting an answer to

                                echo('<input id="question_' . ($hiddenTextValue[$i]['id']) . '" class="form-control" name="question_' . ($hiddenTextValue[$i]['id']) . '" '. ('type="hidden"') . ('value="' . $hiddenTextValue[$i]['text'] . '"') . '>');

                            };
                            //HiddenTextValue ends



                            //HiddenTrueFalse begins
                            $hiddenTrueFalse = [];
                            //Iterate hiddentrufalse questions (HiddenTrueFalse)
                            if (!empty(get_post_meta( $shortcodeID, 'meta-survey-sv-hiddentruefalse-' . $i . '-type', true ))) {
                                $hiddenTrueFalse[$i]['type'] = get_post_meta( $shortcodeID, 'meta-survey-sv-hiddentruefalse-' . $i . '-type', true );  //Just the type, aka short text
                                $hiddenTrueFalse[$i]['text'] = get_post_meta( $shortcodeID, 'meta-survey-sv-hiddentruefalse-' . $i . '-text', true ); //Just the field label, aka what the human description is from LO
                                $hiddenTrueFalse[$i]['id'] = get_post_meta( $shortcodeID, 'meta-survey-sv-hiddentruefalse-' . $i . '-id', true ); //Just the id, for submitting an answer to

                                echo('<input id="question_' . ($hiddenTrueFalse[$i]['id']) . '" class="form-control" name="question_' . ($hiddenTrueFalse[$i]['id']) . '" '. ('type="hidden"') . ('value="' . $hiddenTrueFalse[$i]['text'] . '"') . '>');

                            };
                            //HiddenTrueFalse ends




                            //Interest Category checkboxes
                            $categories = [];
                            $categoriesSubquestion = [];

                            //Iterate interest categories (Categories)
                            if (!empty(get_post_meta( $shortcodeID, 'meta-survey-sv-categories-' . $i . '-type', true ))) {
                            $categories[$i]['type'] = get_post_meta( $shortcodeID, 'meta-survey-sv-categories-' . $i . '-type', true );
                            $categories[$i]['text'] = get_post_meta( $shortcodeID, 'meta-survey-sv-categories-' . $i . '-text', true );
                            $categories[$i]['id'] = get_post_meta( $shortcodeID, 'meta-survey-sv-categories-' . $i . '-id', true );
                            $categories[$i]['required'] = get_post_meta( $shortcodeID, 'meta-survey-sv-categories-' . $i . '-required', true );
                            $cur_field_status = $categories[$i]['required'];

                        if ($categories[$i]['type'] !== null || $categories[$i]['text'] !== null || $categories[$i]['id'] !== null || $categories[$i]['required'] !== null || $categories[$i]['max'] !== null) {
                            //                                echo($categories[$i]['type']);
                            echo('<p class="mb-2 wplo-survey-question w-100" id="question-sentence-' . $i . '">' . $categories[$i]['text'] . ($cur_field_status == "true" ? ' <span class="wplosvy-alert-asterisk">*</span>' : '') . '</p>');
                            //                                echo($categories[$i]['id']);
                            //                                echo($categories[$i]['required']);
                        }

                        ?>
                            <script type="text/javascript">
                                jQuery(document).ready(function(){
                                    var requiredInterests = jQuery('input[name="question_<?php echo( $categories[$i]['id']) ?>"]:checkbox[required]');
                                    requiredInterests.change(function(){
                                        if(requiredInterests.is(':checked')) {
                                            requiredInterests.removeAttr('required');
                                        } else {
                                            requiredInterests.attr('required', 'required');
                                        }
                                    });
                                });
                            </script>

                        <?php


                        $i_2 = 0;

                        //Open row for input items
                        echo('<div ' . (get_post_meta( $shortcodeID, 'meta-survey-radio-email-top-btn', true ) == "on" ? 'style="display:flex;flex-direction:column;align-items:center;"' : '' ) . ' class="row w-100 mb-4' . '">');
                        while (!empty(get_post_meta( $shortcodeID, 'meta-survey-sv-categories-' . $i . '-label-' . $i_2, true ))) {
                            // Add a new array for each iteration
                            $categoriesSubquestion[$i]['label-' . $i_2] = get_post_meta( $shortcodeID, 'meta-survey-sv-categories-' . $i . '-label-' . $i_2, true );
                            $categoriesSubquestion[$i]['fieldValues-' . $i_2] = get_post_meta( $shortcodeID, 'meta-survey-sv-categories-' . $i . '-value-' . $i_2, true );

                            if (($categoriesSubquestion[$i]['label-' . $i_2] !== null )){
                                //Checkbox items
                                echo('
                                    <div ' . (get_post_meta( $shortcodeID, 'meta-survey-radio-email-top-btn', true ) == "on" ? ($categoriesSubquestion[$i]['fieldName-' . $i_2] == "cons_email" ? 'style="order:0;"' : 'style="order:2"' ) : '' ) . 'class="input-margins-forms col-sm-12 ' . ($meta_survey_input_single_line == "single" ? '' : 'col-md-6') . ' ">
                                        <div class="form-label d-flex align-items-center ml-4 mb-2 ' . '"><input id="question_' . ($categories[$i]['id']) . '_' . $i_2  . '" class="wplo-survey-form-control-checkbox mt-0 w-25" name="question_' . ($categories[$i]['id']) . '" '. 'type="checkbox"' . ' value="' . ($categoriesSubquestion[$i]['fieldValues-' . $i_2]) . ($cur_field_status == "REQUIRED" ? '' : '') .'" ' . ($cur_field_status == "REQUIRED" ? 'required="required"' : '') . '>
                                            <label class="control-label ml-3 checkbox-label w-75" style="margin-bottom:0!important;" for="question_' . ($categories[$i]['id']) . '_' . $i_2  . '">' . ($categoriesSubquestion[$i]['label-' . $i_2]) . ($cur_field_status == "true" ? 'required="required"' : '') . '</label></div>
                                    </div>
                                    ');

                            }
                            $i_2++;
                        }
                        //Close row for input items
                        echo('</div>');
                      };




                            //ComboChoice Box (select and an other field)
                            $comboChoiceQuestion = [];
                            $comboChoiceSubquestion = [];
                            //Iterate combochoicebox (ComboChoice)
                            if (!empty(get_post_meta( $shortcodeID, 'meta-survey-sv-combochoice-' . $i . '-type', true ))) {

                                $comboChoiceQuestion[$i]['type'] = get_post_meta( $shortcodeID, 'meta-survey-sv-combochoice-' . $i . '-type', true );
                                $comboChoiceQuestion[$i]['text'] = get_post_meta( $shortcodeID, 'meta-survey-sv-combochoice-' . $i . '-text', true );
                                $comboChoiceQuestion[$i]['id'] = get_post_meta( $shortcodeID, 'meta-survey-sv-combochoice-' . $i . '-id', true );
                                $comboChoiceQuestion[$i]['required'] = get_post_meta( $shortcodeID, 'meta-survey-sv-combochoice-' . $i . '-required', true );
                                $comboChoiceQuestion[$i]['max'] = get_post_meta( $shortcodeID, 'meta-survey-sv-combochoice-' . $i . '-max', true );
                                $cur_field_status = $comboChoiceQuestion[$i]['required'];


                                echo('<div ' . (get_post_meta( $shortcodeID, 'meta-survey-radio-email-top-btn', true ) == "on" ? ($comboChoiceQuestion[$i]['text'] == "Email" ? 'style="order:0;"' : 'style="order:2"' ) : '' ) . 'class="mb-4 input-margins-forms col-sm-12 wplo-survey-question ' . ($meta_survey_input_single_line == "single" ? '' : 'col-md-6') . ' ">    
    <div class="form-label-group-float">
    <div class="selectdiv">
    <label class="control-label pb-2" for="question_' . ($comboChoiceQuestion[$i]['id']) . '">' . ($comboChoiceQuestion[$i]['text']) . ($cur_field_status == "true" ? ' <span class="wplosvy-alert-asterisk">*</span>' : '') . '</label>
    <div class="w-100 d-flex">
    <input class="w-auto d-inline-flex" type="radio" id="select1' . $comboChoiceQuestion[$i]['id'] .'" name="select' . $comboChoiceQuestion[$i]['id'] . '" value="1" checked="checked">
    <select id="question_' . ($comboChoiceQuestion[$i]['id']) . '" class="form-control d-flex flex-1 ml-3" name="question_' . ($comboChoiceQuestion[$i]['id']) . '" '. ($comboChoiceQuestion[$i]['text'] == "Email" ? 'type="email"' : 'type="text"') . ' placeholder="' . ($comboChoiceQuestion[$i]['text']) .'" ' . ($cur_field_status == "true" ? 'required="required"' : '') . '>
    <option value="" disabled="disabled" selected="selected">Please select response</option>
    ');

                                $i_2 = 0;

                                //Open row for input items
                                while (!empty(get_post_meta( $shortcodeID, 'meta-survey-sv-combochoice-' . $i . '-label-' . $i_2, true ))) {
                                    //                                // Add a new array for each iteration
                                    $comboChoiceSubquestion[$i]['label-' . $i_2] = get_post_meta( $shortcodeID, 'meta-survey-sv-combochoice-' . $i . '-label-' . $i_2, true );
                                    $comboChoiceSubquestion[$i]['fieldValues-' . $i_2] = get_post_meta( $shortcodeID, 'meta-survey-sv-combochoice-' . $i . '-value-' . $i_2, true );

                                    if (($comboChoiceSubquestion[$i]['label-' . $i_2] !== null )){
                                        //Select items
                                        echo('
                                        <option id="question_' . ($comboChoiceQuestion[$i]['id']) . '_' . $i_2  . '" name="question_' . ($comboChoiceQuestion[$i]['id']) . '" value="' . ($comboChoiceSubquestion[$i]['fieldValues-' . $i_2]) . '">
                                        ' . $comboChoiceSubquestion[$i]['label-' . $i_2] . '
                                        </option>
                                        ');

                                    }
                                    $i_2++;
                                }
                                //Close row for input items

                                echo('</select></div></div></div>
                                        <div class="w-100 d-flex">
                                        <input class="w-auto d-inline-flex" type="radio" id="select2' . $comboChoiceQuestion[$i]['id'] .'" name="select' . $comboChoiceQuestion[$i]['id'] . '" value="2">
                                        <div class="form-label-group d-flex flex-1 ml-3 w-100">
                                        <input type="text" id="other_question_' . ($comboChoiceQuestion[$i]['id']) . '" class="form-control" name="question_' . ($comboChoiceQuestion[$i]['id']) . '" '. ' placeholder="Other" ' . ($cur_field_status == "true" ? 'required="required"' : '') . 'disabled="disabled"' . '>
                                        </div>
                                        </div>
                                        </div>');
?>
                        <script type="text/javascript">
                            jQuery(document).ready(function(){
                                jQuery('#select2<?php echo($comboChoiceQuestion[$i]['id']) ?>').on('change', function() {
                                    jQuery('#question_<?php echo($comboChoiceQuestion[$i]['id']) ?>').prop('disabled',true);
                                    jQuery('#other_question_<?php echo($comboChoiceQuestion[$i]['id']) ?>').prop('disabled',false);

                                });

                                jQuery('#select1<?php echo($comboChoiceQuestion[$i]['id']) ?>').on('change', function() {
                                    jQuery('#other_question_<?php echo($comboChoiceQuestion[$i]['id']) ?>').val('').prop('disabled',true);
                                    jQuery('#question_<?php echo($comboChoiceQuestion[$i]['id']) ?>').prop('disabled',false);
                                });

                            });
                        </script>
<?php
                    };  //ComboChoice Box ends





                            //MultiSingleRadio begins
                            $multiSingleRadioQuestion = [];
                            $multiSingleRadioSubquestion = [];
                            //Iterate custom questions (MultiSingleRadio)
                            if (!empty(get_post_meta( $shortcodeID, 'meta-survey-sv-multisingleradio-' . $i . '-type', true ))) {

                                $multiSingleRadioQuestion[$i]['type'] = get_post_meta( $shortcodeID, 'meta-survey-sv-multisingleradio-' . $i . '-type', true );
                                $multiSingleRadioQuestion[$i]['text'] = get_post_meta( $shortcodeID, 'meta-survey-sv-multisingleradio-' . $i . '-text', true );
                                $multiSingleRadioQuestion[$i]['id'] = get_post_meta( $shortcodeID, 'meta-survey-sv-multisingleradio-' . $i . '-id', true );
                                $multiSingleRadioQuestion[$i]['required'] = get_post_meta( $shortcodeID, 'meta-survey-sv-multisingleradio-' . $i . '-required', true );
                                $multiSingleRadioQuestion[$i]['max'] = get_post_meta( $shortcodeID, 'meta-survey-sv-multisingleradio-' . $i . '-max', true );
                                $cur_field_status = $multiSingleRadioQuestion[$i]['required'];


                                ?>

                            <script type="text/javascript">
                                jQuery(document).ready(function(){
                                    var requiredSingleRadio = jQuery('input[name="question_<?php echo($multiSingleRadioQuestion[$i]['id']) ?>"]:radio[required]');
                                    requiredSingleRadio.change(function(){
                                        if(requiredSingleRadio.is(':checked')) {
                                            requiredSingleRadio.removeAttr('required');
                                            requiredSingleRadio.removeClass('invalid');
                                        } else {
                                            requiredSingleRadio.attr('required', 'required');
                                        }
                                    });
                                });
                            </script>
                                <?php


                                echo('<p class="mb-2 wplo-survey-question w-100" id="question-sentence-' . $i . '">' . $multiSingleRadioQuestion[$i]['text'] . ' ' . ($cur_field_status == "true" ? ' <span class="wplosvy-alert-asterisk">*</span>' : '') . '</p>');

                                echo('<div ' . (get_post_meta( $shortcodeID, 'meta-survey-radio-email-top-btn', true ) == "on" ? ($multiSingleRadioQuestion[$i]['text'] == "Email" ? 'style="order:0;"' : 'style="order:2"' ) : '' ) . 'class="mb-4 input-margins-forms col-sm-12 ' . ($meta_survey_input_single_line == "single" ? '' : 'col-md-6') . ' ">
    <div class="radiodiv">
    ');

                                $i_2 = 0;

                                //Open row for input items
                                while (!empty(get_post_meta( $shortcodeID, 'meta-survey-sv-multisingleradio-' . $i . '-label-' . $i_2, true ))) {
                                    //                                // Add a new array for each iteration
                                    $multiSingleRadioSubquestion[$i]['label-' . $i_2] = get_post_meta( $shortcodeID, 'meta-survey-sv-multisingleradio-' . $i . '-label-' . $i_2, true );
                                    $multiSingleRadioSubquestion[$i]['fieldValues-' . $i_2] = get_post_meta( $shortcodeID, 'meta-survey-sv-multisingleradio-' . $i . '-value-' . $i_2, true );

                                    if (($multiSingleRadioSubquestion[$i]['label-' . $i_2] !== null )){
                                        //Select items
                                        echo('
                                        <div class="w-100 d-flex ml-4 mb-2">
                                        <input type="radio" class="w-auto d-inline-flex" id="question_' . ($multiSingleRadioQuestion[$i]['id']) . '_' . $i_2  . '" name="question_' . ($multiSingleRadioQuestion[$i]['id']) . '" value="' . ($multiSingleRadioSubquestion[$i]['fieldValues-' . $i_2]) . '" ' .  ($cur_field_status == "true" ? 'required="required"' : '') . '>
                                        <label class="control-label pb-0 d-flex flex-1 ml-3" style="margin-bottom:0!important;" for="question_' . ($multiSingleRadioQuestion[$i]['id']) . '_' . $i_2  . '">' . ($multiSingleRadioSubquestion[$i]['label-' . $i_2]) . '</label>                                        
                                        </div>
                                      
                                        ');

                                    }
                                    $i_2++;
                                }
                                //Close row for input items

                                echo('</div></div>');
                            } //multisingleradio ends





                            //numericValue begins
                            $numericValue = [];
                            //Iterate numericvalue questions (NumericValue)
                            if (!empty(get_post_meta( $shortcodeID, 'meta-survey-sv-numericvalue-' . $i . '-type', true ))) {
                                $numericValue[$i]['type'] = get_post_meta( $shortcodeID, 'meta-survey-sv-numericvalue-' . $i . '-type', true );  //Just the type, aka short text
                                $numericValue[$i]['text'] = get_post_meta( $shortcodeID, 'meta-survey-sv-numericvalue-' . $i . '-text', true ); //Just the field label, aka what the human description is from LO
                                $numericValue[$i]['id'] = get_post_meta( $shortcodeID, 'meta-survey-sv-numericvalue-' . $i . '-id', true ); //Just the id, for submitting an answer to
                                $numericValue[$i]['required'] = get_post_meta( $shortcodeID, 'meta-survey-sv-numericvalue-' . $i . '-required', true ); //Just the required field, to set it required if true
                                $cur_field_status = $numericValue[$i]['required'];

                                echo('<div ' . (get_post_meta( $shortcodeID, 'meta-survey-radio-email-top-btn', true ) == "on" ? ($numericValue[$i]['text'] == "Email" ? 'style="order:0;"' : 'style="order:2"' ) : '' ) . 'class="mb-4 input-margins-forms col-sm-12 wplo-survey-question ' . ($meta_survey_input_single_line == "single" ? '' : 'col-md-6') . ' ">
    <label class="control-label pb-2" for="question_' . ($numericValue[$i]['id']) . '">' . ($numericValue[$i]['text']) . ($cur_field_status == "true" ? ' <span class="wplosvy-alert-asterisk">*</span>' : '') . '</label>
    <div class="form-label-group"><input id="question_' . ($numericValue[$i]['id']) . '" class="form-control" name="question_' . ($numericValue[$i]['id']) . '" '. 'type="text" ' . ($cur_field_status == "true" ? 'required="required"' : '') . ' maxlength="9" pattern="[0-9]+">
    </div></div>');

                            };
                            //numericValue ends





                            //RatingScale
                            $ratingScaleQuestion = [];
                            $ratingScaleSubquestion = [];
                            //Iterate custom questions (RatingScale)
                            if (!empty(get_post_meta( $shortcodeID, 'meta-survey-sv-ratingscale-' . $i . '-type', true ))) {

                                $ratingScaleQuestion[$i]['type'] = get_post_meta( $shortcodeID, 'meta-survey-sv-ratingscale-' . $i . '-type', true );
                                $ratingScaleQuestion[$i]['text'] = get_post_meta( $shortcodeID, 'meta-survey-sv-ratingscale-' . $i . '-text', true );
                                $ratingScaleQuestion[$i]['id'] = get_post_meta( $shortcodeID, 'meta-survey-sv-ratingscale-' . $i . '-id', true );
                                $ratingScaleQuestion[$i]['required'] = get_post_meta( $shortcodeID, 'meta-survey-sv-ratingscale-' . $i . '-required', true );
                                $ratingScaleQuestion[$i]['max'] = get_post_meta( $shortcodeID, 'meta-survey-sv-ratingscale-' . $i . '-max', true );
                                $cur_field_status = $ratingScaleQuestion[$i]['required'];

                                ?>
                            <script type="text/javascript">
                                jQuery(document).ready(function(){
                                    var requiredRatingScale = jQuery('input[name="question_<?php echo($ratingScaleQuestion[$i]['id']) ?>"]:radio[required]');
                                    requiredRatingScale.change(function(){
                                        if(requiredRatingScale.is(':checked')) {
                                            requiredRatingScale.removeAttr('required');
                                            requiredRatingScale.removeClass('invalid');
                                        } else {
                                            requiredRatingScale.attr('required', 'required');
                                        }
                                    });
                                });
                            </script>
                                <?php

                                echo('<p class="mb-2 wplo-survey-question w-100" id="question-sentence-' . $i . '">' . $ratingScaleQuestion[$i]['text'] . ' ' . ($cur_field_status == "true" ? ' <span class="wplosvy-alert-asterisk">*</span>' : '') . '</p>');

                                echo('<div ' . (get_post_meta( $shortcodeID, 'meta-survey-radio-email-top-btn', true ) == "on" ? ($ratingScaleQuestion[$i]['text'] == "Email" ? 'style="order:0;"' : 'style="order:2"' ) : '' ) . 'class="mb-4 input-margins-forms col-sm-12 ' . ($meta_survey_input_single_line == "single" ? '' : 'col-md-6') . ' ">
    <div class="radiodiv">
    ');

                                $i_2 = 0;

                                //Open row for input items
                                while (!empty(get_post_meta( $shortcodeID, 'meta-survey-sv-ratingscale-' . $i . '-label-' . $i_2, true ))) {
                                    //                                // Add a new array for each iteration
                                    $ratingScaleSubquestion[$i]['label-' . $i_2] = get_post_meta( $shortcodeID, 'meta-survey-sv-ratingscale-' . $i . '-label-' . $i_2, true );
                                    $ratingScaleSubquestion[$i]['fieldValues-' . $i_2] = get_post_meta( $shortcodeID, 'meta-survey-sv-ratingscale-' . $i . '-value-' . $i_2, true );

                                    if (($ratingScaleSubquestion[$i]['label-' . $i_2] !== null )){
                                        //Select items
                                        echo('
                                        <div class="w-100 d-flex ml-4 mb-2">
                                        <input type="radio" class="w-auto d-inline-flex" id="question_' . ($ratingScaleQuestion[$i]['id']) . '_' . $i_2  . '" name="question_' . ($ratingScaleQuestion[$i]['id']) . '" ' . ($cur_field_status == "true" ? 'required="required"' : '') . ' value="' . ($ratingScaleSubquestion[$i]['fieldValues-' . $i_2]) . '">
                                        <label class="control-label pb-0 d-flex flex-1 ml-3" style="margin-bottom:0!important;" for="question_' . ($ratingScaleQuestion[$i]['id']) . '_' . $i_2  . '">' . ($ratingScaleSubquestion[$i]['label-' . $i_2]) . '</label>                                        
                                        </div>
                                      
                                        ');

                                    }
                                    $i_2++;
                                }
                                //Close row for input items

                                echo('</div></div>');
                            } //RatingScale ends






                            //textValue begins
                            $textValue = [];
                            //Iterate textvalue questions (textValue)
                            if (!empty(get_post_meta( $shortcodeID, 'meta-survey-sv-textvalue-' . $i . '-type', true ))) {
                                $textValue[$i]['type'] = get_post_meta( $shortcodeID, 'meta-survey-sv-textvalue-' . $i . '-type', true );  //Just the type, aka short text
                                $textValue[$i]['text'] = get_post_meta( $shortcodeID, 'meta-survey-sv-textvalue-' . $i . '-text', true ); //Just the field label, aka what the human description is from LO
                                $textValue[$i]['id'] = get_post_meta( $shortcodeID, 'meta-survey-sv-textvalue-' . $i . '-id', true ); //Just the id, for submitting an answer to
                                $textValue[$i]['required'] = get_post_meta( $shortcodeID, 'meta-survey-sv-textvalue-' . $i . '-required', true ); //Just the required field, to set it required if true
                                $cur_field_status = $textValue[$i]['required'];

                                echo('<div ' . (get_post_meta( $shortcodeID, 'meta-survey-radio-email-top-btn', true ) == "on" ? ($textValue[$i]['text'] == "Email" ? 'style="order:0;"' : 'style="order:2"' ) : '' ) . 'class="mb-4 input-margins-forms col-sm-12 wplo-survey-question ' . ($meta_survey_input_single_line == "single" ? '' : 'col-md-6') . ' ">
    <label class="control-label pb-2" for="question_' . ($textValue[$i]['id']) . '">' . ($textValue[$i]['text']) . ($cur_field_status == "true" ? ' <span class="wplosvy-alert-asterisk">*</span>' : '') . '</label>
    <div class="form-label-group-float"><textarea id="question_' . ($textValue[$i]['id']) . '" class="form-control" name="question_' . ($textValue[$i]['id']) . '" ' . ($cur_field_status == "true" ? 'required="required"' : '') . ' maxlength="255" rows="4"></textarea>
    </div></div>');

                            };
                            //textValue ends




                            //TrueFalse begins
                            $TrueFalseQuestion = [];
                            $TrueFalseSubquestion = [];
                            //Iterate custom questions (TrueFalse)
                            if (!empty(get_post_meta( $shortcodeID, 'meta-survey-sv-truefalse-' . $i . '-type', true ))) {

                                $TrueFalseQuestion[$i]['type'] = get_post_meta( $shortcodeID, 'meta-survey-sv-truefalse-' . $i . '-type', true );
                                $TrueFalseQuestion[$i]['text'] = get_post_meta( $shortcodeID, 'meta-survey-sv-truefalse-' . $i . '-text', true );
                                $TrueFalseQuestion[$i]['id'] = get_post_meta( $shortcodeID, 'meta-survey-sv-truefalse-' . $i . '-id', true );
                                $TrueFalseQuestion[$i]['required'] = get_post_meta( $shortcodeID, 'meta-survey-sv-truefalse-' . $i . '-required', true );
                                $TrueFalseQuestion[$i]['max'] = get_post_meta( $shortcodeID, 'meta-survey-sv-truefalse-' . $i . '-max', true );
                                $cur_field_status = $TrueFalseQuestion[$i]['required'];


                                echo('<div ' . (get_post_meta( $shortcodeID, 'meta-survey-radio-email-top-btn', true ) == "on" ? ($TrueFalseQuestion[$i]['text'] == "Email" ? 'style="order:0;"' : 'style="order:2"' ) : '' ) . 'class="mb-4 input-margins-forms col-sm-12 wplo-survey-question ' . ($meta_survey_input_single_line == "single" ? '' : 'col-md-6') . ' ">
    <div class="form-label-group-float">
    <div class="selectdiv">
    <label class="control-label pb-2" for="question_' . ($TrueFalseQuestion[$i]['id']) . '">' . ($TrueFalseQuestion[$i]['text']) . ($cur_field_status == "true" ? ' <span class="wplosvy-alert-asterisk">*</span>' : '') . '</label>
    <select id="question_' . ($TrueFalseQuestion[$i]['id']) . '" class="form-control" name="question_' . ($TrueFalseQuestion[$i]['id']) . '" '. ($TrueFalseQuestion[$i]['text'] == "Email" ? 'type="email"' : 'type="text"') . ' placeholder="' . ($TrueFalseQuestion[$i]['text']) .'" ' . ($cur_field_status == "true" ? 'required="required"' : '') . '>
    <option value="" disabled="disabled" selected="selected">Please select response</option>
    ');

                                $i_2 = 0;

                                //Open row for input items
                                while (!empty(get_post_meta( $shortcodeID, 'meta-survey-sv-truefalse-' . $i . '-label-' . $i_2, true ))) {
                                    //                                // Add a new array for each iteration
                                    $TrueFalseSubquestion[$i]['label-' . $i_2] = get_post_meta( $shortcodeID, 'meta-survey-sv-truefalse-' . $i . '-label-' . $i_2, true );
                                    $TrueFalseSubquestion[$i]['fieldValues-' . $i_2] = get_post_meta( $shortcodeID, 'meta-survey-sv-truefalse-' . $i . '-value-' . $i_2, true );

                                    if (($TrueFalseSubquestion[$i]['label-' . $i_2] !== null )){
                                        //Select items
                                        echo('
                                        <option id="question_' . ($TrueFalseQuestion[$i]['id']) . '_' . $i_2  . '" name="question_' . ($TrueFalseQuestion[$i]['id']) . '" value="' . ($TrueFalseSubquestion[$i]['fieldValues-' . $i_2]) . '">
                                        ' . $TrueFalseSubquestion[$i]['label-' . $i_2] . '
                                        </option>
                                        ');

                                    }
                                    $i_2++;
                                }
                                //Close row for input items

                                echo('</select></div></div></div>');

                            };






                            //LargeTextValue begins
                            $largeTextValue = [];
                            //Iterate textvalue questions (textValue)
                            if (!empty(get_post_meta( $shortcodeID, 'meta-survey-sv-largetextvalue-' . $i . '-type', true ))) {
                                $largeTextValue[$i]['type'] = get_post_meta( $shortcodeID, 'meta-survey-sv-largetextvalue-' . $i . '-type', true );  //Just the type, aka short text
                                $largeTextValue[$i]['text'] = get_post_meta( $shortcodeID, 'meta-survey-sv-largetextvalue-' . $i . '-text', true ); //Just the field label, aka what the human description is from LO
                                $largeTextValue[$i]['id'] = get_post_meta( $shortcodeID, 'meta-survey-sv-largetextvalue-' . $i . '-id', true ); //Just the id, for submitting an answer to
                                $largeTextValue[$i]['required'] = get_post_meta( $shortcodeID, 'meta-survey-sv-largetextvalue-' . $i . '-required', true ); //Just the required field, to set it required if true
                                $cur_field_status = $largeTextValue[$i]['required'];

                                echo('<div ' . (get_post_meta( $shortcodeID, 'meta-survey-radio-email-top-btn', true ) == "on" ? ($largeTextValue[$i]['text'] == "Email" ? 'style="order:0;"' : 'style="order:2"' ) : '' ) . 'class="mb-4 input-margins-forms col-sm-12 wplo-survey-question ' . ($meta_survey_input_single_line == "single" ? '' : 'col-md-6') . ' ">
    <label class="control-label pb-2" for="question_' . ($largeTextValue[$i]['id']) . '">' . ($largeTextValue[$i]['text']) . ($cur_field_status == "true" ? ' <span class="wplosvy-alert-asterisk">*</span>' : '') . '</label>
    <div class="form-label-group-float"><textarea id="question_' . ($largeTextValue[$i]['id']) . '" class="form-control" name="question_' . ($largeTextValue[$i]['id']) . '" ' . ($cur_field_status == "true" ? 'required="required"' : '') . ' rows="10"></textarea>
    </div></div>');

                            };
                            //textValue ends





                            $x++;
                        }

                        //Close row for input items
                        echo('</div>');


                        echo("<br />");


                        ?>




                    <div class="wplo-panel wplo-panel-default panel-df-input-padding mt-0 mb-0" id="submitSection">


                        <div class="mt-0 mb-2">

                            <?php
                            $meta_sv_reset_label = get_post_meta( $shortcodeID, 'meta-survey-sv-reset-label', true );
                            $meta_sv_submit_label = get_post_meta( $shortcodeID, 'meta-survey-sv-submitlabel-label', true );
                            $meta_sv_cancel_label = get_post_meta( $shortcodeID, 'meta-survey-sv-cancel-label', true );
                            $meta_sv_skip_label = get_post_meta( $shortcodeID, 'meta-survey-sv-skipurl-label', true );

                            $meta_sv_reset_enable_btn = get_post_meta( $shortcodeID, 'meta-survey-radio-reset-btn', true );
                            $meta_sv_skip_enable_btn = get_post_meta( $shortcodeID, 'meta-survey-radio-skip-btn', true );

                            ?>

                            <div class="flex justify-content-center text-center">

                                <button type="submit" id="survey-submit" class="btn-w-100-mobile justify-content-center button d-inline-flex btn-wp-lo-form align-items-center mb-3 mb-md-0"><?php if( !empty( $meta_sv_submit_label ) ) {echo $meta_sv_submit_label;} ?></button>

                                <?php if($meta_sv_reset_enable_btn == "on"):?>
                                <button type="reset" id="survey-reset" class="btn-w-100-mobile justify-content-center button d-inline-flex btn-wp-lo-form align-items-center mb-3 mb-md-0"><?php if( !empty( $meta_sv_reset_label ) ) {echo $meta_sv_reset_label;} ?></button>
                                <?php endif?>

                                <?php if(($meta_sv_skip_enable_btn == "on")):?>
                                <button type="button" id="survey-skip" class="btn-w-100-mobile justify-content-center button d-inline-flex btn-wp-lo-form align-items-center mb-3 mb-md-0" onclick="location.href='<?php if( !empty( $meta_sv_cancel_label ) ) {echo $meta_sv_cancel_label;} ?>';"><?php if( !empty( $meta_sv_skip_label ) ) {echo $meta_sv_skip_label;} ?></button>
                                <?php endif ?>

                            </div>

                            <br />

                            <div class="container-fluid">
                                <div class="row justify-content-center">
                                    <div class="col-md-12">
                                        <div class="outroParagraph">
                                            <?php $meta_value_license = get_post_meta( $shortcodeID, '_meta-survey-privacy-paragraph', true );
                                            if( !empty( $meta_value_license ) ) {echo $meta_value_license;} ?>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>


                    </div>



                </form>
            </div>

            <!-- /Survey Form -->

        </div>


    </div><!-- #primary -->

</div>

<?php
if (!isset($HF_off)) {
    get_footer();
};
