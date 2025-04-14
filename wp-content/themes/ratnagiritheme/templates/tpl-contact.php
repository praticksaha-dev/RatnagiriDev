<?php /*Template Name: Layout: Contact Us*/
 get_header();
 if (have_posts()):
    while (have_posts()):
        the_post();
 get_sidebar('banner');
?>


<?php 
endwhile;
endif;
get_footer(); ?>