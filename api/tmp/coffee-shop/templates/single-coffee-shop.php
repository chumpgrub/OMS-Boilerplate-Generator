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

    <div class="back">
        <a href="<?php echo get_post_type_archive_link( get_post_type( $post ) ); ?>" class="back-link">&lsaquo;&nbsp;Back</a>
    </div>

    <!-- WordPress Loop: Only happens if there are posts. -->
    <?php if ( have_posts() ) : ?>
        <?php while ( have_posts() ) : the_post(); ?>

            <div id="post-<?php the_ID(); ?>" class="post detail">

                <h1 class="entry-title"><?php the_title(); ?></h1>

                <?php do_action( 'oms_post_meta_output' ); // Post/Page meta. ?>

                <?php
                if ( function_exists( 'has_post_thumbnail' ) && has_post_thumbnail() ) :
                    echo '<div class="detail-featured-img featured-img">' . get_the_post_thumbnail( $post->ID, 'full' ) . '</div>';
                endif;
                ?>

                <?php the_content(); ?>

            </div> <!-- /.post -->

        <?php endwhile; ?>
    <?php endif; ?>

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
