<?php
echo "<!DOCTYPE html>";
echo "<html>";
echo "<head><title>Simple Test</title></head>";
echo "<body>";
echo "<h1>Simple Test Page</h1>";
echo "<p>If you can see this, PHP is working.</p>";
echo "<p>REQUEST_URI: " . $_SERVER['REQUEST_URI'] . "</p>";
echo "<p>SCRIPT_FILENAME: " . $_SERVER['SCRIPT_FILENAME'] . "</p>";
echo "<p>DOCUMENT_ROOT: " . $_SERVER['DOCUMENT_ROOT'] . "</p>";
echo "<hr>";
echo "<h2>Test Links:</h2>";
echo "<ul>";
echo "<li><a href='test.php'>test.php</a></li>";
echo "<li><a href='simple-test.php'>simple-test.php</a></li>";
echo "<li><a href='index.php'>index.php</a> (should trigger router)</li>";
echo "<li><a href='./'>Home (./)</a></li>";
echo "</ul>";
echo "</body>";
echo "</html>";
?>