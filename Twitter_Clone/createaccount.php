
<!-- these functions are just setting up the database stuff -->

<!DOCTYPE html>
<html>
<link rel="stylesheet" href="style.css">
</head>
<body>

<?php
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

$pdo = new PDO('sqlite:mydb.db');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//header

echo "<div class='header'>";
echo    "<div class='title'><img src='twitter.png' alt='Twitter' style='width:80px;height:80px;'></div>";
echo "</div>";
echo "<div class='header2'> </div>";


//saving variables from the form, and inserting into database if the criteria for username/password is met

if (isset($_POST['create'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];
  $email = $_POST['email'];

  if (!query('SELECT username FROM users WHERE username=:username', array(':username'=>$username)))
  {
    if (strlen($username) > 0 && strlen($username) < 32)
    {
      if (strlen($password) > 0 && strlen($password) < 32)
      {
        if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
          query('INSERT INTO users VALUES (null, :username, :password, :email)', array(':username'=>$username, ':password'=>password_hash($password, PASSWORD_BCRYPT), ':email'=>$email));
          $success = '<div id="successflag">Success! Your account has been created.</div>';
        }
        else {
          $error = '<div id="errorflag">Enter a valid email</div>';
        }
      }
      else {
        $error = '<div id="errorflag">make sure your password is not too long</div>';
      }

    }
    else {
      $error = '<div id="errorflag">make sure your username is not too long</div>';
    }

  }
  else
  {
  $error = '<div id="errorflag">Username already exists! Try another one.</div>';
  }
}
?>

<!-- this is the form for creating an account -->
<div class="createpage">
<h2>Register Account</h2>
<form class="create-account.php" method="post">
<?php if(isset($error)){ echo $error; } ?>
<?php if(isset($success)){ echo $success; } ?>
  <div class="text"><input type="text" name="username" value="" placeholder="Username"></div>
  <div class="text">  <input type="password" name="password" value="" placeholder="Password"></div>
  <div class="text">  <input type="email" name="email" value="" placeholder="Email Address"></div>
  <input type="submit" name="create" value="Create Account">
</form>
<br></br>
<a href="login.php">Already have an account? Click here to login</a>
</div>
</body>
<style>
  #errorflag{
    color: red;
    font-family: Montserrat;
    margin: 1em 0em -2em;
  }
  #successflag{
    color: green;
    font-family: Montserrat;
    margin: -.5em 0em 0em;
  }
</style>
</html>
