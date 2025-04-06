<?php /*Template Name: Layout: Blog*/?>
<?php get_header(); ?>
<ul>
<?php         
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;        
$args = array( 'post_type' => 'post','paged' => $paged , 'order' => 'DESC','posts_per_page' => 2);        
$wp_query = new WP_Query($args);        
while ( have_posts() ) : the_post();         
?>
<li>
<div class="thumb">
<?php if (has_post_thumbnail( $post->ID ) ){ ?>
<?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' ); ?>
<img src="<?php echo $image[0]; ?>"  />
<?php } ?>
</div>
<div class="infobox">
<div class="date_row"> <span class="date"><?php echo get_post_time('d/m/Y'); ?></span> </div>
<h3><span><?php echo substr(get_the_title(),0,15);?></span><?php echo substr(get_the_title(),15,50);?></h3>
<p><?php echo substr(get_the_excerpt(), 0, 150); ?></p>
<a class="blue_link" href="<?php echo get_permalink(); ?>">Read More</a></div>
</li>
<?php endwhile; ?>          	
</ul>

<?php get_footer(); ?>