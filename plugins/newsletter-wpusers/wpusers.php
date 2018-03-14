<?php

/*
  Plugin Name: Newsletter - WP Users Integration
  Plugin URI: http://www.thenewsletterplugin.com
  Description: Integrates the WP user registration with Newsletter subscription
  Version: 1.1.0
  Author: The Newsletter Team
  Author URI: http://www.thenewsletterplugin.com
  Disclaimer: Use at your own risk. No warranty expressed or implied is provided.
 */

class NewsletterWpUsers {

    /**
     * @var NewsletterWpUsers
     */
    static $instance;
    var $prefix = 'newsletter_wpusers';
    var $slug = 'newsletter-wpusers';
    var $plugin = 'newsletter-wpusers/wpusers.php';
    var $id = 73;
    var $options;
    /* @var NewsletterLogger */
    var $logger;

    function __construct() {
        self::$instance = $this;
        $this->options = get_option($this->prefix, array());
        if (empty($this->options)) {
            $this->options = get_option('newsletter_wp', array());
        }
        add_action('init', array($this, 'hook_init'));
        register_activation_hook(__FILE__, array($this, 'hook_activation'));
    }

    function hook_activation() {
        $opt_in = (int) NewsletterSubscription::instance()->options['noconfirmation'];
        $defaults = array('login' => 1, 'status' => $opt_in ? 'S' : 'C');

        $this->options = array_merge($defaults, $this->options);

        update_option('newsletter_wp', $this->options);
    }

    /**
     * 
     * @return NewsletterLogger
     */
    function get_logger() {
        if ($this->logger) {
            return $this->logger;
        }
        $this->logger = new NewsletterLogger('wpusers');
        return $this->logger;
    }

    function hook_init() {
        if (!class_exists('Newsletter') || NEWSLETTER_VERSION < '4.8.7') {
            return;
        }
        add_filter('site_transient_update_plugins', array($this, 'hook_site_transient_update_plugins'));
        if (is_admin()) {
            add_action('admin_menu', array($this, 'hook_admin_menu'), 100);
            add_filter('newsletter_menu_subscription', array($this, 'hook_newsletter_menu_subscription'));
            add_filter('plugin_action_links_' . $this->plugin, array($this, 'hook_plugin_action_links'));
            add_action('edit_user_profile', array($this, 'hook_edit_user_profile'));
        }
        add_action('delete_user', array($this, 'hook_delete_user'));
        if (isset($this->options['login']) && $this->options['login']) {
            add_action('wp_login', array($this, 'hook_wp_login'));
        }
        add_action('register_form', array($this, 'hook_register_form'));
        // The hook is always active so the module can be activated only on registration (otherwise we should check that
        // option on every page load. The registration code should be moved inside the module...
        add_action('user_register', array($this, 'hook_user_register'));
    }

    function hook_edit_user_profile($wp_user) {
        global $wpdb;
        if (!current_user_can('administrator')) {
            return;
        }
        echo '<h3>Connected subscriber</h3>';
        $newsletter = Newsletter::instance();
        $user = $newsletter->get_user_by_wp_user_id($wp_user->ID);
        if (!$user) {
            echo '<p>No subscriber linked to this user.</p>';
            return;
        }

        echo '<p>Subscriber #' . $user->id . ' connected. <a href="admin.php?page=newsletter_users_edit&id=' . $user->id . '">Edit</a>.</p>';
    }

    function hook_plugin_action_links($links) {
        $settings_link = '<a href="admin.php?page=' . $this->prefix . '_index">' . __('Settings') . '</a>';
        array_push($links, $settings_link);
        return $links;
    }

    function hook_newsletter_menu_subscription($entries) {
        $entries[] = array('label' => '<i class="fa fa-wordpress"></i> WP Users Integration', 'url' => '?page=newsletter_wpusers_index', 'description' => 'Subscribe on WP registration');
        return $entries;
    }

    function hook_admin_menu() {
        add_submenu_page('newsletter_main_index', 'WP Users Integration', '<span class="tnp-side-menu">WP Users Integration</span>', 'manage_options', 'newsletter_wpusers_index', array($this, 'menu_page_index'));
    }

    function menu_page_index() {
        global $wpdb;
        require dirname(__FILE__) . '/index.php';
    }

    function hook_site_transient_update_plugins($value) {
        if (!method_exists('Newsletter', 'set_extension_update_data')) {
            return $value;
        }

        return Newsletter::instance()->set_extension_update_data($value, $this);
    }

    /**
     * See wp-includes/user.php function wp_signon().
     */
    function hook_wp_login($user_login) {
        $logger = $this->get_logger();

        $logger->debug('Logged in user: ' . $user_login);
        $wp_user = get_user_by('login', $user_login);
        if (!empty($wp_user)) {
            //$logger->info($wp_user);
            // We have a user able to login, so his subscription can be confirmed if not confirmed
            $user = Newsletter::instance()->get_user($wp_user->user_email);
            if (empty($user)) {
                $logger->debug('No connected subscription found');
            } else {
                if ($user->status == 'S') {
                    $logger->debug('Confirming connected subscription');
                    NewsletterSubscription::instance()->confirm($user->id, $this->options['welcome'] == 1);
                } else {
                    $logger->debug('Logged in user was not waiting for confirmation');
                }
            }
        }
    }

    function hook_delete_user($id) {
        global $wpdb;
        if ($this->options['delete'] == 1) {
            $wpdb->delete(NEWSLETTER_USERS_TABLE, array('wp_user_id' => $id));
        }
    }

    function hook_register_form() {
        if ($this->options['subscribe'] == 2 || $this->options['subscribe'] == 3) {
            echo '<p>';
            echo '<label><input type="checkbox" value="1" name="newsletter"';
            if ($this->options['subscribe'] == 3) {
                echo ' checked';
            }
            echo '>&nbsp;';
            echo $this->options['subscribe_label'];
            echo '</label></p>';
        }
    }

    function hook_user_register($wp_user_id) {
        global $wpdb;
        static $last_wp_user_id = 0;

        $logger = $this->get_logger();

        // If the integration is disabled...
        if ($this->options['subscribe'] == 0) {
            return;
        }

        if ($last_wp_user_id == $wp_user_id) {
            $logger->fatal('Called twice with the same user id!');
            return;
        }

        $last_wp_user_id = $wp_user_id;

        // If not forced and the user didn't choose the newsletter...
        if ($this->options['subscribe'] != 1) {
            if (!isset($_REQUEST['newsletter'])) {
                return;
            }
        }

        $logger->info('Adding a registered WordPress user (' . $wp_user_id . ')');
        $wp_user = $wpdb->get_row($wpdb->prepare("select * from $wpdb->users where id=%d limit 1", $wp_user_id));
        if (empty($wp_user)) {
            $logger->fatal('User not found?!');
            return;
        }

        // Yes, some registration procedures allow empty email
        if (!NewsletterModule::is_email($wp_user->user_email)) {
            $logger->fatal('User without a valid email?!');
            return;
        }

        $_REQUEST['ne'] = $wp_user->user_email;
        $_REQUEST['nr'] = 'registration';
        $_REQUEST['nn'] = get_user_meta($wp_user_id, 'first_name', true);
        $_REQUEST['ns'] = get_user_meta($wp_user_id, 'last_name', true);

        $status = $this->options['status'];

        if ($status == 'S') {
            // Single
            $emails = $this->options['confirmation'] == 1;
        } else {
            // Double
            $emails = $this->options['welcome'] == 1;
        }

        $user = NewsletterSubscription::instance()->subscribe($status, $emails);

        if (!$user) {
            $logger->fatal('Unable to create the subscription ');
            return;
        }

        // Now we associate it with wp
        Newsletter::instance()->set_user_wp_user_id($user->id, $wp_user_id);

        // Force the lists
        $user = array('id' => $user->id);

        if (isset($this->options['lists'])) {
            foreach ($this->options['lists'] as $list) {
                $user['list_' . $list] = 1;
            }
            NewsletterSubscription::instance()->save_user($user);
        }
    }

}

new NewsletterWpUsers();

