<?php

/*
 * TNP classes for internal API
 * 
 * Error reference
 * 404	Object not found
 * 403	Not allowed (when the API key is missing or wrong)
 * 400	Bad request, when the parameters are not correct or required parameters are missing
 * 
 */

/**
 * Main API functions
 *
 * @author roby
 */
class TNP {
    /*
     * The full process of subscription
     */

    public static function subscribe($params) {
        
//        error_reporting(E_ALL);
        
        $newsletter = Newsletter::instance();
        $subscription = NewsletterSubscription::instance();
        
        // Messages
        $options = get_option('newsletter', array());

        // Form field configuration
        $options_profile = get_option('newsletter_profile', array());

        $optin = (int) $newsletter->options['noconfirmation']; // 0 - double, 1 - single
        if (isset($params['optin'])) {
            switch ($params['optin']) {
                case 'single': $optin = 1;
                    break;
                case 'double': $optin = 0;
                    break;
            }
        }

        $email = $newsletter->normalize_email(stripslashes($params['email']));

        // Should never reach this point without a valid email address
        if ($email == null) {
            return new WP_Error('-1', 'Email address not valid', array('status' => 400));
        }

        $user = $newsletter->get_user($email);

        if ($user != null) {
            
            $newsletter->logger->info('Subscription of an address with status ' . $user->status);

            // Bounced
            if ($user->status == 'B') {
                return new WP_Error('-1', 'Bounced address', array('status' => 400));
            }

            // If asked to put in confirmed status, do not check further
            if ($params['status'] != 'C' && $optin == 0) {

                // Already confirmed
                //if ($optin == 0 && $user->status == 'C') {
                if ($user->status == 'C') {

                    set_transient($user->id . '-' . $user->token, $params, 3600 * 48);
                    $subscription->set_updated($user);

                    // A second subscription always require confirmation otherwise anywan can change other users' data
                    $user->status = 'S';

                    $prefix = 'confirmation_';

                    if (empty($options[$prefix . 'disabled'])) {
                        
                        $message = $options[$prefix . 'message'];
                        // TODO: This is always empty!
                        //$message_text = $options[$prefix . 'message_text'];
                        $subject = $options[$prefix . 'subject'];
                        $message = $subscription->add_microdata($message);
                        $newsletter->mail($user->email, $newsletter->replace($subject, $user), $newsletter->replace($message, $user));
                    }

                    return $user;
                }
            }
        }
        
        if ($user != null) {
            $newsletter->logger->info("Email address subscribed but not confirmed");
            $user = array('id' => $user->id);
        } else {
            $newsletter->logger->info("New email address");
            $user = array('email' => $email);
        }

        $user = TNP::add_subscriber($params);

//        TODO: decidere se applicare i filtri sulle API
//        $user = apply_filters('newsletter_user_subscribe', $user);

        if (is_wp_error($user)) {
            return ($user);
        }
        
        // Notification to admin (only for new confirmed subscriptions)
        if ($user->status == 'C') {
            do_action('newsletter_user_confirmed', $user);
            $subscription->notify_admin($user, 'Newsletter subscription');
            setcookie('newsletter', $user->id . '-' . $user->token, time() + 60 * 60 * 24 * 365, '/');
        }

        if (empty($params['send_emails']) || !$params['send_emails']) {
            return $user;
        }

        $prefix = ($user->status == 'C') ? 'confirmed_' : 'confirmation_';

        if (empty($options[$prefix . 'disabled'])) {
            $message = $options[$prefix . 'message'];

            if ($user->status == 'S') {
                $message = $newsletter->add_microdata($message);
            }

            // TODO: This is always empty!
            //$message_text = $options[$prefix . 'message_text'];
            $subject = $options[$prefix . 'subject'];

            $newsletter->mail($user->email, $newsletter->replace($subject, $user), $newsletter->replace($message, $user));
        }
        return $user;
        
    }

    /*
     * The UNsubscription
     */

    public static function unsubscribe($params) {

        $newsletter = Newsletter::instance();
        $user = $newsletter->get_user($params['email']);

//        $newsletter->logger->debug($params);

        if (!$user) {
            return new WP_Error('-1', 'Email address not found', array('status' => 404));
        }

        if ($user->status == 'U') {
            return $user;
        }

        $newsletter->set_user_status($user->id, 'U');

        if (empty($newsletter->options['unsubscribed_disabled'])) {
            $newsletter->mail($user->email, $newsletter->replace($newsletter->options['unsubscribed_subject'], $user), $newsletter->replace($newsletter->options['unsubscribed_message'], $user));
        }
        $newsletter->notify_admin($user, 'Newsletter unsubscription');

        return $user;
    }

    /*
     * Adds a subscriber if not already in
     */

    public static function add_subscriber($params) {

        $newsletter = Newsletter::instance();

        $email = $newsletter->normalize_email(stripslashes($params['email']));

        if (!$email) {
            return new WP_Error('-1', 'Email address not valid', array('status' => 400));
        }

        $user = $newsletter->get_user($email);

        if ($user) {
            return new WP_Error('-1', 'Email address already exists', array('status' => 400));
        }

        $user = array('email' => $email);

        if (isset($params['name'])) {
            $user['name'] = $newsletter->normalize_name(stripslashes($params['name']));
        }

        if (isset($params['surname'])) {
            $user['surname'] = $newsletter->normalize_name(stripslashes($params['surname']));
        }

        if (!empty($params['gender'])) {
            $user['gender'] = $newsletter->normalize_sex($params['gender']);
        }

        if (is_array($params['profile'])) {
            foreach ($params['profile'] as $key => $value) {
                $user['profile_' . $key] = trim(stripslashes($value));
            }
        }

        if (!empty($params['status'])) {
            $user['status'] = $params['status'];
        } else {
            $user['status'] = 'C';
        }

        $user['token'] = $newsletter->get_token();
        $user['updated'] = time();

        $user = $newsletter->save_user($user);

        return $user;
    }

    /*
     * Deletes a subscriber
     */

    public static function delete_subscriber($params) {

        global $wpdb;
        $newsletter = Newsletter::instance();

        $user = $newsletter->get_user($params['email']);

        if (!$user) {
            return new WP_Error('-1', 'Email address not found', array('status' => 404));
        }

        if ($wpdb->query($wpdb->prepare("delete from " . NEWSLETTER_USERS_TABLE . " where id=%d", (int) $user->id))) {
            return "OK";
        } else {
            $newsletter->logger->debug($wpdb->last_query);
            return new WP_Error('-1', $wpdb->last_error, array('status' => 400));
        }
    }

}
