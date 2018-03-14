<?php
if (!defined('ABSPATH'))
    exit;

require_once NEWSLETTER_INCLUDES_DIR . '/controls.php';
$controls = new NewsletterControls();
$module = NewsletterUsers::instance();

$id = (int) $_GET['id'];

if ($controls->is_action('save')) {

    $email = $module->normalize_email($controls->data['email']);
    if (empty($email)) {
        $controls->errors = __('Wrong email address', 'newsletter');
    }

    if (empty($controls->errors)) {
        $user = $module->get_user($controls->data['email']);
        if ($user && $user->id != $id) {
            $controls->errors = __('The email address is already in use', 'newsletter');
        }
    }

    if (empty($controls->errors)) {
        // For unselected preferences, force the zero value
        for ($i = 1; $i <= NEWSLETTER_LIST_MAX; $i++) {
            if (!isset($controls->data['list_' . $i])) {
                $controls->data['list_' . $i] = 0;
            }
        }
        
        if (empty($controls->data['token'])) {
            $controls->data['token'] = $module->get_token();
        }

        $controls->data['id'] = $id;
        $r = $module->save_user($controls->data);
        if ($r === false) {
            $controls->errors = __('Error. Check the log files.', 'newsletter');
        } else {
            $controls->add_message_saved();
            $controls->data = $module->get_user($id, ARRAY_A);
        }
    }
}

if ($controls->is_action('delete')) {
    $module->delete_user($id);
    $controls->js_redirect($module->get_admin_page_url('index'));
    return;
}

if (!$controls->is_action()) {
    $controls->data = $module->get_user($id, ARRAY_A);
}

$options_profile = get_option('newsletter_profile');

$panels = Newsletter::instance()->panels;

function percent($value, $total) {
    if ($total == 0)
        return '-';
    return sprintf("%.2f", $value / $total * 100) . '%';
}

function percentValue($value, $total) {
    if ($total == 0)
        return 0;
    return round($value / $total * 100);
}
?>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    google.charts.load('current', {'packages': ['corechart', 'geomap']});
</script>

<div class="wrap tnp-users tnp-users-edit" id="tnp-wrap">

    <?php include NEWSLETTER_DIR . '/tnp-header.php'; ?>

    <div id="tnp-heading">

        <h2><?php _e('Editing', 'newsletter') ?> <?php echo esc_html($controls->data['email']) ?></h2>

    </div>

    <div id="tnp-body">

        <form method="post" action="">
            <p>
                <?php $controls->button_back('?page=newsletter_users_index'); ?>
                <?php $controls->button_save(); ?>
            </p>
            <?php $controls->init(); ?>

            <div id="tabs">

                <ul>
                    <li><a href="#tabs-general">General</a></li>
                    <li><a href="#tabs-preferences">Lists</a></li>
                    <li><a href="#tabs-profile">Profile</a></li>
                    <li><a href="#tabs-other">Other</a></li>
                    <li><a href="#tabs-newsletters">Newsletters</a></li>

                </ul>

                <div id="tabs-general" class="tnp-tab">

                    <?php do_action('newsletter_users_edit_general', $id, $controls) ?>

                    <table class="form-table">

                        <tr>
                            <th><?php _e ('Email', 'newsletter'); ?></th>
                            <td>
                                <?php $controls->text('email', 60); ?>
                            </td>
                        </tr>
                        <tr>
                            <th><?php _e ('First name', 'newsletter'); ?></th>
                            <td>
                                <?php $controls->text('name', 50); ?>
                            </td>
                        </tr>
                        <tr>
                            <th><?php _e('Last name', 'newsletter'); ?></th>
                            <td>
                                <?php $controls->text('surname', 50); ?>
                            </td>
                        </tr>
                        <tr>
                            <th><?php _e ('Gender', 'newsletter'); ?></th>
                            <td>
                                <?php $controls->select('sex', array('n' => 'Not specified', 'f' => 'female', 'm' => 'male')); ?>
                            </td>
                        </tr>
                        <tr>
                            <th><?php _e ('Status', 'newsletter'); ?></th>
                            <td>
                                <?php $controls->select('status', array('C' => 'Confirmed', 'S' => 'Not confirmed', 'U' => 'Unsubscribed', 'B' => 'Bounced')); ?>
                            </td>
                        </tr>
                        <tr>
                            <th><?php _e ('Test subscriber', 'newsletter'); ?>
                                <br><?php $controls->help('https://www.thenewsletterplugin.com/documentation/subscribers#test-subscribers') ?></th>
                            <td>
                                <?php $controls->yesno('test'); ?>
                            </td>
                        </tr>

                        <?php do_action('newsletter_user_edit_extra', $controls); ?>

                        <tr>
                            <th>Feed by mail</th>
                            <td>
                                <?php $controls->yesno('feed'); ?>
                            </td>
                        </tr>
                    </table>
                </div>
                <div id="tabs-preferences" class="tnp-tab">
                    <table class="form-table">
                        <tr>
                            <th><?php _e('Lists', 'newsletter') ?><br><?php echo $controls->help('https://www.thenewsletterplugin.com/plugins/newsletter/newsletter-preferences') ?></th>
                            <td>
                                <?php $controls->preferences('list'); ?>
                            </td>
                        </tr>
                    </table>
                </div>

                <div id="tabs-profile" class="tnp-tab">

                    <table class="widefat">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th><?php _e ('Name', 'newsletter'); ?></th>
                                <th><?php _e ('Value', 'newsletter'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            for ($i = 1; $i <= NEWSLETTER_PROFILE_MAX; $i++) {
                                echo '<tr><td>';
                                echo $i;
                                echo '</td><td>';
                                echo esc_html($options_profile['profile_' . $i]);
                                echo '</td><td>';
                                $controls->text('profile_' . $i, 70);
                                echo '</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <div id="tabs-other" class="tnp-tab">

                    <table class="form-table">
                        <tr>
                            <th>ID</th>
                            <td>
                                <?php $controls->value('id'); ?>
                            </td>
                        </tr>
                        <tr>
                            <th><?php _e('Created', 'newsletter') ?></th>
                            <td>
                                <?php $controls->value('created'); ?>
                            </td>
                        </tr>
                        <tr>
                            <th><?php _e('WP user ID', 'newsletter') ?></th>
                            <td>
                                <?php $controls->text('wp_user_id'); ?>
                            </td>
                        </tr>
                        <tr>
                            <th><?php _e ('IP address', 'newsletter'); ?></th>
                            <td>
                                <?php $controls->value('ip'); ?>
                            </td>
                        </tr>
                        <tr>
                            <th><?php _e ('Secret token', 'newsletter'); ?></th>
                            <td>
                                <?php $controls->text('token', 50); ?>
                            </td>
                        </tr>
                        <tr>
                            <th><?php _e ('Profile URL', 'newsletter'); ?></th>
                            <td>
                                <?php $profile_url = esc_html(home_url('/') . '?na=pe&nk=' . $id . '-' . $controls->data['token']);  ?>
                                <a href='<?php echo $profile_url ?>' target="_blank"><?php echo $profile_url ?></a>
                            </td>
                        </tr>

                    </table>
                </div>
                <div id="tabs-newsletters" class="tnp-tab">
                    <p>Newsletter sent to this subscriber.</p>
                    <?php if (!has_action('newsletter_user_newsletters_tab') && !has_action('newsletter_users_edit_newsletters')) { ?>
                        <div class="tnp-tab-notice">
                            This panel requires the <a href="https://www.thenewsletterplugin.com/plugins/newsletter/reports-module" target="_blank">Reports Extension 4+</a>.
                        </div>
                        <?php
                    } else {
                        do_action('newsletter_user_newsletters_tab', $id);
                        do_action('newsletter_users_edit_newsletters', $id);
                    }
                    ?>
                </div>

                <?php
                if (isset($panels['user_edit'])) {
                    foreach ($panels['user_edit'] as $panel) {
                        call_user_func($panel['callback'], $id, $controls);
                    }
                }
                ?>

            </div>

            <p>
                <?php $controls->button_save(); ?>
                <?php $controls->button_delete(); ?>
            </p>

        </form>
    </div>

    <?php include NEWSLETTER_DIR . '/tnp-footer.php'; ?>

</div>
