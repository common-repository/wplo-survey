<?php
defined( 'ABSPATH' ) or die( 'No script testing please!' );
?>


<script type="text/javascript">

    <?php
    $cleanHttp = strtok($_SERVER['HTTP_REFERER'], '?');
    $cleanHttp = strtok($cleanHttp, '%');
    ?>

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

    ready(function() {
        luminateExtend.init({
            apiKey: '<?php echo get_option( 'WPLOSURVEY_apiKey' ); ?>',
            path: {
                nonsecure: '<?php echo get_option( 'WPLOSURVEY_nonsecure' ); ?>',
                secure: '<?php echo get_option( 'WPLOSURVEY_secure' ); ?>'
            },
            apiCommon: {
                source: '<?php echo esc_js($cleanHttp);?>',
                subSource: (window.location.href.split(/[%,?]+/)[0])
            }
        });
    });


</script>



<?php



