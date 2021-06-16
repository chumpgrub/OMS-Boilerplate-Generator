<?php get_header(); ?>

<?php
/**
 * Hook: orbitmedia_template_wrapper_start.
 *
 * @hooked orbitmedia_wrapper_start - 10
 */
do_action( 'orbitmedia_template_wrapper_start' );
?>

<div id="content" class="col main-content">
    <a name="content"></a>

    <?php
    /**
     * Hook: orbitmedia_before_content.
     *
     * @hooked page_title - 10
     */
    do_action( 'orbitmedia_before_content' );
    ?>

    <div class="entry">
        <?php echo get_content_by_id( get_option( 'page_for_posts' ) ); ?>
    </div>

    <div class="posts-container row facetwp-template">
        <?php if ( have_posts() ) : ?>
            <?php while ( have_posts() ) : ?>
                <?php the_post(); ?>
                <?php get_template_part( 'partials/blog', 'content' ); ?>
            <?php endwhile; ?>
        <?php else : ?>
            <p>Sorry, no posts match your criteria.</p>
        <?php endif; ?>
    </div> <!-- /.posts-container facetwp-template -->

        <div class="posts-nav-wrapper">
            <div class="posts-nav button-container">
                <?php the_posts_navigation( [
                    'prev_text' => __( 'Older posts' ),
                    'next_text' => __( 'Newer posts' ),
                ] ); ?>
            </div>
        </div> <!-- /.posts-nav-wrapper -->

</div> <!-- /#content -->

<?php get_sidebar(); ?>

<?php
/**
 * Hook: orbitmedia_template_wrapper_end.
 *
 * @hooked orbitmedia_wrapper_end - 10
 * @hooked orbitmedia_output_blocks - 20
 */
do_action( 'orbitmedia_template_wrapper_end' );
?>

<?php get_footer(); ?>
