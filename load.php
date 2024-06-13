<?php 

namespace PERN;
require_once PLUGIN_DIR . 'inc/class-admin-settings.php';
require_once PLUGIN_DIR . 'inc/class-create-form.php';
require_once PLUGIN_DIR . 'inc/class-send-email.php';

use PERN\AdminSettings;
use PERN\CreateForm;
use PERN\SendEmail;

new AdminSettings();
new CreateForm();
new SendEmail();