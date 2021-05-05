<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
  <title> Group 8 Capstone Project </title>
  <meta charset="utf-8" />
  <meta name="Author" content="Ryan Morgan" />
  <meta name="generator" content="EMACS" />
  <link rel="stylesheet" href="capstone.css" />
  <style>
    a { text-decoration: none; }
    a:hover { text-decoration: underline; }
  </style>
  <div id="header">
    <p>Group 8 Capstone Project</p>
  </div>
</head>

<body>
  <div id="container">
    <div id="sidebar">
      <h3>New Posts from r/Gaming!</h3>
      <?php
       include 'reddit.php';
       $conn = new mysqli("localhost", "ryan", "pigMachine12", "capstone_db");
       // Check connection
       if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
      }
      $sql = "SELECT author, titles, selftext FROM reddit_api ORDER BY id desc LIMIT 10";
      $result = $conn->query($sql);
      if ($result->num_rows > 0) {
      // output data of each row
      while($row = $result->fetch_assoc()) {
      echo $row["author"] . " posted: <br><b>" . $row["titles"] . "</b><br>" . $row["selftext"]. "<br><br>";
      }
      } else { echo "0 results"; }
      $conn->close();
      ?>
    </div>

    <div id="sidebarLeft">
      <h3>Hot Posts from r/GameDeals!</h3>
      <?php
       $conn = new mysqli("localhost", "ryan", "pigMachine12", "capstone_db");
       // Check connection
       if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
      }
      $sql = "SELECT author, titles, selftext FROM reddit_gamedeals ORDER BY id desc LIMIT 15";
      $result = $conn->query($sql);
      if ($result->num_rows > 0) {
      // output data of each row
      while($row = $result->fetch_assoc()) {
      echo $row["author"] . " posted: <br><b>" . $row["titles"] . "</b><br><br>";
      }
      } else { echo "0 results"; }
      $conn->close();
      ?>
    </div>
    
    <div id="content">
      <p>Enter a game title to see data about the game.</p>
      <form name="form" action="" method="get">
	<input type="text" name="title" id="title" value="Game Title">
	<input type="submit" name="submit" />
      </form>
      <?php
       if ( isset( $_GET['submit'] )){
       echo '<h3>Results:</h3>';
       $title =  $_GET["title"];
       $conn = new mysqli("localhost", "ryan", "pigMachine12", "capstone_db");
       // Check connection
       if ($conn->connect_error) {
          die("Connection failed: " . $conn->connect_error);
       }

       // get slug from games database
       $SlugQL = "SELECT column2 FROM games WHERE column1  = '$title'";
       $slugResult = $conn->query($SlugQL);
       $slug = $slugResult->fetch_assoc()["column2"];

       // Search games database for title entered by user
       $sql2 = "SELECT * FROM games WHERE column1  = '$title'";
       $result2 = $conn->query($sql2);

       // search rawg database using slug value
       $sql = "SELECT * FROM rawg_api WHERE rawg_slug = '$slug'";
       $result = $conn->query($sql);

       // add pricing database code here
	$sql3 = "SELECT * FROM  test_isthereanyapi WHERE slug = '$slug'";
        $result3 = $conn->query($sql3);



       if ($result->num_rows > 0 && $result2->num_rows > 0 ) {
          // output data of each row
          while ($row = $result->fetch_assoc())  {
		  $row2 = $result2->fetch_assoc();
		  $row3 = $result3->fetch_assoc();
                if ($row["rawg_metacritic"] == NULL) {
                echo "<b>" . $row2["column1"]  . "</b><br>";
		echo "Release date: " . $row["rawg_release_date"] . "<br>Metacritic Score: Not Available" . "<br>Current Price: $" . $row3["price"] . "<br>Summary: " . $row2["column3"];
             } else {
               echo "<b>" . $row2["column1"]  . "</b><br>";
               echo "Release date: " . $row["rawg_release_date"] . " <br>Metacritic score: " . $row["rawg_metacritic"] . "<br>Current Price: $" . $row3["price"] . "<br>Summary: " . $row2["column3"];
	     }
          }
       } else { echo "That game has not been added to our database yet."; }
       $conn->close();
       }
      ?>
     </div>
  </div>
</body>
</html>
