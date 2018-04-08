<?php

function authenticateUser($connection, $username)
{
  // Test the username and password parameters
  if (!isset($username))
    return false;

  // Create a digest of the password collected from
  // the challenge

  // Formulate the SQL find the user
  $query = "SELECT * FROM Member WHERE mid = '{$username}'";
  // Execute the query
  if (!$result = @ mysqli_query ($connection,$query))
    echo "login query error";
  
  $count = mysqli_num_rows($result);
  // exactly one row? then we have found the user
  if ($count== 1)
    return True;
  else
    {echo "Invalid Username!";
    return False;}
}


function authenticateResult($connection, $Keyword)
{
  // Test the username and password parameters
  if (is_null($Keyword))
    return True;
  // Create a digest of the password collected from
  // the challenge

  // Formulate the SQL find the user
  $query = "SELECT * FROM Book WHERE booktitle LIKE '%{$Keyword}%' or category LIKE '%{$Keyword}%' or author like '%{$Keyword}%";

  // Execute the query
  if (!$result = @ mysqli_query ($connection,$query))
    echo "Search query error";
  
  $count = mysqli_num_rows($result);

  // exactly one row? then we have found the user
  if ($count< 1)
    return false;
  else
    return true;
}





// Connects to a session and checks that the user has
// authenticated and that the remote IP address matches
// the address used to create the session.
function sessionAuthenticate()
{
  // Check if the user hasn't logged in
  if (!isset($_SESSION["loginUsername"]))
  {
    // The request does not identify a session
    $_SESSION["message"] = "You are not authorized to access the URL 
                            {$_SERVER["REQUEST_URI"]}";

    header("Location: logout.php");
    exit;
  }

  // Check if the request is from a different IP address to previously
  if (!isset($_SESSION["loginIP"]) || 
     ($_SESSION["loginIP"] != $_SERVER["REMOTE_ADDR"]))
  {
    // The request did not originate from the machine
    // that was used to create the session.
    // THIS IS POSSIBLY A SESSION HIJACK ATTEMPT

    $_SESSION["message"] = "You are not authorized to access the URL 
                            {$_SERVER["REQUEST_URI"]} from the address 
                            {$_SERVER["REMOTE_ADDR"]}";

    header("Location: logout.php");
    exit;
  }
}

?>
