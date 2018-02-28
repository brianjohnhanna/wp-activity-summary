<?php

namespace ST\WP_Activity_Summary\SettingsUI;

function setup() {
    add_action(
        'plugins_loaded', function() {
            add_action( 'admin_menu', __NAMESPACE__ . '\options_page' );
            add_action( 'admin_init', __NAMESPACE__ . '\register_settings' );
        }
    );
}

function options_page() {
    add_options_page(
        'Activity Summary Options', 
        'Activity Summary', 
        'manage_options',
        'activity_summary_options',
        __NAMESPACE__ . '\render_options_page'
    );
} 

function render_options_page() {
    ?>
    <div class="wrap">
        <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
        <?php settings_errors( 'wpas_messages' ); ?>
        <form action="options.php" method="post">
            <?php
                settings_fields( 'wpas' );
                do_settings_sections( 'wpas' );
                submit_button( 'Save Settings' );
            ?>
        </form>
    </div>
    <?php
}

function register_settings()
{
    // register a new setting for "reading" page
    register_setting('wpas', 'wpas_emails', [
        'sanitize_callback' => __NAMESPACE__ . '\validate_email_field',
    ]);
 
    // register a new section in the "reading" page
    add_settings_section(
        'wpas_email_settings_section',
        'Email Settings',
        __NAMESPACE__ . '\email_section_cb',
        'wpas'
    );
 
    // register a new field in the "wporg_settings_section" section, inside the "reading" page
    add_settings_field(
        'wpas_emails',
        'Email Addresses',
        __NAMESPACE__ . '\email_field_cb',
        'wpas',
        'wpas_email_settings_section'
    );
}

function email_section_cb() {
    echo '';
}

function email_field_cb() {
    $emails = get_option('wpas_emails');
    echo '<textarea name="wpas_emails" rows="5" cols="50" class="large-text">' . $emails . '</textarea>';
    echo '<p class="description">Provide a list of comma-separated emails to receive a weekly email summary of the activity on the blog.</p>';
}

function validate_email_field($data) {
    if (empty($data)) {
        add_settings_error(
            'empty_error',
            'emailError',
            'You must provide a comma-separated list of valid email addresses.',
            'error'
        );
        return;
    }
    $invalid = [];
    $valid = [];
    $emails = explode(',', $data);
    foreach ($emails as $email) {
        $email = sanitize_email(trim($email));
        if (!is_email($email)) {
            $invalid[] = $email;
            continue;
        }
        $valid[] = $email;
    }

    if (!empty($invalid)) {
        add_settings_error(
            'email_error',
            'emailError',
            'One or more of the emails provided was invalid: ' . implode(', ', $invalid),
            'error'
        );
    }
    return implode(',', $valid);
}