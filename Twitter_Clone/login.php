
<!-- reference for this code: https://www.youtube.com/watch?v=15hVqug7bjM&list=PLBOh8f9FoHHhRk0Fyus5MMeBsQ_qwlAzG&index=4  -->
<!-- This is a change, another change -->

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

if(isset($_SESSION['use'])){
  header("Location:home.php");
}

if (isset($_POST['login'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

    if (query('SELECT username FROM users WHERE username=:username', array(':username'=>$username))){
      if (password_verify($password, query('SELECT password FROM users WHERE username=:username', array(':username'=>$username))[0]['password'])) {
        $_SESSION['use'] = $username;
        if(isset($_SESSION['use'])){
          header("Location:home.php");
        }
      }
      else{
        echo "incorrect password";
      }
    }
    else {
      echo "user not found";
    }

}

?>
<h2>Login to your account</h2>
<form action="login.php" method="post">
<input type="text" name="username" value="" placeholder="type username...">
<input type="password" name="password" value="" placeholder="type password...">
<input type="submit" name="login" value="Login">
</form>
<a href="createaccount.php">Don't have an account? Click here to create one</a>
