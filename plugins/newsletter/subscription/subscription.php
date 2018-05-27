<?php

if (!defined('ABSPATH'))
    exit;

require_once NEWSLETTER_INCLUDES_DIR . '/module.php';

class NewsletterSubscription extends NewsletterModule {

    const MESSAGE_CONFIRMED = 'confirmed';

    static $instance;

    /**
     * @return NewsletterSubscription
     */
    static function instance() {
        if (self::$instance == null) {
            self::$instance = new NewsletterSubscription();
        }
        return self::$instance;
    }

    function __construct() {

        parent::__construct('subscription', '2.0.5');

        // Must be called after the Newsletter::hook_init, since some constants are defined
        // there.
        add_action('init', array($this, 'hook_init'), 90);
    }

    function hook_init() {
        add_action('wp_loaded', array($this, 'hook_wp_loaded'));
        if (is_admin()) {
            add_action('admin_init', array($this, 'hook_admin_init'));
        } else {
            add_action('wp_enqueue_scripts', array($this, 'hook_wp_enqueue_scripts'));
            add_shortcode('newsletter', array($this, 'shortcode_newsletter'));
            add_shortcode('newsletter_form', array($this, 'shortcode_newsletter_form'));
            //add_shortcode('newsletter_profile', array($this, 'shortcode_newsletter_profile'));
            add_shortcode('newsletter_field', array($this, 'shortcode_newsletter_field'));
        }
    }

    function hook_admin_init() {
        if (isset($_GET['page']) && $_GET['page'] === 'newsletter_subscription_forms') {
            header('X-XSS-Protection: 0');
        }
    }

    function hook_wp_enqueue_scripts() {
        if (apply_filters('newsletter_enqueue_style', true)) {
            wp_enqueue_style('newsletter-subscription', plugins_url('newsletter') . '/subscription/style.css', array(), NEWSLETTER_VERSION);
            if (!empty($this->options['css'])) {
                wp_add_inline_style('newsletter-subscription', $this->options['css']);
            }
        }

        wp_enqueue_script('newsletter-subscription', plugins_url('newsletter') . '/subscription/validate.js', array(), NEWSLETTER_VERSION, true);
        $options = $this->get_options('profile');
        $data = array();
        $data['messages'] = array();
        if (isset($options['email_error'])) {
            $data['messages']['email_error'] = $options['email_error'];
        }
        if (isset($options['name_error'])) {
            $data['messages']['name_error'] = $options['name_error'];
        }
        if (isset($options['surname_error'])) {
            $data['messages']['surname_error'] = $options['surname_error'];
        }
        if (isset($options['profile_error'])) {
            $data['messages']['profile_error'] = $options['profile_error'];
        }
        if (isset($options['privacy_error'])) {
            $data['messages']['privacy_error'] = $options['privacy_error'];
        }
        $data['profile_max'] = NEWSLETTER_PROFILE_MAX;
        wp_localize_script('newsletter-subscription', 'newsletter', $data);
    }

    function ip_match($ip, $range) {
        if (strpos($range, '/')) {
            list ($subnet, $bits) = explode('/', $range);
            $ip = ip2long($ip);
            $subnet = ip2long($subnet);
            $mask = -1 << (32 - $bits);
            $subnet &= $mask; # nb: in case the supplied subnet wasn't correctly aligned
            return ($ip & $mask) == $subnet;
        } else {
            return strpos($range, $ip) === 0;
        }
    }

    function hook_wp_loaded() {
        global $newsletter, $wpdb;

        switch ($newsletter->action) {
            case 'profile-change':
                if ($this->antibot_form_check()) {
                    $user = $this->get_user_from_request();
                    if (!$user || $user->status != 'C') {
                        die('Subscriber not found or not active.');
                    }

                    $email = $this->get_email_from_request();
                    if (!$email) {
                        die('Newsletter not found');
                    }

                    if (isset($_REQUEST['list'])) {
                        $list_id = (int) $_REQUEST['list'];

                        // Check if the list is public
                        $list = $this->get_list($list_id);
                        if (!$list || $list['status'] == 0) {
                            die('Private list.');
                        }

                        $url = $_REQUEST['redirect'];

                        $this->set_user_list($user, $list_id, $_REQUEST['value']);
                        NewsletterStatistics::instance()->add_click(wp_sanitize_redirect($url), $user->id, $email->id);
                        wp_safe_redirect($url);
                        die();
                    }
                } else {
                    $this->request_to_antibot_form('Continue');
                }

                die();

            case 'm':
                include dirname(__FILE__) . '/page.php';
                die();

            // normal subscription
            case 's':
            case 'subscribe':

                $ip = $this->get_remote_ip();
                $email = $this->normalize_email($_REQUEST['ne']);
                $first_name = '';
                if (isset($_REQUEST['nn'])) $first_name = $this->normalize_name($_REQUEST['nn']);
                
                $last_name = '';
                if (isset($_REQUEST['ns'])) $last_name = $this->normalize_name($_REQUEST['ns']);
                
                $full_name = trim($first_name . ' ' . $last_name);
                
                $antibot_logger = new NewsletterLogger('antibot');

                if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                    $antibot_logger->fatal($email . ' - ' . $ip . ' - HTTP method invalid');
                    die('Invalid');
                }

                $captcha = !empty($this->options['captcha']);

                if (!empty($this->options['antibot_disable']) || $this->antibot_form_check($captcha)) {


                    if (stripos($full_name, 'http://') !== false || stripos($full_name, 'https://') !== false) {
                        $antibot_logger->fatal($email . ' - ' . $ip . ' - Name with http: ' . $full_name);
                        header("HTTP/1.0 404 Not Found");
                        die();
                    }



                    // Cannot check for administrator here, too early.
                    if (true) {

                        $this->logger->debug('Subscription of: ' . $email);
//                        if ($this->options['domain_check']) {
//                            $this->logger->debug('Domain checking');
//                            list($local, $domain) = explode('@', $email);
//
//                            $hosts = array();
//                            if (!getmxrr($domain, $hosts)) {
//                                $antibot_logger->fatal($email . ' - ' . $ip . ' - MX check failed');
//                                die('Blocked 0');
//                            }
//                        }

                        if (!empty($this->options['ip_blacklist'])) {
                            $this->logger->debug('IP blacklist check');
                            foreach ($this->options['ip_blacklist'] as $item) {
                                if ($this->ip_match($ip, $item)) {
                                    $antibot_logger->fatal($email . ' - ' . $ip . ' - IP blacklisted');
                                    header("HTTP/1.0 404 Not Found");
                                    die();
                                }
                            }
                        }

                        if (!empty($this->options['address_blacklist'])) {
                            $this->logger->debug('Address blacklist check');
                            $rev_email = strrev($email);
                            foreach ($this->options['address_blacklist'] as $item) {
                                if (strpos($rev_email, strrev($item)) === 0) {
                                    $antibot_logger->fatal($email . ' - ' . $ip . ' - Address blacklisted');
                                    header("HTTP/1.0 404 Not Found");
                                    die();
                                }
                            }
                        }

                        // Akismet check
                        if (!empty($this->options['akismet']) && class_exists('Akismet')) {
                            $this->logger->debug('Akismet check');
                            $request = 'blog=' . urlencode(home_url()) . '&referrer=' . urlencode($_SERVER['HTTP_REFERER']) .
                                    '&user_agent=' . urlencode($_SERVER['HTTP_USER_AGENT']) .
                                    '&comment_type=signup' .
                                    '&comment_author_email=' . urlencode($email) .
                                    '&user_ip=' . urlencode($_SERVER['REMOTE_ADDR']);
                            if (!empty($full_name)) {
                                $request .= '&comment_author=' . urlencode($full_name);
                            }

                            $response = Akismet::http_post($request, 'comment-check');

                            if ($response && $response[1] == 'true') {
                                $antibot_logger->fatal($email . ' - ' . $ip . ' - Akismet blocked');
                                header("HTTP/1.0 404 Not Found");
                                die();
                            }
                        }

                        // Flood check
                        if (!empty($this->options['antiflood'])) {
                            $this->logger->debug('Antiflood check');
                            $email = $this->is_email($_REQUEST['ne']);
                            $updated = $wpdb->get_var($wpdb->prepare("select updated from " . NEWSLETTER_USERS_TABLE . " where ip=%s or email=%s order by updated desc limit 1", $ip, $email));

                            if ($updated && time() - $updated < $this->options['antiflood']) {
                                $antibot_logger->fatal($email . ' - ' . $ip . ' - Antiflood triggered');
                                header("HTTP/1.0 404 Not Found");
                                die('Too quick');
                            }
                        }

                        $user = $this->subscribe();

                        if ($user->status == 'E')
                            $this->show_message('error', $user->id);
                        if ($user->status == 'C')
                            $this->show_message('confirmed', $user->id);
                        if ($user->status == 'A')
                            $this->show_message('already_confirmed', $user->id);
                        if ($user->status == 'S')
                            $this->show_message('confirmation', $user->id);
                    }
                } else {
                    // Temporary store data
                    //$data_key =  wp_generate_password(16, false, false);
                    //set_transient('newsletter_' . $data_key, $_REQUEST, 60);
                    //$this->antibot_redirect($data_key);
                    $this->request_to_antibot_form('Subscribe', $captcha);
                }
                die();

            // AJAX subscription
            case 'ajaxsub':
                $user = $this->subscribe();
                if ($user->status == 'E')
                    $key = 'error';
                if ($user->status == 'C')
                    $key = 'confirmed';
                if ($user->status == 'A')
                    $key = 'already_confirmed';
                if ($user->status == 'S')
                    $key = 'confirmation';
                $module = NewsletterSubscription::instance();
                $message = $newsletter->replace($module->options[$key . '_text'], $user);
                if (isset($module->options[$key . '_tracking'])) {
                    $message .= $module->options[$key . '_tracking'];
                }
                echo $message;
                die();

            case 'u':
                $user = $this->get_user_from_request();
                $email = $this->get_email_from_request();
                if ($user == null) {
                    $this->show_message('unsubscription_error', null);
                } else {
                    $this->show_message('unsubscription', $user, null, $email);
                }
                die();
                break;
            case 'uc':
                if ($this->antibot_form_check()) {
                    $user = $this->unsubscribe();
                    if ($user->status == 'E') {
                        $this->show_message('unsubscription_error', $user);
                    } else {
                        $this->show_message('unsubscribed', $user);
                    }
                    return;
                } else {
                    $this->request_to_antibot_form('Unsubscribe');
                }
                die();
                break;
            case 'profile':
            case 'p':
            case 'pe':
                $user = $this->check_user();
                if ($user == null) {
                    die('No subscriber found.');
                }

                $this->show_message('profile', $user);
                die();
                break;

            case 'ps':
                $user = $this->save_profile();
                // $user->alert is a temporary field
                $this->show_message('profile', $user, $user->alert);
                die();
                break;

            case 'c':
            case 'confirm':
                if ($this->antibot_form_check()) {
                    $user = $this->confirm();
                    if ($user->status == 'E') {
                        $this->show_message('error', $user->id);
                    } else {
                        setcookie('newsletter', $user->id . '-' . $user->token, time() + 60 * 60 * 24 * 365, '/');
                        $this->show_message('confirmed', $user);
                    }
                } else {
                    $this->request_to_antibot_form('Confirm');
                }
                die();
                break;

            default:
                return;
        }
    }

    function upgrade() {
        global $wpdb, $charset_collate, $newsletter;

        parent::upgrade();

        $this->init_options('profile');
        $this->init_options('lists');

        $default_options = $this->get_default_options();

        if (empty($this->options['error_text'])) {
            $this->options['error_text'] = $default_options['error_text'];
            $this->save_options($this->options);
        }

        // Old migration code
        if (isset($options_profile['profile_text'])) {
            $this->options['profile_text'] = $options_profile['profile_text'];
            if (empty($this->options['profile_text'])) {
                $this->options['profile_text'] = '{profile_form}<p><a href="{unsubscription_url}">I want to unsubscribe.</a>';
            }

            $this->save_options($this->options);
            unset($options_profile['profile_text']);
            update_option('newsletter_profile', $options_profile);
        }

        if (isset($options_profile['profile_saved'])) {
            $this->options['profile_saved'] = $options_profile['profile_saved'];
            $this->save_options($this->options);
            unset($options_profile['profile_saved']);
            update_option('newsletter_profile', $options_profile);
        }

        if ($this->old_version < '2.0.0') {
            if (!isset($this->options['url']) && !empty($newsletter->options['url'])) {
                $this->options['url'] = $newsletter->options['url'];
                $this->save_options($this->options);
            }

            $options_template = $this->get_options('template');
            if (empty($options_template) && isset($this->options['template'])) {
                $options_template['enabled'] = isset($this->options['template_enabled']) ? 1 : 0;
                $options_template['template'] = $this->options['template'];
                add_option('newsletter_subscription_template', $options_template, null, 'no');
            }

            if (isset($this->options['template'])) {
                unset($this->options['template']);
                unset($this->options['template_enabled']);
                $this->save_options($this->options);
            }
        }

        $this->init_options('template', false);
        
        global $wpdb, $charset_collate;

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');



        $sql = "CREATE TABLE `" . $wpdb->prefix . "newsletter_user_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `data` longtext,
  `created` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) $charset_collate;";

        dbDelta($sql);

        return true;
    }

    function first_install() {
        
    }

    function admin_menu() {
        $this->add_menu_page('options', 'List building');
        $this->add_menu_page('antibot', 'Security');
        $this->add_admin_page('profile', 'Subscription Form');
        $this->add_admin_page('forms', 'Forms');
        $this->add_admin_page('lists', 'Lists');
        $this->add_admin_page('template', 'Template');
        $this->add_admin_page('unsubscription', 'Unsubscription');
    }

    /**
     * This method has been redefined for compatibility with the old options naming. It would
     * be better to change them instead. The subscription options should be named
     * "newsletter_subscription" while the form field options, actually named
     * "newsletter_profile", should be renamed "newsletter_subscription_profile" (since
     * they are retrived with get_options('profile')) or "newsletter_subscription_fields" or
     * "newsletter_subscription_form".
     *
     * @param array $options
     * @param string $sub
     */
    function save_options($options, $sub = '') {
        if ($sub == '') {
            // For compatibility the options are wrongly named
            return update_option('newsletter', $options);
        }
        if ($sub == 'profile') {
            return update_option('newsletter_profile', $options);
        }
        return parent::save_options($options, $sub);
    }

    function get_options($sub = '') {
        if ($sub == '') {
            // For compatibility the options are wrongly named
            $options = get_option('newsletter', array());
            if (!is_array($options))
                $options = array();
            return $options;
        }
        if ($sub == 'profile') {
            // For compatibility the options are wrongly named
            return get_option('newsletter_profile', array());
        }
        return parent::get_options($sub);
    }

    function set_updated($user, $time = 0, $ip = '') {
        global $wpdb;
        if (!$time)
            $time = time();

        if (!$ip)
            $ip = $_SERVER['REMOTE_ADDR'];

        if (is_object($user))
            $id = $user->id;
        else if (is_array($user))
            $id = $user['id'];

        $id = (int) $id;

        $wpdb->update(NEWSLETTER_USERS_TABLE, array('updated' => $time, 'ip' => $ip), array('id' => $id));
    }

    /**
     * Return the subscribed user.
     *
     * @param bool $registration If invoked from the registration process
     * @global Newsletter $newsletter
     */
    function subscribe($status = null, $emails = true) {
        $newsletter = Newsletter::instance();

        // Messages
        $options = get_option('newsletter', array());

        // Form field configuration
        $options_profile = get_option('newsletter_profile', array());

        $opt_in = (int) $this->options['noconfirmation']; // 0 - double, 1 - single
        if (isset($_REQUEST['optin'])) {
            switch ($_REQUEST['optin']) {
                case 'single': $opt_in = 1;
                    break;
                case 'double': $opt_in = 0;
                    break;
            }
        }

        $email = $newsletter->normalize_email(stripslashes($_REQUEST['ne']));

        // Shound never reach this point without a valid email address
        if ($email == null) {
            die('Wrong email');
        }

        $user = $newsletter->get_user($email);

        if ($user != null) {
            $this->logger->info('Subscription of an address with status ' . $user->status);

            // Bounced
            if ($user->status == 'B') {
                // Non persistent status to decide which message to show (error)
                $user->status = 'E';
                return $user;
            }

            // If asked to put in confirmed status, do not check further
            if ($status != 'C' && $opt_in == 0) {

                // Already confirmed
                //if ($opt_in == 0 && $user->status == 'C') {
                if ($user->status == 'C') {

                    set_transient($user->id . '-' . $user->token, $_REQUEST, 3600 * 48);
                    $this->set_updated($user);

                    // A second subscription always require confirmation otherwise anywan can change other users' data
                    $user->status = 'S';

                    $prefix = 'confirmation_';

                    if (empty($options[$prefix . 'disabled'])) {
                        $message = $options[$prefix . 'message'];

                        // TODO: This is always empty!
                        //$message_text = $options[$prefix . 'message_text'];
                        $subject = $options[$prefix . 'subject'];
                        $message = $this->add_microdata($message);
                        $this->mail($user->email, $newsletter->replace($subject, $user), $newsletter->replace($message, $user));
                    }

                    return $user;
                }
            }
        }

        if ($user != null) {
            $this->logger->info("Email address subscribed but not confirmed");
            $user = array('id' => $user->id);
        } else {
            $this->logger->info("New email address");
            $user = array('email' => $email);
        }

        $user = $this->update_user_from_request($user);

        $user['token'] = $newsletter->get_token();
        $user['ip'] = $_SERVER['REMOTE_ADDR'];
        if ($status != null) {
            $user['status'] = $status;
        } else {
            $user['status'] = $opt_in == 1 ? 'C' : 'S';
        }

        $user['updated'] = time();

        $user = apply_filters('newsletter_user_subscribe', $user);
        // TODO: should be removed!!!
        if (defined('NEWSLETTER_FEED_VERSION')) {
            $options_feed = get_option('newsletter_feed', array());
            if ($options_feed['add_new'] == 1) {
                $user['feed'] = 1;
            }
        }

        $user = $this->save_user($user);

        // Notification to admin (only for new confirmed subscriptions)
        if ($user->status == 'C') {
            do_action('newsletter_user_confirmed', $user);
            $this->notify_admin($user, 'Newsletter subscription');
            setcookie('newsletter', $user->id . '-' . $user->token, time() + 60 * 60 * 24 * 365, '/');
        }

        if (!$emails) {
            return $user;
        }

        $prefix = ($user->status == 'C') ? 'confirmed_' : 'confirmation_';

        if (empty($options[$prefix . 'disabled'])) {
            $message = $options[$prefix . 'message'];

            if ($user->status == 'S') {
                $message = $this->add_microdata($message);
            }

            // TODO: This is always empty!
            //$message_text = $options[$prefix . 'message_text'];
            $subject = $options[$prefix . 'subject'];

            $this->mail($user->email, $newsletter->replace($subject, $user), $newsletter->replace($message, $user));
        }
        return $user;
    }

    function add_microdata($message) {
        return $message . '<span itemscope itemtype="http://schema.org/EmailMessage"><span itemprop="description" content="Email address confirmation"></span><span itemprop="action" itemscope itemtype="http://schema.org/ConfirmAction"><meta itemprop="name" content="Confirm Subscription"><span itemprop="handler" itemscope itemtype="http://schema.org/HttpActionHandler"><meta itemprop="url" content="{subscription_confirm_url}"><link itemprop="method" href="http://schema.org/HttpRequestMethod/POST"></span></span></span>';
    }

    function update_user_from_request($user) {
        $newsletter = Newsletter::instance();
        $options = get_option('newsletter', array());

        $options_profile = get_option('newsletter_profile', array());
        if (isset($_REQUEST['nn'])) {
            $user['name'] = $this->normalize_name(stripslashes($_REQUEST['nn']));
        }
        // TODO: required checking

        if (isset($_REQUEST['ns'])) {
            $user['surname'] = $this->normalize_name(stripslashes($_REQUEST['ns']));
        }
        // TODO: required checking

        if (!empty($_REQUEST['nx'])) {
            $user['sex'] = $this->normalize_sex($_REQUEST['nx'][0]);
        }
        // TODO: valid values check

        if (isset($_REQUEST['nr'])) {
            $user['referrer'] = strip_tags(trim($_REQUEST['nr']));
        }

        // From the antibot form
        if (isset($_REQUEST['nhr'])) {
            $user['http_referer'] = strip_tags(trim($_REQUEST['nhr']));
        } else if (isset($_SERVER['HTTP_REFERER'])) {
            $user['http_referer'] = strip_tags(trim($_SERVER['HTTP_REFERER']));
        }

        if (strlen($user['http_referer']) > 200)
            $user['http_referer'] = substr($user['http_referer'], 0, 200);

        // New profiles
        for ($i = 1; $i <= NEWSLETTER_PROFILE_MAX; $i++) {
            // If the profile cannot be set by  subscriber, skip it.
            if ($options_profile['profile_' . $i . '_status'] == 0) {
                continue;
            }

            $user['profile_' . $i] = trim(stripslashes($_REQUEST['np' . $i]));
        }

        // Preferences (field names are nl[] and values the list number so special forms with radio button can work)
        if (isset($_REQUEST['nl']) && is_array($_REQUEST['nl'])) {
            $this->logger->debug($_REQUEST['nl']);
            for ($i = 1; $i <= NEWSLETTER_LIST_MAX; $i++) {
                // If not zero it is selectable by user (on subscription or on profile)
                if ($options_profile['list_' . $i . '_status'] == 0) {
                    continue;
                }
                if (in_array($i, $_REQUEST['nl'])) {
                    $user['list_' . $i] = 1;
                }
            }
        } else {
            $this->logger->debug('No preferences received');
        }

        // Forced preferences as set on subscription configuration
        for ($i = 1; $i <= NEWSLETTER_LIST_MAX; $i++) {
            if (empty($options['preferences_' . $i])) {
                continue;
            }
            $user['list_' . $i] = 1;
        }

        // TODO: should be removed!!!
        if (defined('NEWSLETTER_FEED_VERSION')) {
            $options_feed = get_option('newsletter_feed', array());
            if ($options_feed['add_new'] == 1) {
                $user['feed'] = 1;
            }
        }
        return $user;
    }

    /**
     * Send emails during the subscription process. Emails are themes with email.php file.
     * @global type $newsletter
     * @return type
     */
    function mail($to, $subject, $message) {
        $options_template = $this->get_options('template');
        // If the template setup on administrative panel is enabled, use it, if not
        // use the default old templating system.
        if (!empty($options_template['enabled'])) {
            $template = trim($options_template['template']);
            if (empty($template) || strpos($template, '{message}') === false) {
                $template = '{message}';
            }
            $message = str_replace('{message}', $message, $template);
        } else {
            ob_start();
            include NEWSLETTER_DIR . '/subscription/email.php';
            $message = ob_get_clean();
        }

        $headers = array('Auto-Submitted' => 'auto-generated');

        $message = Newsletter::instance()->replace($message);
        return Newsletter::instance()->mail($to, $subject, $message, $headers);
    }

    /**
     *
     * @global Newsletter $newsletter
     * @param type $user
     * @return stdClass
     */
    function confirm($user_id = null, $emails = true) {
        $newsletter = Newsletter::instance();
        if ($user_id == null) {
            $user = $this->get_user_from_request();
            if ($user) {
                $data = get_transient($user->id . '-' . $user->token);
                if ($data !== false) {
                    $_REQUEST = $data;
                    // Update the user profile since it's now confirmed
                    $user = $this->update_user_from_request((array) $user);
                    $user = $this->save_user($user);
                    delete_transient($user->id . '-' . $user->token);
                    // Forced a fake status so the welcome email is sent
                    $user->status = 'S';
                }
            }
        } else {
            $user = $newsletter->get_user($user_id);
        }

        if ($user == null) {
            $this->logger->debug('Not found');
            die('No subscriber found.');
        }

        setcookie('newsletter', $user->id . '-' . $user->token, time() + 60 * 60 * 24 * 365, '/');

        if ($user->status == 'C') {
            do_action('newsletter_user_confirmed', $user);
            return $user;
        }

//        if ($user->status != 'S') {
//            $this->logger->debug('Was not in status S');
//            $user->status = 'E';
//            return $user;
//        }

        $this->set_user_status($user->id, 'C');
        $user->status = 'C';
        do_action('newsletter_user_confirmed', $user);
        $this->notify_admin($user, 'Newsletter subscription');

        if (!$emails) {
            return $user;
        }

        $this->send_message('confirmed', $user);

        return $user;
    }

    function send_message($type, $user) {
        if (empty($this->options[$type . '_disabled'])) {
            $message = $this->options[$type . '_message'];
            //$message_text = $this->options[$type . '_message_text'];
            $subject = $this->options[$type . '_subject'];

            $this->mail($user->email, $this->replace($subject, $user), $this->replace($message, $user));
        }
    }

    /**
     * Returns the unsubscribed user.
     *
     * @global type $newsletter
     * @return type
     */
    function unsubscribe() {
        $newsletter = Newsletter::instance();
        $user = $this->get_user_from_request(true);

        if ($user->status == 'U') {
            return $user;
        }

        $newsletter->set_user_status($user->id, 'U');
        do_action('newsletter_unsubscribed', $user);

        global $wpdb;
        if (isset($_REQUEST['nek'])) {
            list($email_id, $email_token) = explode('-', $_REQUEST['nek']);
            $wpdb->update(NEWSLETTER_USERS_TABLE, array('unsub_email_id' => (int) $email_id, 'unsub_time' => time()), array('id' => $user->id));
        }

        if (empty($this->options['unsubscribed_disabled'])) {
            $this->mail($user->email, $newsletter->replace($this->options['unsubscribed_subject'], $user), $newsletter->replace($this->options['unsubscribed_message'], $user));
        }
        $this->notify_admin($user, 'Newsletter unsubscription');
    }

    /**
     * Saves the subscriber data.
     * 
     * @return type
     */
    function save_profile() {
global $wpdb;
        // Get the current subscriber
        $user = $this->get_user_from_request(true);

        $options_profile = get_option('newsletter_profile', array());
        $options_main = get_option('newsletter_main', array());

        if (!$this->is_email($_REQUEST['ne'])) {
            $user->alert = $this->options['profile_error'];
            return $user;
        }

        $email = $this->normalize_email(stripslashes($_REQUEST['ne']));
        $email_changed = ($email != $user->email);

        // If the email has been changed, check if it is available
        if ($email_changed) {
            $tmp = $this->get_user($email);
            if ($tmp != null && $tmp->id != $user->id) {
                $user->alert = $this->options['profile_error'];
                return $user;
            }
        }

        // General data
        $data['email'] = $email;
        $data['name'] = $this->normalize_name(stripslashes($_REQUEST['nn']));
        $data['surname'] = $this->normalize_name(stripslashes($_REQUEST['ns']));
        if ($options_profile['sex_status'] >= 1) {
            $data['sex'] = $_REQUEST['nx'][0];
            // Wrong data injection check
            if ($data['sex'] != 'm' && $data['sex'] != 'f' && $data['sex'] != 'n') {
                die('Wrong sex field');
            }
        }

        // Lists. If not list is present or there is no list to choose or all are unchecked.
        $nl = $_REQUEST['nl'];
        if (!is_array($nl)) {
            $nl = array();
        }

        // For each preference which an be edited (and so is present on profile form)...
        $lists_changed = false;
        $changed = array('old'=>array(), 'new'=>array());
        for ($i = 1; $i <= NEWSLETTER_LIST_MAX; $i++) {
            if ($options_profile['list_' . $i . '_status'] == 0) {
                continue;
            }
            $field_name = 'list_' . $i;
            $data[$field_name] = in_array($i, $nl) ? 1 : 0;
            if ($data[$field_name] != $user->$field_name) {
                $lists_changed = true;
                $changed['old'][$field_name] = $user->$field_name;
                $changed['new'][$field_name] = $data[$field_name];
            }
        }

        // Profile
        for ($i = 1; $i <= NEWSLETTER_PROFILE_MAX; $i++) {
            if ($options_profile['profile_' . $i . '_status'] == 0) {
                continue;
            }
            $data['profile_' . $i] = stripslashes($_REQUEST['np' . $i]);
        }

        $data['id'] = $user->id;

        // Feed by Mail service is saved here
        $data = apply_filters('newsletter_profile_save', $data);
        
        // Log the previous user data as a for of consensus if he changed the lists
        if ($lists_changed) {
            $this->store->save($wpdb->prefix . 'newsletter_user_logs', array('user_id'=>$user->id, 'created'=>time(), 'data'=> json_encode($changed)));
        }

        $user = $this->save_user($data);

        // Email has been changed? Are we using double opt-in?
        $opt_in = (int) $this->options['noconfirmation'];
        //die($opt_in);
        if ($opt_in == 0 && $email_changed) {
            $data['status'] = 'S';
            if (empty($this->options['confirmation_disabled'])) {
                $message = $this->options['confirmation_message'];
                $subject = $this->options['confirmation_subject'];
                $res = $this->mail($user->email, $this->replace($subject, $user), $this->replace($message, $user));
                $alert = $this->options['profile_email_changed'];
            }
        }

        if (isset($alert)) {
            $user->alert = $alert;
        } else {
            $user->alert = $this->options['profile_saved'];
        }
        return $user;
    }

    /**
     * Finds the right way to show the message identified by $key (welcome, unsubscription, ...) redirecting the user to the
     * WordPress page or loading the configured url or activating the standard page.
     */
    function show_message($key, $user, $alert = '', $email = null) {
        $newsletter = Newsletter::instance();

        if (!is_object($user)) {
            if (is_array($user))
                $user = (object) $user;
            else {
                $user = $newsletter->get_user($user);
            }
        }

        $params = '';

        if (!empty($alert)) {
            $params .= '&alert=' . urlencode($alert);
        }

        if (isset($_REQUEST['ncu'])) {
            $this->options['confirmation_url'] = esc_url($_REQUEST['ncu']);
            $this->options['confirmed_url'] = esc_url($_REQUEST['ncu']);
        }

        if ($email) {
            $params .= '&nek=' . $email->id . '-' . $email->token;
        }

        // Add exceptions for "profile" key.
        // Is there a custom url?
        if (!empty($this->options[$key . '_url'])) {
            header('Location: ' . self::add_qs($this->options[$key . '_url'], 'nk=' . $user->id . '-' . $user->token, false) . $params);
            die();
        }

        if (!empty($this->options['page'])) {
            $url = get_permalink($this->options['page']);
        }

        // Old URL
        if (empty($url) && !empty($this->options['url'])) {
            $url = $this->options['url'];
        }

        if (empty($url)) {
            $url = home_url('/') . '?na=m';
        }

        header('Location: ' . self::add_qs($url, 'nm=' . $key . '&nk=' . $user->id . '-' . $user->token, false) . $params);
        die();
    }

    function get_email_from_request() {
        $newsletter = Newsletter::instance();

        if (isset($_REQUEST['nek'])) {
            list($id, $token) = @explode('-', $_REQUEST['nek'], 2);
        } else {
            return null;
        }
        $email = $newsletter->get_email($id);

        return $email;
    }

    function get_message_key_from_request() {
        if (empty($_GET['nm'])) {
            return 'subscription';
        }
        $key = $_GET['nm'];
        switch ($key) {
            case 's': return 'confirmation';
            case 'c': return 'confirmed';
            case 'u': return 'unsubscription';
            case 'uc': return 'unsubscribed';
            case 'p':
            case 'pe':
                return 'profile';
            default: return $key;
        }
    }

    function get_form_javascript() {
        $options_profile = get_option('newsletter_profile');
        if (!isset($options_profile['profile_error']))
            $options_profile['profile_error'] = '';
        $buffer = "\n\n";
        $buffer .= '<script type="text/javascript">' . "\n";
        $buffer .= '//<![CDATA[' . "\n";
        $buffer .= 'if (typeof newsletter_check !== "function") {' . "\n";
        $buffer .= 'window.newsletter_check = function (f) {' . "\n";
        $buffer .= '    var re = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-]{1,})+\.)+([a-zA-Z0-9]{2,})+$/;' . "\n";
        $buffer .= '    if (!re.test(f.elements["ne"].value)) {' . "\n";
        $buffer .= '        alert("' . addslashes($options_profile['email_error']) . '");' . "\n";
        $buffer .= '        return false;' . "\n";
        $buffer .= '    }' . "\n";
        if ($options_profile['name_status'] == 2 && $options_profile['name_rules'] == 1) {
            $buffer .= '    if (f.elements["nn"] && (f.elements["nn"].value == "" || f.elements["nn"].value == f.elements["nn"].defaultValue)) {' . "\n";
            $buffer .= '        alert("' . addslashes($options_profile['name_error']) . '");' . "\n";
            $buffer .= '        return false;' . "\n";
            $buffer .= '    }' . "\n";
        }
        if ($options_profile['surname_status'] == 2 && $options_profile['surname_rules'] == 1) {
            $buffer .= '    if (f.elements["ns"] && (f.elements["ns"].value == "" || f.elements["ns"].value == f.elements["ns"].defaultValue)) {' . "\n";
            $buffer .= '        alert("' . addslashes($options_profile['surname_error']) . '");' . "\n";
            $buffer .= '        return false;' . "\n";
            $buffer .= '    }' . "\n";
        }
        $buffer .= '    for (var i=1; i<' . NEWSLETTER_PROFILE_MAX . '; i++) {' . "\n";
        $buffer .= '    if (f.elements["np" + i] && f.elements["np" + i].required && f.elements["np" + i].value == "") {' . "\n";
        $buffer .= '        alert("' . addslashes($options_profile['profile_error']) . '");' . "\n";
        $buffer .= '        return false;' . "\n";
        $buffer .= '    }' . "\n";
        $buffer .= '    }' . "\n";

        $buffer .= '    if (f.elements["ny"] && !f.elements["ny"].checked) {' . "\n";
        $buffer .= '        alert("' . addslashes($options_profile['privacy_error']) . '");' . "\n";
        $buffer .= '        return false;' . "\n";
        $buffer .= '    }' . "\n";
        $buffer .= '    return true;' . "\n";
        $buffer .= '}' . "\n";
        $buffer .= '}' . "\n";
        $buffer .= '//]]>' . "\n";
        $buffer .= '</script>' . "\n\n";
        return $buffer;
    }

    function shortcode_subscription($attrs, $content) {
        if (!is_array($attrs)) {
            $attrs = array();
        }

        $attrs = array_merge(array('class' => 'newsletter', 'style' => ''), $attrs);

        $options_profile = get_option('newsletter_profile');
        $action = esc_attr(home_url('/') . '?na=s');
        $class = esc_attr($attrs['class']);
        $style = esc_attr($attrs['style']);
        $buffer = '<form method="post" action="' . $action . '" class="' . $class . '" style="' . $style . '">' . "\n";

        if (isset($attrs['referrer'])) {
            $buffer .= '<input type="hidden" name="nr" value="' . esc_attr($referrer) . '">' . "\n";
        }

        if (isset($attrs['confirmation_url'])) {
            $buffer .= "<input type='hidden' name='ncu' value='" . esc_attr($attrs['confirmation_url']) . "'>\n";
        }

        if (isset($attrs['list'])) {
            $arr = explode(',', $attrs['list']);
            foreach ($arr as $a) {
                $buffer .= "<input type='hidden' name='nl[]' value='" . esc_attr(trim($a)) . "'>\n";
            }
        }

        //$content = str_replace("\r\n", "", $content);
        $buffer .= do_shortcode($content);

        if (isset($attrs['button_label'])) {
            $label = $attrs['button_label'];
        } else {
            $label = $options_profile['subscribe'];
        }

        if (!empty($label)) {
            $buffer .= '<div class="tnp-field tnp-field-button">';
            if (strpos($label, 'http') === 0) {
                $buffer .= '<input class="tnp-button-image" type="image" src="' . $label . '">';
            } else {
                $buffer .= '<input class="tnp-button" type="submit" value="' . $label . '">';
            }
            $buffer .= '</div>';
        }

        $buffer .= '</form>';

        return $buffer;
    }

    function _shortcode_label($name, $attrs, $suffix = null) {
        if (!$suffix) {
            $suffix = $name;
        }
        $options_profile = get_option('newsletter_profile');
        $buffer = '<label for="tnp-' . $suffix . '">';
        if (isset($attrs['label'])) {
            if (empty($attrs['label'])) {
                return;
            } else {
                $buffer .= esc_html($attrs['label']);
            }
        } else {
            $buffer .= esc_html($options_profile[$name]);
        }
        $buffer .= "</label>\n";
        return $buffer;
    }

    function shortcode_newsletter_field($attrs, $content) {
        $options_profile = get_option('newsletter_profile');
        $name = $attrs['name'];

        $buffer = '';

        if ($name == 'email') {
            $buffer .= '<div class="tnp-field tnp-field-email">';
            $buffer .= $this->_shortcode_label('email', $attrs);

            $buffer .= '<input class="tnp-email" type="email" name="ne" value=""';
            if (isset($attrs['placeholder']))
                $buffer .= ' placeholder="' . esc_attr($attrs['placeholder']) . '"';
            $buffer .= 'required>';
            if (isset($attrs['button_label'])) {
                $label = $attrs['button_label'];
                if (strpos($label, 'http') === 0) {
                    $buffer .= ' <input class="tnp-submit-image" type="image" src="' . esc_attr(esc_url_raw($label)) . '">';
                } else {
                    $buffer .= ' <input class="tnp-submit" type="submit" value="' . esc_attr($label) . '" style="width: 29%">';
                }
            }
            $buffer .= "</div>\n";
            return $buffer;
        }

        if ($name == 'first_name' || $name == 'name') {
            $buffer .= '<div class="tnp-field tnp-field-firstname">';
            $buffer .= $this->_shortcode_label('name', $attrs);

            $buffer .= '<input class="tnp-name" type="text" name="nn" value=""';
            if (isset($attrs['placeholder']))
                $buffer .= ' placeholder="' . esc_attr($attrs['placeholder']) . '"';
            if ($options_profile['name_rules'] == 1) {
                $buffer .= ' required';
            }
            $buffer .= '>';
            $buffer .= "</div>\n";
            return $buffer;
        }

        if ($name == 'last_name' || $name == 'surname') {
            $buffer .= '<div class="tnp-field tnp-field-surname">';
            $buffer .= $this->_shortcode_label('surname', $attrs);

            $buffer .= '<input class="tnp-surname" type="text" name="ns" value=""';
            if (isset($attrs['placeholder']))
                $buffer .= ' placeholder="' . esc_attr($attrs['placeholder']) . '"';
            if ($options_profile['surname_rules'] == 1) {
                $buffer .= ' required';
            }
            $buffer .= '>';
            $buffer .= '</div>';
            return $buffer;
        }

        if ($name == 'preference' || $name == 'list') {
            $list = (int) $attrs['number'];
            if (isset($attrs['hidden'])) {
                return '<input type="hidden" name="nl[]" value="' . esc_attr($list) . '">';
            }
            $buffer .= '<div class="tnp-field tnp-field-checkbox tnp-field-list">';
            $buffer .= '<input type="checkbox" id="nl' . esc_attr($list) . '" name="nl[]" value="' . esc_attr($list) . '"';
            if (isset($attrs['checked'])) {
                $buffer .= ' checked';
            }
            $buffer .= '>';
            if (isset($attrs['label'])) {
                if ($attrs['label'] != '')
                    $buffer .= '<label for="nl' . esc_attr($list) . '">' . esc_html($attrs['label']) . '</label>';
            } else {
                $buffer .= '<label for="nl' . esc_attr($list) . '">' . esc_html($options_profile['list_' . $list]) . '</label>';
            }
            $buffer .= "</div>\n";

            return $buffer;
        }

        // All the lists
        if ($name == 'lists' || $name == 'preferences') {
            $lists = '';
            for ($i = 1; $i <= NEWSLETTER_LIST_MAX; $i++) {
                if ($options_profile['list_' . $i . '_status'] != 2) {
                    continue;
                }
                $lists .= '<div class="tnp-field tnp-field-checkbox tnp-field-list">';
                $lists .= '<input type="checkbox" id="nl' . $i . '" name="nl[]" value="' . $i . '"';
                if ($options_profile['list_' . $i . '_checked'] == 1)
                    $lists .= ' checked';
                $lists .= '> <label for="nl' . $i . '>' . esc_html($options_profile['list_' . $i]) . '</label>';
                $lists .= "</div>\n";
            }
            return $lists;
        }

        // TODO: add the "not specified"
        if ($name == 'sex' || $name == 'gender') {
            $buffer .= '<div class="tnp-field tnp-field-gender">';
            if (isset($attrs['label'])) {
                if ($attrs['label'] != '')
                    $buffer .= '<label for="">' . esc_html($attrs['label']) . '</label>';
            } else {
                $buffer .= '<label for="">' . esc_html($options_profile['sex']) . '</label>';
            }

            $buffer .= '<select name="nx" class="tnp-gender">';
            $buffer .= '<option value="m">' . esc_html($options_profile['sex_male']) . '</option>';
            $buffer .= '<option value="f">' . esc_html($options_profile['sex_female']) . '</option>';
            $buffer .= '</select>';
            $buffer .= "</div>\n";
            return $buffer;
        }

        if ($name == 'profile' && isset($attrs['number'])) {
            $number = (int) $attrs['number'];
            $type = $options_profile['profile_' . $number . '_type'];
            $size = isset($attrs['size']) ? $attrs['size'] : '';
            $buffer .= '<div class="tnp-field tnp-field-profile">';
            if (isset($attrs['label'])) {
                if ($attrs['label'] != '') {
                    $buffer .= '<label>' . esc_html($attrs['label']) . '</label>';
                }
            } else {
                $buffer .= '<label>' . esc_html($options_profile['profile_' . $number]) . '</label>';
            }
            $placeholder = isset($attrs['placeholder']) ? $attrs['placeholder'] : $options_profile['profile_' . $number . '_placeholder'];

            $required = $options_profile['profile_' . $number . '_rules'] == 1;

            // Text field
            if ($type == 'text') {
                $buffer .= '<input class="tnp-profile tnp-profile-' . $number . '" type="text" size="' . esc_attr($size) . '" name="np' . $number . '" placeholder="' . esc_attr($placeholder) . '"';
                if ($required) {
                    $buffer .= ' required';
                }
                $buffer .= '>';
            }

            // Select field
            if ($type == 'select') {
                $buffer .= '<select class="tnp-profile tnp-profile-' . $number . '" name="np' . $number . '"';
                if ($required) {
                    $buffer .= ' required';
                }
                $buffer .= '>';
                if (!empty($placeholder)) {
                    $buffer .= '<option value="">' . esc_html($placeholder) . '</option>';
                }
                $opts = explode(',', $options_profile['profile_' . $number . '_options']);
                for ($j = 0; $j < count($opts); $j++) {
                    $buffer .= '<option>' . esc_html(trim($opts[$j])) . '</option>';
                }
                $buffer .= "</select>\n";
            }

            $buffer .= "</div>\n";

            return $buffer;
        }

        if (strpos($name, 'privacy') === 0) {

            if (!isset($attrs['url'])) {
                $attrs['url'] = $options_profile['privacy_url'];
            }

            if (!isset($attrs['label'])) {
                $attrs['label'] = $options_profile['privacy_label'];
            }

            $buffer .= '<div class="tnp-field tnp-field-checkbox tnp-field-privacy">';

            $buffer .= '<input type="checkbox" name="ny" required class="tnp-privacy" id="tnp-privacy"> ';
            $buffer .= '<label for="tnp-privacy">';
            if (!empty($attrs['url'])) {
                $buffer .= '<a target="_blank" href="' . esc_attr($attrs['url']) . '">';
            }
            $buffer .= $attrs['label'];
            if (!empty($attrs['url'])) {
                $buffer .= '</a>';
            }
            $buffer .= '</label>';
            $buffer .= '</div>';

            return $buffer;
        }
    }

    /**
     * Returns the form html code for subscription.
     *
     * @return string The html code of the subscription form
     */
    function get_subscription_form($referrer = null, $action = null, $attrs = array()) {
        return $this->get_subscription_form_html5($referrer, $action, $attrs);
    }

    /**
     * The new standard form.
     * 
     * @param type $referrer
     * @param type $action
     * @param type $attrs
     * @return string
     */
    function get_subscription_form_html5($referrer = null, $action = null, $attrs = array()) {
        if (isset($attrs['action'])) {
            $action = $attrs['action'];
        }
        if (isset($attrs['referrer'])) {
            $referrer = $attrs['referrer'];
        }

        $options_profile = get_option('newsletter_profile');
        $options = get_option('newsletter');

        $buffer = '';

        if (empty($action)) {
            $action = home_url('/') . '?na=s';
        }

        if ($referrer != 'widget') {
            if (isset($attrs['class'])) {
                $buffer .= '<div class="tnp tnp-subscription ' . $attrs['class'] . '">' . "\n";
            } else {
                $buffer .= '<div class="tnp tnp-subscription">' . "\n";
            }
        }
        $buffer .= '<form method="post" action="' . esc_attr($action) . '" onsubmit="return newsletter_check(this)">' . "\n\n";

        if (!empty($referrer)) {
            $buffer .= '<input type="hidden" name="nr" value="' . esc_attr($referrer) . '">' . "\n";
        }
        if (isset($attrs['confirmation_url'])) {
            $buffer .= "<input type='hidden' name='ncu' value='" . esc_attr($attrs['confirmation_url']) . "'>\n";
        }

        if (isset($attrs['lists']))
            $attrs['list'] = $attrs['lists'];
        if (isset($attrs['list'])) {
            $arr = explode(',', $attrs['list']);
            foreach ($arr as $a) {
                $buffer .= "<input type='hidden' name='nl[]' value='" . ((int) trim($a)) . "'>\n";
            }
        }

        if ($options_profile['name_status'] == 2) {
            $buffer .= '<div class="tnp-field tnp-field-firstname"><label>' . esc_html($options_profile['name']) . '</label>';
            $buffer .= '<input class="tnp-firstname" type="text" name="nn" ' . ($options_profile['name_rules'] == 1 ? 'required' : '') . '></div>';
            $buffer .= "\n";
        }

        if ($options_profile['surname_status'] == 2) {
            $buffer .= '<div class="tnp-field tnp-field-lastname"><label>' . esc_html($options_profile['surname']) . '</label>';
            $buffer .= '<input class="tnp-lastname" type="text" name="ns" ' . ($options_profile['surname_rules'] == 1 ? 'required' : '') . '></div>';
            $buffer .= "\n";
        }

        $buffer .= '<div class="tnp-field tnp-field-email"><label>' . esc_html($options_profile['email']) . '</label>';
        $buffer .= '<input class="tnp-email" type="email" name="ne" required></div>';
        $buffer .= "\n";

        if (isset($options_profile['sex_status']) && $options_profile['sex_status'] == 2) {
            $buffer .= '<div class="tnp-field tnp-field-gender"><label>' . esc_html($options_profile['sex']) . '</label>';
            $buffer .= '<select name="nx" class="tnp-gender"';
            if ($options_profile['sex_rules'] == 1) {
                $buffer .= ' required><option value=""></option>';
            } else {
                $buffer .= '><option value="n">' . esc_html($options_profile['sex_none']) . '</option>';
            }
            $buffer .= '<option value="m">' . esc_html($options_profile['sex_male']) . '</option>';
            $buffer .= '<option value="f">' . esc_html($options_profile['sex_female']) . '</option>';
            $buffer .= '</select></div>';
            $buffer .= "\n";
        }

        $lists = '';
        if (!empty($attrs['lists_field_layout']) && $attrs['lists_field_layout'] == 'dropdown') {
            for ($i = 1; $i <= NEWSLETTER_LIST_MAX; $i++) {
                if ($options_profile['list_' . $i . '_status'] != 2) {
                    continue;
                }
                $lists .= '<option value="' . $i . '"';
                if ($options_profile['list_' . $i . '_checked'] == 1) {
                    $lists .= ' selected';
                }
                $lists .= '>' . esc_html($options_profile['list_' . $i]) . '</option>';
                $lists .= "\n";
            }
            if (!empty($attrs['lists_field_empty_label'])) {
                $lists = '<option value="">' . $attrs['lists_field_empty_label'] . '</option>' . $lists;
            }
            if (!empty($lists)) {
                $lists = '<select class="tnp-lists" name="nl[]" required>' . $lists . '</select>';
            }
        } else {
            for ($i = 1; $i <= NEWSLETTER_LIST_MAX; $i++) {
                if ($options_profile['list_' . $i . '_status'] != 2) {
                    continue;
                }
                $lists .= '<div class="tnp-field tnp-field-list"><label><input class="tnp-preference" type="checkbox" name="nl[]" value="' . $i . '"';
                if ($options_profile['list_' . $i . '_checked'] == 1) {
                    $lists .= ' checked';
                }
                $lists .= '/>&nbsp;' . esc_html($options_profile['list_' . $i]) . '</label></div>';
            }
        }

        if (!empty($lists)) {
            $buffer .= '<div class="tnp-field tnp-field-lists">';
            if (!empty($attrs['lists_field_label'])) {
                $buffer .= '<label>' . $attrs['lists_field_label'] . '</label>';
            }
            $buffer .= $lists . '</div>';
        }

        // Extra profile fields
        for ($i = 1; $i <= NEWSLETTER_PROFILE_MAX; $i++) {
            // Not for subscription form
            if ($options_profile['profile_' . $i . '_status'] != 2) {
                continue;
            }


            $buffer .= '<div class="tnp-field tnp-field-profile"><label>' .
                    esc_html($options_profile['profile_' . $i]) . '</label>';

            // Text field                
            if ($options_profile['profile_' . $i . '_type'] == 'text') {
                $buffer .= '<input class="tnp-profile tnp-profile-' . $i . '" type="text"' . ($options_profile['profile_' . $i . '_rules'] == 1 ? ' required' : '') . ' name="np' . $i . '">';
            }

            // Select field
            if ($options_profile['profile_' . $i . '_type'] == 'select') {
                $buffer .= '<select class="tnp-profile tnp-profile-' . $i . '" name="np' . $i . '" required>' . "\n";
                $buffer .= "<option></option>\n";
                $opts = explode(',', $options_profile['profile_' . $i . '_options']);
                for ($j = 0; $j < count($opts); $j++) {
                    $buffer .= "<option>" . esc_html(trim($opts[$j])) . "</option>\n";
                }
                $buffer .= "</select>\n";
            }
            $buffer .= '</div>';
        }

        $extra = apply_filters('newsletter_subscription_extra', array());
        foreach ($extra as $x) {
            $label = $x['label'];
            if (empty($label)) {
                $label = '&nbsp;';
            }
            $name = '';
            if (!empty($x['name'])) {
                $name = $x['name'];
            }
            $buffer .= '<div class="tnp-field tnp-field-' . $name . '"><label>' . $label . "</label>";
            $buffer .= $x['field'] . "</div>\n";
        }
        
        $privacy_status = (int)$options_profile['privacy_status'];

        if ($privacy_status === 1 || $privacy_status === 2) {
            $buffer .= '<div class="tnp-field tnp-field-privacy">';
            $buffer .= '<label>';
            if ($privacy_status === 1) {
                $buffer .= '<input type="checkbox" name="ny" required class="tnp-privacy">&nbsp;';
            }
            if (!empty($options_profile['privacy_url'])) {
                $buffer .= '<a target="_blank" href="' . esc_attr($options_profile['privacy_url']) . '">';
                $buffer .= esc_attr($options_profile['privacy']) . '</a>';
            } else {
                $buffer .= esc_html($options_profile['privacy']);
            }

            $buffer .= "</label></div>\n";
        } 

        $buffer .= '<div class="tnp-field tnp-field-button">';

        if (strpos($options_profile['subscribe'], 'http://') !== false) {
            $buffer .= '<input class="tnp-submit-image" type="image" src="' . esc_attr($options_profile['subscribe']) . '">' . "\n";
        } else {
            $buffer .= '<input class="tnp-submit" type="submit" value="' . esc_attr($options_profile['subscribe']) . '">' . "\n";
        }

        $buffer .= "</div>\n</form>\n";
        if ($referrer != 'widget') {
            $buffer .= "</div>\n";
        }
        return $buffer;
    }

    /**
     * Generate the profile editing form.
     */
    function get_profile_form($user) {
        $options = get_option('newsletter_profile');

        $buffer = '';

        $buffer .= '<div class="tnp-profile">';
        $buffer .= '<form action="' . esc_attr(home_url('/') . '?na=ps') . '" method="post" onsubmit="return newsletter_check(this)">';
        // TODO: use nk
        $buffer .= '<input type="hidden" name="nk" value="' . esc_attr($user->id . '-' . $user->token) . '">';
        $buffer .= '<table cellspacing="0" cellpadding="3" border="0">';
        $buffer .= '<tr><th align="right">' . esc_html($options['email']) . '</th><td><input class="tnp-email" type="text" size="30" name="ne" required value="' . esc_attr($user->email) . '"></td></tr>';
        if ($options['name_status'] >= 1) {
            $buffer .= '<tr><th align="right">' . esc_html($options['name']) . '</th><td><input class="tnp-firstname" type="text" size="30" name="nn" value="' . esc_attr($user->name) . '"></td></tr>';
        }
        if ($options['surname_status'] >= 1) {
            $buffer .= '<tr><th align="right">' . esc_html($options['surname']) . '</th><td><input class="tnp-lastname" type="text" size="30" name="ns" value="' . esc_attr($user->surname) . '"></td></tr>';
        }
        if ($options['sex_status'] >= 1) {
            $buffer .= '<tr><th align="right">' . esc_html($options['sex']) . '</th><td><select name="nx" class="tnp-gender">';
            $buffer .= '<option value="f"' . ($user->sex == 'f' ? ' selected' : '') . '>' . esc_html($options['sex_female']) . '</option>';
            $buffer .= '<option value="m"' . ($user->sex == 'm' ? ' selected' : '') . '>' . esc_html($options['sex_male']) . '</option>';
            $buffer .= '<option value="n"' . ($user->sex == 'n' ? ' selected' : '') . '>' . esc_html($options['sex_none']) . '</option>';
            $buffer .= '</select></td></tr>';
        }

        // Profile
        for ($i = 1; $i <= NEWSLETTER_PROFILE_MAX; $i++) {
            if ($options['profile_' . $i . '_status'] == 0)
                continue;

            $buffer .= '<tr><th align="right">' . esc_html($options['profile_' . $i]) . '</th><td>';

            $field = 'profile_' . $i;

            if ($options['profile_' . $i . '_type'] == 'text') {
                $buffer .= '<input class="tnp-profile tnp-profile-' . $i . '" type="text" size="50" name="np' . $i . '" value="' . esc_attr($user->$field) . '"/>';
            }

            if ($options['profile_' . $i . '_type'] == 'select') {
                $buffer .= '<select class="tnp-profile tnp-profile-' . $i . '" name="np' . $i . '">';
                $opts = explode(',', $options['profile_' . $i . '_options']);
                for ($j = 0; $j < count($opts); $j++) {
                    $opts[$j] = trim($opts[$j]);
                    $buffer .= '<option';
                    if ($opts[$j] == $user->$field)
                        $buffer .= ' selected';
                    $buffer .= '>' . esc_html($opts[$j]) . '</option>';
                }
                $buffer .= '</select>';
            }

            $buffer .= '</td></tr>';
        }

        // Lists
        $buffer .= '<tr><th>&nbsp;</th><td style="text-align: left"><div class="tnp-lists">';
        for ($i = 1; $i <= NEWSLETTER_LIST_MAX; $i++) {
            if ($options['list_' . $i . '_status'] == 0) {
                continue;
            }
            $buffer .= '<input class="tnp-list" type="checkbox" name="nl[]" value="' . $i . '"';
            $list = 'list_' . $i;
            if ($user->$list == 1) {
                $buffer .= ' checked';
            }
            $buffer .= '/>&nbsp;<span class="tnp-list-label">' . esc_html($options['list_' . $i]) . '</span><br />';
        }
        $buffer .= '</div></td></tr>';

        $extra = apply_filters('newsletter_profile_extra', array(), $user);
        foreach ($extra as &$x) {
            $buffer .= "<tr>\n\t<th>" . $x['label'] . "</th>\n\t<td>\n\t\t";
            $buffer .= $x['field'] . "\n\t</td>\n</tr>\n\n";
        }

        $buffer .= '<tr><td colspan="2" class="tnp-td-submit">';

        if (strpos($options['save'], 'http://') !== false) {
            $buffer .= '<input class="tnp-submit-image" type="image" src="' . esc_attr($options['save']) . '"></td></tr>';
        } else {
            $buffer .= '<input class="tnp-submit" type="submit" value="' . esc_attr($options['save']) . '"/></td></tr>';
        }

        $buffer .= '</table></form></div>';

        return $buffer;
    }

    function get_profile_form_html5($user) {
        $options = get_option('newsletter_profile');

        $buffer = '';

        $buffer .= '<div class="tnp tnp-profile">';
        $buffer .= '<form action="' . esc_attr(home_url('/') . '?na=ps') . '" method="post" onsubmit="return newsletter_check(this)">';
        $buffer .= '<input type="hidden" name="nk" value="' . esc_attr($user->id . '-' . $user->token) . '">';

        $buffer .= '<div class="tnp-field tnp-field-email">';
        $buffer .= '<label>' . esc_html($options['email']) . '</label>';
        $buffer .= '<input class="tnp-email" type="text" name="ne" required value="' . esc_attr($user->email) . '">';
        $buffer .= "</div>\n";


        if ($options['name_status'] >= 1) {
            $buffer .= '<div class="tnp-field tnp-field-firstname">';
            $buffer .= '<label>' . esc_html($options['name']) . '</label>';
            $buffer .= '<input class="tnp-firstname" type="text" name="nn" value="' . esc_attr($user->name) . '"' . ($options['name_rules'] == 1 ? ' required' : '') . '>';
            $buffer .= "</div>\n";
        }

        if ($options['surname_status'] >= 1) {
            $buffer .= '<div class="tnp-field tnp-field-lastname">';
            $buffer .= '<label>' . esc_html($options['surname']) . '</label>';
            $buffer .= '<input class="tnp-lastname" type="text" name="ns" value="' . esc_attr($user->surname) . '"' . ($options['surname_rules'] == 1 ? ' required' : '') . '>';
            $buffer .= "</div>\n";
        }

        if ($options['sex_status'] >= 1) {
            $buffer .= '<div class="tnp-field tnp-field-gender">';
            $buffer .= '<label>' . esc_html($options['sex']) . '</label>';
            $buffer .= '<select name="nx" class="tnp-gender">';
            $buffer .= '<option value="f"' . ($user->sex == 'f' ? ' selected' : '') . '>' . esc_html($options['sex_female']) . '</option>';
            $buffer .= '<option value="m"' . ($user->sex == 'm' ? ' selected' : '') . '>' . esc_html($options['sex_male']) . '</option>';
            $buffer .= '<option value="n"' . ($user->sex == 'n' ? ' selected' : '') . '>' . esc_html($options['sex_none']) . '</option>';
            $buffer .= '</select>';
            $buffer .= "</div>\n";
        }

        // Profile
        for ($i = 1; $i <= NEWSLETTER_PROFILE_MAX; $i++) {
            if ($options['profile_' . $i . '_status'] == 0) {
                continue;
            }

            $buffer .= '<div class="tnp-field tnp-field-profile">';
            $buffer .= '<label>' . esc_html($options['profile_' . $i]) . '</label>';

            $field = 'profile_' . $i;

            if ($options['profile_' . $i . '_type'] == 'text') {
                $buffer .= '<input class="tnp-profile tnp-profile-' . $i . '" type="text" name="np' . $i . '" value="' . esc_attr($user->$field) . '"' .
                        ($options['profile_' . $i . '_rules'] == 1 ? ' required' : '') . '>';
            }

            if ($options['profile_' . $i . '_type'] == 'select') {
                $buffer .= '<select class="tnp-profile tnp-profile-' . $i . '" name="np' . $i . '"' .
                        ($options['profile_' . $i . '_rules'] == 1 ? ' required' : '') . '>';
                $opts = explode(',', $options['profile_' . $i . '_options']);
                for ($j = 0; $j < count($opts); $j++) {
                    $opts[$j] = trim($opts[$j]);
                    $buffer .= '<option';
                    if ($opts[$j] == $user->$field)
                        $buffer .= ' selected';
                    $buffer .= '>' . esc_html($opts[$j]) . '</option>';
                }
                $buffer .= '</select>';
            }

            $buffer .= "</div>\n";
        }

        // Lists
        $lists = '';
        for ($i = 1; $i <= NEWSLETTER_LIST_MAX; $i++) {
            if ($options['list_' . $i . '_status'] == 0 || $options['list_' . $i . '_status'] == 3) {
                continue;
            }

            $lists .= '<div class="tnp-field tnp-field-list">';
            $lists .= '<label><input class="tnp-list tnp-list-' . $i . '" type="checkbox" name="nl[]" value="' . $i . '"';
            $field = 'list_' . $i;
            if ($user->$field == 1) {
                $lists .= ' checked';
            }
            $lists .= '><span class="tnp-list-label">' . esc_html($options['list_' . $i]) . '</span></label>';
            $lists .= "</div>\n";
        }

        if (!empty($lists)) {
            $buffer .= '<div class="tnp-lists">' . "\n" . $lists . "\n" . '</div>';
        }

        $extra = apply_filters('newsletter_profile_extra', array(), $user);
        foreach ($extra as $x) {
            $buffer .= '<div class="tnp-field">';
            $buffer .= '<label>' . $x['label'] . "</label>";
            $buffer .= $x['field'];
            $buffer .= "</div>\n";
        }

        $buffer .= '<div class="tnp-field tnp-field-button">';
        $buffer .= '<input class="tnp-submit" type="submit" value="' . esc_attr($options['save']) . '">';
        $buffer .= "</div>\n";

        $buffer .= "</form>\n</div>\n";

        return $buffer;
    }

    function get_form($number) {
        $options = get_option('newsletter_forms');

        $form = $options['form_' . $number];

        $form = do_shortcode($form);

        $action = home_url('/') . '?na=s';

        if (stripos($form, '<form') === false) {
            $form = '<form method="post" action="' . $action . '">' . $form . '</form>';
        }

        // For compatibility
        $form = str_replace('{newsletter_url}', $action, $form);

        $form = $this->replace_lists($form);

        return $form;
    }

    /** Replaces on passed text the special tag {lists} that can be used to show the preferences as a list of checkbox.
     * They are called lists but on configuration panel they are named preferences!
     *
     * @param string $buffer
     * @return string
     */
    function replace_lists($buffer) {
        $options_profile = get_option('newsletter_profile');
        $lists = '';
        for ($i = 1; $i <= NEWSLETTER_LIST_MAX; $i++) {
            if ($options_profile['list_' . $i . '_status'] != 2)
                continue;
            $lists .= '<input type="checkbox" name="nl[]" value="' . $i . '"/>&nbsp;' . $options_profile['list_' . $i] . '<br />';
        }
        $buffer = str_replace('{lists}', $lists, $buffer);
        $buffer = str_replace('{preferences}', $lists, $buffer);
        return $buffer;
    }

    function notify_admin($user, $subject) {

        if ($this->options['notify'] != 1) {
            return;
        }

        $message = "Subscriber details:\n\n" .
                "email: " . $user->email . "\n" .
                "first name: " . $user->name . "\n" .
                "last name: " . $user->surname . "\n" .
                "gender: " . $user->sex . "\n";

        $options_profile = get_option('newsletter_profile');

        for ($i = 0; $i < NEWSLETTER_LIST_MAX; $i++) {
            if (empty($options_profile['list_' . $i])) {
                continue;
            }
            $field = 'list_' . $i;
            $message .= $options_profile['list_' . $i] . ': ' . (empty($user->$field) ? "NO" : "YES") . "\n";
        }

        for ($i = 0; $i < NEWSLETTER_PROFILE_MAX; $i++) {
            if (empty($options_profile['profile_' . $i])) {
                continue;
            }
            $field = 'profile_' . $i;
            $message .= $options_profile['profile_' . $i] . ': ' . $user->$field . "\n";
        }



        $message .= "token: " . $user->token . "\n" .
                "status: " . $user->status . "\n";
        $email = trim($this->options['notify_email']);
        if (empty($email)) {
            $email = get_option('admin_email');
        }
        $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
        Newsletter::instance()->mail($email, '[' . $blogname . '] ' . $subject, array('text' => $message));
    }

    function get_subscription_form_minimal($attrs) {
        $options_profile = get_option('newsletter_profile');
        if (!is_array($attrs)) {
            $attrs = array();
        }
        $attrs = array_merge(array('class' => '', 'referrer' => 'minimal', 'button' => $options_profile['subscribe'], 'placeholder' => $options_profile['email']), $attrs);

        $form = '';
        $form .= '<div class="tnp tnp-subscription-minimal ' . $attrs['class'] . '">';
        $form .= '<form action="' . esc_attr(home_url('/')) . '?na=s" method="post">';
        $form .= '<input type="hidden" name="nr" value="' . esc_attr($attrs['referrer']) . '">';
        $form .= '<input class="tnp-email" type="email" required name="ne" value="" placeholder="' . esc_attr($attrs['placeholder']) . '">';
        $form .= '<input class="tnp-submit" type="submit" value="' . esc_attr($attrs['button']) . '">';
        $form .= "</form></div>\n";

        return $form;
    }

    function shortcode_newsletter_form($attrs, $content) {

        if (isset($attrs['type']) && $attrs['type'] == 'minimal') {
            return NewsletterSubscription::instance()->get_subscription_form_minimal($attrs);
        }

        if (!empty($content)) {
            return NewsletterSubscription::instance()->shortcode_subscription($attrs, $content);
        }
        if (isset($attrs['form'])) {
            return NewsletterSubscription::instance()->get_form((int) $attrs['form']);
        } else if (isset($attrs['number'])) {
            return NewsletterSubscription::instance()->get_form((int) $attrs['number']);
        } else {
            if (isset($attrs['layout']) && $attrs['layout'] == 'table') {
                return NewsletterSubscription::instance()->get_subscription_form(null, null, $attrs);
            } else {
                return NewsletterSubscription::instance()->get_subscription_form_html5(null, null, $attrs);
            }
        }
    }

    /**
     *
     * @global type $wpdb
     * @global boolean $cache_stop
     * @global Newsletter $newsletter
     * @param type $attrs
     * @param type $content
     * @return string
     */
    function shortcode_newsletter($attrs, $content) {
        global $wpdb, $cache_stop, $newsletter;

        $cache_stop = true;

        $module = NewsletterSubscription::instance();
        $user = $module->get_user_from_request();
        $message_key = $module->get_message_key_from_request();

//    if ($message_key != 'subscription' && $user == null) {
//        die('Invalid subscriber');
//    }


        $message = $module->options[$message_key . '_text'];

        // TODO: the if can be removed
        if ($message_key == 'confirmed') {
            $message .= $module->options[$message_key . '_tracking'];
        }

        // Now check what form must be added
        if ($message_key == 'subscription') {

            // Compatibility check
            if (stripos($message, '<form') !== false) {
                $message .= $module->get_form_javascript();
                $message = str_ireplace('<form', '<form method="post" action="' . esc_attr(home_url('/')) . '?na=s" onsubmit="return newsletter_check(this)"', $message);
            } else {

                if (strpos($message, '{subscription_form') === false) {
                    $message .= '{subscription_form}';
                }

                if (isset($attrs['form'])) {
                    $message = str_replace('{subscription_form}', $module->get_form($attrs['form']), $message);
                } else {
                    if (isset($attrs['layout']) && $attrs['layout'] == 'table') {
                        $message = str_replace('{subscription_form}', $module->get_subscription_form('page'), $message);
                    } else {
                        $message = str_replace('{subscription_form}', $module->get_subscription_form_html5('page'), $message);
                    }
                }
            }
        }

        $email = NewsletterSubscription::instance()->get_email_from_request();

        $message = $newsletter->replace($message, $user, $email, 'page');
        
        $message = do_shortcode($message);

        if (isset($_REQUEST['alert'])) {
            // slashes are already added by wordpress!
            $message .= '<script>alert("' . strip_tags($_REQUEST['alert']) . '");</script>';
        }

        return $message;
    }

}

NewsletterSubscription::instance();

// Compatibility code

function newsletter_form($number = null) {
    if ($number != null) {
        echo NewsletterSubscription::instance()->get_form($number);
    } else {
        echo NewsletterSubscription::instance()->get_subscription_form();
    }
}
