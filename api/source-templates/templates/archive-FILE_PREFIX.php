<?php
/**
 * @version 2.0.0
 *
 * This is the main template for the PLUGIN_NAME archive listing. To use your own template, save a copy of this file
 * into a folder - with the same name as the plugin folder - in your main theme directory.
 **/
?>
<?php get_header(); ?>

<main>
<?php if ( have_posts() ) : ?>
    <?php while ( have_posts() ) : the_post(); ?>
        <?php the_title();?>
     <?php endwhile; ?>
<?php else : ?>
    <p>Sorry, no posts match your criteria.</p>
<?php endif; ?>
</main>

<?php get_footer(); ?>
