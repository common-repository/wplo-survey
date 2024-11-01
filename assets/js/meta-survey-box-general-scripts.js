jQuery(document).ready(function($){

    $('#localeSelect').on('change', function() {
        WPLOSURVEY_update_surveyLocaleInit(this.value);
    });

});




