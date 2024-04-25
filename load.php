<?php 

namespace PERN;

require_once PLUGIN_DIR . 'inc/class-admin-settings.php';
require_once PLUGIN_DIR . 'inc/class-post-sender.php';

use PERN\AdminSettings;
use PERN\PostSender;


new AdminSettings();
new PostSender();