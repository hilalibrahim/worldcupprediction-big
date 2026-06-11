<?php
echo "PHP is working!<br>";
echo "Current URI: " . $_SERVER['REQUEST_URI'] . "<br>";
echo "PHP Version: " . phpversion() . "<br>";
echo "Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "<br>";

// Check if MySQL is available
$mysqli = new mysqli('localhost', 'root', '');
if ($mysqli->connect_error) {
    echo "MySQL Connection Error: " . $mysqli->connect_error . "<br>";
} else {
    echo "MySQL Connected Successfully!<br>";
    $mysqli->close();
}
?>
<h1>Test Page - Works!</h1>
<p>Go to <a href="./">Home</a></p>