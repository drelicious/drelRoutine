<?php
require_once __DIR__ . '/vendor/autoload.php';
date_default_timezone_set('Asia/Jakarta');
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: /webapp/login.php");
    exit;
}
$finished = 0;
$averagetime = 0;
$test = 0;
$client = new MongoDB\Client(
    'mongodb+srv://supersecretdetail:database@cluster0.ox6nld1.mongodb.net/?retryWrites=true&w=majority');
$db = $client->drelRoutine->todo;
if(isset($_POST['add-todo'])) {
    $email = $_SESSION['email'];
    $title = $_POST['todo-title'];
    $description = $_POST['todo-desc'];
    $date = date('Y-m-d');
    $todo = [
        'email' => $email,
        'title' => $title,
        'description' => $description,
        'date' => $date,
        'length' => 2,
        'status' => 'unfinished'
    ];
    $db->insertOne($todo);
}
$email = $_SESSION['email'];
$cursor = $db->find(["email" => $email]);
$id = "";
if (isset($_POST['del-todo'])) {
    $id = $_POST['del-todo-id'];
    // update status to finished
    $db->updateOne(
        ['_id' => new MongoDB\BSON\ObjectId($id)],
        ['$set' => ['status' => 'finished']]
    );
}

$greetings = "";
$minute = date('H:i');
    $time = date('H');
    if ($time < "12") {
        $greetings = "Good morning ";
    } else

    if ($time >= "12" && $time < "17") {
        $greetings = "Good afternoon ";
    } else
    if ($time >= "17" && $time < "19") {
        $greetings = "Good evening ";
    } else
    if ($time >= "19") {
        $greetings = "Good night ";
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>drelRoutine - Dashboard</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="/webapp/styles/loader.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins" rel="stylesheet"> 
        <link href="/webapp/styles/dashboard.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    </head>
    <body onload="myFunction()">
    <!--Scripts for bootstrap do not remove-->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
    <!--Important for every web-page-->
    <div id="loader">
        <img src="/webapp/images/logo-light.png">
    </div>
    <script src="/webapp/scripts/pageanimation.js"></script>
    <script src="/webapp/scripts/greetings.js"></script>
    <section id="allwebpage">
    <script>
                                    function openDropDown() {
                                        // if display set to none, set to block
                                        if (document.getElementById("dropdown").style.display == "none") {
                                            document.getElementById("dropdown").style.display = "block";
                                        } else {
                                            document.getElementById("dropdown").style.display = "none";
                                        }
                                    }
                                </script>
        <section id="header-area">
            <nav class="navbar navbar-expand-lg">
                <div class="container-fluid">
                    <h1><a class="nav-link" href="/dashboard.php">drelRoutine</a></h1>
                    <img src="/webapp/images/user.svg" style="width: 2rem;" onclick="openDropDown()">
                </div>
            </nav>
            <div id="dropdown" style="float: right;">
                <a href="/webapp/logout.php">Sign Out</a>
            </div>
        </section>
        <section id="top-menu">
        <h1 class="specialh1"><?php echo $greetings?></h1>
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3">
                    <div class="col-lg-12">
                        <div class="card" style="margin: 3%;">
                            <div class="card-body">
                                <h2>To-do</h1>
                                <script>
                                    function test() {
                                    alert("test")
                                    }
                                </script>
                                <form action="" method="post">
                                <?php
                                foreach ($cursor as $document) {
                                    $test = 0;
                                    $finished = 0;
                                    $totaltime = 0;
                                    $totaltime = $totaltime + $document['length'];
                                    $averagetime = 0;
                                    if ($document['status'] == "unfinished" || $document['status'] == "finished") {
                                        $test++;
                                        $averagetime = $totaltime / $test;
                                    }
                                    if ($document['status'] == "unfinished") {
                                    echo '
                                    <div class="todo-content" id="' . $document['_id'] . '">
                                    <input type="hidden" name="del-todo-id" style="display: hidden;" value="' .$document['_id'] .'">
                                    <h6><input onChange="this.form.submit()" type="checkbox" name="del-todo"><a> ' . $document['title'] . '</a></h6>
                                    <p>' . $document['length'] . ' Minutes ' . $document['description'] . '</p>
                                    </div>
                                    ';
                                }
                                else {
                                    $finished++;
                                }}
                                ?>
                                </form>
                                    <div class="add">
                                        <div class="row">
                                            <form action="<?=$_SERVER['PHP_SELF'];?>" method="post">
                                        <div class="col-lg-12">
                                            <input type="text" placeholder="To-do title" name="todo-title" class="form-control">
                                            <input type="text" placeholder="To-do details" name="todo-desc" class="form-control">
                                        </div>
                                        <div class="col-lg-6">
                                            <button class="btn btn-primary" name="add-todo" style="margin: 10px 40%">Add</button>
                                        </div>
                                        </form>
                                    </div>

                                    </div>
                            </div>
                            </div>
                        </div>
                        <div class="col-lg-12" id="card2" style="display: none;">
                            <div class="card card2" style="margin: 3%;">
                                <div class="card-body">
                                    <h1></h1>
                                </div>
                                </div>
                            </div>
                </div>
                <div class="col-lg-9">
                    <div class="row">
                <div class="col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Avg. Time Spent (each)</h5>
                            <h1><?php echo $averagetime; ?> Minutes</h1>
                     </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Task finished</h5>
                            <h1><?php echo $finished; ?>/<?php echo $test;?></h1>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Time</h5>
                            <h1><?php echo $minute;  ?></h1>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card" style="margin: 1% 0;">
                        <div class="card-body">
                            <div class="content-area" style="overflow-y: scroll; height:300px;">
                                <div class="col-lg-6">
                                    <h1>Keep it up, you can do it!</h1>
                                </div>
                                <div class="col-lg-6">
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>
            </div>
            </div>
        </div>
        </section>

        
    </section>
        <script src="" async defer></script>
    </body>
</html>