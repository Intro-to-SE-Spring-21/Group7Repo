
<!-- reference for this code: https://www.youtube.com/watch?v=15hVqug7bjM&list=PLBOh8f9FoHHhRk0Fyus5MMeBsQ_qwlAzG&index=10 -->

<!DOCTYPE html>
<html>
<link rel="stylesheet" href="style.css">
<style>
  .wheader{
    height: auto;
    width: 100%;
    background-color: #F5F8FA;
    position: fixed;
    z-index: 999;
  }
  .header{
    width: 50%;
    display: inline-block;
  }
  .header2{
    width: 50%;
    display: inline-block;
    background-color: #F5F8FA;
  }
  .yourpage{
    margin: 0em 6em 1.5em 0em;
  }
  .darkmode .webpage, .darkmode .webpage2, .darkmode .wheader, .darkmode .header, .darkmode .header2, .darkmode .static, .darkmode .posts, .darkmode .yourpage, .darkmode li, .darkmode ul{
    background-color: black;
  }
  a{
    font-family: Montserrat;
    font-weight: 550;
    font-size: 1.1em;
  }
  .header3{
    margin: 2em 0em 0em 0em;
    font-family: Montserrat;
    font-weight: bold;
    font-size: 4rem;
    color: #1DA1F2;
  }
  h3{
    font-family: Montserrat;
    font-weight: 400;
    font-size: 1.5rem;
    padding: .5em 0em 0em 1.5em;
    color: #1DA1F2;
  }
  .username, .tweetbody, .footer, .retweets{
    font-family: Montserrat;
    color: white;
    text-decoration: none;
  }
  .tweetbody{
    margin: 1em 0em 1em .5em;
    font-size: 1em;
  }
  .footer, .retweets{
    font-size: .8rem;
  }
  .form, .footer, .likebutton, form, .retweets{
    display: inline;
  }
  .posts{
    width: 50%;
    display: inline-block;
    background-color: #F5F8FA;
    margin: 8em 0em 0em 40em;
    z-index: 10;
  }
  .posted{
    background-color: #1DA1F2;
    border-radius: 1em;
    padding: 2em;
    margin: 2em 2em;
  }
  .posts input[type=submit]{
  text-align: center;
  color: #1DA1F2;
  background-color: #F5F8FA;
  border: 0em;
  padding: .2em .5em;
  font-family: Montserrat;
  font-size: 1em;
  border-radius: .75em;
  }
  .posts input[type=submit]:hover{
  color:#E1E8ED;
  background-color: #1DA1F2;
  }
  .static{
    z-index: 1;
    width: 35%;
    margin: 0em 0em 0em 6em;
    display: inline-block;
    background-color: #F5F8FA;
    position: fixed;
  }
  .webpage{
    width: 100%;
    height: auto;
    background-color: #F5F8FA;
  }
  .webpage2{
    width: 100%;
    height: 45em;
    background-color: #F5F8FA;
  }
  textarea{
    font-family: Montserrat;
    font-size: 1em;
    margin: 0em 0em 0em 3em;
  }
  .static input[type=text]{
    font-family: Montserrat;
    font-size: 1em;
  }
  .static input[type=submit]{
  text-align: center;
  color: #F5F8FA;
  background-color: #1DA1F2;
  border: 0em;
  padding: .5em;
  margin: 2em 0em 0em 10em;
  font-family: Montserrat;
  font-size: 1em;
  border-radius: .75em;
  }
  a{
    text-decoration: none;
    color: white;
  }
  .other input[type=submit]{
    margin: 0em 0em 0em 2em;
    width: 8em;
  }
  .darkmode input[type=text]{
    background-color: #AAB8C2;
    border: black;
  }
  .darkmode input[type=submit]{
    color: #AAB8C2;
  }
  .darkmode input[type=submit]:hover{
    color: #657786;
    background-color: #AAB8C2;
  }
  .darkmode .posts input[type=submit]{
    color: #AAB8C2;
    background-color: #657786;
  }
  .darkmode .posts input[type=submit]:hover{
    color: #657786;
    background-color: #AAB8C2;
  }
  .darkmode textarea{
    background-color: #AAB8C2;
    border: black;
  }
  .darkmode .tweetbody, .darkmode .footer, .darkmode .retweets{
    font-family: Montserrat;
    color: #AAB8C2;
    text-decoration: none;
  }
  #errorflag{
    color: red;
    font-family: Montserrat;
    margin: -2em 9.5em 1em;
  }
</style>
</head>
<body>
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

//header
echo "<div class='wheader'>";
echo "<div class='header'><img src='twitter.png' alt='Twitter' style='width:80px;height:80px;'></div>";
echo "<div class='header2'><ul>";
echo "<li><div class='yourpage'><a href='logout.php'>Logout</a></div></li>";
echo "<li><div class='yourpage'><a href='javascript:dark()'>Change Theme</button></div></li>";
echo "<li><div class='yourpage'><a href='profile.php?username=" .$_SESSION['use']. "'>Profile</a></div></li>";
echo "<li><div class='yourpage'><a href='home.php'>Home</a></div></li>";
echo "</ul></div></div>";




//initializing username variable
$user = "";

//this checks the profile you are on to see if it is you or someone else
if (isset($_GET['username']))
{
  if (query('SELECT username FROM users WHERE username=:username', array(':username'=>$_GET['username']))){

    //sets follower equal to you, and username equal to whoevers profile you are on
    $user = query('SELECT username FROM users WHERE username=:username', array(':username'=>$_GET['username']))[0]['username'];
    $follower = $_SESSION['use'];
    $blocker = $_SESSION['use'];
    $username = $user;

    //if page you are on is not yours, it will let you follow

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

    //block functionality

    if (isset($_POST['unblock']))
    {
      if (query('SELECT blocker FROM blockers WHERE user=:username AND blocker=:blocker', array(':username'=>$user, 'blocker'=>$blocker))){
        query('DELETE FROM blockers WHERE user=:username AND blocker=:blocker', array(':username'=>$user, 'blocker'=>$blocker));
      }
      $blocking = False;
    }
    if (isset($_POST['block']))
    {
      if (query('SELECT follower FROM followers WHERE user=:username AND follower=:follower', array(':username'=>$user, 'follower'=>$follower))){
        query('DELETE FROM followers WHERE user=:username AND follower=:follower', array(':username'=>$user, 'follower'=>$follower));
      }
      $following = False;
      if (!query('SELECT blocker FROM blockers WHERE user=:username AND blocker=:blocker', array(':username'=>$user, 'blocker'=>$blocker))){
        query('INSERT INTO blockers VALUES (:user, :blocker)', array(':user'=>$user, 'blocker'=>$blocker));
      }

      $blocking = True;
    }
    if (query('SELECT blocker FROM blockers WHERE user=:username AND blocker=:blocker', array(':username'=>$user, 'blocker'=>$blocker))){
      $blocking = True;
    }

  //for posting tweets, kinda straightforward


  $post = "";
  if (isset($_POST['tweet'])){
    $tweet = $_POST['post'];
    $profileuser = $_SESSION['use'];

    if (strlen($tweet) < 150 && strlen($tweet) >= 1)
    {
      query('INSERT INTO posts VALUES (null, :user, :post, datetime(), 0, 0, null)', array(':user'=>$profileuser, ':post'=>$tweet));
    }
    else {
      $error2 = '<div id="errorflag">Incorrect Post Size!</div>';
    }


  }
// like functionality
  if (isset($_POST['like']))
  {
    if (!query('SELECT user FROM postlikes WHERE postid=:postid AND user=:user', array(':postid'=>$_GET['postid'], 'user'=>$_SESSION['use'])))
    {
      query('UPDATE posts SET likes=likes+1 WHERE postid=:postid', array(':postid'=>$_GET['postid']));
      query('INSERT INTO postlikes VALUES (:postid, :user)', array(':postid'=>$_GET['postid'], 'user'=>$_SESSION['use']));
    }
  }
   if (isset($_POST['unlike']))
   {
    {
      query('UPDATE posts SET likes=likes-1 WHERE postid=:postid', array(':postid'=>$_GET['postid']));
      query('DELETE FROM postlikes WHERE postid=:postid AND user=:user', array(':postid'=>$_GET['postid'], 'user'=>$_SESSION['use']));
    }
    }

  }
//delete functionality
  if (isset($_POST['delete']))
  {
    if (query('SELECT postid FROM posts WHERE postid=:postid AND user=:user', array(':postid'=>$_GET['postid'], 'user'=>$_SESSION['use'])))
    {
      query('DELETE FROM posts WHERE postid=:postid AND user=:user', array(':postid'=>$_GET['postid'], 'user'=>$_SESSION['use']));
      query('DELETE FROM postlikes WHERE postid=:postid', array(':postid'=>$_GET['postid']));
    }


  }
//retweet functionality
  if (isset($_POST['retweet']))
  {
    if (!query('SELECT user FROM postretweets WHERE postid=:postid AND user=:user', array(':postid'=>$_GET['postid'], 'user'=>$_SESSION['use'])))
    {
      $selectedpost = query('SELECT * FROM posts WHERE postid=:postid', array(':postid'=>$_GET['postid']));
      $p = "";
      foreach($selectedpost as $t)
      {
         $p .= "<div style='opacity:0.5;'> Retweeted tweet from: "."<a href='profile.php?username=".$t['user']."'>".$t['user']."</a> </div>"."&nbsp;&nbsp;&nbsp;&nbsp;".$t['body'];
         query('INSERT INTO posts VALUES (null, :user, :post, datetime(), 0, 0, :retweetpostid)', array(':user'=>$_SESSION['use'], ':post'=>$p, ':retweetpostid'=>$t['postid']));
         query('UPDATE posts SET retweets=retweets+1 WHERE postid=:postid', array(':postid'=>$_GET['postid']));
         query('INSERT INTO postretweets VALUES (:postid, :user)', array(':postid'=>$_GET['postid'], 'user'=>$_SESSION['use']));
      }

    }
  }
  if (isset($_POST['unretweet']))
  {
    if (query('SELECT user FROM postretweets WHERE postid=:postid AND user=:user', array(':postid'=>$_GET['postid'], 'user'=>$_SESSION['use'])))
    {
      query('UPDATE posts SET retweets=retweets-1 WHERE postid=:postid', array(':postid'=>$_GET['postid']));
      query('DELETE FROM postretweets WHERE postid=:postid AND user=:user', array(':postid'=>$_GET['postid'], 'user'=>$_SESSION['use']));
      query('DELETE FROM posts WHERE retweetpostid=:postid AND user=:user', array(':postid'=>$_GET['postid'], 'user'=>$_SESSION['use']));
    }
  }

//this is what makes the posts show up, basically the same as the code in home.php 
  $sentposts = query('SELECT * FROM posts WHERE user=:user ORDER BY time DESC', array(':user'=>$user));
  $post = "";
  foreach($sentposts as $t){

    if (query('SELECT user FROM posts WHERE user=:user AND postid=:postid', array('user'=>$_SESSION['use'], ':postid'=>$t['postid'])))
    {
      if (!query('SELECT postid FROM postlikes WHERE postid=:postid AND user=:user', array(':postid'=>$t['postid'], 'user'=>$_SESSION['use'])))
      {
        $post .= "<div class='posted'>"."<div class='tweetbody'>".$t['body']." "."</div>"."<div class='footer'>".$t['time'].'&nbsp;&nbsp;'."Likes: ".$t['likes']."</div>"."
        <div class='likebutton'>
        <form action='profile.php?username=$user&postid=".$t['postid']."' method='post'>
          <input type='submit' name='like' value='Like ❤'>
        </form>
        "."<div class='retweets'>"."Retweets: ".$t['retweets']."</div>"."
        <form action='profile.php?username=$user&postid=".$t['postid']."' method='post'>
          <input type='submit' name='delete' value='Delete'>
        </form>
        </div></div>";
      }
      else {
        $post .= "<div class='posted'>"."<div class='tweetbody'>".$t['body']." "."</div>"."<div class='footer'>".$t['time'].'&nbsp;&nbsp;'."Likes: ".$t['likes']."</div>"."
        <div class='likebutton''>
        <form action='profile.php?username=$user&postid=".$t['postid']."' method='post'>
          <input type='submit' name='unlike' value='Unlike'>
        </form>
        "."<div class='retweets'>"."Retweets: ".$t['retweets']."</div>"."
        <form action='profile.php?username=$user&postid=".$t['postid']."' method='post'>
          <input type='submit' name='delete' value='Delete'>
        </form>

        </div></div>";
      }
    }
    else if (!query('SELECT postid FROM postretweets WHERE postid=:postid AND user=:user', array(':postid'=>$t['postid'], 'user'=>$_SESSION['use'])))
    {
      if (!query('SELECT postid FROM postlikes WHERE postid=:postid AND user=:user', array(':postid'=>$t['postid'], 'user'=>$_SESSION['use'])))
      {
        $post .= "<div class='posted'>"."<div class='tweetbody'>".$t['body']." "."</div>"."<div class='footer'>".$t['time'].'&nbsp;&nbsp;'."Likes: ".$t['likes']."</div>"."
        <div class='likebutton'>
        <form action='profile.php?username=$user&postid=".$t['postid']."' method='post'>
          <input type='submit' name='like' value='Like ❤'>
        </form>
        "."<div class='retweets'>"."Retweets: ".$t['retweets']."</div>"."
        <form action='profile.php?username=$user&postid=".$t['postid']."' method='post'>
          <input type='submit' name='retweet' value='Retweet🔄'>
        </form>
        </div></div>";
      }
      else {
        $post .= "<div class='posted'>"."<div class='tweetbody'>".$t['body']." "."</div>"."<div class='footer'>".$t['time'].'&nbsp;&nbsp;'."Likes: ".$t['likes']."</div>"."
        <div class='likebutton'>
        <form action='profile.php?username=$user&postid=".$t['postid']."' method='post'>
          <input type='submit' name='unlike' value='Unlike'>
        </form>
        "."<div class='retweets'>"."Retweets: ".$t['retweets']."</div>"."
        <form action='profile.php?username=$user&postid=".$t['postid']."' method='post'>
          <input type='submit' name='retweet' value='Retweet🔄'>
        </form>
        </div></div>";
      }
    }
    else
    {
      if (!query('SELECT postid FROM postlikes WHERE postid=:postid AND user=:user', array(':postid'=>$t['postid'], 'user'=>$_SESSION['use'])))
      {
        $post .= "<div class='posted'>"."<div class='tweetbody'>".$t['body']." "."</div>"."<div class='footer'>".$t['time'].'&nbsp;&nbsp;'."Likes: ".$t['likes']."</div>"."
        <div class='likebutton'>
        <form action='profile.php?username=$user&postid=".$t['postid']."' method='post'>
          <input type='submit' name='like' value='Like ❤'>
        </form>
        "."<div class='retweets'>"."Retweets: ".$t['retweets']."</div>"."
        <form action='profile.php?username=$user&postid=".$t['postid']."' method='post'>
          <input type='submit' name='unretweet' value='Unretweet'>
        </form>
        </div></div>";
      }
      else {
        $post .= "<div class='posted'>"."<div class='tweetbody'>".$t['body']." "."</div>"."<div class='footer'>".$t['time'].'&nbsp;&nbsp;'."Likes: ".$t['likes']."</div>"."
        <div class='likebutton'>
        <form action='profile.php?username=$user&postid=".$t['postid']."' method='post'>
          <input type='submit' name='unlike' value='Unlike'>
        </form>
        "."<div class='retweets'>"."Retweets: ".$t['retweets']."</div>"."
        <form action='profile.php?username=$user&postid=".$t['postid']."' method='post'>
          <input type='submit' name='unretweet' value='Unretweet'>
        </form>
        </div></div>";
      }
    }




  }

}



?>

<!-- counts followers -->
<div class="webpage2">
<div class="webpage">
<div class="static">
<br />
<div class= "profilename">
<div class="header3">@<?php echo $user; ?></div>
<?php $count = query('SELECT COUNT(follower) as nFollowers FROM followers WHERE user=:username', array(':username'=>$username)); ?>
<h3>Followers: <?php echo $count[0]["nFollowers"]; ?></h3><br />
</div>

<!-- form for follow/unfollow button -->
<div class="margins">
<form action="profile.php?username=<?php echo $user; ?>" method="post">
  <?php
    if($follower != $username)
    {
      if ($following == True && $blocking == false) {
        echo '<div class="other"><input type="submit" name="unfollow" value ="unfollow"></div>';
      }
      else if ($following == false && $blocking == false)
      {
        echo '<div class="other"><input type="submit" name="follow" value ="follow"></div>';
      }
      else
      {
        echo '';
      }
    }

   ?>
</form>
<br />
<div class="block">
<form action="profile.php?username=<?php echo $user; ?>" method="post">
  <?php
    if($blocker != $username)
    {
      if ($blocking) {
        echo '<div class = "other"><input type="submit" name="unblock" value ="unblock"></div>';
      }
      else {
        echo '<div class = "other"><input type="submit" name="block" value ="block"></div>';
      }
    }

   ?>
</form>
</div>
</div>
<br />
<!-- form for posting tweets -->
<form action="profile.php?username=<?php echo $user; ?>" method="post">
  <?php
  if($follower == $username)
  {
    if(isset($error2)){ echo $error2; };
    echo "<textarea name='post' rows='10' cols='40'></textarea><br />";
    echo "<input type='submit' name='tweet' value='Post a tweet'><br></br>";
  }

  ?>
</form>
</div>


<div class="posts">
  <?php echo $post; ?>
</div>
<script>
function dark() {
  var element = document.body;
  element.classList.toggle("darkmode");

}
</script>
</div>
</div>
</body>
</html>
