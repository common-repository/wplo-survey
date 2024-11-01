function tabSetup(event) {
    event.preventDefault();

    jQuery(".nav-tab").removeClass("survey-tab-active");
    jQuery(event.currentTarget).addClass("survey-tab-active");

    jQuery('#meta-survey-box-survey-css').hide();
    jQuery('#meta-survey-box-survey-custom-css').hide();
    jQuery('#meta-survey-box-survey-thankyou').hide();
    jQuery('#meta-survey-box-survey-analytics').hide();


    jQuery('#meta-survey-box-survey-id').show();
    jQuery('#meta-survey-box-survey-questions').show();

}

function tabStyle(event) {
    event.preventDefault();

    jQuery(".nav-tab").removeClass("survey-tab-active");
    jQuery(event.currentTarget).addClass("survey-tab-active");

    jQuery('#meta-survey-box-survey-id').hide();
    jQuery('#meta-survey-box-survey-questions').hide();
    jQuery('#meta-survey-box-survey-thankyou').hide();
    jQuery('#meta-survey-box-survey-analytics').hide();


    jQuery('#meta-survey-box-survey-css').show();
    jQuery('#meta-survey-box-survey-custom-css').show();


}

function tabSections(event) {
    event.preventDefault();

    jQuery(".nav-tab").removeClass("survey-tab-active");
    jQuery(event.currentTarget).addClass("survey-tab-active");

    jQuery('#meta-survey-box-survey-id').hide();
    jQuery('#meta-survey-box-survey-questions').hide();
    jQuery('#meta-survey-box-survey-css').hide();
    jQuery('#meta-survey-box-survey-custom-css').hide();
    jQuery('#meta-survey-box-survey-analytics').hide();

    jQuery('#meta-survey-box-survey-thankyou').show();


}

function tabAnalytics(event) {
    event.preventDefault();

    jQuery(".nav-tab").removeClass("survey-tab-active");
    jQuery(event.currentTarget).addClass("survey-tab-active");

    jQuery('#meta-survey-box-survey-id').hide();
    jQuery('#meta-survey-box-survey-questions').hide();
    jQuery('#meta-survey-box-survey-css').hide();
    jQuery('#meta-survey-box-survey-custom-css').hide();
    jQuery('#meta-survey-box-survey-thankyou').hide();

    jQuery('#meta-survey-box-survey-analytics').show();

}
