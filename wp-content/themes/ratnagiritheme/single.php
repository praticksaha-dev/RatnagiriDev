<?php get_header(); ?>

<?php if(have_posts()): while(have_posts()): the_post(); ?>  
<h2><?php the_title(); ?></h2>
<div class="postnavigation"><p>Posted in <?php the_category(',')?> | <?php _e('Posted by:','zbench'); ?> <a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>" title="<?php printf(__('View all posts by %s','zbench'),get_the_author())?>" rel="author"><?php the_author(); ?></a> | Tagged: <?php the_tags('',',',''); ?> | <a href="<?php the_permalink(); ?>#comments">Leave a reply</a></p></div>
<?php the_content(); ?>
<?php endwhile; else: ?>
<h2><?php _e('Not Found')?></h2>
<p><?php _e('Sorry,no posts matched your criteria.'); ?></p>
<?php endif; ?>
<p><?php comments_template('',true); ?></p>

<?php get_footer(); ?>