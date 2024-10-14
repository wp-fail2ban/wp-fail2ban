<?php

/**
 * About
 *
 * @package wp-fail2ban
 * @since   4.2.0
 */
namespace org\lecklider\charles\wordpress\wp_fail2ban;

defined( 'ABSPATH' ) or exit;
/**
 * Pull in extra "about" information
 *
 * @since 4.3.0
 *
 * @return string
 */
function _get_extra_about()
{
    $extra = '';
    /**
     * Don't make a remote call if the user hasn't opted in
     */
    
    if ( !wf_fs()->is_tracking_prohibited() ) {
        $extra = get_site_transient( 'wp_fail2ban_extra_about' );
        
        if ( false === apply_filters( 'wp_fail2ban_extra_about_transient', $extra ) ) {
            $url = apply_filters( 'wp_fail2ban_extra_about_url', 'https://wp-fail2ban.com/extra-about/?version=' . WP_FAIL2BAN_VER );
            
            if ( !is_wp_error( $rv = wp_remote_get( $url ) ) ) {
                /**
                 * Try not to fetch more than once per day
                 */
                set_site_transient( 'wp_fail2ban_extra_about', $rv['body'], DAY_IN_SECONDS );
                $extra = $rv['body'];
            }
        
        }
    
    }
    
    return $extra;
}

/**
 * About content
 *
 * @since 4.2.0
 *
 * @param bool  $hide_title
 */
function about( $hide_title = false )
{
    $wp_f2b_ver = substr( WP_FAIL2BAN_VER, 0, strrpos( WP_FAIL2BAN_VER, '.' ) );
    $extra = _get_extra_about();
    $utm = '?utm_source=about&utm_medium=about&utm_campaign=' . WP_FAIL2BAN_VER;
    ?>
<div class="wrap">
  <style>
    div.inside ul {
      list-style: disc;
      padding-left: 2em;
    }
    h2#4-3-0 {
      font-size: 18px !important;
    }
  </style>
    <?php 
    if ( !$hide_title ) {
        ?>
  <h1>WP fail2ban</h1>
    <?php 
    }
    ?>
  <div id="poststuff">
    <div id="post-body" class="metabox-holder columns-2">
      <div id="post-body-content">
        <div class="meta-box-sortables ui-sortable">
          <?php 
    echo  $extra ;
    ?>
          <div class="postbox">
            <h2 id="4-3-0" style="font-size: 18px">Version 4.3.0</h2>
            <div class="inside">
              <ul>
                <li>Add new dashboard widget: last 5 <tt>syslog</tt> messages.</li>
                <li>Add full <a href="https://wp-fail2ban.com/features/multisite-networks/<?php 
    echo  $utm ;
    ?>" rel="noopener" target="_blank">multisite support</a>.</li>
                <li>Add <a href="https://wp-fail2ban.com/features/block-username-logins/<?php 
    echo  $utm ;
    ?>" rel="noopener" target="_blank">username login blocking</a> (force login with email).</li>
                <li>Add <a href="https://wp-fail2ban.com/features/empty-username-logging/<?php 
    echo  $utm ;
    ?>" rel="noopener" target="_blank">separate logging</a> for login attempts with an empty username.</li>
                <li>Improve <a href="https://wp-fail2ban.com/features/block-user-enumeration/<?php 
    echo  $utm ;
    ?>" rel="noopener" target="_blank">user enumeration blocking</a> compatibility with the WordPress block editor (Gutenberg).</li>
                <li>Bump the minimum PHP version to 5.6.</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <div id="postbox-container-1" class="postbox-container">
        <div class="meta-box-sortables">
          <div class="postbox">
            <h3>Getting Started</h3>
            <div class="inside">
              <ol>
                <li><a href="https://docs.wp-fail2ban.com/en/<?php 
    echo  $wp_f2b_ver ;
    ?>/installation.html<?php 
    echo  $utm ;
    ?>" rel="noopener" target="docs.wp-fail2ban.com">Installation</a></li>
                <li><a href="https://docs.wp-fail2ban.com/en/<?php 
    echo  $wp_f2b_ver ;
    ?>/configuration.html<?php 
    echo  $utm ;
    ?>" rel="noopener" target="docs.wp-fail2ban.com">Configuration</a></li>
              </ol>
            </div>
          </div>
          <div class="postbox">
            <h3>Getting Help</h3>
            <div class="inside">
              <ul>
        <?php 
    
    if ( wf_fs()->is_trial() ) {
        ?>
                <li><a href="https://forums.invis.net/c/wp-fail2ban-premium/support-trial/<?php 
        echo  $utm ;
        ?>" rel="noopener" target="_blank">Trial Support Forum</a></li>
        <?php 
    } elseif ( wf_fs()->is_free_plan() ) {
        ?>
                <li><a href="https://forums.invis.net/c/wp-fail2ban/support/<?php 
        echo  $utm ;
        ?>" rel="noopener" target="_blank">Free Support Forum</a></li>
        <?php 
    }
    
    ?>
        <?php 
    ?>
            </div>
          </div>
        </div>
      </div>
    </div>
    &nbsp;
  </div>
</div>
    <?php 
}
