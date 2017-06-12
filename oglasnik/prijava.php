<?php ob_start(); ?>
<!DOCTYPE html>
<html>
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <!-- Latest compiled and minified JavaScript -->
    <script src="./jquery-3.2.1.min.js"></script>

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
            integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
            crossorigin="anonymous"></script>

    <title>Oglasnik</title>
</head>

<body>
<div class="container-fluid">

    <?php require_once('navbar.php'); ?>


    <div class="row">
        <div class="col-md-4 col-offset-4">
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-group">
                <label for="exampleInputEmail1">Email address</label>
                <input type="email" class="form-control" id="exampleInputEmail1" name="email" placeholder="Email">
            </div>
            <div class="form-group">
                <label for="exampleInputPassword1">Password</label>
                <input type="password" class="form-control" id="exampleInputPassword1" name="password"
                       placeholder="Password">
            </div>
            <button type="submit" class="btn btn-default">Prijavi se</button>
        </form>
    </div>
    </div>
</div>


<?php
include_once('spoj.php');
if(isset($_SESSION["email"]) && !empty($_SESSION["email"])){
    header('Location: index.php');
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = validate($_POST['email']);
    $pass = md5($_POST['password']);
    if (isset($email) && isset($pass)) {

        $stmt = $veza->prepare("SELECT * FROM korisnici WHERE email=:email AND lozinka=:password");//SQL injection
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $pass);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $count = $stmt->rowCount();
        if ($count !== 0) {
            $_SESSION["id"] = $user['id'];
            $_SESSION["email"] = $email;
            $_SESSION["f_name"] = $user['firstname'];
            $_SESSION["l_name"] = $user['lastname'];
            $_SESSION["slika"] = $user['avatar'];
            header('Location: index.php');
        } else {
            echo "<h2>Korisnik ne postoji!</h2>";
        }
    } else {
        echo "<h2>Molim popunite sva polja!</h2>";
    }
}

?>
</body>
</html>
<?php ob_end_flush(); ?>
