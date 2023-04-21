<?php
/**
 * @package WordPress
 * @subpackage base1
 */

get_header(); ?>

		<div id="primary">
			<div id="content">
			<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> role="article">
					<header class="entry-header">
						<h1 class="entry-title"><?php the_title(); ?></h1>

						<div class="entry-meta">
							<?php
								printf( __( '<time class="entry-date" datetime="%2$s" pubdate>%3$s</time> <span class="meta-sep"> by </span> <span class="author vcard">%6$s</span>', 'themename' ),
									get_permalink(),
									get_the_date( 'c' ),
									get_the_date(),
									get_author_posts_url( get_the_author_meta( 'ID' ) ),
									sprintf( esc_attr__( 'View all posts by %s', 'themename' ), get_the_author() ),
									get_the_author()
								);
							?>
						</div><!-- .entry-meta -->
					</header><!-- .entry-header -->

					<div class="entry-content">
						<?php the_content(); ?>
						<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'themename' ), 'after' => '</div>' ) ); ?>
					</div><!-- .entry-content -->

					<footer class="entry-meta">
						<?php
							$tag_list = get_the_tag_list( '', ', ' );
							if ( '' != $tag_list ) {
								$utility_text = __( 'Posted in %1$s and tagged %2$s.', 'themename' );
							} else {
								$utility_text = __( 'Posted in %1$s.', 'themename' );
							}
							printf(
								$utility_text,
								get_the_category_list( ', ' ),
								$tag_list,
								get_permalink(),
								the_title_attribute( 'echo=0' )
							);
						?>
					</footer><!-- .entry-meta -->
				</article><!-- #post-<?php the_ID(); ?> -->

				<?php // comments_template( '', true ); ?>

			<?php endwhile; // end of the loop. ?>

			</div><!-- #content -->
		</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>