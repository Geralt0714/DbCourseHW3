<?php
require 'authentication.inc';
require 'db.inc';

if (!$connection = @ mysqli_connect("127.0.0.1", "geralt", "","ps3"))
  die("Cannot connect");

// Clean the data collected in the <form>
$loginUsername = mysqlclean($_POST, "loginUsername", 10, $connection);
$Keyword = mysqlclean($_POST, "Keyword", 10, $connection);

if ($connection->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

session_start();

// Authenticate the user
if (authenticateUser($connection, $loginUsername))
{
  // Register the loginUsername
  $_SESSION["loginUsername"] = $loginUsername;

  // Register the IP address that started this session
  $_SESSION["loginIP"] = $_SERVER["REMOTE_ADDR"];
  
  // Construct SQL query
  if ($Keyword==''){
    $query = "SELECT * From Book natural join (select bookid,min(copyid) as copyid from bookcopy where copyid not in (select copyid from checkedout where status ='Overdue' or status = 'Holding') group by bookid) as A";
  }
  else{
  // Formulate the SQL find the user
  $query = "SELECT * FROM (select * from book   WHERE booktitle LIKE '%" .$Keyword. 
            "%' or category LIKE '%" . $Keyword. 
            "%' or author like '%" . $Keyword ."%') as Z
            natural join 
            (select bookid,min(copyid) as copyid from bookcopy where copyid not in (select copyid from checkedout where status ='Overdue' or status = 'Holding') group by bookid) as X";
  }
  // Execute the query
  if (!$result = @ mysqli_query ($connection,$query))
  {  echo "Search query error";
    echo "<a href=\"http://localhost:8080/login.html\">GO BACK</a>";
    exit;}
  $count = mysqli_num_rows($result);
  if ($count ==0)
  {echo "No such book related to Keyword or there are is no book for you to rent";
    echo "<a href=\"http://localhost:8080/login.html\">GO BACK</a>";
    exit;
  }
  else
  { echo "Search Result";
    echo "<table>";
    echo "<tr><td>Bookid</td><td>Booktitle</td><td>Category</td><td>Author</td><td>Publishdate</td><td>Rentlink</td></tr>";
    while($row = mysqli_fetch_array($result))
        {
          echo "<tr>";
          $templink = "http://localhost:8080/RentCheckout.php?userid=" . $loginUsername.
                      "&copyid=" . $row['copyid'];
          $rentlink = "<a href=" . $templink . ">Rent Me!</a>";
          echo "<td>" . $row['bookid'] . "</td>";
          echo "<td>" . $row['booktitle'] . "</td>";
          echo "<td>" . $row['category'] . "</td>";
          echo "<td>" . $row['author'] . "</td>";
          echo "<td>" . $row['publishdate'] . "</td>";
          echo "<td>" . $rentlink . "</td>";
          echo "</tr>";
          }
    echo "</table>";
    }
    echo "<a href=\"http://localhost:8080/login.html\">GO BACK</a>";
    exit;
}
else
{
  // The authentication failed: setup a logout message
  $_SESSION["message"] = 
    "Could not connect to the application as '{$loginUsername}'";

  // Relocate to the logout page
  echo "<a href=\"http://localhost:8080/login.html\">GO BACK</a>";
  exit;
}
?>
