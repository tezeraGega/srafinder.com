<?php
echo "Server is working correctly!<br>";
echo "Current directory: " . getcwd() . "<br>";
echo "Base URL would be calculated as: " . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']) . "<br>";
echo "Request URI: " . $_SERVER['REQUEST_URI'] . "<br>";
echo "Script name: " . $_SERVER['SCRIPT_NAME'] . "<br>";
?>