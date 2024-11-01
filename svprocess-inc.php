<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
?>
<script type="text/javascript">

    var ready = function ( fn ) {

        // Sanity check
        if ( typeof fn !== 'function' ) return;

        // If document is already loaded, run method
        if ( document.readyState === 'complete'  ) {
            return fn();
        }

        // Otherwise, wait until document is loaded
        document.addEventListener( 'DOMContentLoaded', fn, false );

    };

    //Ensure array utility adapted from Noah Cooper's Luminate Extend library utilities https://github.com/noahcooper/luminateExtend
    function WPLOSURVEY_ensureArray(pArray) {
        if(jQuery.isArray(pArray)) {
            return pArray;
        }
        else if(pArray) {
            return [pArray];
        }
        else {
            return [];
        }
    }


    function WPLOSURVEY_validate_survey(event) {
        event.preventDefault();
        if(jQuery('#wp-svy-form').valid()){
            WPLOSURVEY_process_survey(event);
        }
    }



    //function for processing survey submit
    function WPLOSURVEY_process_survey(event) {
        event.preventDefault();

        jQuery('#surveyAlertWrapper').empty().hide();


        jQuery('#wploSvy_ajaxModal').modal('show');
        var formLoaded9lb4TkY0 = jQuery('input[name=formLoaded9lb4TkY0]').val();
        var phone_number_95231er = jQuery('input[name=phone_number_95231er]').val();


        window.submitSurveyCallback = {
            error: function(data) {
                console.log(data);

                jQuery('#wploSvy_ajaxModal').modal('hide');

                jQuery('#surveyAlertWrapper').empty().show();

                jQuery('#wp-svy-form').show();

                if (data.errorResponse.code){

                    switch(data.errorResponse.code){
                        case "1725":
                            errorMessage = "Error code " + data.errorResponse.code + ": You've already signed up for this form. If you've received this message in error, contact us at <a href=\"mailto:<?php $meta_survey_contact_email = get_post_meta($shortcodeID, 'meta-survey-survey-contact-email', true); if (!empty($meta_survey_contact_email)) {echo $meta_survey_contact_email;} ?>\"><?php echo $meta_survey_contact_email ?>";
                            break;
                        default:
                            errorMessage = "Error code " + data.errorResponse.code + ": " + data.errorResponse.message + ". Contact us with this info at <a href=\"mailto:<?php $meta_survey_contact_email = get_post_meta($shortcodeID, 'meta-survey-survey-contact-email', true); if (!empty($meta_survey_contact_email)) {echo $meta_survey_contact_email;} ?>\"><?php echo $meta_survey_contact_email ?>";
                    }

                    jQuery('#surveyAlertWrapper').append('<p class="alert-danger alert" id="wplo-survey-errors">' + errorMessage + '</p>');

                }

                if(data.errorResponse.fieldError) {
                    var fieldErrors = luminateExtend.utils.WPLOSURVEY_ensureArray(data.errorResponse.fieldError);
                    jQuery.each(fieldErrors, function() {
                        jQuery('#surveyAlertWrapper').append('<div class="alert alert-danger">' +
                            this +
                            '</div>');
                    });
                }

                if (jQuery(window).width() <= 767) {
                    jQuery('html, body').animate({
                        scrollTop: jQuery('#surveyAlertWrapper').offset().top + (-50)
                    }, 600);
                }
                else{
                    jQuery('html, body').animate({
                        scrollTop: jQuery('#surveyAlertWrapper').offset().top + (-150)
                    }, 600);
                }

            },
            success: function(data) {
                console.log(data);

                <?php $meta_survey_radio_ty_btn_override = get_post_meta( $shortcodeID, 'meta-survey-radio-ty-btn-override', true ); if( ( $meta_survey_radio_ty_btn_override != "on" ) ) : ?>
                jQuery('#wploSvy_ajaxModal').modal('hide');
                <?php endif ?>

                jQuery('#surveyAlertWrapper').empty().hide();

                if(data.submitSurveyResponse.success == 'false') {

                    // if (data.submitSurveyResponse.errors){
                    //switch(data.submitSurveyResponse.errors.errorCode){
                    //    case "1725":
                    //        errorMessage = "Error code " + data.submitSurveyResponse.errors.errorCode + ": You've already signed up to receive updates to this email address.";
                    //        break;
                    //    case "1734":
                    //        errorMessage = "Error code " + data.submitSurveyResponse.errors.errorCode + ": This email address is already in use by another person with a different name in our database- use a different email address to submit survey.";
                    //        break;
                    //    case "1733":
                    //        errorMessage = "Error code " + data.submitSurveyResponse.errors.errorCode + ": Invalid number of selections for checkboxes- check if you have selected more than the maximum number of choices!";
                    //        break;
                    //    default:
                    //        errorMessage = "Error code " + data.submitSurveyResponse.errors.errorCode + ": " + data.submitSurveyResponse.errors.errorMessage + " Contact us with this info at <a href=\"mailto:<?php //$meta_survey_contact_email = get_post_meta($shortcodeID, 'meta-survey-survey-contact-email', true); if (!empty($meta_survey_contact_email)) {echo $meta_survey_contact_email;} ?>//\"><?php //echo $meta_survey_contact_email ?>//";
                    //}
                    // jQuery('#surveyAlertWrapper').append('<p class="alert-danger alert" id="wplo-survey-errors">' + errorMessage + '</p>');
                    // }

                    var surveyErrors = luminateExtend.utils.ensureArray(data.submitSurveyResponse.errors);
                    jQuery.each(surveyErrors, function() {
                        if(this.errorField) {
                            jQuery('#surveyAlertWrapper').append('<div class="alert alert-danger">' +
                                this.errorMessage +
                                '</div>');
                        }
                    });

                    jQuery('#surveyAlertWrapper').show();


                    if (jQuery(window).width() <= 767) {
                        jQuery('html, body').animate({
                            scrollTop: jQuery('#surveyAlertWrapper').offset().top + (-50)
                        }, 600);
                    }
                    else{
                        jQuery('html, body').animate({
                            scrollTop: jQuery('#surveyAlertWrapper').offset().top + (-150)
                        }, 600);
                    }

                    jQuery('#wp-svy-form').show();



                    jQuery('#wploSvy_ajaxModal').modal('hide');

                }

                else {
                    if (data.submitSurveyResponse.success) {

                        <?php $meta_radio_analytics = get_post_meta( $shortcodeID, 'meta-survey-radio-analytics', true ); if( ( $meta_radio_analytics == "on" ) ) : ?>

                        dataLayer.push({
                            'event': '<?php $meta_analytics_event = get_post_meta( $shortcodeID, 'meta-survey-analytics-event', true ); if( !empty( $meta_analytics_event ) ) {echo $meta_analytics_event;} ?>',
                            'eventCategory': '<?php $meta_analytics_event_category = get_post_meta( $shortcodeID, 'meta-survey-analytics-event-category', true ); if( !empty( $meta_analytics_event_category ) ) {echo $meta_analytics_event_category;} ?>',
                            'eventAction': 'survey',
                            'eventLabel': window.location.href, // Page URL where form is submitted
                            'eventValue': '<?php $meta_analytics_event_value = get_post_meta( $shortcodeID, 'meta-survey-analytics-event-value', true ); if( !empty( $meta_analytics_event_value ) ) {echo $meta_analytics_event_value;} ?>',
                        });

                        <?php endif ?>

                        <?php $meta_radio_fb_analytics = get_post_meta( $shortcodeID, 'meta-survey-radio-fb-analytics', true ); if( ( $meta_radio_fb_analytics == "on" ) ) : ?>

                        fbq('track', 'CompleteRegistration');

                        <?php endif ?>

                        <?php $meta_survey_radio_ty_btn_override = get_post_meta( $shortcodeID, 'meta-survey-radio-ty-btn-override', true ); if( ( $meta_survey_radio_ty_btn_override == "on" ) ) : ?>


                        <?php $meta_sv_submiturl_label = get_post_meta( $shortcodeID, 'meta-survey-sv-submiturl-label', true ); ?>

                        var tyButtonLoLink= "<?php echo $meta_sv_submiturl_label; ?>";

                        window.location = tyButtonLoLink;

                        <?php else : ?>


                        <?php $meta_sv_radio_ty_content_btn = get_post_meta( $shortcodeID, 'meta-survey-radio-ty-content-btn', true );
                        if($meta_sv_radio_ty_content_btn == "lo"):?>

                        var tyText = data.submitSurveyResponse.thankYouPageContent;

                        <?php else:?>

                        var tyText = encodeURIComponent(jQuery("#ty_text_encoded").html());

                        <?php endif;?>

                        jQuery('#surveyAlertWrapper').empty().show();

                        jQuery('#surveyAlertWrapper').append(decodeURIComponent(tyText));
                        jQuery('#wp-svy-form').hide();

                        <?php $meta_radio_ty_btn = get_post_meta( $shortcodeID, 'meta-survey-radio-ty-btn', true ); if( ( $meta_radio_ty_btn == "on" ) ) : ?>
                        var tyButtonLink = "<?php $meta_ty_btn_link = get_post_meta( $shortcodeID, 'meta-survey-radio-ty-btn-link', true ); if( !empty( $meta_ty_btn_link ) ) {echo $meta_ty_btn_link;} ?>";
                        var tyButtonText = "<?php $meta_ty_btn_text = get_post_meta( $shortcodeID, 'meta-survey-radio-ty-btn-text', true ); if( !empty( $meta_ty_btn_text ) ) {echo $meta_ty_btn_text;} ?>";
                        var tyButton = "<div class=\"text-center mt-5\"><a class=\"button btn-wp-lo-form-ty mt-4 mb-0\" href=\"" + tyButtonLink + "\">" + tyButtonText + "</a></div>";
                        jQuery('#surveyAlertWrapper').append(tyButton);
                        <?php endif ?>

                        if (jQuery(window).width() <= 767) {
                            jQuery('html, body').animate({
                                scrollTop: jQuery('#surveyAlertWrapper').offset().top + (-50)
                            }, 600);
                        }
                        else{
                            jQuery('html, body').animate({
                                scrollTop: jQuery('#surveyAlertWrapper').offset().top + (-150)
                            }, 600);
                        }

                        <?php endif ?>


                    }
                }
            }
        };


        var a1= jQuery.ajax({
                type: "POST",
                url: "<?php echo get_site_url(); ?>/wp-admin/admin-ajax.php",
                data: "action=dosurvey&formLoaded9lb4TkY0=" + formLoaded9lb4TkY0 + "&phone_number_95231er=" + phone_number_95231er,
                dataType: 'json',
                cache: false,
                success: function (data) {
                    //console.log(data);
                    var errorMessage;
                    jQuery('#surveyAlertWrapper').empty();

                    //Prelim errors
                    if (data.surveyRejected) {
                        jQuery('#wploSvy_ajaxModal').modal('hide');
                        jQuery('#surveyAlertWrapper').show();

                        switch(data.surveyRejected.code){
                            case "42fy":
                                errorMessage = "Error code " + data.surveyRejected.code + ": " + data.surveyRejected.message + " Contact us with this info at <a href=\"mailto:<?php $meta_df_contact_email = get_post_meta( $shortcodeID, 'meta-survey-df-contact-email', true ); if( !empty( $meta_df_contact_email ) ) {echo $meta_df_contact_email;} ?>\"><?php echo $meta_df_contact_email ?></a>";
                                break;
                            default:
                                errorMessage = "Error code " + data.surveyRejected.code + ": " + data.surveyRejected.message;
                        }

                        jQuery('#surveyAlertWrapper').empty().append('<p class="alert-danger alert" id="donation-errors">' + errorMessage + '</p>');
                        jQuery('html, body').animate({
                            scrollTop: jQuery('#surveyAlertWrapper').offset().top + (-40)
                        }, 0);
                    }
                }
            }),
            a2 = a1.then(function(data) {
                // .then() returns a new promise
                if (data.surveyAccepted){

                    var formdata = jQuery('#wp-svy-form').serialize();


                    <?php
                    $meta_survey_radio_logoutSvy = get_post_meta( $shortcodeID, 'meta-survey-radio-logoutSvy-btn', true );
                    ?>

                    <?php if ($meta_survey_radio_logoutSvy == "on") : ?>

                    var doLogoutCallback = function(data) {
                        luminateExtend.api.request({
                            api: 'CRSurveyAPI',
                            callback: submitSurveyCallback,
                            data: formdata,
                            requiresAuth: "true"
                        });
                    };

                    luminateExtend.api({
                        api: 'cons',
                        callback: doLogoutCallback,
                        data: 'method=logout'
                    });

                    <?php else :?>

                    luminateExtend.api.request({
                        api: 'CRSurveyAPI',
                        callback: submitSurveyCallback,
                        data: formdata,
                        requiresAuth: "true"
                    });

                    <?php endif?>

                    // console.log(formdata);



                }
            });


    }

</script>


<div id="ty_text_encoded" style="position:absolute;visibility:hidden;left:-30000px;">
    <?php $meta_ty = get_post_meta( $shortcodeID, '_meta-survey-thank-you-paragraph', true ); if( !empty( $meta_ty ) ) {echo $meta_ty;} ?>
</div>
