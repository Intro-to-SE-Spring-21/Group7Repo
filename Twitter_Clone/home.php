<?php session_start();

//the "session" thing is what lets you log in/stay logged in

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

echo "<a href='logout.php'>Logout</a><br></br>";

echo "<a href='profile.php?username=" .$_SESSION['use']. "'>Your Page</a>";


echo "<h1>Group 7 Twitter Clone (we still dont have a name)</h1>";

 ?>

<!-- form for searching for a user -->

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


 if (isset($_POST['tweet'])){
   $tweet = $_POST['post'];
   $profileuser = $_SESSION['use'];

   if (strlen($tweet) < 150 && strlen($tweet) >= 1)
   {
     query('INSERT INTO posts VALUES (null, :user, :post, datetime(), 0)', array(':user'=>$profileuser, ':post'=>$tweet));
   }
   else {
     echo "post too small or too big";
   }
 }

 if (isset($_GET['postid']))
 {
   if (!query('SELECT user FROM postlikes WHERE postid=:postid AND user=:user', array(':postid'=>$_GET['postid'], 'user'=>$_SESSION['use'])))
   {
     query('UPDATE posts SET likes=likes+1 WHERE postid=:postid', array(':postid'=>$_GET['postid']));
     query('INSERT INTO postlikes VALUES (:postid, :user)', array(':postid'=>$_GET['postid'], 'user'=>$_SESSION['use']));
   }
   else
   {
     query('UPDATE posts SET likes=likes-1 WHERE postid=:postid', array(':postid'=>$_GET['postid']));
     query('DELETE FROM postlikes WHERE postid=:postid AND user=:user', array(':postid'=>$_GET['postid'], 'user'=>$_SESSION['use']));
   }
   }



$posts = query('SELECT posts.body, posts.time, posts.user, posts.likes, posts.postid FROM posts, followers WHERE posts.user=followers.user AND followers.follower=:user ORDER BY time DESC', array(':user'=>$_SESSION['use']));
$post = "";
foreach($posts as $t){
  $userlink = $t['user'];

  if (!query('SELECT postid FROM postlikes WHERE postid=:postid AND user=:user', array(':postid'=>$t['postid'], 'user'=>$_SESSION['use'])))
  {
    $post .= $t['body']."</br /></br />"."<a href='profile.php?username=".$t['user']."'>".$t['user']."</a>"." ".$t['time']."<br></br>"."likes:".$t['likes']."
    <form action='home.php?&postid=".$t['postid']."' method='post'>
      <input type='submit' name='like' value='Like❤'>
    </form>
    <hr /></br />";
  }
  else {
    $post .= $t['body']."</br /></br />"."<a href='profile.php?username=".$t['user']."'>".$t['user']."</a>"." ".$t['time']."<br></br>"."likes:".$t['likes']."
    <form action='home.php?&postid=".$t['postid']."' method='post'>
      <input type='submit' name='unlike' value='Unlike'>
    </form>
    <hr /></br />";
  }

 }



 ?>

 <form action="home.php" method="post">

     <textarea name='post' rows='10' cols='100' style='background-color:#0092D6;'></textarea>
     <input type='submit' name='tweet' value='Post a tweet'>

 </form>

 <div class="posts">
   <?php echo $post; ?>
 </div>


<style>
input[type=text] {
  background-color: #0092D6;
  color: white;
}
body {
  font-family: Helvetica, sans-serif;
  background-color: black;
  color: white;
}
a:link {
  color: #0092D6;
  background-color: transparent;
  text-decoration: none;
}
a:visited {
  color: #0092D6;
  background-color: transparent;
  text-decoration: none;
}
a:hover {
  color: #0092D6;
  background-color: transparent;
  text-decoration: underline;
}
a:active {
  color: #0092D6;
  background-color: transparent;
  text-decoration: underline;
}
</style>
<!-- dark mode functionality -->
<!--
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




<script>
function dark() {
   var element = document.body;
   element.classList.toggle("darkmode");
}


</script>

<p></p>

<button onclick="dark()">dark mode</button>
-->
