<?php
class beaverWploSurveyModule extends FLBuilderModule {

    public function __construct()
    {
        parent::__construct(array(
            'name'            => __( 'WPLO Survey', 'fl-builder' ),
            'description'     => __( 'Place a survey form you have created with WPLO Survey anywhere on your page.  Note: only one survey form can be placed per page.', 'fl-builder' ),
            'group'           => __( 'Luminate Online', 'fl-builder' ),
            'category'        => __( 'WPLO Survey', 'fl-builder' ),
            'dir'             => WPLO_SURVEY_DIR . 'beaver-wplo-survey-module/',
            'url'             => WPLO_SURVEY_URL . 'beaver-wplo-survey-module/',
            'icon'            => 'icon.svg',
            'editor_export'   => true, // Defaults to true and can be omitted.
            'enabled'         => true, // Defaults to true and can be omitted.
            'partial_refresh' => false, // Defaults to false and can be omitted.
        ));
    }

}

//Get all survey form custom post IDs and post names
$all_wplo_survey_post_ids = get_posts(array(
    'fields'          => 'ids,title',
    'posts_per_page'  => -1,
    'post_type' => 'survey'
));

$beaverSurveyPostTypesList = array();
foreach($all_wplo_survey_post_ids as $dValue){
    $beaverSurveyPostTypesList[$dValue->ID] = $dValue->post_title;
}

FLBuilder::register_module( 'beaverWploSurveyModule', array(
    'my-tab-1'      => array(
        'title'         => __( 'Select Survey Form', 'fl-builder' ),
        'sections'      => array(
            'my-section-1'  => array(
                'title'         => __( 'Survey Form List', 'fl-builder' ),
                'fields'        => array(
                    'my_select_field' => array(
                        'type'          => 'select',
                        'label'         => __( 'Select a Survey Form', 'fl-builder' ),
                        'default'       => (isset($beaverSurveyPostTypesList[0]->ID) ? $beaverSurveyPostTypesList[0]->ID : 'option-1'),
                        'options'       => $beaverSurveyPostTypesList
                    )
                )
            )
        ),
    ),
) );
