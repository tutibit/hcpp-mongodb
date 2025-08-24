<?php
/**
 * Plugin Name: MongoDB Admin
 * Plugin URI: https://github.com/waltersolano/hcpp-mongodb
 * Description: MongoDB administration interface for HestiaCP via Adminer
 * Author: Walter Solano
 * Version: 1.0.0
 * License: MIT
 */

// Register the install and uninstall scripts
global $hcpp;

$hcpp->register_install_script( dirname(__FILE__) . '/install' );
$hcpp->register_uninstall_script( dirname(__FILE__) . '/uninstall' );

// Add MongoDB button to database list page (alongside phpMyAdmin/phpPgAdmin)
$hcpp->add_action( 'render_page', function( $args ) {
    if ( $args[0] == 'list_db.php' && is_mongodb_available() ) {
        // Inject MongoDB button after phpPgAdmin button
        $args[1] = str_replace(
            '</a>
				<?php if (ipUsed()) { ?>',
            '</a>
				<a class="button button-secondary" href="/adminer/?mongo=" target="_blank">
					<i class="fas fa-leaf icon-green"></i>MongoDB
				</a>
				<?php if (ipUsed()) { ?>',
            $args[1]
        );
    }
    return $args;
});

// Check if MongoDB is available
function is_mongodb_available() {
    // Check if MongoDB service is running
    $status = shell_exec('systemctl is-active mongod 2>/dev/null') ?: shell_exec('systemctl is-active mongodb 2>/dev/null');
    
    if (trim($status) === 'active') {
        // Check if MongoDB extension is loaded
        return extension_loaded('mongodb');
    }
    
    return false;
}

// Initialize Adminer MongoDB support on init
$hcpp->add_action( 'init', function() {
    if (is_mongodb_available()) {
        // Ensure Adminer directory exists
        $adminer_dir = '/usr/local/hestia/web/adminer';
        if (!is_dir($adminer_dir)) {
            mkdir($adminer_dir, 0755, true);
            chown($adminer_dir, 'admin');
            chgrp($adminer_dir, 'admin');
        }
        
        // Copy MongoDB driver if doesn't exist
        $mongo_driver = dirname(__FILE__) . '/mongo.php';
        $target = $adminer_dir . '/mongo.php';
        
        if (file_exists($mongo_driver) && !file_exists($target)) {
            copy($mongo_driver, $target);
            chmod($target, 0644);
            chown($target, 'admin');
            chgrp($target, 'admin');
        }
        
        // Ensure main Adminer file exists
        $adminer_main = $adminer_dir . '/index.php';
        if (!file_exists($adminer_main)) {
            // Create basic Adminer index that includes MongoDB support
            $adminer_content = '<?php
require_once "mongo.php";
// Include main Adminer here or download it
// For now, redirect to direct Adminer access
if (isset($_GET["mongo"])) {
    // Handle MongoDB connection
    header("Content-Type: text/html; charset=utf-8");
    echo "<h2>MongoDB Admin</h2>";
    echo "<p>MongoDB administration interface</p>";
    echo "<p>Server: localhost:27017</p>";
} else {
    echo "<h2>Database Administration</h2>";
    echo "<a href=\"?mongo=\">MongoDB</a>";
}
?>';
            file_put_contents($adminer_main, $adminer_content);
            chmod($adminer_main, 0644);
            chown($adminer_main, 'admin');
            chgrp($adminer_main, 'admin');
        }
    }
});