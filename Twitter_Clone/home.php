<?php session_start();

function connect() {
  $pdo = new PDO('sqlite:mydb.db');

  return $pdo;
}
function query($query, $parameter = array()){

  $statement = connect()->prepare($query);
  $statement->execute($parameter);

  if(explode(' ', $query)[0] == 'SELECT') {
  $data = $statement->fetchAll();
  return $data;
  }
}

echo "You are logged in as " . $_SESSION['use'] . ".";

echo "<a href='profile.php?username=" .$_SESSION['use']. "'>Your Page</a>";

 ?>

 <h3>Search for a user</h3>
 <?php
 echo "<form action='home.php' method='post'>";
 echo "<input type='text' name='username'>";
 echo "<input type='submit' name='search' value='Search'>";
 echo "</form>";

 if (isset($_POST['search'])) {
   $user = $_POST['username'];
   if (query('SELECT username FROM users WHERE username=:username', array(':username'=>$user))){
     $profile = "Location:profile.php?username=";
     $profile .= $user;
     if ($user != null){
       header($profile);
     }
   }
   else {
     echo "user does not exist";
   }
 }

 ?>

 <style>
 body {
   background-color: white;
   color: black;
 }
 .darkmode {
   background-color: black;
   color: white;
 }
 </style>

<a href="logout.php">Logout</a>
<h1>Group 7 Twitter Clone (we dont have a name yet...)</h1>

<script>
function dark() {
   var element = document.body;
   element.classList.toggle("darkmode");
}
</script>

<p></p>

<button onclick="dark()">dark mode</button>
