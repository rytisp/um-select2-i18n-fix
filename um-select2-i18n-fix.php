<?php
/**
 * Plugin Name: UM Select2 i18n Fix
 * Description: Prevents Select2 i18n “reading 'define'” crashes by loading the full Select2 build early, blocking i18n bundles (incl. en.js), and adding an early AMD guard.
 * Version: 1.1.2
 * Author: Rytis Petkevicius (rytisp@gmail.com)
 * License: (C) 2025 All Rights Reserved
 * Requires at least: 5.8
 * Requires PHP: 7.2
 * Tested up to: 6.8
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * 0) EARLY HEAD GUARD — first thing in <head>.
 * Prevents a fatal if any Select2 i18n file executes before AMD exists.
 */
add_action( 'wp_head', function () {
    ?>
    <script>
    (function(w){
      if (!w.S2) { w.S2 = {}; }
      if (!w.S2.define) { w.S2.define = function(){ /* no-op */ }; }
      if (!w.S2.require) { w.S2.require = function(){ /* no-op */ }; }
    })(window);
    </script>
    <?php
}, 0 );

/**
 * 1) On register: remove ANY Select2 i18n scripts (en.js and locales).
 * Blocks UM’s i18n files before they reach the queue.
 */
add_action( 'wp_default_scripts', function( WP_Scripts $scripts ) {
    foreach ( $scripts->registered as $handle => $obj ) {
        if ( empty( $obj->src ) ) continue;
        if ( strpos( $obj->src, 'ultimate-member/assets/libs/select2/i18n/' ) !== false ) {
            unset( $scripts->registered[ $handle ] );
        }
        // Optional: block other plugins' Select2 i18n too:
        // if ( preg_match( '~select2(?:\.full)?(?:\.min)?/i18n/.*\.js$~', $obj->src ) ) { unset( $scripts->registered[ $handle ] ); }
    }
}, 1 );

/**
 * Helper: Build the URL to UM's Select2 full build if present.
 */
function um_s2_fix_get_um_select2_full_url() : string {
    $um_main = WP_PLUGIN_DIR . '/ultimate-member/ultimate-member.php';
    if ( file_exists( $um_main ) ) {
        $um_base_url = plugins_url( '', $um_main ); // URL to /ultimate-member
        return $um_base_url . '/assets/libs/select2/select2.full.min.js';
    }
    return '';
}

/**
 * 2) Force-load Select2 FULL build early (in HEAD) so AMD exists (front-end only).
 */
add_action( 'wp_enqueue_scripts', function () {
    if ( is_admin() ) return;

    $s2_full_url = um_s2_fix_get_um_select2_full_url();
    if ( $s2_full_url ) {
        wp_register_script( 'um-select2-full-force', $s2_full_url, array( 'jquery' ), '4.0.13', false );
        wp_enqueue_script( 'um-select2-full-force' );
    }
}, 1 );

/**
 * 3) Last-chance: if any i18n script source sneaks through, block it at print time.
 */
add_filter( 'script_loader_src', function( $src ) {
    if ( strpos( $src, 'ultimate-member/assets/libs/select2/i18n/' ) !== false ) {
        return false;
    }
    return $src;
}, 1 );
