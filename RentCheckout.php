<?php

// get variable 
$loginUsername = $_GET['userid'];
$requestedCopy = $_GET['copyid'];

if (!$connection = @ mysqli_connect("127.0.0.1", "geralt", "","ps3"))
  die("Cannot connect");


if ($connection->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

// Construct  SQL query
$query = "insert into `checkedout` values ('". $requestedCopy."','". $loginUsername . "',NOW(),DATE_ADD(NOW(),INTERVAL 30 DAY),'Holding')";
if (!$result = @ mysqli_query ($connection,$query))
  {  echo "Rent book error\n";
	echo $query;
    echo "<a href=\"http://localhost:8080/login.html\">GO BACK</a>";
    exit;}

echo "Book Checked Out!\nYou have checked out the following books\n:";

// Construct  SQL query
$query = "select booktitle,category,author,publishdate
			from Book
      natural join
			(select bookid,status from checkedout natural join bookcopy where mid = '".$loginUsername."' and (status = 'Overdue' or status = 'Holding')) as B";
if (!$result = @ mysqli_query ($connection,$query))
  {  echo "Search query error";
    //echo $query;
    echo "<a href=\"http://localhost:8080/login.html\">GO BACK</a>";
    exit;
  };
  // Show Query Result
    echo "<table>";
    echo "<tr><td>Booktitle</td><td>Category</td><td>Author</td><td>Publishdate</td></tr>";
    while($row = mysqli_fetch_array($result))
        {
          echo "<tr>";
          echo "<td>" . $row['booktitle'] . "</td>";
          echo "<td>" . $row['category'] . "</td>";
          echo "<td>" . $row['author'] . "</td>";
          echo "<td>" . $row['publishdate'] . "</td>";
          echo "</tr>";
          }
    echo "</table>";
echo "<a href=\"http://localhost:8080/login.html\">Start Over</a>";

?>