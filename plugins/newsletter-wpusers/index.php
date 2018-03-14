<?php
defined('ABSPATH') || exit;

@include_once NEWSLETTER_INCLUDES_DIR . '/controls.php';
$controls = new NewsletterControls();
$module = NewsletterWpUsers::$instance;

if (!$controls->is_action()) {
    $controls->data = $module->options;
} else {
    if ($controls->is_action('save')) {
        //$module->merge_options($controls->data);
        unset($controls->data['align_wp_users_status']);
        update_option('newsletter_wp', $controls->data);
        $controls->add_message_saved();
    }

    if ($controls->is_action('align_wp_users')) {
        ignore_user_abort(true);
        set_time_limit(0);

        // TODO: check if the user is already there
        $wp_user_ids = $wpdb->get_results("select id from $wpdb->users");
        $count = 0;
        foreach ($wp_user_ids as $wp_user_id) {
            $wp_user = new WP_User($wp_user_id->id);
            
            // A subscriber is already there with the same wp_user_id? Do Nothing.
            $nl_user = Newsletter::instance()->get_user_by_wp_user_id($wp_user->id);
            if (!empty($nl_user)) {
                continue;
            }

            // A subscriber has the same email? Align them if not already associated to another wordpress user
            $nl_user = Newsletter::instance()->get_user(Newsletter::instance()->normalize_email($wp_user->user_email));
            if (!empty($nl_user)) {
                if (empty($nl_user->wp_user_id)) {
                    //$module->logger->info('Linked');
                    Newsletter::instance()->set_user_wp_user_id($nl_user->id, $wp_user->id);
                    continue;
                }
            }

            // Create a new subscriber
            $nl_user = array();
            $nl_user['email'] = Newsletter::instance()->normalize_email($wp_user->user_email);
            $nl_user['name'] = $wp_user->first_name;
            if (empty($nl_user['name'])) {
                $nl_user['name'] = $wp_user->user_login;
            }
            $nl_user['surname'] = $wp_user->last_name;
            $nl_user['status'] = $controls->data['align_wp_users_status'];
            $nl_user['wp_user_id'] = $wp_user->id;
            $nl_user['referrer'] = 'wordpress';

            // Adds the force subscription preferences
            $preferences = NewsletterSubscription::instance()->options['preferences'];
            if (is_array($preferences)) {
                foreach ($preferences as $p) {
                    $nl_user['list_' . $p] = 1;
                }
            }
            
            // Adds the selected lists for new registered users
            if (!empty($controls->data['lists'])) {
                foreach ($controls->data['lists'] as $p) {
                    $nl_user['list_' . $p] = 1;
                }
            }

            Newsletter::instance()->save_user($nl_user);
            $count++;
        }
        $controls->messages = count($wp_user_ids) . ' ' . __('WordPress users processed', 'newsletter') . '. ';
        $controls->messages .= $count . ' ' . __('subscriptions added', 'newsletter') . '.';
    }
    
    if ($controls->is_action('link')) {
        /* @var $wpdb wpdb */
        $res = $wpdb->query("update " . NEWSLETTER_USERS_TABLE . " n join " . $wpdb->users . " u on u.user_email=n.email set n.wp_user_id=u.id");
        $controls->messages = $res . ' ' . __('subscribers linked', 'newsletter') . '.';
    }
}
?>
<div class="wrap" id="tnp-wrap">

    <?php include NEWSLETTER_DIR . '/tnp-header.php'; ?>

    <div id="tnp-heading">

        <h2>Subscription on registration</h2>
        
        <p>
            <a href="http://www.thenewsletterplugin.com/documentation/wpusers-extension" target="_blank"><i class="fa fa-book" aria-hidden="true"></i> Read our guide</a>.
        </p>

    </div>

    <div id="tnp-body">

        <form method="post" action="">

            <?php $controls->init(); ?>

            <table class="form-table">
                <tr valign="top">
                    <th>Subscription on registration</th>
                    <td>
                        <?php $controls->select('subscribe', array(0 => 'No', 1 => 'Yes, force subscription', 2 => 'Yes, show the option', 3 => 'Yes, show the option already checked')); ?>
                    </td>
                </tr>
                <tr valign="top">
                    <th><?php _e('Check box label', 'newsletter') ?></th>
                    <td>
                        <?php $controls->text('subscribe_label', 30); ?>
                    </td>
                </tr>
                <tr valign="top">
                    <th><?php _e('Subscribe as', 'newsletter') ?></th>
                    <td>
                        <?php $controls->select('status', array('S'=>'Confirmation required', 'C'=>'Confirmed')); ?>
                    </td>
                </tr>
                <tr valign="top">
                    <th>Send the confirmation email</th>
                    <td>
                        <?php $controls->yesno('confirmation'); ?>
                        <p class="description">Only if the subscription requires confirmation</p>
                    </td>
                </tr>  
                <tr valign="top">
                    <th>Confirm on login</th>
                    <td>
                        <?php $controls->yesno('login'); ?>
                    </td>
                </tr>
                <tr valign="top">
                    <th><?php _e('Send welcome email', 'newsletter') ?></th>
                    <td>
                        <?php $controls->yesno('welcome'); ?>
                    </td>
                </tr>
           

                 <tr valign="top">
                    <th>Lists</th>
                    <td>
                        <?php $controls->preferences_group('lists'); ?>
                        <p class="description">
                        Forcibly add the subscriber to those lists.
                        </p>
                    </td>
                </tr>  
                
                <tr valign="top">
                    <th><?php _e('Subscription delete', 'newsletter') ?></th>
                    <td>
                        <?php $controls->yesno('delete'); ?>
                        <p class="description">Delete the subscription connected to a WordPress user when that user is deleted</p>
                    </td>
                </tr>
            </table>

            <p>
                <?php $controls->button_save(); ?>
            </p>
            
            <h3><?php _e('Import already registered users', 'newsletter') ?>
            <table class="form-table">   
                <tr>
                    <th><?php _e('Import with status', 'newsletter') ?></th>
                    <td>
                        <?php $controls->select('align_wp_users_status', array('C' => __('Confirmed', 'newsletter'), 'S' => __('Not confirmed', 'newsletter'))); ?>
                        <?php $controls->button_confirm('align_wp_users', __('Import', 'newsletter'), __('Proceed?', 'newsletter')); ?>
                        <p class="description">
                            <a href="http://www.thenewsletterplugin.com/plugins/newsletter/subscribers-module#import-wp-users" target="_blank">
                                <?php _e('Please, carefully read the documentation before taking this action!', 'newsletter') ?>
                            </a>
                        </p>
                    </td>
                </tr>
            </table>
                
            <h3><?php _e('Maintenance', 'newsletter') ?>
            <table class="form-table">   
                <tr>
                    <th><?php _e('Link subscribers with users by email', 'newsletter') ?></th>
                    <td>
                        <?php $controls->button_confirm('link', __('Link', 'newsletter'), __('Proceed?', 'newsletter')); ?>
                    </td>
                </tr>
            </table>
            
        </form>
    </div>

    <?php include NEWSLETTER_DIR . '/tnp-footer.php'; ?>

</div>
