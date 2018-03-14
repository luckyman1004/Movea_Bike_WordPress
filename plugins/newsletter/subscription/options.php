<?php
if (!defined('ABSPATH'))
    exit;

@include_once NEWSLETTER_INCLUDES_DIR . '/controls.php';
$controls = new NewsletterControls();
$module = NewsletterSubscription::instance();

// TODO: Remove and use the $module->options.
$options = get_option('newsletter', array());

if ($controls->is_action()) {
    if ($controls->is_action('save')) {

        $defaults = $module->get_default_options();

        if (empty($controls->data['profile_text'])) {
            $controls->data['profile_text'] = $defaults['profile_text'];
        }

        // Without the last curly bracket since there can be a form number apended
        if (empty($controls->data['subscription_text'])) {
            $controls->data['subscription_text'] = $defaults['subscription_text'];
        }

        if (empty($controls->data['confirmation_text'])) {
            $controls->data['confirmation_text'] = $defaults['confirmation_text'];
        }

        if (empty($controls->data['confirmation_subject'])) {
            $controls->data['confirmation_subject'] = $defaults['confirmation_subject'];
        }

        if (empty($controls->data['confirmation_message'])) {
            $controls->data['confirmation_message'] = $defaults['confirmation_message'];
        }

        if (empty($controls->data['confirmed_text'])) {
            $controls->data['confirmed_text'] = $defaults['confirmed_text'];
        }

        if (empty($controls->data['confirmed_subject'])) {
            $controls->data['confirmed_subject'] = $defaults['confirmed_subject'];
        }

        if (empty($controls->data['confirmed_message'])) {
            $controls->data['confirmed_message'] = $defaults['confirmed_message'];
        }

        $controls->data['confirmed_message'] = NewsletterModule::clean_url_tags($controls->data['confirmed_message']);
        $controls->data['confirmed_text'] = NewsletterModule::clean_url_tags($controls->data['confirmed_text']);
        $controls->data['confirmation_text'] = NewsletterModule::clean_url_tags($controls->data['confirmation_text']);
        $controls->data['confirmation_message'] = NewsletterModule::clean_url_tags($controls->data['confirmation_message']);

        $controls->data['confirmed_url'] = trim($controls->data['confirmed_url']);
        $controls->data['confirmation_url'] = trim($controls->data['confirmation_url']);

        if (!empty($controls->data['page'])) {
            $controls->data['url'] = ''; // do not unset
        }

        $module->merge_options($controls->data);
        $controls->add_message_saved();
    }

    if ($controls->is_action('create')) {
        $page = array();
        $page['post_title'] = 'Newsletter';
        $page['post_content'] = '[newsletter]';
        $page['post_status'] = 'publish';
        $page['post_type'] = 'page';
        $page['comment_status'] = 'closed';
        $page['ping_status'] = 'closed';
        $page['post_category'] = array(1);

        // Insert the post into the database
        $page_id = wp_insert_post($page);

        $controls->data['page'] = $page_id;
        $module->merge_options($controls->data);
    }

    if ($controls->is_action('reset')) {
        $controls->data = $module->reset_options();
    }

    if ($controls->is_action('test-confirmation')) {

        $users = NewsletterUsers::instance()->get_test_users();
        if (count($users) == 0) {
            $controls->errors = 'There are no test subscribers. Read more about test subscribers <a href="https://www.thenewsletterplugin.com/plugins/newsletter/subscribers-module#test" target="_blank">here</a>.';
        } else {
            $addresses = array();
            foreach ($users as &$user) {
                $addresses[] = $user->email;
                $res = $module->mail($user->email, $newsletter->replace($module->options['confirmation_subject']), $newsletter->replace($module->options['confirmation_message'], $user));
                if (!$res) {
                    $controls->errors = 'The email address ' . $user->email . ' failed.';
                    break;
                }
            }
            $controls->messages .= 'Test emails sent to ' . count($users) . ' test subscribers: ' .
                    implode(', ', $addresses) . '. Read more about test subscribers <a href="https://www.thenewsletterplugin.com/plugins/newsletter/subscribers-module#test" target="_blank">here</a>.';
            $controls->messages .= '<br>If the message is not received, try to change the message text it could trigger some antispam filters.';
        }
    }

    if ($controls->is_action('test-confirmed')) {

        $users = NewsletterUsers::instance()->get_test_users();
        if (count($users) == 0) {
            $controls->errors = 'There are no test subscribers. Read more about test subscribers <a href="https://www.thenewsletterplugin.com/plugins/newsletter/subscribers-module#test" target="_blank">here</a>.';
        } else {
            $addresses = array();
            foreach ($users as &$user) {
                $addresses[] = $user->email;
                $res = $module->mail($user->email, $newsletter->replace($module->options['confirmed_subject']), $newsletter->replace($module->options['confirmed_message'], $user));
                if (!$res) {
                    $controls->errors = 'The email address ' . $user->email . ' failed.';
                    break;
                }
            }
            $controls->messages .= 'Test emails sent to ' . count($users) . ' test subscribers: ' .
                    implode(', ', $addresses) . '. Read more about test subscribers <a href="https://www.thenewsletterplugin.com/plugins/newsletter/subscribers-module#test" target="_blank">here</a>.';
            $controls->messages .= '<br>If the message is not received, try to change the message text it could trigger some antispam filters.';
        }
    }
} else {
    $controls->data = get_option('newsletter', array());
}

if (empty($controls->data['page'])) {
    $controls->messages .= '<p>You should set a dedicated page for Newsletter which used to interact with your subscribers.</p>';
} else {
    $post = get_post($controls->data['page']);

    if (!$post || $post->post_status != 'publish') {
        $controls->errors .= '<p>The dedicated page selected below does not exist anymore or has been unpublished. Please, select a different one.</p>';
    } else {
        if (strpos($post->post_content, '[newsletter') === false) {
            $controls->errors .= '<p>The dedicated page selected DOES NOT contain the [newsletter] shortcode. Please fix it. It should contain ONLY the [newsletter] shortcode.</p>';
        }
    }
}
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.20.2/codemirror.css" type="text/css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.20.2/addon/hint/show-hint.css">
<style>
    .CodeMirror {
        border: 1px solid #ddd;
    }
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.20.2/codemirror.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.20.2/mode/css/css.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.20.2/addon/hint/show-hint.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.20.2/addon/hint/css-hint.js"></script>
<script>
    jQuery(function () {
        var editor = CodeMirror.fromTextArea(document.getElementById("options-css"), {
            lineNumbers: true,
            mode: 'css',
            extraKeys: {"Ctrl-Space": "autocomplete"}
        });
    });
</script>

<div class="wrap" id="tnp-wrap">

    <?php include NEWSLETTER_DIR . '/tnp-header.php'; ?>

    <div id="tnp-heading">

        <h2><?php _e('Subscription, Profile Page Configuration', 'newsletter') ?></h2>
        <?php $controls->page_help('https://www.thenewsletterplugin.com/documentation/subscription') ?>

    </div>

    <div id="tnp-body">

        <form method="post" action="">
            <?php $controls->init(); ?>
            <div id="tabs">
                <ul>
                    <li><a href="#tabs-general"><?php _e('General', 'newsletter') ?></a></li>
                    <li><a href="#tabs-2"><?php _e('Subscription', 'newsletter') ?></a></li>
                    <li><a href="#tabs-3"><?php _e('Activation', 'newsletter') ?></a></li>
                    <li><a href="#tabs-4"><?php _e('Welcome', 'newsletter') ?></a></li>
                    <li><a href="#tabs-9"><?php _e('Profile', 'newsletter') ?></a></li>
                </ul>

                <div id="tabs-general">
                    <table class="form-table">
                        <tr>
                            <th><?php _e('Opt In', 'newsletter') ?></th>
                            <td>
                                <?php $controls->select('noconfirmation', array(0 => __('Double Opt In', 'newsletter'), 1 => __('Single Opt In', 'newsletter'))); ?>
                                <?php $controls->help('https://www.thenewsletterplugin.com/documentation/subscription#opt-in') ?>
                            </td>
                        </tr>
                        <tr>
                            <th><?php _e('Dedicated page', 'newsletter') ?></th>
                            <td>
                                <?php $controls->page('page', __('Unstyled page', 'newsletter')); ?>
                                <?php
                                if (empty($controls->data['url']) && empty($controls->data['page'])) {
                                    $controls->button('create', __('Create the page', 'newsletter'));
                                }
                                ?>
                                <?php if (!empty($controls->data['url'])) { ?>
                                    <!-- do not translate, will be removed -->
                                    <p class="description">
                                        <strong>
                                            You're currently using the URL <code><?php echo esc_html($controls->data['url']) ?></code>
                                            as dedicated page. Please select the corrisponding page above (new as version 4.6.5+).
                                        </strong>
                                    </p>
                                <?php } ?>
                            </td>
                        </tr>
                        <tr>
                            <th><?php _e('Notifications', 'newsletter') ?></th>
                            <td>
                                <?php $controls->yesno('notify'); ?>
                                <?php $controls->text_email('notify_email'); ?>
                            </td>
                        </tr>
                        <tr>
                            <th><?php _e('Custom styles', 'newsletter') ?></th>
                            <td>
                                <?php if (apply_filters('newsletter_enqueue_style', true) === false) { ?>
                                    <p><strong>Warning: Newsletter styles and custom styles are disable by your theme or a plugin.</strong></p>
                                <?php } ?>
                                <?php $controls->textarea('css'); ?>
                            </td>
                        </tr>
                    </table>
                </div>


                <div id="tabs-2">

                    <table class="form-table">
                        <tr>
                            <th><?php _e('Subscription page', 'newsletter') ?><br><?php echo $controls->help('https://www.thenewsletterplugin.com/documentation/newsletter-tags') ?></th>
                            <td>
                                <?php $controls->wp_editor('subscription_text'); ?>
                            </td>
                        </tr>
                        <tr>
                            <th><?php _e('Forced lists', 'newsletter') ?></th>
                            <td>
                                <?php $controls->preferences(); ?>
                            </td>
                        </tr>
                        <tr>
                            <th><?php _e('Disable antibot/antispam?', 'newsletter') ?></th>
                            <td>
                                <?php $controls->yesno('antibot_disable'); ?>
                                <p class="description">
                                    <?php _e ('Disable for ajax form submission', 'newsletter'); ?>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <th><?php _e('Antiflood', 'newsletter') ?></th>
                            <td>
                                <?php
                                $controls->select('antiflood', array(
                                    0 => __('Disabled', 'newsletter'),
                                    5 => '5 ' . __('seconds', 'newsletter'),
                                    10 => '10 ' . __('seconds', 'newsletter'),
                                    15 => '15 ' . __('seconds', 'newsletter'),
                                    30 => '30 ' . __('seconds', 'newsletter'),
                                    60 => '1 ' . __('minute', 'newsletter'),
                                    120 => '2 ' . __('minutes', 'newsletter'),
                                    300 => '5 ' . __('minutes', 'newsletter')
                                ));
                                ?>
                                <?php $controls->help('https://www.thenewsletterplugin.com/documentation/antiflood') ?>
                            </td>
                        </tr>
                    </table>

                    <h3>Special cases</h3>

                    <table class="form-table">
                        <!--
                        <tr>
                            <th>Already subscribed page content</th>
                            <td>
                        <?php //$controls->wp_editor('already_confirmed_text');  ?><br>
                        <?php //$controls->checkbox('resend_welcome_email_disabled', 'Do not resend the welcome email');  ?>
                                <p class="description">
                                    Shown when the email is already subscribed and confirmed. The welcome email, if not disabled, will
                                    be sent. Find out more on this topic on its
                                    <a href="https://www.thenewsletterplugin.com/plugins/newsletter/subscription-module#repeated" target="_blank">documentation page</a>.
                                </p>
                            </td>
                        </tr>
                        -->
                        <tr>
                            <th><?php _e('Error page', 'newsletter') ?></th>
                            <td>
                                <?php $controls->wp_editor('error_text'); ?>
                            </td>
                        </tr>
                    </table>
                </div>


                <div id="tabs-3">
                    
                    <p><?php _e('Only for double opt-in mode.', 'newsletter') ?></p>
                    <?php $controls->panel_help('https://www.thenewsletterplugin.com/documentation/subscription#activation') ?>
                    
                    <table class="form-table">
                        <tr>
                            <th><?php _e('Activation message', 'newsletter') ?></th>
                            <td>
                                <?php $controls->wp_editor('confirmation_text'); ?>
                            </td>
                        </tr>

                        <tr>
                            <th><?php _e ('Alternative activation page', 'newsletter'); ?></th>
                            <td>
                                <?php $controls->text('confirmation_url', 70, 'https://...'); ?>
                            </td>
                        </tr>


                        <!-- CONFIRMATION EMAIL -->
                        <tr>
                            <th><?php _e('Activation email', 'newsletter') ?></th>
                            <td>
                                <?php $controls->email('confirmation', 'wordpress'); ?>
                                <br>
                                <?php $controls->button('test-confirmation', 'Send a test'); ?>
                            </td>
                        </tr>
                    </table>
                </div>


                <div id="tabs-4">
                    <p>
                        <?php $controls->panel_help('https://www.thenewsletterplugin.com/documentation/subscription#welcome') ?>
                    </p>
                    <table class="form-table">
                        <tr>
                            <th><?php _e('Welcome message', 'newsletter') ?></th>
                            <td>
                                <?php $controls->wp_editor('confirmed_text'); ?>
                            </td>
                        </tr>

                        <tr>
                            <th><?php _e('Alternative welcome page URL', 'newsletter') ?></th>
                            <td>
                                <?php $controls->text('confirmed_url', 70, 'https://...'); ?>
                            </td>
                        </tr>

                        <tr>
                            <th><?php _e('Conversion tracking code', 'newsletter') ?>
                            <?php $controls->help('https://www.thenewsletterplugin.com/documentation/subscription#conversion') ?></th>
                            <td>
                                <?php $controls->textarea('confirmed_tracking'); ?>
                            </td>
                        </tr>

                        <!-- WELCOME/CONFIRMED EMAIL -->
                        <tr>
                            <th>
                                <?php _e('Welcome email', 'newsletter') ?>
                            </th>
                            <td>
                                <?php $controls->email('confirmed', 'wordpress', true); ?>
                                <br>
                                <?php $controls->button('test-confirmed', 'Send a test'); ?>
                            </td>
                        </tr>

                    </table>
                </div>

                <!-- PROFILE -->
                <div id="tabs-9">

                    <table class="form-table">

                        <tr>
                            <th><?php _e('Profile page', 'newsletter') ?>
                                <br><?php $controls->help('https://www.thenewsletterplugin.com/documentation/subscription#profile') ?>
                            </th>
                            <td>
                                <?php $controls->wp_editor('profile_text'); ?>
                            </td>
                        </tr>

                        <tr>
                            <th><?php _e('Alternative profile page URL', 'newsletter') ?></th>
                            <td>
                                <?php $controls->text('profile_url', 70); ?>
                            </td>
                        </tr>

                        <tr>
                            <th>Other messages</th>
                            <td>
                                confirmation after profile save<br>
                                <?php $controls->text('profile_saved', 80); ?><br><br>
                                email changed notice<br>
                                <?php $controls->text('profile_email_changed', 80); ?>
                                <p class="description">when a subscriber changes his email, he will be unconfirmed and a new confirmation email is sent</p>
                                <br><br>
                                generic error<br>
                                <?php $controls->text('profile_error', 80); ?>
                                <p class="description">when the email is not valid or already used by another subscriber</p>
                            </td>
                        </tr>
                    </table>
                </div>

            </div>

            <p>
                <?php $controls->button_save() ?>
                <?php $controls->button_reset() ?>
            </p>

        </form>
    </div>

    <?php include NEWSLETTER_DIR . '/tnp-footer.php'; ?>

</div>
