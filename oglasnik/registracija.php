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
        <div class="col-md-8     col-offset-2">
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"
                  enctype="multipart/form-data">
                <div class="form-group">
                    <label for="email">Email address</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password"
                           required minlength="6">
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Password confirm</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
                           placeholder="Password confirm" required minlength="6">
                </div>

                <div class="form-group">
                    <label for="f_name">First name</label>
                    <input type="text" class="form-control" id="f_name" name="f_name" placeholder="First name" required>
                </div>

                <div class="form-group">
                    <label for="l_name">Last name</label>
                    <input type="text" class="form-control" id="l_name" name="l_name" placeholder="Last name" required>
                </div>

                <div class="form-group">
                    <label for="adresa">Adresa</label>
                    <input type="text" class="form-control" id="adresa" name="adresa" placeholder="Adresa" required>
                </div>

                <div class="form-group">
                    <label for="grad">Grad</label>
                    <input type="text" class="form-control" id="grad" name="grad" placeholder="Grad">
                </div>

                <div class="form-group">
                    <label for="kontakt">Kontakt</label>
                    <input type="text" class="form-control" id="kontakt" name="kontakt" placeholder="Kontakt" required>
                </div>

                <div class="form-group">
                    <label for="avatar">Avatar</label>
                    <input type="file" id="avatar" name="avatar">
                </div>

                <button type="submit" class="btn btn-default">Registriraj se</button>
            </form>
        </div>

    </div>
</div>
<?php
if (isset($_SESSION["email"]) && !empty($_SESSION["email"])) {
    header('Location: index.php');
}
include_once('spoj.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = validate($_POST['email']);
    $f_name = validate($_POST['f_name']);
    $l_name = validate($_POST['l_name']);
    $adresa = validate($_POST['adresa']);
    $kontakt = validate($_POST['kontakt']);
    $grad = validate($_POST['grad']);
    $pass = md5($_POST['password']);
    $pass_c = md5($_POST['password_confirmation']);
    if (isset($email) && isset($f_name) && isset($l_name) && isset($adresa) && isset($kontakt) && isset($grad) && isset($kontakt) && isset($pass)) {
        if ($pass === $pass_c) {

            $stmt = $veza->prepare("SELECT * FROM korisnici WHERE email=:email");//SQL injection
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $count = $stmt->rowCount();

            if ($count == 0) {
                $target_dir = "uploads/";
                $target_file = $target_dir . basename($_FILES["avatar"]["name"]);
                $uploadOk = 1;
                $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
                $check = getimagesize($_FILES["avatar"]["tmp_name"]);
                $temp = explode(".", $_FILES["avatar"]["name"]);
                $newfilename = round(microtime(true)) . '.' . end($temp);

                if ($check !== false) {
//                echo "File is an image - " . $check["mime"] . ".";
                    $uploadOk = 1;
                    if (!move_uploaded_file($_FILES["avatar"]["tmp_name"], $target_dir . $newfilename)) {
                        echo "Oprosti, doslo je do pogreske.";
                    }
                } else {
//                    echo "Molimo vas /predajte sliku.";
                    $uploadOk = 0;
                }
                if ($uploadOk) {
                    $stmt = $veza->prepare("INSERT INTO korisnici (firstname, lastname, email, lozinka, adresa, grad, avatar, kontakt) 
                  VALUES (:firstname, :lastname, :email, :lozinka, :adresa, :grad, :avatar, :kontakt)");
                    $stmt->bindParam(':firstname', $f_name);
                    $stmt->bindParam(':lastname', $l_name);
                    $stmt->bindParam(':email', $email);
                    $stmt->bindParam(':lozinka', $pass);
                    $stmt->bindParam(':adresa', $adresa);
                    $stmt->bindParam(':grad', $grad);
                    $stmt->bindParam(':avatar', $newfilename);
                    $stmt->bindParam(':kontakt', $kontakt);
                    $stmt->execute();
                    header("Location: index.php");
                }
            } else {
                echo "<h2>Korisnik s unesenim emailom već postoji!</h2>";
            }
        } else {
            echo "<h2>Zaporke nisu iste!</h2>";
        }
    } else {
        echo "<h2>Molim popunite sva polja!</h2>";
    }
}

?>
</body>
</html>
<?php ob_end_flush(); ?>
