<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/database/config.php';
session_start();
if (isset($_SESSION['email'])) {
    header("Location: /webapp/dashboard.php");
    exit;
}
$client = new MongoDB\Client(
    'mongodb+srv://supersecretdetail:database@cluster0.ox6nld1.mongodb.net/?retryWrites=true&w=majority');
$db = $client->drelRoutine->users;
$message = "";
$password = "";
if(isset($_POST['submitButton'])){ 
    $email = $_POST['email'];
    if(strlen($_POST['password']) < 8){
        $message = "Password must be at least 8 characters long";
    } else {
        $password = $_POST['password'];
        $password = md5($password);
    }
    $cursor = $db->findOne(["email" => $email]);
    if ($cursor) {
        if ($cursor->email == $email) {
            $message = "Email already exists";
        }
    }
    if (!$password == "") {
        $cursor = $db->findOne(["email" => $email]);
        if ($cursor) {
            if ($cursor->email == $email) {
                $message = "Email already exists";
            }
        } else {
            $user = [
                "email" => $email,
                "password" => $password,
                "role" => "user"
            ];
            $db->insertOne($user);
            header("Location: /webapp/login.php");
        }
    }
  }    
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>drelRoutine - Sign Up</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="/webapp/styles/loader.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins" rel="stylesheet"> 
        <link href="/webapp/styles/login.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    </head>
    <body onload="myFunction()">
    <!--Scripts for bootstrap do not remove-->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
    <!--Important for every web-page-->
    <script>
        function errorpass() {
            Swal.fire({
  icon: 'error',
  title: 'Oops...',
  text: '<?php echo $message?>',
})
        }
        </script>
    <div id="loader">
        <img src="/webapp/images/logo-light.png">
    </div>
    <script src="/webapp/scripts/pageanimation.js"></script>
    <section id="allwebpage">
        <section id="header-area">
            <nav class="navbar navbar-expand-lg">
                <div class="container-fluid">
                    <h1><a class="nav-link" href="/index.html">drelRoutine</a></h1>
                    <button style="border-color: #e0e0e0;" class="btn btn rounded-pill buttonlogin"><a href="/webapp/login.php" class="test"">Sign In</a></button>
                </div>
            </nav>
        </section>

        <section id="signup-area">
            <div class="d-flex justify-content-center">
                <div class="card" style="width: 18rem; margin-top: 4%;">
                    <div class="card-body">
                        <h2 class="card-title">Sign Up</h2>
                        <p>A step to achieving your goal.</p>
                        <form action="<?=$_SERVER['PHP_SELF'];?>" method="post">
                            <div class="form-group">
                                <label for="username" style="margin: 10px 0px;">Email</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Email">
                            </div>
                            <div class="form-group">
                                <label for="password" style="margin: 10px 0px;">Password</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                                <a style="margin-top: 10px;">By signing up, you agree to our<a href="/webapp/privacy-policy.php"> privacy policy.</a></a>
                            </div>
                            <button type="submit" name="submitButton" style="margin-top: 40px; border-color: #e0e0e0;" class="btn btn position-relative start-50 translate-middle rounded-pill buttonlogin">Sign Up</button>
                        </form>
                        <?php
                    if (!$message == "") {
                     echo '<script>errorpass()</script>';   
                    }
                        ?>
                    </div>
                </div>
            </div>
    </section>
    </section>
        <script src="" async defer></script>
    </body>
</html>