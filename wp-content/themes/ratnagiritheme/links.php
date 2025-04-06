<?php /*Template Name: Layout: Links Page*/?>
<?php get_header(); ?>

<h1>Links</h1>
<?php 
wp_list_bookmarks('
title_li=
&title_before=<h3>
&title_after=</h3>
&category_before=
&category_after=
&orderby=rating
&order=DESC
&show_description=1
&between= — 
');
?>

<?php get_footer(); ?>