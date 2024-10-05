<?php
/**
 * Plugin Name:       WP Restrict Access
 * Description:       WordPress plugin to restrict access of custom post types for not logged in users
 * Version:           1.0.0
 * Author: Sib
 * Author URI: https://innovisionlab.com
 * Text Domain:       ilab
*/

class iLab_WP_Restrict_Access
{
    function __construct() 
    {
        add_action( 'wp_footer', [$this, 'ilab_wp_footer']);
        add_filter( 'template_redirect', [$this, 'ilab_template_redirect'] );
        add_filter( 'post_type_link', [$this, 'ilab_restrict_clinic_link'], 10, 2 );
    }

    function ilab_wp_footer() {
        if (!is_user_logged_in()) :
        ?>
        <script>
            function checkRestrictAccess()
            {
                const elements = document.getElementsByClassName('clinic_detail');
                for (let i = 0; i < elements.length; i++) 
                {
                    const anchors = elements[i].querySelectorAll('a');
                    for (let j = 0; j < anchors.length; j++) 
                    {
                        const anchor = anchors[j];
                        if (anchor) 
                        {
                            anchor.href = 'javascript:;';
                            anchor.addEventListener('click', function(){
                                console.log('anchor clicked');
                                openElementorPopup(4847);
                            })
                        }   
                    }
                }
            }
            window.addEventListener('load', function(){
                checkRestrictAccess();
            });

            window.addEventListener('scroll', function(){
                checkRestrictAccess();
            });

            function openElementorPopup(popupId) 
            {
                if (typeof elementorProFrontend !== "undefined" && typeof elementorProFrontend.modules.popup !== "undefined") {
                    console.log('elementor pro found');
                    elementorProFrontend.modules.popup.showPopup({ id: popupId });
                } else {
                    console.log("Elementor Popup module not found.");
                }
            }
        </script>
        <?php
        endif;
    }

    function ilab_template_redirect()
    {
        global $post;
        if ( 'clinic' === $post->post_type && !is_user_logged_in() ) 
        {
            wp_redirect( home_url('/') );
		    die;
        }
    }

    function ilab_restrict_clinic_link( $post_link, $post ) {
		if ( 'clinic' === $post->post_type && !is_user_logged_in() ) {
			return "javascript:;";
		}
		return $post_link;
	}

}

new iLab_WP_Restrict_Access();