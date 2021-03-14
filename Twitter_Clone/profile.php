
<!-- reference for this code: https://www.youtube.com/watch?v=15hVqug7bjM&list=PLBOh8f9FoHHhRk0Fyus5MMeBsQ_qwlAzG&index=10 -->


<?php session_start();

error_reporting(E_ALL ^ E_WARNING);

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


echo "<a href='home.php'>Home page</a>";

//initializing username variable
$user = "";

//this checks the profile you are on to see if it is you or someone else
if (isset($_GET['username']))
{
  if (query('SELECT username FROM users WHERE username=:username', array(':username'=>$_GET['username']))){

    //sets follower equal to you, and username equal to whoevers profile you are on
    $user = query('SELECT username FROM users WHERE username=:username', array(':username'=>$_GET['username']))[0]['username'];
    $follower = $_SESSION['use'];
    $username = $user;

    //if page you are on is not yours, it will let you follow
    if($follower != $username)

    if (isset($_POST['follow']))
    {
      if (!query('SELECT follower FROM followers WHERE user=:username AND follower=:follower', array(':username'=>$user, 'follower'=>$follower))){
        query('INSERT INTO followers VALUES (:user, :follower)', array(':user'=>$user, 'follower'=>$follower));
      }
      else {
        echo "you are following this person";
      }
      $following = True;
    }
    if (isset($_POST['unfollow']))
    {
      if (query('SELECT follower FROM followers WHERE user=:username AND follower=:follower', array(':username'=>$user, 'follower'=>$follower))){
        query('DELETE FROM followers WHERE user=:username AND follower=:follower', array(':username'=>$user, 'follower'=>$follower));
      }
      $following = False;
    }

    if (query('SELECT follower FROM followers WHERE user=:username AND follower=:follower', array(':username'=>$user, 'follower'=>$follower))){
      $following = True;
  }

  //for posting tweets, kinda straightforward


  $post = "";
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

  }

  $sentposts = query('SELECT * FROM posts WHERE user=:user ORDER BY time DESC', array(':user'=>$user));
  $post = "";
  foreach($sentposts as $t){

    if (!query('SELECT postid FROM postlikes WHERE postid=:postid AND user=:user', array(':postid'=>$t['postid'], 'user'=>$_SESSION['use'])))
    {
      $post .= $t['body']."</br /></br />".$t['time']."<br></br>"."likes:".$t['likes']."
      <form action='profile.php?username=$user&postid=".$t['postid']."' method='post'>
        <input type='submit' name='like' value='Like❤'>
      </form>
      <hr /></br />";
    }
    else {
      $post .= $t['body']."</br /></br />".$t['time']."<br></br>"."likes:".$t['likes']."
      <form action='profile.php?username=$user&postid=".$t['postid']."' method='post'>
        <input type='submit' name='unlike' value='Unlike'>
      </form>
      <hr /></br />";
    }


  }

}



?>

<!-- counts followers -->

<h1> <?php echo $user; ?>'s profile</h1>
<?php $count = query('SELECT COUNT(follower) as nFollowers FROM followers WHERE user=:username', array(':username'=>$username)); ?>
<h1>Follower count: <?php echo $count[0]["nFollowers"]; ?></h1>

<!-- form for follow/unfollow button -->

<form action="profile.php?username=<?php echo $user; ?>" method="post">
  <?php

    if($follower != $username)
    {
      if ($following) {
        echo '<input type="submit" name="unfollow" value ="unfollow">';
      }
      else {
        echo '<input type="submit" name="follow" value ="follow">';
      }
    }

   ?>

</form>

<!-- form for posting tweets -->

<form action="profile.php?username=<?php echo $user; ?>" method="post">
  <?php
  if($follower == $username)
  {
    echo "<textarea name='post' rows='10' cols='100' style='background-color:#0092D6;'></textarea>";
    echo "<input type='submit' name='tweet' value='Post a tweet'>";
  }

  ?>
</form>




<div class="posts">
  <?php echo $post; ?>
</div>

<a href="home.php">Home page</a>


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
