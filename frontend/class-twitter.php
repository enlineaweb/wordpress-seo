<?php
/**
 * @package Frontend
 */

if ( ! defined( 'WPSEO_VERSION' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}


if ( ! class_exists( 'WPSEO_Twitter' ) ) {
	/**
	 * This class handles the Twitter card functionality.
	 *
	 * @link https://dev.twitter.com/docs/cards
	 */
	class WPSEO_Twitter extends WPSEO_Frontend {

		/**
		 * @var	object	Instance of this class
		 */
		public static $instance;

		/**
		 * @var array Images
		 */
		var $images;

		/**
		 * @var array $options Holds the options for the Twitter Card functionality
		 */
		var $options;

		/**
		 * Class constructor
		 */
		public function __construct() {
			$this->options = get_option( 'wpseo_social' );
			$this->shown_images = array(); // Instantiate as empty array
			$this->twitter();
		}

		/**
		 * Get the singleton instance of this class
		 *
		 * @return object
		 */
		public static function get_instance() {
			if ( ! ( self::$instance instanceof self ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Outputs the Twitter Card code on singular pages.
		 *
		 * @return  void   Only shows on singular pages, false on non-singular pages.
		 */
		public function twitter() {
			wp_reset_query();

			$this->type();
			$this->site_twitter();
			$this->site_domain();
			$this->author_twitter();
			if ( 'summary_large_image' == $this->options['twitter_card_type'] ) {
				$this->image();
			}

			// No need to show these when OpenGraph is also showing, as it'd be the same contents and Twitter
			// would fallback to OpenGraph anyway.
			if ( $this->options['opengraph'] === false ) {
				if ( 'summary' == $this->options['twitter_card_type'] ) {
					$this->image();
				}
				$this->twitter_description();
				$this->twitter_title();
				$this->twitter_url();
			}

			/**
			 * Action: 'wpseo_twitter' - Hook to add all WP SEO Twitter output to so they're close together.
			 */
			do_action( 'wpseo_twitter' );
		}

		/**
		 * Display the Twitter card type.
		 *
		 * This defaults to summary but can be filtered using the <code>wpseo_twitter_card_type</code> filter.
		 *
		 * @link https://dev.twitter.com/docs/cards
		 */
		public function type() {
			/**
			 * Filter: 'wpseo_twitter_card_type' - Allow changing the Twitter Card type as output in the Twitter card by WP SEO
			 *
			 * @api string $unsigned The type string
			 */
			$type = apply_filters( 'wpseo_twitter_card_type', $this->options['twitter_card_type'] );
			if ( ! in_array( $type, array( 'summary', 'summary_large_image', 'photo', 'gallery', 'app', 'player', 'product' ) ) ) {
				$type = 'summary';
			}

			echo '<meta name="twitter:card" content="' . esc_attr( $type ) . '"/>' . "\n";
		}

		/**
		 * Displays the Twitter account for the site.
		 */
		public function site_twitter() {
			/**
			 * Filter: 'wpseo_twitter_site' - Allow changing the Twitter site account as output in the Twitter card by WP SEO
			 *
			 * @api string $unsigned Twitter site account string
			 */
			$site = apply_filters( 'wpseo_twitter_site', $this->options['twitter_site'] );
			if ( is_string( $site ) && $site !== '' ) {
				echo '<meta name="twitter:site" content="@' . esc_attr( $site ) . '"/>' . "\n";
			}
		}

		/**
		 * Displays the domain tag for the site.
		 */
		public function site_domain() {
			/**
			 * Filter: 'wpseo_twitter_domain' - Allow changing the Twitter domain as output in the Twitter card by WP SEO
			 *
			 * @api string $unsigned Name string
			 */
			$domain = apply_filters( 'wpseo_twitter_domain', get_bloginfo( 'name' ) );
			if ( is_string( $domain ) && $domain !== '' ) {
				echo '<meta name="twitter:domain" content="' . esc_attr( $domain ) . '"/>' . "\n";
			}
		}

		/**
		 * Displays the authors Twitter account.
		 */
		public function author_twitter() {
			$twitter = ltrim( trim( get_the_author_meta( 'twitter' ) ), '@' );
			/**
			 * Filter: 'wpseo_twitter_creator_account' - Allow changing the Twitter account as output in the Twitter card by WP SEO
			 *
			 * @api string $twitter The twitter account name string
			 */
			$twitter = apply_filters( 'wpseo_twitter_creator_account', $twitter );

			if ( is_string( $twitter ) && $twitter !== '' ) {
				echo '<meta name="twitter:creator" content="@' . esc_attr( $twitter ) . '"/>' . "\n";
			}
			else if ( $this->options['twitter_site'] !== '' ) {
				if ( is_string( $this->options['twitter_site'] ) && $this->options['twitter_site'] !== '' ) {
					echo '<meta name="twitter:creator" content="@' . esc_attr( $this->options['twitter_site'] ) . '"/>' . "\n";
				}
			}
		}

		/**
		 * Displays the title for Twitter.
		 *
		 * Only used when OpenGraph is inactive.
		 */
		public function twitter_title() {
			/**
			 * Filter: 'wpseo_twitter_title' - Allow changing the Twitter title as output in the Twitter card by WP SEO
			 *
			 * @api string $twitter The title string
			 */
			$title = apply_filters( 'wpseo_twitter_title', $this->title( '' ) );
			if ( is_string( $title ) && $title !== '' ) {
				echo '<meta name="twitter:title" content="' . esc_attr( $title ) . '"/>' . "\n";
			}
		}

		/**
		 * Displays the description for Twitter.
		 *
		 * Only used when OpenGraph is inactive.
		 */
		public function twitter_description() {
			$metadesc = trim( $this->metadesc( false ) );
			if ( empty( $metadesc ) ) {
				$metadesc = false;
			}

			if ( ! $metadesc ) {
				$metadesc = strip_tags( get_the_excerpt() );
			}

			/**
			 * Filter: 'wpseo_twitter_description' - Allow changing the Twitter description as output in the Twitter card by WP SEO
			 *
			 * @api string $twitter The description string
			 */
			$metadesc = apply_filters( 'wpseo_twitter_description', $metadesc );
			if ( is_string( $metadesc ) && $metadesc !== '' ) {
				echo '<meta name="twitter:description" content="' . esc_attr( $metadesc ) . '"/>' . "\n";
			}
		}

		/**
		 * Displays the URL for Twitter.
		 *
		 * Only used when OpenGraph is inactive.
		 */
		public function twitter_url() {
			/**
			 * Filter: 'wpseo_twitter_url' - Allow changing the URL as output in the Twitter card by WP SEO
			 *
			 * @api string $unsigned Canonical URL
			 */
			$url = apply_filters( 'wpseo_twitter_url', $this->canonical( false ) );
			if ( is_string( $url ) && $url !== '' ) {
				echo '<meta name="twitter:url" content="' . esc_url( $url ) . '"/>' . "\n";
			}
		}

		/**
		 * Outputs a Twitter image tag for a given image
		 *
		 * @param string $img
		 */
		public function image_output( $img ) {

			$escaped_img = esc_url( $img );

			if ( in_array( $escaped_img, $this->shown_images ) ) {
				return;
			}

			if ( is_string( $escaped_img ) && $escaped_img !== ''  ) {
				echo '<meta name="twitter:image:src" content="' . $escaped_img . '"/>' . "\n";

				array_push( $this->shown_images, $escaped_img );
			}
		}

		/**
		 * Displays the image for Twitter
		 *
		 * Only used when OpenGraph is inactive or Summary Large Image card is chosen.
		 */
		public function image() {
			global $post;

			if ( is_singular() ) {
				if ( is_front_page() ) {
					if ( $this->options['og_frontpage_image'] !== '' ) {
						$this->image_output( $this->options['og_frontpage_image'] );
					}
				}

				$twitter_img = WPSEO_Meta::get_value( 'twitter-image' );
				if ( $twitter_img !== '' ) {
					$this->image_output( $twitter_img );
					return;
				}
				else if ( function_exists( 'has_post_thumbnail' ) && has_post_thumbnail( $post->ID ) ) {
					/**
					 * Filter: 'wpseo_twitter_image_size' - Allow changing the Twitter Card image size
					 *
					 * @api string $featured_img Image size string
					 */
					$featured_img = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), apply_filters( 'wpseo_twitter_image_size', 'full' ) );

					if ( $featured_img ) {
						/**
						 * Filter: 'wpseo_twitter_image' - Allow changing the Twitter Card image
						 *
						 * @api string $featured_img Image URL string
						 */
						$this->image_output( apply_filters( 'wpseo_twitter_image', $featured_img[0] ) );
					}
				} else if ( preg_match_all( '`<img [^>]+>`', $post->post_content, $matches ) ) {
					foreach ( $matches[0] as $img ) {
						if ( preg_match( '`src=(["\'])(.*?)\1`', $img, $match ) ) {
							apply_filters( 'wpseo_twitter_image', $match[2] );
						}
					}
				}
			}

			if ( count( $this->shown_images ) == 0 && $this->options['og_default_image'] !== '' ) {
				$this->image_output( $this->options['og_default_image'] );
			}
		}
	} /* End of class */

} /* End of class-exists wrapper */
