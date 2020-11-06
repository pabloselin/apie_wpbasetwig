<?php
/**
 * Timber starter-theme
 * https://github.com/timber/starter-theme
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since   Timber 0.1
 */

define('CTCI_VERSION', '0.5.1');

/**
 * If you are installing Timber as a Composer dependency in your theme, you'll need this block
 * to load your dependencies and initialize Timber. If you are using Timber via the WordPress.org
 * plug-in, you can safely delete this block.
 */
$composer_autoload = dirname( __DIR__ ) . '/vendor/autoload.php';
if ( file_exists( $composer_autoload ) ) {
	require_once $composer_autoload;
	$timber = new Timber\Timber();
}

/**
 * This ensures that Timber is loaded and available as a PHP class.
 * If not, it gives an error message to help direct developers on where to activate
 */
if ( ! class_exists( 'Timber' ) ) {

	add_action(
		'admin_notices',
		function() {
			echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' . esc_url( admin_url( 'plugins.php#timber' ) ) . '">' . esc_url( admin_url( 'plugins.php' ) ) . '</a></p></div>';
		}
	);

	add_filter(
		'template_include',
		function( $template ) {
			return dirname( get_stylesheet_directory() ) . '/static/no-timber.html';
		}
	);
	return;
}

/**
 * Sets the directories (inside your theme) to find .twig files
 */
Timber::$dirname = array( '../views' );

/**
 * By default, Timber does NOT autoescape values. Want to enable Twig's autoescape?
 * No prob! Just set this value to true
 */
Timber::$autoescape = false;


/**
 * We're going to configure our theme inside of a subclass of Timber\Site
 * You can move this to its own file and include here via php's include("MySite.php")
 */
class CTCISite extends Timber\Site {
	/** Add timber support. */
	public function __construct() {
		add_action( 'after_setup_theme', array( $this, 'theme_supports' ) );
		add_filter( 'timber/context', array( $this, 'add_to_context' ) );
		add_filter( 'timber/twig', array( $this, 'add_to_twig' ) );
		add_action( 'init', array( $this, 'register_post_types' ) );
		add_action( 'init', array( $this, 'register_taxonomies' ) );
		add_action( 'wp_head', array( $this, 'add_to_head') );
		add_action( 'wp_enqueue_scripts', array($this, 'enqueue_scripts'));
		add_action( 'after_setup_theme', array($this, 'register_terms_menus'));
		parent::__construct();
	}
	/** This is where you can register custom post types. */
	public function register_post_types() {

	}
	/** This is where you can register custom taxonomies. */
	public function register_taxonomies() {

	}


	public function register_terms_menus() {
		register_nav_menus(
			array(
				'mainmenu' => 'Menu Principal'
			)
		);
	}



	/** This is where you add some context
	 *
	 * @param string $context context['this'] Being the Twig's {{ this }}.
	 */
	public function add_to_context( $context ) {
		$context['menu']  = new Timber\Menu('mainmenu');
		$context['site']  = $this;
		$context['version'] = CTCI_VERSION;
		$context['is_front_page'] = is_front_page();
		$context['is_logged_in'] = is_user_logged_in() || SCRIPT_DEBUG;
		return $context;
	}

	public function theme_supports() {
		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
			)
		);

		/*
		 * Enable support for Post Formats.
		 *
		 * See: https://codex.wordpress.org/Post_Formats
		 */
		add_theme_support(
			'post-formats',
			array(
				'aside',
				'image',
				'video',
				'quote',
				'link',
				'gallery',
				'audio',
			)
		);

		add_theme_support( 'menus' );
	}

	public function enqueue_scripts() {
		wp_enqueue_style( 'ctci', get_bloginfo('template_url') . '/../dist/css/style.css', array(), CTCI_VERSION, 'screen' );
		wp_enqueue_script( 'ctcijs', get_bloginfo('template_url') . '/../dist/js/bundle.js', array(), CTCI_VERSION, false);
	}

	public function add_to_head() {
		?>

		<style>@import url('https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,400;0,600;0,800;1,400&display=swap');</style>
		<script src="https://kit.fontawesome.com/14643ca681.js" crossorigin="anonymous"></script>
		<link rel="apple-touch-icon-precomposed" sizes="57x57" href="<?php bloginfo('template_url');?>/../dist/img/icons/apple-touch-icon-57x57.png" />
		<link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php bloginfo('template_url');?>/../dist/img/icons/apple-touch-icon-114x114.png" />
		<link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php bloginfo('template_url');?>/../dist/img/icons/apple-touch-icon-72x72.png" />
		<link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php bloginfo('template_url');?>/../dist/img/icons/apple-touch-icon-144x144.png" />
		<link rel="apple-touch-icon-precomposed" sizes="60x60" href="<?php bloginfo('template_url');?>/../dist/img/icons/apple-touch-icon-60x60.png" />
		<link rel="apple-touch-icon-precomposed" sizes="120x120" href="<?php bloginfo('template_url');?>/../dist/img/icons/apple-touch-icon-120x120.png" />
		<link rel="apple-touch-icon-precomposed" sizes="76x76" href="<?php bloginfo('template_url');?>/../dist/img/icons/apple-touch-icon-76x76.png" />
		<link rel="apple-touch-icon-precomposed" sizes="152x152" href="<?php bloginfo('template_url');?>/../dist/img/icons/apple-touch-icon-152x152.png" />
		<link rel="icon" type="image/png" href="<?php bloginfo('template_url');?>/../dist/img/icons/favicon-196x196.png" sizes="196x196" />
		<link rel="icon" type="image/png" href="<?php bloginfo('template_url');?>/../dist/img/icons/favicon-96x96.png" sizes="96x96" />
		<link rel="icon" type="image/png" href="<?php bloginfo('template_url');?>/../dist/img/icons/favicon-32x32.png" sizes="32x32" />
		<link rel="icon" type="image/png" href="<?php bloginfo('template_url');?>/../dist/img/icons/favicon-16x16.png" sizes="16x16" />
		<link rel="icon" type="image/png" href="<?php bloginfo('template_url');?>/../dist/img/icons/favicon-128.png" sizes="128x128" />
		<meta name="application-name" content="&nbsp;"/>
		<meta name="msapplication-TileColor" content="#FFFFFF" />
		<meta name="msapplication-TileImage" content="<?php bloginfo('template_url');?>/../dist/img/icons/mstile-144x144.png" />
		<meta name="msapplication-square70x70logo" content="<?php bloginfo('template_url');?>/../dist/img/icons/mstile-70x70.png" />
		<meta name="msapplication-square150x150logo" content="<?php bloginfo('template_url');?>/../dist/img/icons/mstile-150x150.png" />
		<meta name="msapplication-wide310x150logo" content="<?php bloginfo('template_url');?>/../dist/img/icons/mstile-310x150.png" />
		<meta name="msapplication-square310x310logo" content="<?php bloginfo('template_url');?>/../dist/img/icons/mstile-310x310.png" />

		<?php 
	}


	/** This is where you can add your own functions to twig.
	 *
	 * @param string $twig get extension.
	 */
	public function add_to_twig( $twig ) {
		$twig->addExtension( new Twig\Extension\StringLoaderExtension() );
		$twig->addFilter( new Twig\TwigFilter( 'myfoo', array( $this, 'myfoo' ) ) );
		return $twig;
	}

}

new CTCISite();
