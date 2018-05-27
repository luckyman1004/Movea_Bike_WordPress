<?php

defined('ABSPATH') || exit;

require_once NEWSLETTER_INCLUDES_DIR . '/module.php';

class NewsletterProfile extends NewsletterModule {

    static $instance;
    var $home_url;

    /**
     * @return NewsletterProfile
     */
    static function instance() {
        if (self::$instance == null) {
            self::$instance = new NewsletterProfile();
        }
        return self::$instance;
    }

    function __construct() {
        parent::__construct('profile', '1.0.0');
        add_action('init', array($this, 'hook_init'));
        add_action('wp_loaded', array($this, 'hook_wp_loaded'));
        add_shortcode('newsletter_profile', array($this, 'shortcode_newsletter_profile'));
    }

    function hook_init() {
        if (is_admin()) {
            add_action('wp_ajax_newsletter_users_export', array($this, 'hook_wp_ajax_newsletter_users_export'));
        }
        add_filter('newsletter_replace', array($this, 'hook_newsletter_replace'), 10, 3);
    }

    function hook_wp_loaded() {
        global $wpdb;

        $this->home_url = home_url('/');

        switch (Newsletter::instance()->action) {
            case 'profile_export':
                $user = $this->get_user_from_request(true);
                header('Content-Type: application/json;chartse=UTF-8');
                echo $this->to_json($user);
                die();
        }
    }

    /**
     * 
     * @param stdClass $user
     */
    function get_profile_export_url($user) {
        return $this->home_url . '?na=profile_export&nk=' . $user->id . '-' . $user->token;
    }

    /**
     * 
     * @param stdClass $user
     */
    function get_profile_url($user) {
        $options_profile = get_option('newsletter_profile');

        if (empty($options_profile['profile_url'])) {
            $profile_url = $this->home_url . '?na=profile&nk=' . $user->id . '-' . $user->token;
        } else {
            $profile_url = self::add_qs($options_profile['profile_url'], 'nk=' . $user->id . '-' . $user->token);
        }

        $profile_url = apply_filters('newsletter_profile_url', $profile_url, $user);
        return $profile_url;
    }

    function hook_newsletter_replace($text, $user, $email) {
        if (!$user) return $text;
        
        $profile_options = NewsletterSubscription::instance()->get_options('profile');

        // Profile edit page URL and link
        $url = $this->get_profile_url($user);
        $text = $this->replace_url($text, 'PROFILE_URL', $url);
        $text = str_replace('{profile_link}', '<a class="tnp-profile-link" href="' . $url . '">' . $profile_options['profile_edit'] . '</a>', $text);

        // Profile export URL and link
        $url = $this->get_profile_export_url($user);
        $text = $this->replace_url($text, 'PROFILE_EXPORT_URL', $url);
        $text = str_replace('{profile_export_link}', '<a class="tnp-profile-export-link" href="' . $url . '">' . $profile_options['profile_export'] . '</a>', $text);
        return $text;
    }

    function shortcode_newsletter_profile($attrs, $content) {
        $user = $this->check_user();

        if (empty($user)) {
            if (empty($content)) {
                return __('Subscriber profile not found.', 'newsletter');
            } else {
                return $content;
            }
        }

        if (isset($attrs['layout']) && $attrs['layout'] == 'table') {
            return NewsletterSubscription::instance()->get_profile_form($user);
        } else {
            return NewsletterSubscription::instance()->get_profile_form_html5($user);
        }
    }

    function to_json($user) {
        global $wpdb;

        $user = (array) $user;
        $fields = array('id', 'name', 'surname', 'sex', 'created', 'ip');
        $data = array();
        $options_profile = get_option('newsletter_profile', array());
        $lists = array();
        $profiles = array();
        foreach ($user as $key => $value) {
            if (strpos($key, 'list_') === 0) {
                if (empty($value)) continue;
                if ($options_profile[$key . '_status'] != 1 && $options_profile[$key . '_status'] != 2) {
                    continue;
                }
                $lists[] = $options_profile[$key];
            }

            // Check if disabled
            if (strpos($key, 'profile_') === 0) {
                if (empty($value)) continue;
                if ($options_profile[$key . '_status'] == 0) {
                    continue;
                }
                $profiles[$key] = array('name' => $options_profile[$key], 'value' => $value);
            }


            if (in_array($key, $fields))
                $data[$key] = $value;
        }
        
        $data['lists'] = $lists;
        $data['profiles'] = $profiles;
        

        // Newsletters
        $sent = $wpdb->get_results($wpdb->prepare("select * from {$wpdb->prefix}newsletter_sent where user_id=%d order by email_id asc", $user['id']));
        $newsletters = array();
        foreach ($sent as $item) {
            $action = 'none';
            if ($item->open == 1)
                $action = 'read';
            else if ($item->open == 2)
                $action = 'click';

            $email = $this->get_email($item->email_id);
            if (!$email)
                continue;
            // 'id'=>$item->email_id, 
            $newsletters[] = array('subject' => $email->subject, 'action' => $action, 'sent' => date('Y-m-d h:i:s', $email->send_on));
        }

        $data['newsletters'] = $newsletters;

        $extra = apply_filters('newsletter_profile_export_extra', array());

        $data = array_merge($extra, $data);

        return json_encode($data, JSON_PRETTY_PRINT);
    }

    function upgrade() {
        global $wpdb, $charset_collate;

        parent::upgrade();
    }

    function admin_menu() {
        //$this->add_menu_page('index', 'Subscribers');
        //$this->add_admin_page('index', 'Profile');
    }

}

NewsletterProfile::instance();
