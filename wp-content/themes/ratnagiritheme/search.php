<?php get_header(); ?>

<?php if(have_posts()): while(have_posts()): the_post(); ?>  
<h2><?php the_title(); ?></h2>
<?php the_excerpt(); ?>
<div class="blg_link"><a href="<?php the_permalink(); ?>">Continue Reading</a></div> <br /><br />   
<?php endwhile; else: ?>
<h2><?php _e('Not Found')?></h2>
<p><?php _e('Sorry,no posts matched your criteria.'); ?></p>
<?php endif; ?>

<?php get_footer(); ?>