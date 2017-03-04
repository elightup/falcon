<?php
/**
 * Load CSS asynchronously using loadCSS script.
 *
 * @link https://github.com/filamentgroup/loadCSS
 *
 * @package Falcon
 * @author  GretaThemes <info@gretathemes.com>
 * @link    https://gretathemes.com
 */

namespace Falcon;

/**
 * Async CSS class.
 * @package Falcon
 */
class AsyncCSS {
	/**
	 * Add hooks.
	 */
	public function __construct() {
		add_action( 'wp_head', [ $this, 'add_js' ], 5 );
		add_filter( 'style_loader_tag', [ $this, 'load_css' ], 99, 3 );
	}

	/**
	 * Add loadCSS script.
	 */
	public function add_js() {
		if ( ! $this->get_handles() ) {
			return;
		}
		?><script>!function(e){"use strict";var n=function(n,t,o){function i(e){return a.body?e():void setTimeout(function(){i(e)})}function r(){l.addEventListener&&l.removeEventListener("load",r),l.media=o||"all"}var d,a=e.document,l=a.createElement("link");if(t)d=t;else{var s=(a.body||a.getElementsByTagName("head")[0]).childNodes;d=s[s.length-1]}var f=a.styleSheets;l.rel="stylesheet",l.href=n,l.media="only x",i(function(){d.parentNode.insertBefore(l,t?d:d.nextSibling)});var u=function(e){for(var n=l.href,t=f.length;t--;)if(f[t].href===n)return e();setTimeout(function(){u(e)})};return l.addEventListener&&l.addEventListener("load",r),l.onloadcssdefined=u,u(r),l};"undefined"!=typeof exports?exports.loadCSS=n:e.loadCSS=n}("undefined"!=typeof global?global:this);</script><?php
	}

	/**
	 * Change CSS style tag to script tag and load it asynchronously.
	 *
	 * @param string $tag HTML link tag
	 * @param string $handle ID of the CSS resource
	 * @param string $href URL of the CSS file.
	 *
	 * @return string
	 */
	public function load_css( $tag, $handle, $href ) {
		$handles = $this->get_handles();
		if ( in_array( $handle, $handles ) ) {
			$tag = "<script>loadCSS('$href');</script>";
		}
		return $tag;
	}

	/**
	 * Get list of async CSS handles.
	 * @return array
	 */
	protected function get_handles() {
		$option  = get_option( 'falcon' );
		$handles = isset( $option['async_css_handles'] ) ? $option['async_css_handles'] : '';
		$handles = array_filter( array_map( 'trim', explode( ',', $handles . ',' ) ) );

		// Make sure $handle does not contain suffix -css added automatically by WordPress.
		array_walk( $handles, function ( &$handle ) {
			$handle = preg_replace( '/-css$/', '', $handle );
		} );

		return $handles;
	}
}
