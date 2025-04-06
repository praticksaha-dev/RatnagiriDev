<?php get_header();
    get_sidebar('banner');
        ?>

<?php if(have_posts()): while(have_posts()): the_post(); ?>  
<h2><?php the_title(); ?></h2>


<?php endwhile; 
        endif; ?>

<?php get_footer(); ?>