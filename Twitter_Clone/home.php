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
  a{
    font-family: Montserrat;
    font-weight: 550;
    font-size: 1.1em;
  }
  .homepage{
    width: 100%;
    height: 45em;
    background-color: #F5F8FA;
  }
  .homepage2{
    width: 100%;
    height: auto;
    background-color: #F5F8FA;
  }
  .darkmode .homepage, .darkmode .homepage2, .darkmode .wheader, .darkmode .header, .darkmode .header2, .darkmode .static, .darkmode .posts, .darkmode .yourpage, .darkmode li, .darkmode ul{
    background-color: black;
  }
  .posts{
    width: 50%;
    display: inline-block;
    background-color: #F5F8FA;
    margin: 0em 0em 0em 0em;
  }
  .static{
    width: 42%;
    text-align: center;
    display: inline-block;
    background-color: #F5F8FA;
    position: fixed;
  }
  .darkmode .taskbar{
    background-color: #657786
;
  }
  .taskbar{
    background-color: #E1E8ED;
    margin: 6em 6em;
    padding: 1.5em 0em;
    border-radius: 3em;
  }
  input[type=text]{
    font-family: Montserrat;
    font-size: 1em;
  }
  .darkmode input[type=text]{
    background-color: #AAB8C2;
    border: black;
  }
  input[type=submit]{
  text-align: center;
  color: #F5F8FA;
  background-color: #1DA1F2;
  border: 0em;
  padding: .5em;
  font-family: Montserrat;
  font-size: 1em;
  border-radius: .75em;
  }
  .darkmode input[type=submit]{
    color: #AAB8C2;
  }
  input[type=submit]:hover{
  color:#1DA1F2;
  background-color: #E1E8ED;
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
  .darkmode .posts input[type=submit]{
    background-color: #AAB8C2;
    color: #657786;
  }
  .posts input[type=submit]:hover{
  color:#E1E8ED;
  background-color: #1DA1F2;
  }
  textarea{
    font-family: Montserrat;
    font-size: 1em;
    margin-top: 1em;
  }
  .darkmode textarea{
    background-color: #AAB8C2;
    border: black;
  }
  .text{
    display: block;
    margin: 1.5em;
  }
  .username, .tweetbody, .footer, .retweets{
    font-family: Montserrat;
    color: white;
    text-decoration: none;
  }
  .darkmode .username, .darkmode .tweetbody, .darkmode .footer, .darkmode .retweets{
    font-family: Montserrat;
    color: #AAB8C2;
    text-decoration: none;
  }
  .username{
    font-size: 1.2rem;
    margin: 0em 0em .4em 0em;
  }
  .tweetbody{
    margin: 1em 0em 1em 1em;
    font-size: 1em;
  }
  .footer, .retweets{
    font-size: .8rem;
  }
  .form, .footer, .likebutton, form, .retweets{
    display: inline;
  }
  .posts{
    margin: 6em 0em 0em 40em;
  }
  .posted{
    background-color: #1DA1F2;
    border-radius: 1em;
    padding: 2em;
    margin: 2em 2em;
  }
  a{
    text-decoration: none;
    color: white;
  }
  .darkmode a{
    color: #AAB8C2;
  }
  .header3{
    font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Ubuntu, "Helvetica Neue", sans-serif; ;
    font-size: 5em;
    font-weight: bold;
    color: #1DA1F2;
  }
</style>
</head>
<body>


<?php session_start();

//the "session" thing is what lets you log in/stay logged in

date_default_timezone_set("America/Chicago");

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

//header for website with a bunch of divs for the css file
echo "<div class='wheader'>";
echo "<div class='header'><img src='twitter.png' alt='Twitter' style='width:80px;height:80px;'></div>";
echo "<div class='header2'><ul>";
echo "<li><div class='yourpage'><a href='logout.php'>Logout</a></div></li>";
echo "<li><div class='yourpage'><a href='javascript:dark()'>Change Theme</button></div></li>";
echo "<li><div class='yourpage'><a href='profile.php?username=" .$_SESSION['use']. "'>Profile</a></div></li>";
echo "<li><div class='yourpage'><a href='home.php'>Home</a></div></li>";
echo "</ul></div></div>";

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
    $error2 = '<div id="errorflag">Username Invalid!</div>';
   }
 }

//tweet functionality
 if (isset($_POST['tweet'])){
   $tweet = $_POST['post'];
   $profileuser = $_SESSION['use'];

   if (strlen($tweet) < 150 && strlen($tweet) >= 1)
   {
     query('INSERT INTO posts VALUES (null, :user, :post, datetime(), 0, 0, null)', array(':user'=>$profileuser, ':post'=>$tweet));
   }
   else {
     $error1 = '<div id="errorflag">Incorrect Post Size!</div>';
   }
 }

//like functionality
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

//delete functionality
   if (isset($_POST['delete']))
   {
     if (query('SELECT postid FROM posts WHERE postid=:postid AND user=:user', array(':postid'=>$_GET['postid'], 'user'=>$_SESSION['use'])))
     {
       query('DELETE FROM posts WHERE postid=:postid AND user=:user', array(':postid'=>$_GET['postid'], 'user'=>$_SESSION['use']));
       query('DELETE FROM postlikes WHERE postid=:postid', array(':postid'=>$_GET['postid']));
       query('DELETE FROM postretweets WHERE postid=:postid AND user=:user', array(':postid'=>$_GET['postid'], 'user'=>$_SESSION['use']));
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
          $p .= "<div style='opacity:0.5;'> Retweeted tweet from: "."<a href='profile.php?username=".$t['user']."'>".$t['user']."</a> </div>".$t['body'];
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

//this is what makes the tweets show up
$posts = query('SELECT DISTINCT posts.body, posts.time, posts.user, posts.likes, posts.retweets, posts.postid FROM posts, followers WHERE (posts.user=followers.user AND followers.follower=:user) OR (posts.user=:user) ORDER BY time DESC', array(':user'=>$_SESSION['use']));
$post = "";
foreach($posts as $t){
  $userlink = $t['user'];
  $user = $_SESSION['use'];

  //if the tweet is your own (lets you like/unlike + delete)
  if (query('SELECT user FROM posts WHERE user=:user AND postid=:postid', array('user'=>$_SESSION['use'], ':postid'=>$t['postid'])))
  {
    if (!query('SELECT postid FROM postlikes WHERE postid=:postid AND user=:user', array(':postid'=>$t['postid'], 'user'=>$_SESSION['use'])))
    {
      $post .= "<div class='posted'>"."<div class='username'>"."<a href='profile.php?username=".$t['user']."'>"."@".$t['user']."</a>"."</div>"."<div class='tweetbody'>".$t['body']." "."</div>"."<div class='footer'>".$t['time'].'&nbsp;&nbsp;'."Likes: ".$t['likes']."</div>"."
      <div class='likebutton'>
      <form action='home.php?&postid=".$t['postid']."' method='post'>
        <input type='submit' name='like' value='Like ❤'>
      </form>
      "."<div class='retweets'>"."Retweets: ".$t['retweets']."</div>"."
      <form action='home.php?&postid=".$t['postid']."' method='post'>
        <input type='submit' name='delete' value='Delete'>
      </form>
      </div></div>";
    }
    else {
      $post .= "<div class='posted'>"."<div class='username'>"."<a href='profile.php?username=".$t['user']."'>"."@".$t['user']."</a>"."</div>"."<div class='tweetbody'>".$t['body']." "."</div>"."<div class='footer'>".$t['time'].'&nbsp;&nbsp;'."Likes: ".$t['likes']."</div>"."
      <div class='likebutton'>
      <form action='home.php?&postid=".$t['postid']."' method='post'>
        <input type='submit' name='unlike' value='Unlike'>
      </form>
      "."<div class='retweets'>"."Retweets: ".$t['retweets']."</div>"."
      <form action='home.php?&postid=".$t['postid']."' method='post'>
        <input type='submit' name='delete' value='Delete'>
      </form>
      </div></div>";
    }
  }
  //if the post is not your own and has not been retweeted yet (needs to be like this because of the like functionality)
  else if (!query('SELECT postid FROM postretweets WHERE postid=:postid AND user=:user', array(':postid'=>$t['postid'], 'user'=>$_SESSION['use'])))
  {
    if (!query('SELECT postid FROM postlikes WHERE postid=:postid AND user=:user', array(':postid'=>$t['postid'], 'user'=>$_SESSION['use'])))
    {
      $post .= "<div class='posted'>"."<div class='username'>"."<a href='profile.php?username=".$t['user']."'>"."@".$t['user']."</a>"."</div>"."<div class='tweetbody'>".$t['body']." "."</div>"."<div class='footer'>".$t['time'].'&nbsp;&nbsp;'."Likes: ".$t['likes']."</div>"."
      <div class='likebutton'>
      <form action='home.php?&postid=".$t['postid']."' method='post'>
        <input type='submit' name='like' value='Like ❤'>
      </form>
      "."<div class='retweets'>"."Retweets: ".$t['retweets']."</div>"."
      <form action='home.php?&postid=".$t['postid']."' method='post'>
        <input type='submit' name='retweet' value='Retweet 🔄'>
      </form>
      </div></div>";
    }
    else {
      $post .= "<div class='posted'>"."<div class='username'>"."<a href='profile.php?username=".$t['user']."'>"."@".$t['user']."</a>"."</div>"."<div class='tweetbody'>".$t['body']." "."</div>"."<div class='footer'>".$t['time'].'&nbsp;&nbsp;'."Likes: ".$t['likes']."</div>"."
      <div class='likebutton'>
      <form action='home.php?&postid=".$t['postid']."'>
        <input type='submit' name='unlike' value='Unlike'>
      </form>
      "."<div class='retweets'>"."Retweets: ".$t['retweets']."</div>"."
      <form action='home.php?&postid=".$t['postid']."'>
        <input type='submit' name='retweet' value='Retweet 🔄'>
      </form>
      </div></div>";
    }
  }
  //if the tweet is not your own and has been retweeted
  else
  {
    if (!query('SELECT postid FROM postlikes WHERE postid=:postid AND user=:user', array(':postid'=>$t['postid'], 'user'=>$_SESSION['use'])))
    {
      $post .= "<div class='posted'>"."<div class='username'>"."<a href='profile.php?username=".$t['user']."'>"."@".$t['user']."</a>"."</div>"."<div class='tweetbody'>".$t['body']." "."</div>"."<div class='footer'>".$t['time'].'&nbsp;&nbsp;'."Likes: ".$t['likes']."</div>"."
      <div class='likebutton'>
      <form action='home.php?&postid=".$t['postid']."' method='post'>
        <input type='submit' name='like' value='Like ❤'>
      </form>
      "."<div class='retweets'>"."Retweets: ".$t['retweets']."</div>"."
      <form action='home.php?&postid=".$t['postid']."' method='post'>
        <input type='submit' name='unretweet' value='Unretweet'>
      </form>
      </div></div>";
    }
    else {
      $post .= "<div class='posted'>"."<div class='username'>"."<a href='profile.php?username=".$t['user']."'>"."@".$t['user']."</a>"."</div>"."<div class='tweetbody'>".$t['body']." "."</div>"."<div class='footer'>".$t['time'].'&nbsp;&nbsp;'."Likes: ".$t['likes']."</div>"."
      <div class='likebutton'>
      <form action='home.php?&postid=".$t['postid']."' method='post'>
        <input type='submit' name='unlike' value='Unlike'>
      </form>
      "."<div class='retweets'>"."Retweets: ".$t['retweets']."</div>"."
      <form action='home.php?&postid=".$t['postid']."' method='post'>
        <input type='submit' name='unretweet' value='Unretweet'>
      </form>
      </div></div>";
    }
  }

 }

//all the forms and stuff (dark mode at the bottom)
 ?>
<div class="homepage">
<div class="homepage2">

<div class="static">
  <div class="taskbar">
  <div class="header3">Twitter</div>
  <form action='home.php' method='post'>
    <div class="search">
    <?php if(isset($error2)){ echo $error2; } ?>
      <div class="text"><input type='text' name='username'></div>
      <input type='submit' name='search' value='Search for User'>
    </div> 
  </form>
  <div class="post">
    <form action="home.php" method="post">
    <?php if(isset($error1)){ echo $error1; } ?>
      <div class="text"><textarea name='post' rows='10' cols='33'></textarea></div>
      <input type='submit' name='tweet' value='Post a tweet'>
    </form>
  </div></div>
</div>
<div class="posts">
  <div class="margin"><?php echo $post ?></div>
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
<style>
  #errorflag{
    color: red;
    font-family: Montserrat;
    margin: 1em 0em 0em;
  }
</style>
</html>
