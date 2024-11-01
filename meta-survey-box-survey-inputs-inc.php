<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
?>
<?php require_once(plugin_dir_path( __FILE__ ) . "cnct-set.php"); ?>

<script type="text/javascript">


    var entityMap = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#39;',
        '/': '&#x2F;',
        '`': '&#x60;',
        '=': '&#x3D;'
    };

    function escapeHtml (string) {
        return String(string).replace(/[&<>"'`=\/]/g, function (s) {
            return entityMap[s];
        });
    }

    function WPLOSURVEY_update_surveyInputs(event) {
        event.preventDefault();

        var survey_id_js = jQuery('#meta-survey-svy-setup').val();

        var newDate = new Date();

        luminateExtend.api.request({
            api: 'CRSurveyAPI',
            data: "method=getSurvey&preventCache="+newDate+"&survey_id=" + survey_id_js,
            callback: submitSurveyCallback,
            useCache : "false",
            requiresAuth: "true"
        });

    }

    function WPLOSURVEY_update_surveyLocaleInit(varLocale) {

        luminateExtend.init({
            apiKey: '<?php echo get_option( 'WPLOSURVEY_apiKey' ); ?>',
            path: {
                nonsecure: '<?php echo get_option( 'WPLOSURVEY_nonsecure' ); ?>',
                secure: '<?php echo get_option( 'WPLOSURVEY_secure' ); ?>'
            },
            locale: varLocale
        });

        WPLOSURVEY_update_surveyInputs(event);

    }

    submitSurveyCallback = {
        error: function(data) {
            console.log(data);
            jQuery("#connection-test").empty().append(
                '<h3>Testing Connection:</h3>' +
                '' +
                '<h2><span class="dashicons dashicons-no" style="color:red;"></span> Connection not working!  Check browser console log for more info or input form id and click get levels.</h2>'
            )
        },
        success: function(data) {
             console.log(data);
            if (data.getSurveyResponse.survey.surveyId)
            {
                jQuery("#form-test").empty();
                jQuery("#connection-test").empty().append(
                    '<h3>Testing Connection:</h3>' +
                    '' +
                    '<h2><span class="dashicons dashicons-yes" style="color:green;"></span> Survey form connected!</h2>'
                );
                // console.log(typeof data.getSurveyResponse.survey.surveyQuestions);


                //Append base skip button label
                jQuery("#form-test").append('' +
                    '    <div class="df-row-container">\n' +
                    '        <div class="df-row-input">Survey Name: ' +
                    '        <input type="text" name="meta-survey-sv-surveyname-label" id="meta-survey-sv-surveyname-label" value="' + data.getSurveyResponse.survey.surveyName + '" readonly="readonly" />\n' +
                    '    </div>'
                );

                if((data.getSurveyResponse.survey.surveyIntroduction !== undefined) ||  (data.getSurveyResponse.survey.surveyIntroduction !== null) || (data.getSurveyResponse.survey.hasOwnProperty('surveyIntroduction') && data.getSurveyResponse.survey.surveyIntroduction.length > 0)){
                    if(data.getSurveyResponse.survey.surveyIntroduction == "[object Object]"){

                    }
                    else{
                        jQuery("#form-test").append('' +
                            '    <div class="df-row-container">\n' +
                            '        <div class="df-row-input">Survey Intro' +
                            '        <textarea type="text" name="meta-survey-sv-introduction-label" id="meta-survey-sv-introduction-label" style="width:100%;" rows="4" readonly="readonly" />' +
                            '</textarea></div>'
                        );
                        jQuery("#meta-survey-sv-introduction-label").val(data.getSurveyResponse.survey.surveyIntroduction);
                    }
                }

                if(data.getSurveyResponse.survey.isNumberQuestions !== undefined){
                    jQuery("#form-test").append('' +
                        '    <div class="df-row-container">\n' +
                        '        <div class="df-row-input">Numbered Questions:' +
                        '        <input type="text" name="meta-survey-sv-numbered-label" id="meta-survey-sv-numbered-label" value="' + data.getSurveyResponse.survey.isNumberQuestions + '" readonly="readonly" />' +
                        '</div>'
                    );
                    jQuery("#meta-survey-sv-numbered-label").val(data.getSurveyResponse.survey.isNumberQuestions);
                }


                subQuestionMaxCount = 0;

                if(!Array.isArray(data.getSurveyResponse.survey.surveyQuestions)) {


                    var tempArray = new Array();
                    tempArray[0] = data.getSurveyResponse.survey.surveyQuestions;
                    data.getSurveyResponse.survey.surveyQuestions = tempArray;


                    // var i = 0;
                    // switch(data.getSurveyResponse.survey.surveyQuestions.questionType) {
                    //
                    //     case "Caption":
                    //         var k = 0;
                    //         //Append base question info
                    //         jQuery("#form-test").append('' +
                    //             '    <div class="df-row-container"><span class="wplo-survey-number-block">' + (i+1) + '.) </span>' +
                    //             '        <div class="df-row-input">' +
                    //             '        <input type="text" name="meta-survey-sv-caption-'+ (i) + '-type" id="meta-survey-sv-caption-'+ (i) + '-type" value="' + data.getSurveyResponse.survey.surveyQuestions.questionType + '" readonly="readonly" />\n' +
                    //             '        <input type="text" name="meta-survey-sv-caption-'+ (i) + '-text" id="meta-survey-sv-caption-'+ (i) + '-text" value="' + escapeHtml(data.getSurveyResponse.survey.surveyQuestions.questionText) + '" readonly="readonly" />\n' +
                    //             '        <input type="text" name="meta-survey-sv-caption-'+ (i) + '-id" id="meta-survey-sv-caption-'+ (i) + '-id" value="' + data.getSurveyResponse.survey.surveyQuestions.questionId  + '" readonly="readonly" />\n' +
                    //             '        <input type="text" name="meta-survey-sv-caption-'+ (i) + '-hidden" id="meta-survey-sv-caption-'+ (i) + '-hidden" value="' + data.getSurveyResponse.survey.surveyQuestions.hidden + '" readonly="readonly" />\n' +
                    //             '        <input type="text" name="meta-survey-sv-caption-'+ (i) + '-required" id="meta-survey-sv-caption-'+ (i) + '-required" value="' + data.getSurveyResponse.survey.surveyQuestions.questionRequired + '" readonly="readonly" /></div>\n' +
                    //             '    </div>'
                    //         );
                    //         break;
                    //
                    //     case "ConsQuestion":
                    //         //Append base question info
                    //         jQuery("#form-test").append('' +
                    //             '    <div class="df-row-container"><span class="wplo-survey-number-block">' + (i+1) + '.) </span>' +
                    //             '        <div class="df-row-input">' +
                    //             '        <input type="text" name="meta-survey-sv-question-'+ (i) + '-type" id="meta-survey-sv-question-'+ (i) + '-type" value="' + data.getSurveyResponse.survey.surveyQuestions.questionType + '" readonly="readonly" />\n' +
                    //             '        <input type="text" name="meta-survey-sv-question-'+ (i) + '-text" id="meta-survey-sv-question-'+ (i) + '-text" value="' + data.getSurveyResponse.survey.surveyQuestions.questionText + '" readonly="readonly" />\n' +
                    //             '        <input type="text" name="meta-survey-sv-question-'+ (i) + '-id" id="meta-survey-sv-question-'+ (i) + '-id" value="' + data.getSurveyResponse.survey.surveyQuestions.questionId  + '" readonly="readonly" />\n' +
                    //             '        <input type="text" name="meta-survey-sv-question-'+ (i) + '-required" id="meta-survey-sv-question-'+ (i) + '-required" value="' + data.getSurveyResponse.survey.surveyQuestions.questionRequired + '" readonly="readonly" /></div>\n' +
                    //             '    </div>'
                    //         );
                    //         //Iterate through question values
                    //         var contactInfoFieldLength = data.getSurveyResponse.survey.surveyQuestions.questionTypeData.consRegInfoData.contactInfoField.length;
                    //         var get_fieldOptionValues = null;
                    //         if (!Array.isArray(data.getSurveyResponse.survey.surveyQuestions.questionTypeData.consRegInfoData.contactInfoField)){
                    //             contactInfoFieldLength = 1;
                    //             get_fieldOptionValues = data.getSurveyResponse.survey.surveyQuestions.questionTypeData.consRegInfoData.contactInfoField;
                    //         }
                    //         for(var i_2 = 0, fLen = contactInfoFieldLength; i_2 < fLen; i_2++){
                    //
                    //             if (Array.isArray(data.getSurveyResponse.survey.surveyQuestions.questionTypeData.consRegInfoData.contactInfoField)){
                    //                 get_fieldOptionValues = data.getSurveyResponse.survey.surveyQuestions.questionTypeData.consRegInfoData.contactInfoField[i_2];
                    //             }
                    //
                    //             if (get_fieldOptionValues.fieldOptionValues) {
                    //
                    //                 let fieldValueOptionsHtml;
                    //
                    //
                    //                 // console.log(get_fieldOptionValues);
                    //
                    //                 var fVCount = 0;
                    //                 if (!Array.isArray(get_fieldOptionValues.fieldOptionValues)){
                    //                     fieldValueOptionsHtml += get_fieldOptionValues.label + ",";
                    //                 }
                    //                 else{
                    //                     for (let fieldValue of get_fieldOptionValues.fieldOptionValues) {
                    //                         if (fieldValue.value.length > 0) {
                    //                             fieldValueOptionsHtml += fieldValue.label + ",";
                    //                         }
                    //                         fVCount++;
                    //                     }
                    //                 }
                    //
                    //
                    //                 fieldValueOptionsHtml = fieldValueOptionsHtml.replace("undefined", "");
                    //                 fieldValueOptionsHtml = fieldValueOptionsHtml.replace(/^,/, '');
                    //                 fieldValueOptionsHtml = fieldValueOptionsHtml.replace(/,\s*$/, "");
                    //
                    //                 // console.log(fieldValueOptionsHtml);
                    //
                    //                 jQuery("#form-test").append('' +
                    //                     '    <div class="df-row-container">\n' +
                    //                     '        <div class="df-row-input">' + '-->' + (i_2 + 1) + ') ' +
                    //                     '        <input type="text" name="meta-survey-sv-question-' + (i) + '-label-' + (i_2) + '" id="meta-survey-sv-question-' + (i) + '-label-' + (i_2) + '" value="' + get_fieldOptionValues.label.replace(/\:$/, '') + '" readonly="readonly" />\n' +
                    //                     '        <input type="text" name="meta-survey-sv-question-' + (i) + '-fieldName-' + (i_2) + '" id="meta-survey-sv-question-' + (i) + '-fieldName-' + (i_2) + '" value="' + get_fieldOptionValues.fieldName + '" readonly="readonly" />\n' +
                    //                     '        <input type="text" name="meta-survey-sv-question-' + (i) + '-fieldStatus-' + (i_2) + '" id="meta-survey-sv-question-' + (i) + '-fieldStatus-' + (i_2) + '" value="' + get_fieldOptionValues.fieldStatus + '" readonly="readonly" />\n' +
                    //                     '        <textarea type="text" name="meta-survey-sv-question-' + (i) + '-fieldValues-' + (i_2) + '" id="meta-survey-sv-question-' + (i) + '-fieldValues-' + (i_2) + '">' +
                    //                     fieldValueOptionsHtml +
                    //                     '</textarea></div>\n' +
                    //                     '    </div>'
                    //                 );
                    //             }
                    //             else{
                    //                 jQuery("#form-test").append('' +
                    //                     '    <div class="df-row-container">\n' +
                    //                     '        <div class="df-row-input">' + '-->' + (i_2+1) + ') ' +
                    //                     '        <input type="text" name="meta-survey-sv-question-'+ (i) + '-label-' + (i_2) + '" id="meta-survey-sv-question-'+ (i) + '-label-' + (i_2) + '" value="' + get_fieldOptionValues.label.replace(/\:$/, '') + '" readonly="readonly" />\n' +
                    //                     '        <input type="text" name="meta-survey-sv-question-'+ (i) + '-fieldName-' + (i_2) + '" id="meta-survey-sv-question-'+ (i) + '-fieldName-' + (i_2) + '" value="' + get_fieldOptionValues.fieldName + '" readonly="readonly" />\n' +
                    //                     '        <input type="text" name="meta-survey-sv-question-'+ (i) + '-fieldStatus-' + (i_2) + '" id="meta-survey-sv-question-'+ (i) + '-fieldStatus-' + (i_2) + '" value="' + get_fieldOptionValues.fieldStatus + '" readonly="readonly" /></div>\n' +
                    //                     '    </div>'
                    //                 );
                    //             }
                    //
                    //         }
                    //         // console.log(data.getSurveyResponse.survey.surveyQuestions.hidden);
                    //         break;
                    //
                    //     case "DateQuestion":
                    //         var k = 0;
                    //         //Append base question info
                    //         jQuery("#form-test").append('' +
                    //             '    <div class="df-row-container"><span class="wplo-survey-number-block">' + (i+1) + '.) </span>' +
                    //             '        <div class="df-row-input">' +
                    //             '        <input type="text" name="meta-survey-sv-datequestion-'+ (i) + '-type" id="meta-survey-sv-datequestion-'+ (i) + '-type" value="' + data.getSurveyResponse.survey.surveyQuestions.questionType + '" readonly="readonly" />\n' +
                    //             '        <input type="text" name="meta-survey-sv-datequestion-'+ (i) + '-text" id="meta-survey-sv-datequestion-'+ (i) + '-text" value="' + data.getSurveyResponse.survey.surveyQuestions.questionText + '" readonly="readonly" />\n' +
                    //             '        <input type="text" name="meta-survey-sv-datequestion-'+ (i) + '-id" id="meta-survey-sv-datequestion-'+ (i) + '-id" value="' + data.getSurveyResponse.survey.surveyQuestions.questionId  + '" readonly="readonly" />\n' +
                    //             '        <input type="text" name="meta-survey-sv-datequestion-'+ (i) + '-required" id="meta-survey-sv-datequestion-'+ (i) + '-required" value="' + data.getSurveyResponse.survey.surveyQuestions.questionRequired + '" readonly="readonly" /></div>\n' +
                    //             '    </div>'
                    //         );
                    //         break;
                    //
                    //     case "HiddenInterests":
                    //         //Append base question info
                    //         jQuery("#form-test").append('' +
                    //             '    <div class="df-row-container"><span class="wplo-survey-number-block">' + (i+1) + '.) </span>' +
                    //             '        <div class="df-row-input">' +
                    //             '        <input type="text" name="meta-survey-sv-hiddenInterest-'+ (i) + '-type" id="meta-survey-sv-hiddenInterest-'+ (i) + '-type" value="' + data.getSurveyResponse.survey.surveyQuestions.questionType + '" readonly="readonly" />\n' +
                    //             '        <input type="text" name="meta-survey-sv-hiddenInterest-'+ (i) + '-text" id="meta-survey-sv-hiddenInterest-'+ (i) + '-text" value="' + data.getSurveyResponse.survey.surveyQuestions.questionText + '" readonly="readonly" />\n' +
                    //             '        <input type="text" name="meta-survey-sv-hiddenInterest-'+ (i) + '-id" id="meta-survey-sv-hiddenInterest-'+ (i) + '-id" value="' + data.getSurveyResponse.survey.surveyQuestions.questionId  + '" readonly="readonly" />\n' +
                    //             '        <input type="text" name="meta-survey-sv-hiddenInterest-'+ (i) + '-required" id="meta-survey-sv-hiddenInterest-'+ (i) + '-required" value="' + data.getSurveyResponse.survey.surveyQuestions.questionRequired + '" readonly="readonly" /></div>\n' +
                    //             '    </div>'
                    //         );
                    //         //Iterate through question values
                    //
                    //         var availableAnswerFieldLength = null;
                    //         var get_availableAnswerArray = null;
                    //         if (!Array.isArray(data.getSurveyResponse.survey.surveyQuestions.questionTypeData.surveyQuestionData.availableAnswer)){
                    //             availableAnswerFieldLength = 1;
                    //             get_availableAnswerArray = data.getSurveyResponse.survey.surveyQuestions.questionTypeData.surveyQuestionData.availableAnswer;
                    //         }
                    //         else{
                    //             availableAnswerFieldLength = data.getSurveyResponse.survey.surveyQuestions.questionTypeData.surveyQuestionData.availableAnswer.length;
                    //         }
                    //         for (var i_2 = 0, fLen = availableAnswerFieldLength; i_2 < fLen; i_2++) {
                    //             if (Array.isArray(data.getSurveyResponse.survey.surveyQuestions.questionTypeData.surveyQuestionData.availableAnswer)){
                    //                 get_availableAnswerArray = data.getSurveyResponse.survey.surveyQuestions.questionTypeData.surveyQuestionData.availableAnswer[i_2];
                    //             }
                    //             jQuery("#form-test").append('' +
                    //                 '    <div class="df-row-container">\n' +
                    //                 '        <div class="df-row-input">' + '-->' + (i_2 + 1) + ') ' +
                    //                 '        <input type="text" name="meta-survey-sv-hiddenInterest-' + (i) + '-label-' + (i_2) + '" id="meta-survey-sv-hiddenInterest-' + (i) + '-label-' + (i_2) + '" value="' + get_availableAnswerArray.label + '" readonly="readonly" />\n' +
                    //                 '        <input type="text" name="meta-survey-sv-hiddenInterest-' + (i) + '-value-' + (i_2) + '" id="meta-survey-sv-hiddenInterest-' + (i) + '-value-' + (i_2) + '" value="' + get_availableAnswerArray.value + '" readonly="readonly" /></div>\n' +
                    //                 '    </div>'
                    //             );
                    //         }
                    //         break;
                    //
                    //     case "HiddenTextValue":
                    //         var k = 0;
                    //         //Append base question info
                    //         jQuery("#form-test").append('' +
                    //             '    <div class="df-row-container"><span class="wplo-survey-number-block">' + (i+1) + '.) </span>' +
                    //             '        <div class="df-row-input">' +
                    //             '        <input type="text" name="meta-survey-sv-hiddentextvalue-'+ (i) + '-type" id="meta-survey-sv-hiddentextvalue-'+ (i) + '-type" value="' + data.getSurveyResponse.survey.surveyQuestions.questionType + '" readonly="readonly" />\n' +
                    //             '        <input type="text" name="meta-survey-sv-hiddentextvalue-'+ (i) + '-text" id="meta-survey-sv-hiddentextvalue-'+ (i) + '-text" value="' + data.getSurveyResponse.survey.surveyQuestions.questionText + '" readonly="readonly" />\n' +
                    //             '        <input type="text" name="meta-survey-sv-hiddentextvalue-'+ (i) + '-id" id="meta-survey-sv-hiddentextvalue-'+ (i) + '-id" value="' + data.getSurveyResponse.survey.surveyQuestions.questionId  + '" readonly="readonly" />\n' +
                    //             '        <input type="text" name="meta-survey-sv-hiddentextvalue-'+ (i) + '-required" id="meta-survey-sv-hiddentextvalue-'+ (i) + '-required" value="' + data.getSurveyResponse.survey.surveyQuestions.questionRequired + '" readonly="readonly" /></div>\n' +
                    //             '    </div>'
                    //         );
                    //         break;
                    //
                    //     case "HiddenTrueFalse":
                    //         //Append base question info
                    //         jQuery("#form-test").append('' +
                    //             '    <div class="df-row-container"><span class="wplo-survey-number-block">' + (i+1) + '.) </span>' +
                    //             '        <div class="df-row-input">' +
                    //             '        <input type="text" name="meta-survey-sv-hiddentruefalse-'+ (i) + '-type" id="meta-survey-sv-hiddentruefalse-'+ (i) + '-type" value="' + data.getSurveyResponse.survey.surveyQuestions.questionType + '" readonly="readonly" />\n' +
                    //             '        <input type="text" name="meta-survey-sv-hiddentruefalse-'+ (i) + '-text" id="meta-survey-sv-hiddentruefalse-'+ (i) + '-text" value="' + data.getSurveyResponse.survey.surveyQuestions.questionText + '" readonly="readonly" />\n' +
                    //             '        <input type="text" name="meta-survey-sv-hiddentruefalse-'+ (i) + '-id" id="meta-survey-sv-hiddentruefalse-'+ (i) + '-id" value="' + data.getSurveyResponse.survey.surveyQuestions.questionId  + '" readonly="readonly" />\n' +
                    //             '        <input type="text" name="meta-survey-sv-hiddentruefalse-'+ (i) + '-required" id="meta-survey-sv-hiddentruefalse-'+ (i) + '-required" value="' + data.getSurveyResponse.survey.surveyQuestions.questionRequired + '" readonly="readonly" />\n' +
                    //             '    </div>'
                    //         );
                    //         break;
                    //
                    //     case "Categories":
                    //         //Append base question info
                    //         jQuery("#form-test").append('' +
                    //             '    <div class="df-row-container"><span class="wplo-survey-number-block">' + (i+1) + '.) </span>' +
                    //             '        <div class="df-row-input">' +
                    //             '        <input type="text" name="meta-survey-sv-categories-'+ (i) + '-type" id="meta-survey-sv-categories-'+ (i) + '-type" value="' + data.getSurveyResponse.survey.surveyQuestions.questionType + '" readonly="readonly" />\n' +
                    //             '        <input type="text" name="meta-survey-sv-categories-'+ (i) + '-text" id="meta-survey-sv-categories-'+ (i) + '-text" value="' + data.getSurveyResponse.survey.surveyQuestions.questionText + '" readonly="readonly" />\n' +
                    //             '        <input type="text" name="meta-survey-sv-categories-'+ (i) + '-id" id="meta-survey-sv-categories-'+ (i) + '-id" value="' + data.getSurveyResponse.survey.surveyQuestions.questionId  + '" readonly="readonly" />\n' +
                    //             '        <input type="text" name="meta-survey-sv-categories-'+ (i) + '-required" id="meta-survey-sv-categories-'+ (i) + '-required" value="' + data.getSurveyResponse.survey.surveyQuestions.questionRequired + '" readonly="readonly" />\n' +
                    //             '        <input type="text" name="meta-survey-sv-categories-'+ (i) + '-max" id="meta-survey-sv-categories-'+ (i) + '-max" value="' + data.getSurveyResponse.survey.surveyQuestions.questionMaxResponses + '" readonly="readonly" /></div>\n' +
                    //             '    </div>'
                    //         );
                    //         //Iterate through question values
                    //
                    //         var availableAnswerFieldLength = null;
                    //         var get_availableAnswerArray = null;
                    //         if (!Array.isArray(data.getSurveyResponse.survey.surveyQuestions.questionTypeData.surveyQuestionData.availableAnswer)){
                    //             availableAnswerFieldLength = 1;
                    //             get_availableAnswerArray = data.getSurveyResponse.survey.surveyQuestions.questionTypeData.surveyQuestionData.availableAnswer;
                    //         }
                    //         else{
                    //             availableAnswerFieldLength = data.getSurveyResponse.survey.surveyQuestions.questionTypeData.surveyQuestionData.availableAnswer.length;
                    //         }
                    //         for (var i_2 = 0, fLen = availableAnswerFieldLength; i_2 < fLen; i_2++) {
                    //             if (Array.isArray(data.getSurveyResponse.survey.surveyQuestions.questionTypeData.surveyQuestionData.availableAnswer)){
                    //                 get_availableAnswerArray = data.getSurveyResponse.survey.surveyQuestions.questionTypeData.surveyQuestionData.availableAnswer[i_2];
                    //             }
                    //             jQuery("#form-test").append('' +
                    //                 '    <div class="df-row-container">\n' +
                    //                 '        <div class="df-row-input">' + '-->' + (i_2 + 1) + ') ' +
                    //                 '        <input type="text" name="meta-survey-sv-categories-' + (i) + '-label-' + (i_2) + '" id="meta-survey-sv-categories-' + (i) + '-label-' + (i_2) + '" value="' + get_availableAnswerArray.label + '" readonly="readonly" />\n' +
                    //                 '        <input type="text" name="meta-survey-sv-categories-' + (i) + '-value-' + (i_2) + '" id="meta-survey-sv-categories-' + (i) + '-value-' + (i_2) + '" value="' + get_availableAnswerArray.value + '" readonly="readonly" />\n' +
                    //                 '        <input type="text" name="meta-survey-sv-categories-' + (i) + '-selected-' + (i_2) + '" id="meta-survey-sv-categories-' + (i) + '-selected-' + (i_2) + '" value="' + get_availableAnswerArray.selected + '" readonly="readonly" /></div>\n' +
                    //                 '    </div>'
                    //             );
                    //         }
                    //         break;
                    //
                    //     case "ComboChoice":
                    //         //Append base question info
                    //         jQuery("#form-test").append('' +
                    //             '    <div class="df-row-container"><span class="wplo-survey-number-block">' + (i+1) + '.) </span>' +
                    //             '        <div class="df-row-input">' +
                    //             '        <input type="text" name="meta-survey-sv-combochoice-'+ (i) + '-type" id="meta-survey-sv-combochoice-'+ (i) + '-type" value="' + data.getSurveyResponse.survey.surveyQuestions.questionType + '" readonly="readonly" />\n' +
                    //             '        <input type="text" name="meta-survey-sv-combochoice-'+ (i) + '-text" id="meta-survey-sv-combochoice-'+ (i) + '-text" value="' + data.getSurveyResponse.survey.surveyQuestions.questionText + '" readonly="readonly" />\n' +
                    //             '        <input type="text" name="meta-survey-sv-combochoice-'+ (i) + '-id" id="meta-survey-sv-combochoice-'+ (i) + '-id" value="' + data.getSurveyResponse.survey.surveyQuestions.questionId  + '" readonly="readonly" />\n' +
                    //             '        <input type="text" name="meta-survey-sv-combochoice-'+ (i) + '-hint" id="meta-survey-sv-combochoice-'+ (i) + '-hint" value="' + data.getSurveyResponse.survey.surveyQuestions.questionHint  + '" readonly="readonly" />\n' +
                    //             '        <input type="text" name="meta-survey-sv-combochoice-'+ (i) + '-required" id="meta-survey-sv-combochoice-'+ (i) + '-required" value="' + data.getSurveyResponse.survey.surveyQuestions.questionRequired + '" readonly="readonly" />\n' +
                    //             '        <input type="text" name="meta-survey-sv-combochoice-'+ (i) + '-min" id="meta-survey-sv-combochoice-'+ (i) + '-min" value="' + data.getSurveyResponse.survey.surveyQuestions.questionMinResponses + '" readonly="readonly" />\n' +
                    //             '        <input type="text" name="meta-survey-sv-combochoice-'+ (i) + '-max" id="meta-survey-sv-combochoice-'+ (i) + '-max" value="' + data.getSurveyResponse.survey.surveyQuestions.questionMaxResponses + '" readonly="readonly" /></div>\n' +
                    //             '    </div>'
                    //         );
                    //         //Iterate through question values
                    //
                    //         var availableAnswerFieldLength = null;
                    //         var get_availableAnswerArray = null;
                    //         if (!Array.isArray(data.getSurveyResponse.survey.surveyQuestions.questionTypeData.surveyQuestionData.availableAnswer)){
                    //             availableAnswerFieldLength = 1;
                    //             get_availableAnswerArray = data.getSurveyResponse.survey.surveyQuestions.questionTypeData.surveyQuestionData.availableAnswer;
                    //         }
                    //         else{
                    //             availableAnswerFieldLength = data.getSurveyResponse.survey.surveyQuestions.questionTypeData.surveyQuestionData.availableAnswer.length;
                    //         }
                    //         for (var i_2 = 0, fLen = availableAnswerFieldLength; i_2 < fLen; i_2++) {
                    //             if (Array.isArray(data.getSurveyResponse.survey.surveyQuestions.questionTypeData.surveyQuestionData.availableAnswer)){
                    //                 get_availableAnswerArray = data.getSurveyResponse.survey.surveyQuestions.questionTypeData.surveyQuestionData.availableAnswer[i_2];
                    //             }
                    //             jQuery("#form-test").append('' +
                    //                 '    <div class="df-row-container">\n' +
                    //                 '        <div class="df-row-input">' + '-->' + (i_2 + 1) + ') ' +
                    //                 '        <input type="text" name="meta-survey-sv-combochoice-' + (i) + '-label-' + (i_2) + '" id="meta-survey-sv-combochoice-' + (i) + '-label-' + (i_2) + '" value="' + get_availableAnswerArray.label + '" readonly="readonly" />\n' +
                    //                 '        <input type="text" name="meta-survey-sv-combochoice-' + (i) + '-value-' + (i_2) + '" id="meta-survey-sv-combochoice-' + (i) + '-value-' + (i_2) + '" value="' + get_availableAnswerArray.value + '" readonly="readonly" /></div>\n' +
                    //                 '    </div>'
                    //             );
                    //         }
                    //         break;
                    //
                    //     case "MultiMulti":
                    //         //Append base question info
                    //         jQuery("#form-test").append('' +
                    //             '    <div class="df-row-container"><span class="wplo-survey-number-block">' + (i+1) + '.) </span>' +
                    //             '        <div class="df-row-input">' +
                    //             '        <input type="text" name="meta-survey-sv-multimulti-'+ (i) + '-type" id="meta-survey-sv-multimulti-'+ (i) + '-type" value="' + data.getSurveyResponse.survey.surveyQuestions.questionType + '" readonly="readonly" />\n' +
                    //             '        <input type="text" name="meta-survey-sv-multimulti-'+ (i) + '-text" id="meta-survey-sv-multimulti-'+ (i) + '-text" value="' + data.getSurveyResponse.survey.surveyQuestions.questionText + '" readonly="readonly" />\n' +
                    //             '        <input type="text" name="meta-survey-sv-multimulti-'+ (i) + '-id" id="meta-survey-sv-multimulti-'+ (i) + '-id" value="' + data.getSurveyResponse.survey.surveyQuestions.questionId  + '" readonly="readonly" />\n' +
                    //             '        <input type="text" name="meta-survey-sv-multimulti-'+ (i) + '-hint" id="meta-survey-sv-multimulti-'+ (i) + '-hint" value="' + data.getSurveyResponse.survey.surveyQuestions.questionHint  + '" readonly="readonly" />\n' +
                    //             '        <input type="text" name="meta-survey-sv-multimulti-'+ (i) + '-required" id="meta-survey-sv-multimulti-'+ (i) + '-required" value="' + data.getSurveyResponse.survey.surveyQuestions.questionRequired + '" readonly="readonly" />\n' +
                    //             '        <input type="text" name="meta-survey-sv-multimulti-'+ (i) + '-min" id="meta-survey-sv-multimulti-'+ (i) + '-min" value="' + data.getSurveyResponse.survey.surveyQuestions.questionMinResponses + '" readonly="readonly" />\n' +
                    //             '        <input type="text" name="meta-survey-sv-multimulti-'+ (i) + '-max" id="meta-survey-sv-multimulti-'+ (i) + '-max" value="' + data.getSurveyResponse.survey.surveyQuestions.questionMaxResponses + '" readonly="readonly" /></div>\n' +
                    //             '    </div>'
                    //         );
                    //         //Iterate through question values
                    //
                    //         var availableAnswerFieldLength = null;
                    //         var get_availableAnswerArray = null;
                    //         if (!Array.isArray(data.getSurveyResponse.survey.surveyQuestions.questionTypeData.surveyQuestionData.availableAnswer)){
                    //             availableAnswerFieldLength = 1;
                    //             get_availableAnswerArray = data.getSurveyResponse.survey.surveyQuestions.questionTypeData.surveyQuestionData.availableAnswer;
                    //         }
                    //         else{
                    //             availableAnswerFieldLength = data.getSurveyResponse.survey.surveyQuestions.questionTypeData.surveyQuestionData.availableAnswer.length;
                    //         }
                    //         for (var i_2 = 0, fLen = availableAnswerFieldLength; i_2 < fLen; i_2++) {
                    //             if (Array.isArray(data.getSurveyResponse.survey.surveyQuestions.questionTypeData.surveyQuestionData.availableAnswer)){
                    //                 get_availableAnswerArray = data.getSurveyResponse.survey.surveyQuestions.questionTypeData.surveyQuestionData.availableAnswer[i_2];
                    //             }
                    //             jQuery("#form-test").append('' +
                    //                 '    <div class="df-row-container">\n' +
                    //                 '        <div class="df-row-input">' + '-->' + (i_2 + 1) + ') ' +
                    //                 '        <input type="text" name="meta-survey-sv-multimulti-' + (i) + '-label-' + (i_2) + '" id="meta-survey-sv-multimulti-' + (i) + '-label-' + (i_2) + '" value="' + get_availableAnswerArray.label + '" readonly="readonly" />\n' +
                    //                 '        <input type="text" name="meta-survey-sv-multimulti-' + (i) + '-value-' + (i_2) + '" id="meta-survey-sv-multimulti-' + (i) + '-value-' + (i_2) + '" value="' + get_availableAnswerArray.value + '" readonly="readonly" /></div>\n' +
                    //                 '    </div>'
                    //             );
                    //         }
                    //         break;
                    //
                    //     case "MultiSingle":
                    //         //Append base question info
                    //         jQuery("#form-test").append('' +
                    //             '    <div class="df-row-container"><span class="wplo-survey-number-block">' + (i+1) + '.) </span>' +
                    //             '        <div class="df-row-input">' +
                    //             '        <input type="text" name="meta-survey-sv-multisingle-'+ (i) + '-type" id="meta-survey-sv-multisingle-'+ (i) + '-type" value="' + data.getSurveyResponse.survey.surveyQuestions.questionType + '" readonly="readonly" />\n' +
                    //             '        <input type="text" name="meta-survey-sv-multisingle-'+ (i) + '-text" id="meta-survey-sv-multisingle-'+ (i) + '-text" value="' + data.getSurveyResponse.survey.surveyQuestions.questionText + '" readonly="readonly" />\n' +
                    //             '        <input type="text" name="meta-survey-sv-multisingle-'+ (i) + '-id" id="meta-survey-sv-multisingle-'+ (i) + '-id" value="' + data.getSurveyResponse.survey.surveyQuestions.questionId  + '" readonly="readonly" />\n' +
                    //             '        <input type="text" name="meta-survey-sv-multisingle-'+ (i) + '-required" id="meta-survey-sv-multisingle-'+ (i) + '-required" value="' + data.getSurveyResponse.survey.surveyQuestions.questionRequired + '" readonly="readonly" />\n' +
                    //             '        <input type="text" name="meta-survey-sv-multisingle-'+ (i) + '-max" id="meta-survey-sv-multisingle-'+ (i) + '-max" value="' + data.getSurveyResponse.survey.surveyQuestions.questionMaxResponses + '" readonly="readonly" /></div>\n' +
                    //             '    </div>'
                    //         );
                    //         //Iterate through question values
                    //
                    //         var availableAnswerFieldLength = null;
                    //         var get_availableAnswerArray = null;
                    //         if (!Array.isArray(data.getSurveyResponse.survey.surveyQuestions.questionTypeData.surveyQuestionData.availableAnswer)){
                    //             availableAnswerFieldLength = 1;
                    //             get_availableAnswerArray = data.getSurveyResponse.survey.surveyQuestions.questionTypeData.surveyQuestionData.availableAnswer;
                    //         }
                    //         else{
                    //             availableAnswerFieldLength = data.getSurveyResponse.survey.surveyQuestions.questionTypeData.surveyQuestionData.availableAnswer.length;
                    //         }
                    //         for (var i_2 = 0, fLen = availableAnswerFieldLength; i_2 < fLen; i_2++) {
                    //             if (Array.isArray(data.getSurveyResponse.survey.surveyQuestions.questionTypeData.surveyQuestionData.availableAnswer)){
                    //                 get_availableAnswerArray = data.getSurveyResponse.survey.surveyQuestions.questionTypeData.surveyQuestionData.availableAnswer[i_2];
                    //             }
                    //             jQuery("#form-test").append('' +
                    //                 '    <div class="df-row-container">\n' +
                    //                 '        <div class="df-row-input">' + '-->' + (i_2 + 1) + ') ' +
                    //                 '        <input type="text" name="meta-survey-sv-multisingle-' + (i) + '-label-' + (i_2) + '" id="meta-survey-sv-multisingle-' + (i) + '-label-' + (i_2) + '" value="' + get_availableAnswerArray.label + '" readonly="readonly" />\n' +
                    //                 '        <input type="text" name="meta-survey-sv-multisingle-' + (i) + '-value-' + (i_2) + '" id="meta-survey-sv-multisingle-' + (i) + '-value-' + (i_2) + '" value="' + get_availableAnswerArray.value + '" readonly="readonly" /></div>\n' +
                    //                 '    </div>'
                    //             );
                    //         }
                    //         break;
                    //
                    //     case "MultiSingleRadio":
                    //         //Append base question info
                    //         jQuery("#form-test").append('' +
                    //             '    <div class="df-row-container"><span class="wplo-survey-number-block">' + (i+1) + '.) </span>' +
                    //             '        <div class="df-row-input">' +
                    //             '        <input type="text" name="meta-survey-sv-multisingleradio-'+ (i) + '-type" id="meta-survey-sv-multisingleradio-'+ (i) + '-type" value="' + data.getSurveyResponse.survey.surveyQuestions.questionType + '" readonly="readonly" />\n' +
                    //             '        <input type="text" name="meta-survey-sv-multisingleradio-'+ (i) + '-text" id="meta-survey-sv-multisingleradio-'+ (i) + '-text" value="' + data.getSurveyResponse.survey.surveyQuestions.questionText + '" readonly="readonly" />\n' +
                    //             '        <input type="text" name="meta-survey-sv-multisingleradio-'+ (i) + '-id" id="meta-survey-sv-multisingleradio-'+ (i) + '-id" value="' + data.getSurveyResponse.survey.surveyQuestions.questionId  + '" readonly="readonly" />\n' +
                    //             '        <input type="text" name="meta-survey-sv-multisingleradio-'+ (i) + '-required" id="meta-survey-sv-multisingleradio-'+ (i) + '-required" value="' + data.getSurveyResponse.survey.surveyQuestions.questionRequired + '" readonly="readonly" /></div>\n' +
                    //             '    </div>'
                    //         );
                    //         //Iterate through question values
                    //
                    //         var availableAnswerFieldLength = null;
                    //         var get_availableAnswerArray = null;
                    //         if (!Array.isArray(data.getSurveyResponse.survey.surveyQuestions.questionTypeData.surveyQuestionData.availableAnswer)){
                    //             availableAnswerFieldLength = 1;
                    //             get_availableAnswerArray = data.getSurveyResponse.survey.surveyQuestions.questionTypeData.surveyQuestionData.availableAnswer;
                    //         }
                    //         else{
                    //             availableAnswerFieldLength = data.getSurveyResponse.survey.surveyQuestions.questionTypeData.surveyQuestionData.availableAnswer.length;
                    //         }
                    //         for (var i_2 = 0, fLen = availableAnswerFieldLength; i_2 < fLen; i_2++) {
                    //             if (Array.isArray(data.getSurveyResponse.survey.surveyQuestions.questionTypeData.surveyQuestionData.availableAnswer)){
                    //                 get_availableAnswerArray = data.getSurveyResponse.survey.surveyQuestions.questionTypeData.surveyQuestionData.availableAnswer[i_2];
                    //             }
                    //             jQuery("#form-test").append('' +
                    //                 '    <div class="df-row-container">\n' +
                    //                 '        <div class="df-row-input">' + '-->' + (i_2 + 1) + ') ' +
                    //                 '        <input type="text" name="meta-survey-sv-multisingleradio-' + (i) + '-label-' + (i_2) + '" id="meta-survey-sv-multisingleradio-' + (i) + '-label-' + (i_2) + '" value="' + get_availableAnswerArray.label + '" readonly="readonly" />\n' +
                    //                 '        <input type="text" name="meta-survey-sv-multisingleradio-' + (i) + '-value-' + (i_2) + '" id="meta-survey-sv-multisingleradio-' + (i) + '-value-' + (i_2) + '" value="' + get_availableAnswerArray.value + '" readonly="readonly" /></div>\n' +
                    //                 '    </div>'
                    //             );
                    //         }
                    //         break;
                    //
                    //     case "NumericValue":
                    //         var k = 0;
                    //         //Append base question info
                    //         jQuery("#form-test").append('' +
                    //             '    <div class="df-row-container"><span class="wplo-survey-number-block">' + (i+1) + '.) </span>' +
                    //             '        <div class="df-row-input">' +
                    //             '        <input type="text" name="meta-survey-sv-numericvalue-'+ (i) + '-type" id="meta-survey-sv-numericvalue-'+ (i) + '-type" value="' + data.getSurveyResponse.survey.surveyQuestions.questionType + '" readonly="readonly" />\n' +
                    //             '        <input type="text" name="meta-survey-sv-numericvalue-'+ (i) + '-text" id="meta-survey-sv-numericvalue-'+ (i) + '-text" value="' + data.getSurveyResponse.survey.surveyQuestions.questionText + '" readonly="readonly" />\n' +
                    //             '        <input type="text" name="meta-survey-sv-numericvalue-'+ (i) + '-id" id="meta-survey-sv-numericvalue-'+ (i) + '-id" value="' + data.getSurveyResponse.survey.surveyQuestions.questionId  + '" readonly="readonly" />\n' +
                    //             '        <input type="text" name="meta-survey-sv-numericvalue-'+ (i) + '-required" id="meta-survey-sv-numericvalue-'+ (i) + '-required" value="' + data.getSurveyResponse.survey.surveyQuestions.questionRequired + '" readonly="readonly" /></div>\n' +
                    //             '    </div>'
                    //         );
                    //         break;
                    //
                    //     case "RatingScale":
                    //         //Append base question info
                    //         jQuery("#form-test").append('' +
                    //             '    <div class="df-row-container"><span class="wplo-survey-number-block">' + (i+1) + '.) </span>' +
                    //             '        <div class="df-row-input">' +
                    //             '        <input type="text" name="meta-survey-sv-ratingscale-'+ (i) + '-type" id="meta-survey-sv-ratingscale-'+ (i) + '-type" value="' + data.getSurveyResponse.survey.surveyQuestions.questionType + '" readonly="readonly" />\n' +
                    //             '        <input type="text" name="meta-survey-sv-ratingscale-'+ (i) + '-text" id="meta-survey-sv-ratingscale-'+ (i) + '-text" value="' + data.getSurveyResponse.survey.surveyQuestions.questionText + '" readonly="readonly" />\n' +
                    //             '        <input type="text" name="meta-survey-sv-ratingscale-'+ (i) + '-id" id="meta-survey-sv-ratingscale-'+ (i) + '-id" value="' + data.getSurveyResponse.survey.surveyQuestions.questionId  + '" readonly="readonly" />\n' +
                    //             '        <input type="text" name="meta-survey-sv-ratingscale-'+ (i) + '-required" id="meta-survey-sv-ratingscale-'+ (i) + '-required" value="' + data.getSurveyResponse.survey.surveyQuestions.questionRequired + '" readonly="readonly" /></div>\n' +
                    //             '    </div>'
                    //         );
                    //         //Iterate through question values
                    //
                    //         var availableAnswerFieldLength = null;
                    //         var get_availableAnswerArray = null;
                    //         if (!Array.isArray(data.getSurveyResponse.survey.surveyQuestions.questionTypeData.surveyQuestionData.availableAnswer)){
                    //             availableAnswerFieldLength = 1;
                    //             get_availableAnswerArray = data.getSurveyResponse.survey.surveyQuestions.questionTypeData.surveyQuestionData.availableAnswer;
                    //         }
                    //         else{
                    //             availableAnswerFieldLength = data.getSurveyResponse.survey.surveyQuestions.questionTypeData.surveyQuestionData.availableAnswer.length;
                    //         }
                    //         for (var i_2 = 0, fLen = availableAnswerFieldLength; i_2 < fLen; i_2++) {
                    //             if (Array.isArray(data.getSurveyResponse.survey.surveyQuestions.questionTypeData.surveyQuestionData.availableAnswer)){
                    //                 get_availableAnswerArray = data.getSurveyResponse.survey.surveyQuestions.questionTypeData.surveyQuestionData.availableAnswer[i_2];
                    //             }
                    //             jQuery("#form-test").append('' +
                    //                 '    <div class="df-row-container">\n' +
                    //                 '        <div class="df-row-input">' + '-->' + (i_2 + 1) + ') ' +
                    //                 '        <input type="text" name="meta-survey-sv-ratingscale-' + (i) + '-label-' + (i_2) + '" id="meta-survey-sv-ratingscale-' + (i) + '-label-' + (i_2) + '" value="' + get_availableAnswerArray.label + '" readonly="readonly" />\n' +
                    //                 '        <input type="text" name="meta-survey-sv-ratingscale-' + (i) + '-value-' + (i_2) + '" id="meta-survey-sv-ratingscale-' + (i) + '-value-' + (i_2) + '" value="' + get_availableAnswerArray.value + '" readonly="readonly" /></div>\n' +
                    //                 '    </div>'
                    //             );
                    //         }
                    //         break;
                    //
                    //     case "ShortTextValue":
                    //         var k = 0;
                    //         //Append base question info
                    //         jQuery("#form-test").append('' +
                    //             '    <div class="df-row-container"><span class="wplo-survey-number-block">' + (i+1) + '.) </span>' +
                    //             '        <div class="df-row-input">' +
                    //             '        <input type="text" name="meta-survey-sv-shorttext-'+ (i) + '-type" id="meta-survey-sv-shorttext-'+ (i) + '-type" value="' + data.getSurveyResponse.survey.surveyQuestions.questionType + '" readonly="readonly" />\n' +
                    //             '        <input type="text" name="meta-survey-sv-shorttext-'+ (i) + '-text" id="meta-survey-sv-shorttext-'+ (i) + '-text" value="' + data.getSurveyResponse.survey.surveyQuestions.questionText + '" readonly="readonly" />\n' +
                    //             '        <input type="text" name="meta-survey-sv-shorttext-'+ (i) + '-id" id="meta-survey-sv-shorttext-'+ (i) + '-id" value="' + data.getSurveyResponse.survey.surveyQuestions.questionId  + '" readonly="readonly" />\n' +
                    //             '        <input type="text" name="meta-survey-sv-shorttext-'+ (i) + '-required" id="meta-survey-sv-shorttext-'+ (i) + '-required" value="' + data.getSurveyResponse.survey.surveyQuestions.questionRequired + '" readonly="readonly" /></div>\n' +
                    //             '    </div>'
                    //         );
                    //         break;
                    //
                    //     case "TextValue":
                    //         var k = 0;
                    //         //Append base question info
                    //         jQuery("#form-test").append('' +
                    //             '    <div class="df-row-container"><span class="wplo-survey-number-block">' + (i+1) + '.) </span>' +
                    //             '        <div class="df-row-input">' +
                    //             '        <input type="text" name="meta-survey-sv-textvalue-'+ (i) + '-type" id="meta-survey-sv-textvalue-'+ (i) + '-type" value="' + data.getSurveyResponse.survey.surveyQuestions.questionType + '" readonly="readonly" />\n' +
                    //             '        <input type="text" name="meta-survey-sv-textvalue-'+ (i) + '-text" id="meta-survey-sv-textvalue-'+ (i) + '-text" value="' + data.getSurveyResponse.survey.surveyQuestions.questionText + '" readonly="readonly" />\n' +
                    //             '        <input type="text" name="meta-survey-sv-textvalue-'+ (i) + '-id" id="meta-survey-sv-textvalue-'+ (i) + '-id" value="' + data.getSurveyResponse.survey.surveyQuestions.questionId  + '" readonly="readonly" />\n' +
                    //             '        <input type="text" name="meta-survey-sv-textvalue-'+ (i) + '-hint" id="meta-survey-sv-textvalue-'+ (i) + '-hint" value="' + data.getSurveyResponse.survey.surveyQuestions.questionHint  + '" readonly="readonly" />\n' +
                    //             '        <input type="text" name="meta-survey-sv-textvalue-'+ (i) + '-required" id="meta-survey-sv-textvalue-'+ (i) + '-required" value="' + data.getSurveyResponse.survey.surveyQuestions.questionRequired + '" readonly="readonly" /></div>\n' +
                    //             '    </div>'
                    //         );
                    //         break;
                    //
                    //     case "TrueFalse":
                    //         //Append base question info
                    //         jQuery("#form-test").append('' +
                    //             '    <div class="df-row-container"><span class="wplo-survey-number-block">' + (i+1) + '.) </span>' +
                    //             '        <div class="df-row-input">' +
                    //             '        <input type="text" name="meta-survey-sv-truefalse-'+ (i) + '-type" id="meta-survey-sv-truefalse-'+ (i) + '-type" value="' + data.getSurveyResponse.survey.surveyQuestions.questionType + '" readonly="readonly" />\n' +
                    //             '        <input type="text" name="meta-survey-sv-truefalse-'+ (i) + '-text" id="meta-survey-sv-truefalse-'+ (i) + '-text" value="' + data.getSurveyResponse.survey.surveyQuestions.questionText + '" readonly="readonly" />\n' +
                    //             '        <input type="text" name="meta-survey-sv-truefalse-'+ (i) + '-id" id="meta-survey-sv-truefalse-'+ (i) + '-id" value="' + data.getSurveyResponse.survey.surveyQuestions.questionId  + '" readonly="readonly" />\n' +
                    //             '        <input type="text" name="meta-survey-sv-truefalse-'+ (i) + '-required" id="meta-survey-sv-truefalse-'+ (i) + '-required" value="' + data.getSurveyResponse.survey.surveyQuestions.questionRequired + '" readonly="readonly" />\n' +
                    //             '        <input type="text" name="meta-survey-sv-truefalse-'+ (i) + '-max" id="meta-survey-sv-truefalse-'+ (i) + '-max" value="' + data.getSurveyResponse.survey.surveyQuestions.questionMaxResponses + '" readonly="readonly" /></div>\n' +
                    //             '    </div>'
                    //         );
                    //         //Iterate through question values
                    //
                    //         var availableAnswerFieldLength = null;
                    //         var get_availableAnswerArray = null;
                    //         if (!Array.isArray(data.getSurveyResponse.survey.surveyQuestions.questionTypeData.surveyQuestionData.availableAnswer)){
                    //             availableAnswerFieldLength = 1;
                    //             get_availableAnswerArray = data.getSurveyResponse.survey.surveyQuestions.questionTypeData.surveyQuestionData.availableAnswer;
                    //         }
                    //         else{
                    //             availableAnswerFieldLength = data.getSurveyResponse.survey.surveyQuestions.questionTypeData.surveyQuestionData.availableAnswer.length;
                    //         }
                    //         for (var i_2 = 0, fLen = availableAnswerFieldLength; i_2 < fLen; i_2++) {
                    //             if (Array.isArray(data.getSurveyResponse.survey.surveyQuestions.questionTypeData.surveyQuestionData.availableAnswer)){
                    //                 get_availableAnswerArray = data.getSurveyResponse.survey.surveyQuestions.questionTypeData.surveyQuestionData.availableAnswer[i_2];
                    //             }
                    //             jQuery("#form-test").append('' +
                    //                 '    <div class="df-row-container">\n' +
                    //                 '        <div class="df-row-input">' + '-->' + (i_2 + 1) + ') ' +
                    //                 '        <input type="text" name="meta-survey-sv-truefalse-' + (i) + '-label-' + (i_2) + '" id="meta-survey-sv-truefalse-' + (i) + '-label-' + (i_2) + '" value="' + get_availableAnswerArray.label + '" readonly="readonly" />\n' +
                    //                 '        <input type="text" name="meta-survey-sv-truefalse-' + (i) + '-value-' + (i_2) + '" id="meta-survey-sv-truefalse-' + (i) + '-value-' + (i_2) + '" value="' + get_availableAnswerArray.value + '" readonly="readonly" /></div>\n' +
                    //                 '    </div>'
                    //             );
                    //         }
                    //         break;
                    //
                    //     case "LargeTextValue":
                    //         var k = 0;
                    //         //Append base question info
                    //         jQuery("#form-test").append('' +
                    //             '    <div class="df-row-container"><span class="wplo-survey-number-block">' + (i+1) + '.) </span>' +
                    //             '        <div class="df-row-input">' +
                    //             '        <input type="text" name="meta-survey-sv-largetextvalue-'+ (i) + '-type" id="meta-survey-sv-largetextvalue-'+ (i) + '-type" value="' + data.getSurveyResponse.survey.surveyQuestions.questionType + '" readonly="readonly" />\n' +
                    //             '        <input type="text" name="meta-survey-sv-largetextvalue-'+ (i) + '-text" id="meta-survey-sv-largetextvalue-'+ (i) + '-text" value="' + data.getSurveyResponse.survey.surveyQuestions.questionText + '" readonly="readonly" />\n' +
                    //             '        <input type="text" name="meta-survey-sv-largetextvalue-'+ (i) + '-id" id="meta-survey-sv-largetextvalue-'+ (i) + '-id" value="' + data.getSurveyResponse.survey.surveyQuestions.questionId  + '" readonly="readonly" />\n' +
                    //             '        <input type="text" name="meta-survey-sv-largetextvalue-'+ (i) + '-hint" id="meta-survey-sv-largetextvalue-'+ (i) + '-hint" value="' + data.getSurveyResponse.survey.surveyQuestions.questionHint  + '" readonly="readonly" />\n' +
                    //             '        <input type="text" name="meta-survey-sv-largetextvalue-'+ (i) + '-required" id="meta-survey-sv-largetextvalue-'+ (i) + '-required" value="' + data.getSurveyResponse.survey.surveyQuestions.questionRequired + '" readonly="readonly" /></div>\n' +
                    //             '    </div>'
                    //         );
                    //         break;
                    //
                    //     case "YesNo":
                    //         //Append base question info
                    //         jQuery("#form-test").append('' +
                    //             '    <div class="df-row-container"><span class="wplo-survey-number-block">' + (i+1) + '.) </span>' +
                    //             '        <div class="df-row-input">' +
                    //             '        <input type="text" name="meta-survey-sv-yesno-'+ (i) + '-type" id="meta-survey-sv-yesno-'+ (i) + '-type" value="' + data.getSurveyResponse.survey.surveyQuestions.questionType + '" readonly="readonly" />\n' +
                    //             '        <input type="text" name="meta-survey-sv-yesno-'+ (i) + '-text" id="meta-survey-sv-yesno-'+ (i) + '-text" value="' + data.getSurveyResponse.survey.surveyQuestions.questionText + '" readonly="readonly" />\n' +
                    //             '        <input type="text" name="meta-survey-sv-yesno-'+ (i) + '-id" id="meta-survey-sv-yesno-'+ (i) + '-id" value="' + data.getSurveyResponse.survey.surveyQuestions.questionId  + '" readonly="readonly" />\n' +
                    //             '        <input type="text" name="meta-survey-sv-yesno-'+ (i) + '-required" id="meta-survey-sv-yesno-'+ (i) + '-required" value="' + data.getSurveyResponse.survey.surveyQuestions.questionRequired + '" readonly="readonly" />\n' +
                    //             '        <input type="text" name="meta-survey-sv-yesno-'+ (i) + '-max" id="meta-survey-sv-yesno-'+ (i) + '-max" value="' + data.getSurveyResponse.survey.surveyQuestions.questionMaxResponses + '" readonly="readonly" /></div>\n' +
                    //             '    </div>'
                    //         );
                    //         //Iterate through question values
                    //
                    //         var availableAnswerFieldLength = null;
                    //         var get_availableAnswerArray = null;
                    //         if (!Array.isArray(data.getSurveyResponse.survey.surveyQuestions.questionTypeData.surveyQuestionData.availableAnswer)){
                    //             availableAnswerFieldLength = 1;
                    //             get_availableAnswerArray = data.getSurveyResponse.survey.surveyQuestions.questionTypeData.surveyQuestionData.availableAnswer;
                    //         }
                    //         else{
                    //             availableAnswerFieldLength = data.getSurveyResponse.survey.surveyQuestions.questionTypeData.surveyQuestionData.availableAnswer.length;
                    //         }
                    //         for (var i_2 = 0, fLen = availableAnswerFieldLength; i_2 < fLen; i_2++) {
                    //             if (Array.isArray(data.getSurveyResponse.survey.surveyQuestions.questionTypeData.surveyQuestionData.availableAnswer)){
                    //                 get_availableAnswerArray = data.getSurveyResponse.survey.surveyQuestions.questionTypeData.surveyQuestionData.availableAnswer[i_2];
                    //             }
                    //             jQuery("#form-test").append('' +
                    //                 '    <div class="df-row-container">\n' +
                    //                 '        <div class="df-row-input">' + '-->' + (i_2 + 1) + ') ' +
                    //                 '        <input type="text" name="meta-survey-sv-yesno-' + (i) + '-label-' + (i_2) + '" id="meta-survey-sv-yesno-' + (i) + '-label-' + (i_2) + '" value="' + get_availableAnswerArray.label + '" readonly="readonly" />\n' +
                    //                 '        <input type="text" name="meta-survey-sv-yesno-' + (i) + '-value-' + (i_2) + '" id="meta-survey-sv-yesno-' + (i) + '-value-' + (i_2) + '" value="' + get_availableAnswerArray.value + '" readonly="readonly" /></div>\n' +
                    //                 '    </div>'
                    //             );
                    //         }
                    //         break;
                    //
                    //     case "Captcha":
                    //         //Append base question info
                    //         jQuery("#form-test").append('' +
                    //             '    <div class="df-row-container"><span class="wplo-survey-number-block">' + (i+1) + '.) </span>' +
                    //             '        <div class="df-row-input">' +
                    //             '        <input type="text" name="meta-survey-sv-captcha-'+ (i) + '-type" id="meta-survey-sv-captcha-'+ (i) + '-type" value="' + data.getSurveyResponse.survey.surveyQuestions.questionType + '" readonly="readonly" />\n' +
                    //             '        <input type="text" name="meta-survey-sv-captcha-'+ (i) + '-text" id="meta-survey-sv-captcha-'+ (i) + '-text" value="' + data.getSurveyResponse.survey.surveyQuestions.questionText + '" readonly="readonly" />\n' +
                    //             '        <input type="text" name="meta-survey-sv-captcha-'+ (i) + '-id" id="meta-survey-sv-captcha-'+ (i) + '-id" value="' + data.getSurveyResponse.survey.surveyQuestions.questionId  + '" readonly="readonly" />\n' +
                    //             '        <input type="text" name="meta-survey-sv-captcha-'+ (i) + '-required" id="meta-survey-sv-captcha-'+ (i) + '-required" value="' + data.getSurveyResponse.survey.surveyQuestions.questionRequired + '" readonly="readonly" />\n' +
                    //             '        <input type="text" name="meta-survey-sv-captcha-'+ (i) + '-max" id="meta-survey-sv-captcha-'+ (i) + '-max" value="' + data.getSurveyResponse.survey.surveyQuestions.questionMaxResponses + '" readonly="readonly" /></div>\n' +
                    //             '    </div>'
                    //         );
                    //         //Iterate through question values
                    //
                    //         var availableAnswerFieldLength = null;
                    //         var get_availableAnswerArray = null;
                    //         if (!Array.isArray(data.getSurveyResponse.survey.surveyQuestions.questionTypeData.captchaData)){
                    //             availableAnswerFieldLength = 1;
                    //             get_availableAnswerArray = data.getSurveyResponse.survey.surveyQuestions.questionTypeData.captchaData;
                    //         }
                    //         else{
                    //             availableAnswerFieldLength = data.getSurveyResponse.survey.surveyQuestions.questionTypeData.captchaData.length;
                    //         }
                    //         for (var i_2 = 0, fLen = availableAnswerFieldLength; i_2 < fLen; i_2++) {
                    //             if (Array.isArray(data.getSurveyResponse.survey.surveyQuestions.questionTypeData.captchaData)){
                    //                 get_availableAnswerArray = data.getSurveyResponse.survey.surveyQuestions.questionTypeData.captchaData[i_2];
                    //             }
                    //             jQuery("#form-test").append('' +
                    //                 '    <div class="df-row-container">\n' +
                    //                 '        <div class="df-row-input">' + '-->' + (i_2 + 1) + ') ' +
                    //                 '        <input type="text" name="meta-survey-sv-captcha-' + (i) + '-imagesource-' + (i_2) + '" id="meta-survey-sv-captcha-' + (i) + '-imagesource-' + (i_2) + '" value="' + get_availableAnswerArray.imageSource + '" readonly="readonly" />\n' +
                    //                 '        <input type="text" name="meta-survey-sv-captcha-' + (i) + '-audiolink-' + (i_2) + '" id="meta-survey-sv-captcha-' + (i) + '-audiolink-' + (i_2) + '" value="' + get_availableAnswerArray.audioLink + '" readonly="readonly" />\n' +
                    //                 '        <input type="text" name="meta-survey-sv-captcha-' + (i) + '-audiolinklabel-' + (i_2) + '" id="meta-survey-sv-captcha-' + (i) + '-audiolinklabel-' + (i_2) + '" value="' + get_availableAnswerArray.audioLinkLabel + '" readonly="readonly" />\n' +
                    //                 '        <input type="text" name="meta-survey-sv-captcha-' + (i) + '-standaloneplayerlabel-' + (i_2) + '" id="meta-survey-sv-captcha-' + (i) + '-standaloneplayerlabel-' + (i_2) + '" value="' + get_availableAnswerArray.standAlonePlayerLabel + '" readonly="readonly" />\n' +
                    //                 '        <input type="text" name="meta-survey-sv-captcha-' + (i) + '-newwindowlabel-' + (i_2) + '" id="meta-survey-sv-captcha-' + (i) + '-newwindowlabel-' + (i_2) + '" value="' + get_availableAnswerArray.newWindowLabel + '" readonly="readonly" />\n' +
                    //                 '        <input type="text" name="meta-survey-sv-captcha-' + (i) + '-changeimagelabel-' + (i_2) + '" id="meta-survey-sv-captcha-' + (i) + '-changeimagelabel-' + (i_2) + '" value="' + get_availableAnswerArray.changeImageLabel + '" readonly="readonly" /></div>\n' +
                    //                 '    </div>'
                    //             );
                    //         }
                    //         break;
                    //
                    //
                    // }
                }

                //Multiple questions found, do array instead of object
                // else{
                    //Iterate through the surveys total number of questions
                    for (var i = 0, len = data.getSurveyResponse.survey.surveyQuestions.length; i < len; i++) {
                        //Switch what is done based on question type
                        switch(data.getSurveyResponse.survey.surveyQuestions[i].questionType) {

                            case "Caption":
                                var k = 0;
                                //Append base question info
                                jQuery("#form-test").append('' +
                                    '    <div class="df-row-container"><span class="wplo-survey-number-block">' + (i+1) + '.) </span>' +
                                    '        <div class="df-row-input">' +
                                    '        <input type="text" name="meta-survey-sv-caption-'+ (i) + '-type" id="meta-survey-sv-caption-'+ (i) + '-type" value="' + data.getSurveyResponse.survey.surveyQuestions[i].questionType + '" readonly="readonly" />\n' +
                                    '        <input type="text" name="meta-survey-sv-caption-'+ (i) + '-text" id="meta-survey-sv-caption-'+ (i) + '-text" value="' + escapeHtml(data.getSurveyResponse.survey.surveyQuestions[i].questionText) + '" readonly="readonly" />\n' +
                                    '        <input type="text" name="meta-survey-sv-caption-'+ (i) + '-id" id="meta-survey-sv-caption-'+ (i) + '-id" value="' + data.getSurveyResponse.survey.surveyQuestions[i].questionId  + '" readonly="readonly" />\n' +
                                    '        <input type="text" name="meta-survey-sv-caption-'+ (i) + '-hidden" id="meta-survey-sv-caption-'+ (i) + '-hidden" value="' + data.getSurveyResponse.survey.surveyQuestions[i].hidden + '" readonly="readonly" />\n' +
                                    '        <input type="text" name="meta-survey-sv-caption-'+ (i) + '-required" id="meta-survey-sv-caption-'+ (i) + '-required" value="' + data.getSurveyResponse.survey.surveyQuestions[i].questionRequired + '" readonly="readonly" /></div>\n' +
                                    '    </div>'
                                );
                                break;

                            case "ConsQuestion":
                                //Append base question info
                                jQuery("#form-test").append('' +
                                    '    <div class="df-row-container"><span class="wplo-survey-number-block">' + (i+1) + '.) </span>' +
                                    '        <div class="df-row-input">' +
                                    '        <input type="text" name="meta-survey-sv-question-'+ (i) + '-type" id="meta-survey-sv-question-'+ (i) + '-type" value="' + data.getSurveyResponse.survey.surveyQuestions[i].questionType + '" readonly="readonly" />\n' +
                                    '        <input type="text" name="meta-survey-sv-question-'+ (i) + '-text" id="meta-survey-sv-question-'+ (i) + '-text" value="' + data.getSurveyResponse.survey.surveyQuestions[i].questionText + '" readonly="readonly" />\n' +
                                    '        <input type="text" name="meta-survey-sv-question-'+ (i) + '-id" id="meta-survey-sv-question-'+ (i) + '-id" value="' + data.getSurveyResponse.survey.surveyQuestions[i].questionId  + '" readonly="readonly" />\n' +
                                    '        <input type="text" name="meta-survey-sv-question-'+ (i) + '-required" id="meta-survey-sv-question-'+ (i) + '-required" value="' + data.getSurveyResponse.survey.surveyQuestions[i].questionRequired + '" readonly="readonly" /></div>\n' +
                                    '    </div>'
                                );
                                //Iterate through question values
                                var contactInfoFieldLength = data.getSurveyResponse.survey.surveyQuestions[i].questionTypeData.consRegInfoData.contactInfoField.length;
                                var get_fieldOptionValues = null;
                                if (!Array.isArray(data.getSurveyResponse.survey.surveyQuestions[i].questionTypeData.consRegInfoData.contactInfoField)){
                                    contactInfoFieldLength = 1;
                                    get_fieldOptionValues = data.getSurveyResponse.survey.surveyQuestions[i].questionTypeData.consRegInfoData.contactInfoField;
                                }
                                for(var i_2 = 0, fLen = contactInfoFieldLength; i_2 < fLen; i_2++){

                                    if (Array.isArray(data.getSurveyResponse.survey.surveyQuestions[i].questionTypeData.consRegInfoData.contactInfoField)){
                                        get_fieldOptionValues = data.getSurveyResponse.survey.surveyQuestions[i].questionTypeData.consRegInfoData.contactInfoField[i_2];
                                    }

                                    if (get_fieldOptionValues.fieldOptionValues) {

                                        let fieldValueOptionsHtml;


                                        // console.log(get_fieldOptionValues);

                                        var fVCount = 0;
                                        if (!Array.isArray(get_fieldOptionValues.fieldOptionValues)){
                                            fieldValueOptionsHtml += get_fieldOptionValues.label + ",";
                                        }
                                        else{
                                            for (let fieldValue of get_fieldOptionValues.fieldOptionValues) {
                                                if (fieldValue.value.length > 0) {
                                                    fieldValueOptionsHtml += fieldValue.label + ",";
                                                }
                                                fVCount++;
                                            }
                                        }


                                        fieldValueOptionsHtml = fieldValueOptionsHtml.replace("undefined", "");
                                        fieldValueOptionsHtml = fieldValueOptionsHtml.replace(/^,/, '');
                                        fieldValueOptionsHtml = fieldValueOptionsHtml.replace(/,\s*$/, "");

                                        // console.log(fieldValueOptionsHtml);

                                        jQuery("#form-test").append('' +
                                            '    <div class="df-row-container">\n' +
                                            '        <div class="df-row-input">' + '-->' + (i_2 + 1) + ') ' +
                                            '        <input type="text" name="meta-survey-sv-question-' + (i) + '-label-' + (i_2) + '" id="meta-survey-sv-question-' + (i) + '-label-' + (i_2) + '" value="' + get_fieldOptionValues.label.replace(/\:$/, '') + '" readonly="readonly" />\n' +
                                            '        <input type="text" name="meta-survey-sv-question-' + (i) + '-fieldName-' + (i_2) + '" id="meta-survey-sv-question-' + (i) + '-fieldName-' + (i_2) + '" value="' + get_fieldOptionValues.fieldName + '" readonly="readonly" />\n' +
                                            '        <input type="text" name="meta-survey-sv-question-' + (i) + '-fieldStatus-' + (i_2) + '" id="meta-survey-sv-question-' + (i) + '-fieldStatus-' + (i_2) + '" value="' + get_fieldOptionValues.fieldStatus + '" readonly="readonly" />\n' +
                                            '        <textarea type="text" name="meta-survey-sv-question-' + (i) + '-fieldValues-' + (i_2) + '" id="meta-survey-sv-question-' + (i) + '-fieldValues-' + (i_2) + '">' +
                                            fieldValueOptionsHtml +
                                            '</textarea></div>\n' +
                                            '    </div>'
                                        );
                                    }
                                    else{
                                        jQuery("#form-test").append('' +
                                            '    <div class="df-row-container">\n' +
                                            '        <div class="df-row-input">' + '-->' + (i_2+1) + ') ' +
                                            '        <input type="text" name="meta-survey-sv-question-'+ (i) + '-label-' + (i_2) + '" id="meta-survey-sv-question-'+ (i) + '-label-' + (i_2) + '" value="' + get_fieldOptionValues.label.replace(/\:$/, '') + '" readonly="readonly" />\n' +
                                            '        <input type="text" name="meta-survey-sv-question-'+ (i) + '-fieldName-' + (i_2) + '" id="meta-survey-sv-question-'+ (i) + '-fieldName-' + (i_2) + '" value="' + get_fieldOptionValues.fieldName + '" readonly="readonly" />\n' +
                                            '        <input type="text" name="meta-survey-sv-question-'+ (i) + '-fieldStatus-' + (i_2) + '" id="meta-survey-sv-question-'+ (i) + '-fieldStatus-' + (i_2) + '" value="' + get_fieldOptionValues.fieldStatus + '" readonly="readonly" /></div>\n' +
                                            '    </div>'
                                        );
                                    }


                                }
                                //Append total count of subquestions to value
                                if(contactInfoFieldLength > subQuestionMaxCount){
                                    subQuestionMaxCount = contactInfoFieldLength;
                                }
                                // console.log(data.getSurveyResponse.survey.surveyQuestions[i].hidden);
                                break;

                            case "DateQuestion":
                                var k = 0;
                                //Append base question info
                                jQuery("#form-test").append('' +
                                    '    <div class="df-row-container"><span class="wplo-survey-number-block">' + (i+1) + '.) </span>' +
                                    '        <div class="df-row-input">' +
                                    '        <input type="text" name="meta-survey-sv-datequestion-'+ (i) + '-type" id="meta-survey-sv-datequestion-'+ (i) + '-type" value="' + data.getSurveyResponse.survey.surveyQuestions[i].questionType + '" readonly="readonly" />\n' +
                                    '        <input type="text" name="meta-survey-sv-datequestion-'+ (i) + '-text" id="meta-survey-sv-datequestion-'+ (i) + '-text" value="' + data.getSurveyResponse.survey.surveyQuestions[i].questionText + '" readonly="readonly" />\n' +
                                    '        <input type="text" name="meta-survey-sv-datequestion-'+ (i) + '-id" id="meta-survey-sv-datequestion-'+ (i) + '-id" value="' + data.getSurveyResponse.survey.surveyQuestions[i].questionId  + '" readonly="readonly" />\n' +
                                    '        <input type="text" name="meta-survey-sv-datequestion-'+ (i) + '-required" id="meta-survey-sv-datequestion-'+ (i) + '-required" value="' + data.getSurveyResponse.survey.surveyQuestions[i].questionRequired + '" readonly="readonly" /></div>\n' +
                                    '    </div>'
                                );
                                break;

                            case "HiddenInterests":
                                //Append base question info
                                jQuery("#form-test").append('' +
                                    '    <div class="df-row-container"><span class="wplo-survey-number-block">' + (i+1) + '.) </span>' +
                                    '        <div class="df-row-input">' +
                                    '        <input type="text" name="meta-survey-sv-hiddenInterest-'+ (i) + '-type" id="meta-survey-sv-hiddenInterest-'+ (i) + '-type" value="' + data.getSurveyResponse.survey.surveyQuestions[i].questionType + '" readonly="readonly" />\n' +
                                    '        <input type="text" name="meta-survey-sv-hiddenInterest-'+ (i) + '-text" id="meta-survey-sv-hiddenInterest-'+ (i) + '-text" value="' + data.getSurveyResponse.survey.surveyQuestions[i].questionText + '" readonly="readonly" />\n' +
                                    '        <input type="text" name="meta-survey-sv-hiddenInterest-'+ (i) + '-id" id="meta-survey-sv-hiddenInterest-'+ (i) + '-id" value="' + data.getSurveyResponse.survey.surveyQuestions[i].questionId  + '" readonly="readonly" />\n' +
                                    '        <input type="text" name="meta-survey-sv-hiddenInterest-'+ (i) + '-required" id="meta-survey-sv-hiddenInterest-'+ (i) + '-required" value="' + data.getSurveyResponse.survey.surveyQuestions[i].questionRequired + '" readonly="readonly" /></div>\n' +
                                    '    </div>'
                                );
                                //Iterate through question values

                                var availableAnswerFieldLength = null;
                                var get_availableAnswerArray = null;
                                if (!Array.isArray(data.getSurveyResponse.survey.surveyQuestions[i].questionTypeData.surveyQuestionData.availableAnswer)){
                                    availableAnswerFieldLength = 1;
                                    get_availableAnswerArray = data.getSurveyResponse.survey.surveyQuestions[i].questionTypeData.surveyQuestionData.availableAnswer;
                                }
                                else{
                                    availableAnswerFieldLength = data.getSurveyResponse.survey.surveyQuestions[i].questionTypeData.surveyQuestionData.availableAnswer.length;
                                }
                                for (var i_2 = 0, fLen = availableAnswerFieldLength; i_2 < fLen; i_2++) {
                                    if (Array.isArray(data.getSurveyResponse.survey.surveyQuestions[i].questionTypeData.surveyQuestionData.availableAnswer)){
                                        get_availableAnswerArray = data.getSurveyResponse.survey.surveyQuestions[i].questionTypeData.surveyQuestionData.availableAnswer[i_2];
                                    }
                                    jQuery("#form-test").append('' +
                                        '    <div class="df-row-container">\n' +
                                        '        <div class="df-row-input">' + '-->' + (i_2 + 1) + ') ' +
                                        '        <input type="text" name="meta-survey-sv-hiddenInterest-' + (i) + '-label-' + (i_2) + '" id="meta-survey-sv-hiddenInterest-' + (i) + '-label-' + (i_2) + '" value="' + get_availableAnswerArray.label + '" readonly="readonly" />\n' +
                                        '        <input type="text" name="meta-survey-sv-hiddenInterest-' + (i) + '-value-' + (i_2) + '" id="meta-survey-sv-hiddenInterest-' + (i) + '-value-' + (i_2) + '" value="' + get_availableAnswerArray.value + '" readonly="readonly" /></div>\n' +
                                        '    </div>'
                                    );
                                }
                                if(availableAnswerFieldLength > subQuestionMaxCount){
                                    subQuestionMaxCount = availableAnswerFieldLength;
                                }
                                break;

                            case "HiddenTextValue":
                                var k = 0;
                                //Append base question info
                                jQuery("#form-test").append('' +
                                    '    <div class="df-row-container"><span class="wplo-survey-number-block">' + (i+1) + '.) </span>' +
                                    '        <div class="df-row-input">' +
                                    '        <input type="text" name="meta-survey-sv-hiddentextvalue-'+ (i) + '-type" id="meta-survey-sv-hiddentextvalue-'+ (i) + '-type" value="' + data.getSurveyResponse.survey.surveyQuestions[i].questionType + '" readonly="readonly" />\n' +
                                    '        <input type="text" name="meta-survey-sv-hiddentextvalue-'+ (i) + '-text" id="meta-survey-sv-hiddentextvalue-'+ (i) + '-text" value="' + data.getSurveyResponse.survey.surveyQuestions[i].questionText + '" readonly="readonly" />\n' +
                                    '        <input type="text" name="meta-survey-sv-hiddentextvalue-'+ (i) + '-id" id="meta-survey-sv-hiddentextvalue-'+ (i) + '-id" value="' + data.getSurveyResponse.survey.surveyQuestions[i].questionId  + '" readonly="readonly" />\n' +
                                    '        <input type="text" name="meta-survey-sv-hiddentextvalue-'+ (i) + '-required" id="meta-survey-sv-hiddentextvalue-'+ (i) + '-required" value="' + data.getSurveyResponse.survey.surveyQuestions[i].questionRequired + '" readonly="readonly" /></div>\n' +
                                    '    </div>'
                                );
                                break;

                            case "HiddenTrueFalse":
                                //Append base question info
                                jQuery("#form-test").append('' +
                                    '    <div class="df-row-container"><span class="wplo-survey-number-block">' + (i+1) + '.) </span>' +
                                    '        <div class="df-row-input">' +
                                    '        <input type="text" name="meta-survey-sv-hiddentruefalse-'+ (i) + '-type" id="meta-survey-sv-hiddentruefalse-'+ (i) + '-type" value="' + data.getSurveyResponse.survey.surveyQuestions[i].questionType + '" readonly="readonly" />\n' +
                                    '        <input type="text" name="meta-survey-sv-hiddentruefalse-'+ (i) + '-text" id="meta-survey-sv-hiddentruefalse-'+ (i) + '-text" value="' + data.getSurveyResponse.survey.surveyQuestions[i].questionText + '" readonly="readonly" />\n' +
                                    '        <input type="text" name="meta-survey-sv-hiddentruefalse-'+ (i) + '-id" id="meta-survey-sv-hiddentruefalse-'+ (i) + '-id" value="' + data.getSurveyResponse.survey.surveyQuestions[i].questionId  + '" readonly="readonly" />\n' +
                                    '        <input type="text" name="meta-survey-sv-hiddentruefalse-'+ (i) + '-required" id="meta-survey-sv-hiddentruefalse-'+ (i) + '-required" value="' + data.getSurveyResponse.survey.surveyQuestions[i].questionRequired + '" readonly="readonly" />\n' +
                                    '    </div>'
                                );
                                break;

                            case "Categories":
                                //Append base question info
                                jQuery("#form-test").append('' +
                                    '    <div class="df-row-container"><span class="wplo-survey-number-block">' + (i+1) + '.) </span>' +
                                    '        <div class="df-row-input">' +
                                    '        <input type="text" name="meta-survey-sv-categories-'+ (i) + '-type" id="meta-survey-sv-categories-'+ (i) + '-type" value="' + data.getSurveyResponse.survey.surveyQuestions[i].questionType + '" readonly="readonly" />\n' +
                                    '        <input type="text" name="meta-survey-sv-categories-'+ (i) + '-text" id="meta-survey-sv-categories-'+ (i) + '-text" value="' + data.getSurveyResponse.survey.surveyQuestions[i].questionText + '" readonly="readonly" />\n' +
                                    '        <input type="text" name="meta-survey-sv-categories-'+ (i) + '-id" id="meta-survey-sv-categories-'+ (i) + '-id" value="' + data.getSurveyResponse.survey.surveyQuestions[i].questionId  + '" readonly="readonly" />\n' +
                                    '        <input type="text" name="meta-survey-sv-categories-'+ (i) + '-required" id="meta-survey-sv-categories-'+ (i) + '-required" value="' + data.getSurveyResponse.survey.surveyQuestions[i].questionRequired + '" readonly="readonly" />\n' +
                                    '        <input type="text" name="meta-survey-sv-categories-'+ (i) + '-max" id="meta-survey-sv-categories-'+ (i) + '-max" value="' + data.getSurveyResponse.survey.surveyQuestions[i].questionMaxResponses + '" readonly="readonly" /></div>\n' +
                                    '    </div>'
                                );
                                //Iterate through question values

                                var availableAnswerFieldLength = null;
                                var get_availableAnswerArray = null;
                                if (!Array.isArray(data.getSurveyResponse.survey.surveyQuestions[i].questionTypeData.surveyQuestionData.availableAnswer)){
                                    availableAnswerFieldLength = 1;
                                    get_availableAnswerArray = data.getSurveyResponse.survey.surveyQuestions[i].questionTypeData.surveyQuestionData.availableAnswer;
                                }
                                else{
                                    availableAnswerFieldLength = data.getSurveyResponse.survey.surveyQuestions[i].questionTypeData.surveyQuestionData.availableAnswer.length;
                                }
                                for (var i_2 = 0, fLen = availableAnswerFieldLength; i_2 < fLen; i_2++) {
                                    if (Array.isArray(data.getSurveyResponse.survey.surveyQuestions[i].questionTypeData.surveyQuestionData.availableAnswer)){
                                        get_availableAnswerArray = data.getSurveyResponse.survey.surveyQuestions[i].questionTypeData.surveyQuestionData.availableAnswer[i_2];
                                    }
                                    jQuery("#form-test").append('' +
                                        '    <div class="df-row-container">\n' +
                                        '        <div class="df-row-input">' + '-->' + (i_2 + 1) + ') ' +
                                        '        <input type="text" name="meta-survey-sv-categories-' + (i) + '-label-' + (i_2) + '" id="meta-survey-sv-categories-' + (i) + '-label-' + (i_2) + '" value="' + get_availableAnswerArray.label + '" readonly="readonly" />\n' +
                                        '        <input type="text" name="meta-survey-sv-categories-' + (i) + '-value-' + (i_2) + '" id="meta-survey-sv-categories-' + (i) + '-value-' + (i_2) + '" value="' + get_availableAnswerArray.value + '" readonly="readonly" />\n' +
                                        '        <input type="text" name="meta-survey-sv-categories-' + (i) + '-selected-' + (i_2) + '" id="meta-survey-sv-categories-' + (i) + '-selected-' + (i_2) + '" value="' + get_availableAnswerArray.selected + '" readonly="readonly" /></div>\n' +
                                        '    </div>'
                                    );
                                }
                                if(availableAnswerFieldLength > subQuestionMaxCount){
                                    subQuestionMaxCount = availableAnswerFieldLength;
                                }
                                break;

                            case "ComboChoice":
                                //Append base question info
                                jQuery("#form-test").append('' +
                                    '    <div class="df-row-container"><span class="wplo-survey-number-block">' + (i+1) + '.) </span>' +
                                    '        <div class="df-row-input">' +
                                    '        <input type="text" name="meta-survey-sv-combochoice-'+ (i) + '-type" id="meta-survey-sv-combochoice-'+ (i) + '-type" value="' + data.getSurveyResponse.survey.surveyQuestions[i].questionType + '" readonly="readonly" />\n' +
                                    '        <input type="text" name="meta-survey-sv-combochoice-'+ (i) + '-text" id="meta-survey-sv-combochoice-'+ (i) + '-text" value="' + data.getSurveyResponse.survey.surveyQuestions[i].questionText + '" readonly="readonly" />\n' +
                                    '        <input type="text" name="meta-survey-sv-combochoice-'+ (i) + '-id" id="meta-survey-sv-combochoice-'+ (i) + '-id" value="' + data.getSurveyResponse.survey.surveyQuestions[i].questionId  + '" readonly="readonly" />\n' +
                                    '        <input type="text" name="meta-survey-sv-combochoice-'+ (i) + '-hint" id="meta-survey-sv-combochoice-'+ (i) + '-hint" value="' + data.getSurveyResponse.survey.surveyQuestions[i].questionHint  + '" readonly="readonly" />\n' +
                                    '        <input type="text" name="meta-survey-sv-combochoice-'+ (i) + '-required" id="meta-survey-sv-combochoice-'+ (i) + '-required" value="' + data.getSurveyResponse.survey.surveyQuestions[i].questionRequired + '" readonly="readonly" />\n' +
                                    '        <input type="text" name="meta-survey-sv-combochoice-'+ (i) + '-min" id="meta-survey-sv-combochoice-'+ (i) + '-min" value="' + data.getSurveyResponse.survey.surveyQuestions[i].questionMinResponses + '" readonly="readonly" />\n' +
                                    '        <input type="text" name="meta-survey-sv-combochoice-'+ (i) + '-max" id="meta-survey-sv-combochoice-'+ (i) + '-max" value="' + data.getSurveyResponse.survey.surveyQuestions[i].questionMaxResponses + '" readonly="readonly" /></div>\n' +
                                    '    </div>'
                                );
                                //Iterate through question values

                                var availableAnswerFieldLength = null;
                                var get_availableAnswerArray = null;
                                if (!Array.isArray(data.getSurveyResponse.survey.surveyQuestions[i].questionTypeData.surveyQuestionData.availableAnswer)){
                                    availableAnswerFieldLength = 1;
                                    get_availableAnswerArray = data.getSurveyResponse.survey.surveyQuestions[i].questionTypeData.surveyQuestionData.availableAnswer;
                                }
                                else{
                                    availableAnswerFieldLength = data.getSurveyResponse.survey.surveyQuestions[i].questionTypeData.surveyQuestionData.availableAnswer.length;
                                }
                                for (var i_2 = 0, fLen = availableAnswerFieldLength; i_2 < fLen; i_2++) {
                                    if (Array.isArray(data.getSurveyResponse.survey.surveyQuestions[i].questionTypeData.surveyQuestionData.availableAnswer)){
                                        get_availableAnswerArray = data.getSurveyResponse.survey.surveyQuestions[i].questionTypeData.surveyQuestionData.availableAnswer[i_2];
                                    }
                                    jQuery("#form-test").append('' +
                                        '    <div class="df-row-container">\n' +
                                        '        <div class="df-row-input">' + '-->' + (i_2 + 1) + ') ' +
                                        '        <input type="text" name="meta-survey-sv-combochoice-' + (i) + '-label-' + (i_2) + '" id="meta-survey-sv-combochoice-' + (i) + '-label-' + (i_2) + '" value="' + get_availableAnswerArray.label + '" readonly="readonly" />\n' +
                                        '        <input type="text" name="meta-survey-sv-combochoice-' + (i) + '-value-' + (i_2) + '" id="meta-survey-sv-combochoice-' + (i) + '-value-' + (i_2) + '" value="' + get_availableAnswerArray.value + '" readonly="readonly" /></div>\n' +
                                        '    </div>'
                                    );
                                }
                                if(availableAnswerFieldLength > subQuestionMaxCount){
                                    subQuestionMaxCount = availableAnswerFieldLength;
                                }
                                break;

                            case "MultiMulti":
                                //Append base question info
                                jQuery("#form-test").append('' +
                                    '    <div class="df-row-container"><span class="wplo-survey-number-block">' + (i+1) + '.) </span>' +
                                    '        <div class="df-row-input">' +
                                    '        <input type="text" name="meta-survey-sv-multimulti-'+ (i) + '-type" id="meta-survey-sv-multimulti-'+ (i) + '-type" value="' + data.getSurveyResponse.survey.surveyQuestions[i].questionType + '" readonly="readonly" />\n' +
                                    '        <input type="text" name="meta-survey-sv-multimulti-'+ (i) + '-text" id="meta-survey-sv-multimulti-'+ (i) + '-text" value="' + data.getSurveyResponse.survey.surveyQuestions[i].questionText + '" readonly="readonly" />\n' +
                                    '        <input type="text" name="meta-survey-sv-multimulti-'+ (i) + '-id" id="meta-survey-sv-multimulti-'+ (i) + '-id" value="' + data.getSurveyResponse.survey.surveyQuestions[i].questionId  + '" readonly="readonly" />\n' +
                                    '        <input type="text" name="meta-survey-sv-multimulti-'+ (i) + '-hint" id="meta-survey-sv-multimulti-'+ (i) + '-hint" value="' + data.getSurveyResponse.survey.surveyQuestions[i].questionHint  + '" readonly="readonly" />\n' +
                                    '        <input type="text" name="meta-survey-sv-multimulti-'+ (i) + '-required" id="meta-survey-sv-multimulti-'+ (i) + '-required" value="' + data.getSurveyResponse.survey.surveyQuestions[i].questionRequired + '" readonly="readonly" />\n' +
                                    '        <input type="text" name="meta-survey-sv-multimulti-'+ (i) + '-min" id="meta-survey-sv-multimulti-'+ (i) + '-min" value="' + data.getSurveyResponse.survey.surveyQuestions[i].questionMinResponses + '" readonly="readonly" />\n' +
                                    '        <input type="text" name="meta-survey-sv-multimulti-'+ (i) + '-max" id="meta-survey-sv-multimulti-'+ (i) + '-max" value="' + data.getSurveyResponse.survey.surveyQuestions[i].questionMaxResponses + '" readonly="readonly" /></div>\n' +
                                    '    </div>'
                                );
                                //Iterate through question values

                                var availableAnswerFieldLength = null;
                                var get_availableAnswerArray = null;
                                if (!Array.isArray(data.getSurveyResponse.survey.surveyQuestions[i].questionTypeData.surveyQuestionData.availableAnswer)){
                                    availableAnswerFieldLength = 1;
                                    get_availableAnswerArray = data.getSurveyResponse.survey.surveyQuestions[i].questionTypeData.surveyQuestionData.availableAnswer;
                                }
                                else{
                                    availableAnswerFieldLength = data.getSurveyResponse.survey.surveyQuestions[i].questionTypeData.surveyQuestionData.availableAnswer.length;
                                }
                                for (var i_2 = 0, fLen = availableAnswerFieldLength; i_2 < fLen; i_2++) {
                                    if (Array.isArray(data.getSurveyResponse.survey.surveyQuestions[i].questionTypeData.surveyQuestionData.availableAnswer)){
                                        get_availableAnswerArray = data.getSurveyResponse.survey.surveyQuestions[i].questionTypeData.surveyQuestionData.availableAnswer[i_2];
                                    }
                                    jQuery("#form-test").append('' +
                                        '    <div class="df-row-container">\n' +
                                        '        <div class="df-row-input">' + '-->' + (i_2 + 1) + ') ' +
                                        '        <input type="text" name="meta-survey-sv-multimulti-' + (i) + '-label-' + (i_2) + '" id="meta-survey-sv-multimulti-' + (i) + '-label-' + (i_2) + '" value="' + get_availableAnswerArray.label + '" readonly="readonly" />\n' +
                                        '        <input type="text" name="meta-survey-sv-multimulti-' + (i) + '-value-' + (i_2) + '" id="meta-survey-sv-multimulti-' + (i) + '-value-' + (i_2) + '" value="' + get_availableAnswerArray.value + '" readonly="readonly" /></div>\n' +
                                        '    </div>'
                                    );
                                }
                                if(availableAnswerFieldLength > subQuestionMaxCount){
                                    subQuestionMaxCount = availableAnswerFieldLength;
                                }
                                break;

                            case "MultiSingle":
                                //Append base question info
                                jQuery("#form-test").append('' +
                                    '    <div class="df-row-container"><span class="wplo-survey-number-block">' + (i+1) + '.) </span>' +
                                    '        <div class="df-row-input">' +
                                    '        <input type="text" name="meta-survey-sv-multisingle-'+ (i) + '-type" id="meta-survey-sv-multisingle-'+ (i) + '-type" value="' + data.getSurveyResponse.survey.surveyQuestions[i].questionType + '" readonly="readonly" />\n' +
                                    '        <input type="text" name="meta-survey-sv-multisingle-'+ (i) + '-text" id="meta-survey-sv-multisingle-'+ (i) + '-text" value="' + data.getSurveyResponse.survey.surveyQuestions[i].questionText + '" readonly="readonly" />\n' +
                                    '        <input type="text" name="meta-survey-sv-multisingle-'+ (i) + '-id" id="meta-survey-sv-multisingle-'+ (i) + '-id" value="' + data.getSurveyResponse.survey.surveyQuestions[i].questionId  + '" readonly="readonly" />\n' +
                                    '        <input type="text" name="meta-survey-sv-multisingle-'+ (i) + '-required" id="meta-survey-sv-multisingle-'+ (i) + '-required" value="' + data.getSurveyResponse.survey.surveyQuestions[i].questionRequired + '" readonly="readonly" />\n' +
                                    '        <input type="text" name="meta-survey-sv-multisingle-'+ (i) + '-max" id="meta-survey-sv-multisingle-'+ (i) + '-max" value="' + data.getSurveyResponse.survey.surveyQuestions[i].questionMaxResponses + '" readonly="readonly" /></div>\n' +
                                    '    </div>'
                                );
                                //Iterate through question values

                                var availableAnswerFieldLength = null;
                                var get_availableAnswerArray = null;
                                if (!Array.isArray(data.getSurveyResponse.survey.surveyQuestions[i].questionTypeData.surveyQuestionData.availableAnswer)){
                                    availableAnswerFieldLength = 1;
                                    get_availableAnswerArray = data.getSurveyResponse.survey.surveyQuestions[i].questionTypeData.surveyQuestionData.availableAnswer;
                                }
                                else{
                                    availableAnswerFieldLength = data.getSurveyResponse.survey.surveyQuestions[i].questionTypeData.surveyQuestionData.availableAnswer.length;
                                }
                                for (var i_2 = 0, fLen = availableAnswerFieldLength; i_2 < fLen; i_2++) {
                                    if (Array.isArray(data.getSurveyResponse.survey.surveyQuestions[i].questionTypeData.surveyQuestionData.availableAnswer)){
                                        get_availableAnswerArray = data.getSurveyResponse.survey.surveyQuestions[i].questionTypeData.surveyQuestionData.availableAnswer[i_2];
                                    }
                                    jQuery("#form-test").append('' +
                                        '    <div class="df-row-container">\n' +
                                        '        <div class="df-row-input">' + '-->' + (i_2 + 1) + ') ' +
                                        '        <input type="text" name="meta-survey-sv-multisingle-' + (i) + '-label-' + (i_2) + '" id="meta-survey-sv-multisingle-' + (i) + '-label-' + (i_2) + '" value="' + get_availableAnswerArray.label + '" readonly="readonly" />\n' +
                                        '        <input type="text" name="meta-survey-sv-multisingle-' + (i) + '-value-' + (i_2) + '" id="meta-survey-sv-multisingle-' + (i) + '-value-' + (i_2) + '" value="' + get_availableAnswerArray.value + '" readonly="readonly" /></div>\n' +
                                        '    </div>'
                                    );
                                }
                                if(availableAnswerFieldLength > subQuestionMaxCount){
                                    subQuestionMaxCount = availableAnswerFieldLength;
                                }
                                break;

                            case "MultiSingleRadio":
                                //Append base question info
                                jQuery("#form-test").append('' +
                                    '    <div class="df-row-container"><span class="wplo-survey-number-block">' + (i+1) + '.) </span>' +
                                    '        <div class="df-row-input">' +
                                    '        <input type="text" name="meta-survey-sv-multisingleradio-'+ (i) + '-type" id="meta-survey-sv-multisingleradio-'+ (i) + '-type" value="' + data.getSurveyResponse.survey.surveyQuestions[i].questionType + '" readonly="readonly" />\n' +
                                    '        <input type="text" name="meta-survey-sv-multisingleradio-'+ (i) + '-text" id="meta-survey-sv-multisingleradio-'+ (i) + '-text" value="' + data.getSurveyResponse.survey.surveyQuestions[i].questionText + '" readonly="readonly" />\n' +
                                    '        <input type="text" name="meta-survey-sv-multisingleradio-'+ (i) + '-id" id="meta-survey-sv-multisingleradio-'+ (i) + '-id" value="' + data.getSurveyResponse.survey.surveyQuestions[i].questionId  + '" readonly="readonly" />\n' +
                                    '        <input type="text" name="meta-survey-sv-multisingleradio-'+ (i) + '-required" id="meta-survey-sv-multisingleradio-'+ (i) + '-required" value="' + data.getSurveyResponse.survey.surveyQuestions[i].questionRequired + '" readonly="readonly" /></div>\n' +
                                    '    </div>'
                                );
                                //Iterate through question values

                                var availableAnswerFieldLength = null;
                                var get_availableAnswerArray = null;
                                if (!Array.isArray(data.getSurveyResponse.survey.surveyQuestions[i].questionTypeData.surveyQuestionData.availableAnswer)){
                                    availableAnswerFieldLength = 1;
                                    get_availableAnswerArray = data.getSurveyResponse.survey.surveyQuestions[i].questionTypeData.surveyQuestionData.availableAnswer;
                                }
                                else{
                                    availableAnswerFieldLength = data.getSurveyResponse.survey.surveyQuestions[i].questionTypeData.surveyQuestionData.availableAnswer.length;
                                }
                                for (var i_2 = 0, fLen = availableAnswerFieldLength; i_2 < fLen; i_2++) {
                                    if (Array.isArray(data.getSurveyResponse.survey.surveyQuestions[i].questionTypeData.surveyQuestionData.availableAnswer)){
                                        get_availableAnswerArray = data.getSurveyResponse.survey.surveyQuestions[i].questionTypeData.surveyQuestionData.availableAnswer[i_2];
                                    }
                                    jQuery("#form-test").append('' +
                                        '    <div class="df-row-container">\n' +
                                        '        <div class="df-row-input">' + '-->' + (i_2 + 1) + ') ' +
                                        '        <input type="text" name="meta-survey-sv-multisingleradio-' + (i) + '-label-' + (i_2) + '" id="meta-survey-sv-multisingleradio-' + (i) + '-label-' + (i_2) + '" value="' + get_availableAnswerArray.label + '" readonly="readonly" />\n' +
                                        '        <input type="text" name="meta-survey-sv-multisingleradio-' + (i) + '-value-' + (i_2) + '" id="meta-survey-sv-multisingleradio-' + (i) + '-value-' + (i_2) + '" value="' + get_availableAnswerArray.value + '" readonly="readonly" /></div>\n' +
                                        '    </div>'
                                    );
                                }
                                if(availableAnswerFieldLength > subQuestionMaxCount){
                                    subQuestionMaxCount = availableAnswerFieldLength;
                                }
                                break;

                            case "NumericValue":
                                var k = 0;
                                //Append base question info
                                jQuery("#form-test").append('' +
                                    '    <div class="df-row-container"><span class="wplo-survey-number-block">' + (i+1) + '.) </span>' +
                                    '        <div class="df-row-input">' +
                                    '        <input type="text" name="meta-survey-sv-numericvalue-'+ (i) + '-type" id="meta-survey-sv-numericvalue-'+ (i) + '-type" value="' + data.getSurveyResponse.survey.surveyQuestions[i].questionType + '" readonly="readonly" />\n' +
                                    '        <input type="text" name="meta-survey-sv-numericvalue-'+ (i) + '-text" id="meta-survey-sv-numericvalue-'+ (i) + '-text" value="' + data.getSurveyResponse.survey.surveyQuestions[i].questionText + '" readonly="readonly" />\n' +
                                    '        <input type="text" name="meta-survey-sv-numericvalue-'+ (i) + '-id" id="meta-survey-sv-numericvalue-'+ (i) + '-id" value="' + data.getSurveyResponse.survey.surveyQuestions[i].questionId  + '" readonly="readonly" />\n' +
                                    '        <input type="text" name="meta-survey-sv-numericvalue-'+ (i) + '-required" id="meta-survey-sv-numericvalue-'+ (i) + '-required" value="' + data.getSurveyResponse.survey.surveyQuestions[i].questionRequired + '" readonly="readonly" /></div>\n' +
                                    '    </div>'
                                );
                                break;

                            case "RatingScale":
                                //Append base question info
                                jQuery("#form-test").append('' +
                                    '    <div class="df-row-container"><span class="wplo-survey-number-block">' + (i+1) + '.) </span>' +
                                    '        <div class="df-row-input">' +
                                    '        <input type="text" name="meta-survey-sv-ratingscale-'+ (i) + '-type" id="meta-survey-sv-ratingscale-'+ (i) + '-type" value="' + data.getSurveyResponse.survey.surveyQuestions[i].questionType + '" readonly="readonly" />\n' +
                                    '        <input type="text" name="meta-survey-sv-ratingscale-'+ (i) + '-text" id="meta-survey-sv-ratingscale-'+ (i) + '-text" value="' + data.getSurveyResponse.survey.surveyQuestions[i].questionText + '" readonly="readonly" />\n' +
                                    '        <input type="text" name="meta-survey-sv-ratingscale-'+ (i) + '-id" id="meta-survey-sv-ratingscale-'+ (i) + '-id" value="' + data.getSurveyResponse.survey.surveyQuestions[i].questionId  + '" readonly="readonly" />\n' +
                                    '        <input type="text" name="meta-survey-sv-ratingscale-'+ (i) + '-required" id="meta-survey-sv-ratingscale-'+ (i) + '-required" value="' + data.getSurveyResponse.survey.surveyQuestions[i].questionRequired + '" readonly="readonly" /></div>\n' +
                                    '    </div>'
                                );
                                //Iterate through question values

                                var availableAnswerFieldLength = null;
                                var get_availableAnswerArray = null;
                                if (!Array.isArray(data.getSurveyResponse.survey.surveyQuestions[i].questionTypeData.surveyQuestionData.availableAnswer)){
                                    availableAnswerFieldLength = 1;
                                    get_availableAnswerArray = data.getSurveyResponse.survey.surveyQuestions[i].questionTypeData.surveyQuestionData.availableAnswer;
                                }
                                else{
                                    availableAnswerFieldLength = data.getSurveyResponse.survey.surveyQuestions[i].questionTypeData.surveyQuestionData.availableAnswer.length;
                                }
                                for (var i_2 = 0, fLen = availableAnswerFieldLength; i_2 < fLen; i_2++) {
                                    if (Array.isArray(data.getSurveyResponse.survey.surveyQuestions[i].questionTypeData.surveyQuestionData.availableAnswer)){
                                        get_availableAnswerArray = data.getSurveyResponse.survey.surveyQuestions[i].questionTypeData.surveyQuestionData.availableAnswer[i_2];
                                    }
                                    jQuery("#form-test").append('' +
                                        '    <div class="df-row-container">\n' +
                                        '        <div class="df-row-input">' + '-->' + (i_2 + 1) + ') ' +
                                        '        <input type="text" name="meta-survey-sv-ratingscale-' + (i) + '-label-' + (i_2) + '" id="meta-survey-sv-ratingscale-' + (i) + '-label-' + (i_2) + '" value="' + get_availableAnswerArray.label + '" readonly="readonly" />\n' +
                                        '        <input type="text" name="meta-survey-sv-ratingscale-' + (i) + '-value-' + (i_2) + '" id="meta-survey-sv-ratingscale-' + (i) + '-value-' + (i_2) + '" value="' + get_availableAnswerArray.value + '" readonly="readonly" /></div>\n' +
                                        '    </div>'
                                    );
                                }
                                if(availableAnswerFieldLength > subQuestionMaxCount){
                                    subQuestionMaxCount = availableAnswerFieldLength;
                                }
                                break;

                            case "ShortTextValue":
                                var k = 0;
                                //Append base question info
                                jQuery("#form-test").append('' +
                                    '    <div class="df-row-container"><span class="wplo-survey-number-block">' + (i+1) + '.) </span>' +
                                    '        <div class="df-row-input">' +
                                    '        <input type="text" name="meta-survey-sv-shorttext-'+ (i) + '-type" id="meta-survey-sv-shorttext-'+ (i) + '-type" value="' + data.getSurveyResponse.survey.surveyQuestions[i].questionType + '" readonly="readonly" />\n' +
                                    '        <input type="text" name="meta-survey-sv-shorttext-'+ (i) + '-text" id="meta-survey-sv-shorttext-'+ (i) + '-text" value="' + data.getSurveyResponse.survey.surveyQuestions[i].questionText + '" readonly="readonly" />\n' +
                                    '        <input type="text" name="meta-survey-sv-shorttext-'+ (i) + '-id" id="meta-survey-sv-shorttext-'+ (i) + '-id" value="' + data.getSurveyResponse.survey.surveyQuestions[i].questionId  + '" readonly="readonly" />\n' +
                                    '        <input type="text" name="meta-survey-sv-shorttext-'+ (i) + '-required" id="meta-survey-sv-shorttext-'+ (i) + '-required" value="' + data.getSurveyResponse.survey.surveyQuestions[i].questionRequired + '" readonly="readonly" /></div>\n' +
                                    '    </div>'
                                );
                                break;

                            case "TextValue":
                                var k = 0;
                                //Append base question info
                                jQuery("#form-test").append('' +
                                    '    <div class="df-row-container"><span class="wplo-survey-number-block">' + (i+1) + '.) </span>' +
                                    '        <div class="df-row-input">' +
                                    '        <input type="text" name="meta-survey-sv-textvalue-'+ (i) + '-type" id="meta-survey-sv-textvalue-'+ (i) + '-type" value="' + data.getSurveyResponse.survey.surveyQuestions[i].questionType + '" readonly="readonly" />\n' +
                                    '        <input type="text" name="meta-survey-sv-textvalue-'+ (i) + '-text" id="meta-survey-sv-textvalue-'+ (i) + '-text" value="' + data.getSurveyResponse.survey.surveyQuestions[i].questionText + '" readonly="readonly" />\n' +
                                    '        <input type="text" name="meta-survey-sv-textvalue-'+ (i) + '-id" id="meta-survey-sv-textvalue-'+ (i) + '-id" value="' + data.getSurveyResponse.survey.surveyQuestions[i].questionId  + '" readonly="readonly" />\n' +
                                    '        <input type="text" name="meta-survey-sv-textvalue-'+ (i) + '-hint" id="meta-survey-sv-textvalue-'+ (i) + '-hint" value="' + data.getSurveyResponse.survey.surveyQuestions[i].questionHint  + '" readonly="readonly" />\n' +
                                    '        <input type="text" name="meta-survey-sv-textvalue-'+ (i) + '-required" id="meta-survey-sv-textvalue-'+ (i) + '-required" value="' + data.getSurveyResponse.survey.surveyQuestions[i].questionRequired + '" readonly="readonly" /></div>\n' +
                                    '    </div>'
                                );
                                break;

                            case "TrueFalse":
                                //Append base question info
                                jQuery("#form-test").append('' +
                                    '    <div class="df-row-container"><span class="wplo-survey-number-block">' + (i+1) + '.) </span>' +
                                    '        <div class="df-row-input">' +
                                    '        <input type="text" name="meta-survey-sv-truefalse-'+ (i) + '-type" id="meta-survey-sv-truefalse-'+ (i) + '-type" value="' + data.getSurveyResponse.survey.surveyQuestions[i].questionType + '" readonly="readonly" />\n' +
                                    '        <input type="text" name="meta-survey-sv-truefalse-'+ (i) + '-text" id="meta-survey-sv-truefalse-'+ (i) + '-text" value="' + data.getSurveyResponse.survey.surveyQuestions[i].questionText + '" readonly="readonly" />\n' +
                                    '        <input type="text" name="meta-survey-sv-truefalse-'+ (i) + '-id" id="meta-survey-sv-truefalse-'+ (i) + '-id" value="' + data.getSurveyResponse.survey.surveyQuestions[i].questionId  + '" readonly="readonly" />\n' +
                                    '        <input type="text" name="meta-survey-sv-truefalse-'+ (i) + '-required" id="meta-survey-sv-truefalse-'+ (i) + '-required" value="' + data.getSurveyResponse.survey.surveyQuestions[i].questionRequired + '" readonly="readonly" />\n' +
                                    '        <input type="text" name="meta-survey-sv-truefalse-'+ (i) + '-max" id="meta-survey-sv-truefalse-'+ (i) + '-max" value="' + data.getSurveyResponse.survey.surveyQuestions[i].questionMaxResponses + '" readonly="readonly" /></div>\n' +
                                    '    </div>'
                                );
                                //Iterate through question values

                                var availableAnswerFieldLength = null;
                                var get_availableAnswerArray = null;
                                if (!Array.isArray(data.getSurveyResponse.survey.surveyQuestions[i].questionTypeData.surveyQuestionData.availableAnswer)){
                                    availableAnswerFieldLength = 1;
                                    get_availableAnswerArray = data.getSurveyResponse.survey.surveyQuestions[i].questionTypeData.surveyQuestionData.availableAnswer;
                                }
                                else{
                                    availableAnswerFieldLength = data.getSurveyResponse.survey.surveyQuestions[i].questionTypeData.surveyQuestionData.availableAnswer.length;
                                }
                                for (var i_2 = 0, fLen = availableAnswerFieldLength; i_2 < fLen; i_2++) {
                                    if (Array.isArray(data.getSurveyResponse.survey.surveyQuestions[i].questionTypeData.surveyQuestionData.availableAnswer)){
                                        get_availableAnswerArray = data.getSurveyResponse.survey.surveyQuestions[i].questionTypeData.surveyQuestionData.availableAnswer[i_2];
                                    }
                                    jQuery("#form-test").append('' +
                                        '    <div class="df-row-container">\n' +
                                        '        <div class="df-row-input">' + '-->' + (i_2 + 1) + ') ' +
                                        '        <input type="text" name="meta-survey-sv-truefalse-' + (i) + '-label-' + (i_2) + '" id="meta-survey-sv-truefalse-' + (i) + '-label-' + (i_2) + '" value="' + get_availableAnswerArray.label + '" readonly="readonly" />\n' +
                                        '        <input type="text" name="meta-survey-sv-truefalse-' + (i) + '-value-' + (i_2) + '" id="meta-survey-sv-truefalse-' + (i) + '-value-' + (i_2) + '" value="' + get_availableAnswerArray.value + '" readonly="readonly" /></div>\n' +
                                        '    </div>'
                                    );
                                }
                                if(availableAnswerFieldLength > subQuestionMaxCount){
                                    subQuestionMaxCount = availableAnswerFieldLength;
                                }
                                break;

                            case "LargeTextValue":
                                var k = 0;
                                //Append base question info
                                jQuery("#form-test").append('' +
                                    '    <div class="df-row-container"><span class="wplo-survey-number-block">' + (i+1) + '.) </span>' +
                                    '        <div class="df-row-input">' +
                                    '        <input type="text" name="meta-survey-sv-largetextvalue-'+ (i) + '-type" id="meta-survey-sv-largetextvalue-'+ (i) + '-type" value="' + data.getSurveyResponse.survey.surveyQuestions[i].questionType + '" readonly="readonly" />\n' +
                                    '        <input type="text" name="meta-survey-sv-largetextvalue-'+ (i) + '-text" id="meta-survey-sv-largetextvalue-'+ (i) + '-text" value="' + data.getSurveyResponse.survey.surveyQuestions[i].questionText + '" readonly="readonly" />\n' +
                                    '        <input type="text" name="meta-survey-sv-largetextvalue-'+ (i) + '-id" id="meta-survey-sv-largetextvalue-'+ (i) + '-id" value="' + data.getSurveyResponse.survey.surveyQuestions[i].questionId  + '" readonly="readonly" />\n' +
                                    '        <input type="text" name="meta-survey-sv-largetextvalue-'+ (i) + '-hint" id="meta-survey-sv-largetextvalue-'+ (i) + '-hint" value="' + data.getSurveyResponse.survey.surveyQuestions[i].questionHint  + '" readonly="readonly" />\n' +
                                    '        <input type="text" name="meta-survey-sv-largetextvalue-'+ (i) + '-required" id="meta-survey-sv-largetextvalue-'+ (i) + '-required" value="' + data.getSurveyResponse.survey.surveyQuestions[i].questionRequired + '" readonly="readonly" /></div>\n' +
                                    '    </div>'
                                );
                                break;

                            case "YesNo":
                                //Append base question info
                                jQuery("#form-test").append('' +
                                    '    <div class="df-row-container"><span class="wplo-survey-number-block">' + (i+1) + '.) </span>' +
                                    '        <div class="df-row-input">' +
                                    '        <input type="text" name="meta-survey-sv-yesno-'+ (i) + '-type" id="meta-survey-sv-yesno-'+ (i) + '-type" value="' + data.getSurveyResponse.survey.surveyQuestions[i].questionType + '" readonly="readonly" />\n' +
                                    '        <input type="text" name="meta-survey-sv-yesno-'+ (i) + '-text" id="meta-survey-sv-yesno-'+ (i) + '-text" value="' + data.getSurveyResponse.survey.surveyQuestions[i].questionText + '" readonly="readonly" />\n' +
                                    '        <input type="text" name="meta-survey-sv-yesno-'+ (i) + '-id" id="meta-survey-sv-yesno-'+ (i) + '-id" value="' + data.getSurveyResponse.survey.surveyQuestions[i].questionId  + '" readonly="readonly" />\n' +
                                    '        <input type="text" name="meta-survey-sv-yesno-'+ (i) + '-required" id="meta-survey-sv-yesno-'+ (i) + '-required" value="' + data.getSurveyResponse.survey.surveyQuestions[i].questionRequired + '" readonly="readonly" />\n' +
                                    '        <input type="text" name="meta-survey-sv-yesno-'+ (i) + '-max" id="meta-survey-sv-yesno-'+ (i) + '-max" value="' + data.getSurveyResponse.survey.surveyQuestions[i].questionMaxResponses + '" readonly="readonly" /></div>\n' +
                                    '    </div>'
                                );
                                //Iterate through question values

                                var availableAnswerFieldLength = null;
                                var get_availableAnswerArray = null;
                                if (!Array.isArray(data.getSurveyResponse.survey.surveyQuestions[i].questionTypeData.surveyQuestionData.availableAnswer)){
                                    availableAnswerFieldLength = 1;
                                    get_availableAnswerArray = data.getSurveyResponse.survey.surveyQuestions[i].questionTypeData.surveyQuestionData.availableAnswer;
                                }
                                else{
                                    availableAnswerFieldLength = data.getSurveyResponse.survey.surveyQuestions[i].questionTypeData.surveyQuestionData.availableAnswer.length;
                                }
                                for (var i_2 = 0, fLen = availableAnswerFieldLength; i_2 < fLen; i_2++) {
                                    if (Array.isArray(data.getSurveyResponse.survey.surveyQuestions[i].questionTypeData.surveyQuestionData.availableAnswer)){
                                        get_availableAnswerArray = data.getSurveyResponse.survey.surveyQuestions[i].questionTypeData.surveyQuestionData.availableAnswer[i_2];
                                    }
                                    jQuery("#form-test").append('' +
                                        '    <div class="df-row-container">\n' +
                                        '        <div class="df-row-input">' + '-->' + (i_2 + 1) + ') ' +
                                        '        <input type="text" name="meta-survey-sv-yesno-' + (i) + '-label-' + (i_2) + '" id="meta-survey-sv-yesno-' + (i) + '-label-' + (i_2) + '" value="' + get_availableAnswerArray.label + '" readonly="readonly" />\n' +
                                        '        <input type="text" name="meta-survey-sv-yesno-' + (i) + '-value-' + (i_2) + '" id="meta-survey-sv-yesno-' + (i) + '-value-' + (i_2) + '" value="' + get_availableAnswerArray.value + '" readonly="readonly" /></div>\n' +
                                        '    </div>'
                                    );
                                }
                                if(availableAnswerFieldLength > subQuestionMaxCount){
                                    subQuestionMaxCount = availableAnswerFieldLength;
                                }
                                break;

                            case "Captcha":
                                //Append base question info
                                jQuery("#form-test").append('' +
                                    '    <div class="df-row-container"><span class="wplo-survey-number-block">' + (i+1) + '.) </span>' +
                                    '        <div class="df-row-input">' +
                                    '        <input type="text" name="meta-survey-sv-captcha-'+ (i) + '-type" id="meta-survey-sv-captcha-'+ (i) + '-type" value="' + data.getSurveyResponse.survey.surveyQuestions[i].questionType + '" readonly="readonly" />\n' +
                                    '        <input type="text" name="meta-survey-sv-captcha-'+ (i) + '-text" id="meta-survey-sv-captcha-'+ (i) + '-text" value="' + data.getSurveyResponse.survey.surveyQuestions[i].questionText + '" readonly="readonly" />\n' +
                                    '        <input type="text" name="meta-survey-sv-captcha-'+ (i) + '-id" id="meta-survey-sv-captcha-'+ (i) + '-id" value="' + data.getSurveyResponse.survey.surveyQuestions[i].questionId  + '" readonly="readonly" />\n' +
                                    '        <input type="text" name="meta-survey-sv-captcha-'+ (i) + '-required" id="meta-survey-sv-captcha-'+ (i) + '-required" value="' + data.getSurveyResponse.survey.surveyQuestions[i].questionRequired + '" readonly="readonly" />\n' +
                                    '        <input type="text" name="meta-survey-sv-captcha-'+ (i) + '-max" id="meta-survey-sv-captcha-'+ (i) + '-max" value="' + data.getSurveyResponse.survey.surveyQuestions[i].questionMaxResponses + '" readonly="readonly" /></div>\n' +
                                    '    </div>'
                                );
                                //Iterate through question values

                                var availableAnswerFieldLength = null;
                                var get_availableAnswerArray = null;
                                if (!Array.isArray(data.getSurveyResponse.survey.surveyQuestions[i].questionTypeData.captchaData)){
                                    availableAnswerFieldLength = 1;
                                    get_availableAnswerArray = data.getSurveyResponse.survey.surveyQuestions[i].questionTypeData.captchaData;
                                }
                                else{
                                    availableAnswerFieldLength = data.getSurveyResponse.survey.surveyQuestions[i].questionTypeData.captchaData.length;
                                }
                                for (var i_2 = 0, fLen = availableAnswerFieldLength; i_2 < fLen; i_2++) {
                                    if (Array.isArray(data.getSurveyResponse.survey.surveyQuestions[i].questionTypeData.captchaData)){
                                        get_availableAnswerArray = data.getSurveyResponse.survey.surveyQuestions[i].questionTypeData.captchaData[i_2];
                                    }
                                    jQuery("#form-test").append('' +
                                        '    <div class="df-row-container">\n' +
                                        '        <div class="df-row-input">' + '-->' + (i_2 + 1) + ') ' +
                                        '        <input type="text" name="meta-survey-sv-captcha-' + (i) + '-imagesource-' + (i_2) + '" id="meta-survey-sv-captcha-' + (i) + '-imagesource-' + (i_2) + '" value="' + get_availableAnswerArray.imageSource + '" readonly="readonly" />\n' +
                                        '        <input type="text" name="meta-survey-sv-captcha-' + (i) + '-audiolink-' + (i_2) + '" id="meta-survey-sv-captcha-' + (i) + '-audiolink-' + (i_2) + '" value="' + get_availableAnswerArray.audioLink + '" readonly="readonly" />\n' +
                                        '        <input type="text" name="meta-survey-sv-captcha-' + (i) + '-audiolinklabel-' + (i_2) + '" id="meta-survey-sv-captcha-' + (i) + '-audiolinklabel-' + (i_2) + '" value="' + get_availableAnswerArray.audioLinkLabel + '" readonly="readonly" />\n' +
                                        '        <input type="text" name="meta-survey-sv-captcha-' + (i) + '-standaloneplayerlabel-' + (i_2) + '" id="meta-survey-sv-captcha-' + (i) + '-standaloneplayerlabel-' + (i_2) + '" value="' + get_availableAnswerArray.standAlonePlayerLabel + '" readonly="readonly" />\n' +
                                        '        <input type="text" name="meta-survey-sv-captcha-' + (i) + '-newwindowlabel-' + (i_2) + '" id="meta-survey-sv-captcha-' + (i) + '-newwindowlabel-' + (i_2) + '" value="' + get_availableAnswerArray.newWindowLabel + '" readonly="readonly" />\n' +
                                        '        <input type="text" name="meta-survey-sv-captcha-' + (i) + '-changeimagelabel-' + (i_2) + '" id="meta-survey-sv-captcha-' + (i) + '-changeimagelabel-' + (i_2) + '" value="' + get_availableAnswerArray.changeImageLabel + '" readonly="readonly" /></div>\n' +
                                        '    </div>'
                                    );
                                }
                                break;


                        }
                    }


                // }
                //Append base reset button label
                jQuery("#form-test").append('' +
                    '    <div class="df-row-container">\n' +
                    '        <div class="df-row-input">Reset button text: ' +
                    '        <input type="text" name="meta-survey-sv-reset-label" id="meta-survey-sv-reset-label" value="' + data.getSurveyResponse.survey.resetButtonLabel + '" readonly="readonly" />\n' +
                    '    </div>'
                );

                //Append base submit button label
                jQuery("#form-test").append('' +
                    '    <div class="df-row-container">\n' +
                    '        <div class="df-row-input">Submit button text: ' +
                    '        <input type="text" name="meta-survey-sv-submitlabel-label" id="meta-survey-sv-submitlabel-label" value="' + data.getSurveyResponse.survey.submitButtonLabel + '" readonly="readonly" />\n' +
                    '    </div>'
                );

                //Append base cancel survey url
                jQuery("#form-test").append('' +
                    '    <div class="df-row-container">\n' +
                    '        <div class="df-row-input">Cancel survey url: ' +
                    '        <input type="text" name="meta-survey-sv-cancel-label" id="meta-survey-sv-cancel-label" value="' + data.getSurveyResponse.survey.cancelSurveyUrl + '" readonly="readonly" />\n' +
                    '    </div>'
                );

                //Append base submit survey url
                jQuery("#form-test").append('' +
                    '    <div class="df-row-container">\n' +
                    '        <div class="df-row-input">Submit Survey Url: ' +
                    '        <input type="text" name="meta-survey-sv-submiturl-label" id="meta-survey-sv-submiturl-label" value="' + data.getSurveyResponse.survey.submitSurveyUrl + '" readonly="readonly" />\n' +
                    '    </div>'
                );

                //Append base skip button label
                jQuery("#form-test").append('' +
                    '    <div class="df-row-container">\n' +
                    '        <div class="df-row-input">Skip button text: ' +
                    '        <input type="text" name="meta-survey-sv-skipurl-label" id="meta-survey-sv-skipurl-label" value="' + data.getSurveyResponse.survey.skipButtonLabel + '" readonly="readonly" />\n' +
                    '    </div>'
                );


                var surveyQuestionsCount;

                //Append total number of questions for looping in single-survey-form
                if(Array.isArray(data.getSurveyResponse.survey.surveyQuestions)){
                    surveyQuestionsCount = (data.getSurveyResponse.survey.surveyQuestions.length)
                }
                else{
                    surveyQuestionsCount = 1;
                }
                console.log(surveyQuestionsCount);

                jQuery("#form-test").append('' +
                    '    <div class="df-row-container">\n' +
                    '        <div class="df-row-input">Total number of questions: ' +
                    '        <input type="text" name="meta-survey-sv-count-label" id="meta-survey-sv-count-label" value="' + (surveyQuestionsCount) + '" readonly="readonly" />\n' +
                    '    </div>'
                );
                jQuery("#post").append('<input type="hidden" id="WPLOSURVEY_loopCount" name="WPLOSURVEY_loopCount" value="' + (surveyQuestionsCount) + '">');
                jQuery("#post").append('<input type="hidden" id="WPLOSURVEY_subloopMaxCount" name="WPLOSURVEY_subloopMaxCount" value="' + (subQuestionMaxCount) + '">');

            }
        }
    };


</script>
