<?php
/**
 * Test mod_rewrite
 */
echo "Testing mod_rewrite...<br>";
echo "REQUEST_URI: " . $_SERVER['REQUEST_URI'] . "<br>";
echo "SCRIPT_NAME: " . $_SERVER['SCRIPT_NAME'] . "<br>";
echo "PHP_SELF: " . $_SERVER['PHP_SELF'] . "<br>";

// Check if .htaccess is being processed
if (isset($_SERVER['REDIRECT_STATUS'])) {
    echo "REDIRECT_STATUS: " . $_SERVER['REDIRECT_STATUS'] . "<br>";
} else {
    echo "No REDIRECT_STATUS found - mod_rewrite may not be active<br>";
}

// Check if we can access Apache modules
if (function_exists('apache_get_modules')) {
    $modules = apache_get_modules();
    echo "Apache modules: " . implode(', ', $modules) . "<br>";
    if (in_array('mod_rewrite', $modules)) {
        echo "mod_rewrite is ENABLED<br>";
    } else {
        echo "mod_rewrite is NOT enabled<br>";
    }
} else {
    echo "Cannot get Apache modules list<br>";
}

echo "<hr>";
echo "<a href='./'>Home</a> | ";
echo "<a href='test.php'>Test PHP</a> | ";
echo "<a href='test-rewrite.php'>Test Again</a>";
?>