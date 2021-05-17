<?php
// create function to load icon Website
// please put this function inside head to work properly
function load_icon()
{
  echo <<<EOT
  <link rel="apple-touch-icon" sizes="180x180" href="../imgs/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="../imgs/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="../imgs/favicon-16x16.png">
  <link rel="manifest" href="../imgs/site.webmanifest">
  EOT;
}
function mysqli_connect_to_database()
{
  // Change this to your connection info.
  $DATABASE_HOST = 'localhost';
  $DATABASE_USER = 'root';
  $DATABASE_PASS = '';
  $DATABASE_NAME = 'shoppingcart';
  // Try and connect using the info above.
  return mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
  if (mysqli_connect_errno()) {
    // If there is an error with the connection, stop the script and display the error.
    exit('Failed to connect to MySQL: ' . mysqli_connect_error());
  }
}
