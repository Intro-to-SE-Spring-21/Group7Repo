
<!-- reference for this code: https://www.youtube.com/watch?v=NLsbLB2Qgvg&list=PLBOh8f9FoHHhRk0Fyus5MMeBsQ_qwlAzG&index=2 -->


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
          echo "Success! Your account has been created.";
        }
        else {
          echo 'enter a valid email';
        }
      }
      else {
        echo "make sure your password is not too long";
      }

    }
    else {
      echo 'make sure your username is not too long';
    }

  }
  else
  {
  echo "Username already exists! Try another one.";
  }
}
?>

<h2>Register for an account</h2>
<form class="create-account.php" method="post">
  <input type="text" name="username" value="" placeholder="type username...">
  <input type="password" name="password" value="" placeholder="type password...">
  <input type="email" name="email" value="" placeholder="type email...">
  <input type="submit" name="create" value="Create Account">
</form>
<a href="login.php">Login</a>
