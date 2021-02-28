
<!-- reference for this code: https://www.youtube.com/watch?v=15hVqug7bjM&list=PLBOh8f9FoHHhRk0Fyus5MMeBsQ_qwlAzG&index=10  -->


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

$user = "";

if (isset($_GET['username']))
{
  if (query('SELECT username FROM users WHERE username=:username', array(':username'=>$_GET['username']))){

    $user = query('SELECT username FROM users WHERE username=:username', array(':username'=>$_GET['username']))[0]['username'];
    $follower = $_SESSION['use'];
    $username = $user;

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

  $post = "";
  if (isset($_POST['tweet'])){
    $tweet = $_POST['post'];
    $profileuser = $_SESSION['use'];

    if (strlen($tweet) < 150 && strlen($tweet) >= 1)
    {
      query('INSERT INTO posts VALUES (:user, :post, datetime(), 0)', array(':user'=>$profileuser, ':post'=>$tweet));
    }
    else {
      echo "post too small or too big";
    }


  }
  $sentposts = query('SELECT * FROM posts WHERE user=:user ORDER BY time DESC', array(':user'=>$user));
  $post = "";
  foreach($sentposts as $t){
    $post .= $t['body']."</br /></br />".$t['time']."<hr /></br />";
  }

}
}


?>

<h1> <?php echo $user; ?>'s profile</h1>
<?php $count = query('SELECT COUNT(follower) as nFollowers FROM followers WHERE user=:username', array(':username'=>$username)); ?>
<h1>Follower count: <?php echo $count[0]["nFollowers"]; ?></h1>

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


<form action="profile.php?username=<?php echo $user; ?>" method="post">
  <textarea name="post" rows="10" cols="100"></textarea>
  <input type="submit" name="tweet" value="Post a tweet">
</form>


<div class="posts">
  <?php echo $post; ?>
</div>

<a href="home.php">Home page</a>
