<?php
/*
 Plugin Name: Bridaluxe
 Description: This plugin adds a shortcode and new template tag that you can use on your website to quickly and easily integrate the bridaluxe storefront.
 Version: 1.0.5
 Author: Plugin-Developer.com
 Author URI: http://www.plugin-developer.com
 */

if( !class_exists( 'Bridaluxe_Storefront' ) ) {

	class Bridaluxe_Storefront {
		
		var $options;

		/**
		 * Constructor registers actions and filters as necessary.
		 *
		 * @return Bridaluxe_Storefront
		 */
		function Bridaluxe_Storefront( ) {
			$this->initializeOptions( );
			
			// Activation/Deactivation
			register_activation_hook( __FILE__, array( &$this, 'onActivate' ) );
			register_deactivation_hook( __FILE__, array( &$this, 'onDeactivate' ) );
			
			// Actions
			add_action( 'admin_menu', array( &$this, 'onAdminMenu' ) );
			add_action( 'wp_head', array( &$this, 'onHead' ) );
			
			// Filters
			

			// Other
			add_shortcode( 'bridaluxe', array( &$this, 'onBridaluxeShortcode' ) );
		}

		/**
		 * Activation callback which initializes the plugin options for the first time and
		 * saves them to the database.  Also creates a new page containing only the Bridaluxe 
		 * storefront shortcode.
		 *
		 */
		function onActivate( ) {
			$this->initializeOptions( );
		}

		/**
		 * Deactivation callback removes the plugins options from the database.
		 *
		 */
		function onDeactivate( ) {
			$this->deleteOptions( );
		}

		/**
		 * Adds an appropriate settings page for the Bridaluxe Storefront Page
		 *
		 */
		function onAdminMenu( ) {
			add_options_page( __( 'Bridaluxe' ), __( 'Bridaluxe' ), 8, 'bridaluxe', array( &$this, 'settingsPage' ) );
		}

		/**
		 * Returns a string containing the appropriate Bridaluxe storefront page to display.
		 *
		 * @param array $attributes the attributes passed with the shorttag.
		 * @param string $content the content enclosed between bridaluxe shorttags
		 */
		function onBridaluxeShortcode( $attributes, $content = null ) {
			$output = $this->getContent( );
			
			return $output;
		}

		/**
		 * Enqueues the two styles necessary from the Bridaluxe website.
		 *
		 */
		function onHead( ) {
			if( 1 == $this->options[ 'use-stylesheets' ] ) {
				?>
<link rel="stylesheet" href="http://services.bridaluxe.com/css/istore/wordpress/main.css" type="text/css" media="screen" />
<?php
			}
		}

		/**
		 * Constructs the appropriate URL with which to call the Bridaluxe Storefront API.
		 *
		 * @param array $parameters
		 */
		function constructUrl( $parameters ) {
			extract( $parameters );
			$url = "http://services.bridaluxe.com/store/{$method}/{$view}/{$id}/{$affiliateId}/{$taxonomyId}/{$color}/" . $this->implodeWithKey( $_GET );
			return $url;
		}

		/**
		 * Retrieves and returns the appropriate content, based on the current Url parameters.
		 *
		 * @param array $parameters
		 */
		function getContent( ) {
			$content = '';
			
			// Variables
			$view = 'wordpress';
			$color = empty( $this->options[ 'color-scheme' ] ) ? 'red' : $this->options[ 'color-scheme' ];
			$affiliateId = empty( $this->options[ 'affiliate-id' ] ) ? 0 : $this->options[ 'affiliate-id' ];
			
			$method = ( isset( $_GET[ 'm' ] ) ? $_GET[ 'm' ] : 'index' );
			$taxonomyId = '009180';
			$id = ( isset( $_GET[ 'id' ] ) ? $_GET[ 'id' ] : '009180' );
			$id = implode( '-', explode( ' ', $id ) );
			
			$parameters = compact( 'method', 'view', 'id', 'affiliateId', 'taxonomyId', 'color' );
			$url = $this->constructUrl( $parameters );
			
			$content = $this->getRemoteFile( $url );
			
			global $post;
			$theId = $post->ID;
			
			return $content;
		}

		/**
		 * Returns a string with each key and value of an associated array concatenated together using
		 * the specified glue.
		 *
		 * @param string $assoc
		 * @param string $inglue
		 * @param string $outglue
		 * @return string
		 */
		function implodeWithKey( $assoc, $inglue = "/", $outglue = "/" ) {
			$return = '';
			foreach( $assoc as $tk => $tv ) {
				$return .= $outglue . $tk . $inglue . $tv;
			}
			return substr( $return, strlen( $outglue ) );
		}

		/**
		 * Retrieves a remote file.
		 *
		 * @return string The response retrieved from the remote file.
		 */
		function getRemoteFile( $url ) {
			$parsedUrl = parse_url( $url );
			
			// Host
			$host = $parsedUrl[ 'host' ];
			
			// Path
			if( isset( $parsedUrl[ 'path' ] ) ) {
				$path = $parsedUrl[ 'path' ];
			} else {
				$path = '/';
			}
			
			// Query
			if( isset( $parsedUrl[ 'query' ] ) ) {
				$path .= '?' . $parsedUrl[ 'query' ];
			}
			
			// Port
			if( isset( $parsedUrl[ 'port' ] ) ) {
				$port = $parsedUrl[ 'port' ];
			} else {
				$port = '80';
			}
			
			$timeout = 10;
			$response = '';
			if( 1 == ini_get( 'allow_url_fopen' ) ) {
				$fp = @fsockopen( $host, '80', $errno, $errstr, $timeout );
				
				if( !$fp ) {
					$response = __( 'Cannot retrieve ' . $url . '.' );
					break;
				} else {
					// send the necessary headers to get the file
					fputs( $fp, "GET $path HTTP/1.0\r\n" . "Host: $host\r\n" . "User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.0.3) Gecko/20060426 Firefox/1.5.0.3\r\n" . "Accept: */*\r\n" . "Accept-Language: en-us,en;q=0.5\r\n" . "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7\r\n" . "Keep-Alive: 300\r\n" . "Connection: keep-alive\r\n" . "Referer: http://$host\r\n\r\n" );
					
					// retrieve the response from the remote server
					while( $line = fread( $fp, 4096 ) ) {
						$response .= $line;
					}
					
					fclose( $fp );
					
					// strip the headers
					$pos = strpos( $response, "\r\n\r\n" );
					$response = substr( $response, $pos + 4 );
				}
			} else if( function_exists( 'curl_init' ) ) {
				$handle = curl_init( $url );
				
				curl_setopt( $handle, CURLOPT_RETURNTRANSFER, true );
				curl_setopt( $handle, CURLOPT_FOLLOWLOCATION, true );
				curl_setopt( $handle, CURLOPT_HEADER, false );
				curl_setopt( $handle, CURLOPT_HTTPHEADER, array( 'Host' => $host, 'User-Agent' => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.0.3) Gecko/20060426 Firefox/1.5.0.3', 'Accept' => '*/*', 'Accept-Language' => 'en-us,en;q=0.5', 'Accept-Charset' => 'ISO-8859-1,utf-8;q=0.7,*;q=0.7', 'Keep-Alive' => '300', 'Connection' => 'keep-alive', 'Referer' => "http://$host" ) );
				
				$response = curl_exec( $handle );
			} else {
				$response = __( 'allow_url_fopen must be enabled or you must have cURL installed in order to use the Bridaluxe Storefront plugin.' );
			}
			
			return $response;
		}

		/**
		 * Callback for the settings page for this plugin.  Processes posted information and displays
		 * the appropriate view.
		 *
		 */
		function settingsPage( ) {
			$this->processSettings( );
			
			include ( path_join( dirname( __FILE__ ), 'views/settings.view.php' ) );
		}

		/**
		 * Processes POSTed settings variables and stores them if appropriate.
		 *
		 */
		function processSettings( ) {
			if( isset( $_POST ) && !empty( $_POST ) ) {
				$newId = $_POST[ 'bridaluxe-affiliate-id' ];
				$this->options[ 'affiliate-id' ] = is_numeric( $newId ) ? $newId : $this->options[ 'affiliate-id' ];
				$this->options[ 'use-stylesheets' ] = $_POST[ 'bridaluxe-use-stylesheets' ];
				$this->saveOptions( );
			}
		}

		/**
		 * Deletes the plugin's options from the WordPress database.
		 *
		 */
		function deleteOptions( ) {
			delete_option( 'Bridaluxe Storefront Options' );
		}

		/**
		 * Retrieves the plugin's options from the database.  If no options are found, then default values
		 * are used.
		 *
		 */
		function initializeOptions( ) {
			$options = get_option( 'Bridaluxe Storefront Options' );
			if( false === $options || !is_array( $options ) ) {
				$options = array( 'affiliate-id' => '', 'color-scheme' => 'red', 'use-stylesheets' => 1 );
				add_option( 'Bridaluxe Storefront Options', $options );
			}
			$this->options = $options;
		}

		/**
		 * Saves the plugin's current options to the WordPress database.
		 *
		 */
		function saveOptions( ) {
			update_option( 'Bridaluxe Storefront Options', $this->options );
		}
	}
}

if( class_exists( 'Bridaluxe_Storefront' ) ) {
	$bis = new Bridaluxe_Storefront( );

	function bridaluxe_navigation( ) {
		global $bis;
		$nav = $bis->getRemoteFile( 'http://services.bridaluxe.com/store/navigation/wordpress/null/009180/' );
		echo $nav;
	}
}

?>