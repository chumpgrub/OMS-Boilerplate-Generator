<?php
/**
 * @version 2.0.0
 *
 * This is the main template for the PLUGIN_NAME detail page. To use your own template, save a copy of this file
 * into a folder with the name FILE_PREFIX in your main theme directory.
 **/
?>
<?php get_header(); ?>

<main>
<?php if ( have_posts() ) : ?>
    <?php while ( have_posts() ) : the_post(); ?>
            <?php the_content(); ?>
    <?php endwhile; ?>
<?php endif; ?>
</main>

<?php get_footer(); ?>
