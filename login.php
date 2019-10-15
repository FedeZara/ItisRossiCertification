<?php
$password = "";
$password_err = "";
$class_err = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty(trim($_POST['password']))) {
        $password_err = 'Inserire una password!';
        $class_err = "has-error has-danger";
    } else {
        $password = trim($_POST['password']);
    }

    if (empty($password_err)) {
        if ($password == getenv("ADMIN_PASSWORD")) {
            session_start();
            $_SESSION['logged'] = true;
            header("location: courseManager.php");
        } else {
            $password_err = 'Password incorretta!';
            $class_err = "has-error has-danger";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Pannello di amministrazione - Login</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://bootswatch.com/3/flatly/bootstrap.css">
  <link rel="stylesheet" href="styles/login.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.11.9/validator.js"></script>
  <script>
    function clearErrorBlock(){
      if($("#pass").val() != ""){
        $("#errBlock").html("");
      }
    }
  </script>
</head>
<body>
<div class="container main">
  <div class="row">
    <div class="col-md-3 col-xs-0 col-sm-2"></div>
    <div class="col-md-6 col-xs-12 col-sm-8">
      <div class="row">
        <div class = "col-xs-12">
          <h2><span class="glyphicon glyphicon-wrench"></span> Pannello di amministrazione</h2>
        </div>
      </div>
      <div class="row form-row">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" data-toggle="validator" role="form">
          <div class="form-group col-sm-10 <?php echo $class_err; ?>" style="text-align: left">
            <div class="input-group">
              <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
              <input class="form-control" id="pass" name="password" type="password" placeholder="Password" data-error="Inserire una password!" oninput="clearErrorBlock()" required>
            </div>
            <div id="errBlock" class="help-block with-errors"><?php echo $password_err; ?></div>
          </div>
          <div class="col-sm-2">
              <button type="submit" class="btn btn-success">Accedi</button>
          </div>
        </form>
      </div>
    </div>
    <div class="col-md-3 col-xs-0 col-sm-2"></div>
  </div>
</div>
<footer class="footer">
    <div class="container">
      <span class="text-muted">Sito a cura di Federico Zarantonello</span>
    </div>
</footer>
</body>
</html>
