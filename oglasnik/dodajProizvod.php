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

    <link rel="stylesheet" href="./noty.css" />
    <script src="./noty.js"></script>
    <script src="./jquery-3.2.1.min.js"></script>
    <title>Oglasnik</title>
</head>

<body>
<div class="container">

    <?php require_once('navbar.php'); ?>

    <div class="row">
        <div class="col-md-8    col-offset-2">
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"
              enctype="multipart/form-data">
            <div class="form-group">
                <label for="naziv">Naziv</label>
                <input type="text" class="form-control" id="naziv" name="naziv" placeholder="Naziv" required>
            </div>

            <div class="form-group">
                <label for="cijena">Cijena [EUR]</label>
                <input name="cijena" id="cijena" rows="10" class="form-control" type="number" step="0.01" required>
            </div>


            <div class="form-group">
                <label for="opis">Opis</label>
                <textarea name="opis" id="opis" rows="10" class="form-control" placeholder="Opis..."></textarea>
            </div>

            <div class="form-group">
                <label for="stanje">Stanje</label>
                <select name="stanje" class="form-control">
                    <option value="rabljeno">Rabljen</option>
                    <option value="novo">Novo</option>
                    <option value="nesipravno">Neispravno</option>
                </select>
            </div>

            <div class="form-group">
                <label for="kategorija">Kategorija</label>
                <select name="kategorija" class="form-control">
                    <option value="tehnika">Tehnika</option>
                    <option value="roba">Roba</option>
                    <option value="obuca">Obuca</option>
                    <option value="automobili">Automobili</option>
                </select>
            </div>

            <div class="form-group">
                <label for="slika">Slika</label>
                <input type="file" id="slika" name="slika">
<!--                <p class="help-block">Example block-level help text here.</p>-->
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>

    </div>
<?php
if (!isset($_SESSION["email"]) && empty($_SESSION["email"])) {
    header('Location: index.php');
}
include_once('spoj.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $naziv = validate($_POST['naziv']);
    $opis = validate($_POST['opis']);
    $stanje = validate($_POST['stanje']);
    $kategorija = validate($_POST['kategorija']);
    $cijena = validate($_POST['cijena']);
    if (isset($naziv) && isset($opis) && isset($stanje) && isset($kategorija)) {

        $target_dir = "proizvodi/";
        $target_file = $target_dir . basename($_FILES["slika"]["name"]);
        $uploadOk = 1;
        $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
        $check = getimagesize($_FILES["slika"]["tmp_name"]);
        $temp = explode(".", $_FILES["slika"]["name"]);
        $newfilename = round(microtime(true)) . '.' . end($temp);

        if ($check !== false) {
//                echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
            if (!move_uploaded_file($_FILES["slika"]["tmp_name"], $target_dir . $newfilename)) {
                echo "Oprosti, doslo je do pogreske.";
            }
        } else {
//                    echo "Molimo vas /predajte sliku.";
            $uploadOk = 0;
        }
        if ($uploadOk) {
            $stmt = $veza->prepare("INSERT INTO proizvodi (naziv, opis, stanje, kategorija, cijena, slika, korisnik_id, datumobjave) 
                  VALUES (:naziv, :opis, :stanje, :kategorija, :cijena, :slika, :korisnik_id, :datumobjave)");
            $stmt->bindParam(':naziv', $naziv);
            $stmt->bindParam(':opis', $opis);
            $stmt->bindParam(':stanje', $stanje);
            $stmt->bindParam(':kategorija', $kategorija);
            $stmt->bindParam(':cijena', $cijena);
            $stmt->bindParam(':slika', $newfilename);
            $stmt->bindParam(':korisnik_id', $_SESSION['id']);
            $stmt->bindParam(':datumobjave', (new DateTime('now'))  ->format('Y-m-d H:i:s'));
            $stmt->execute();
            echo "<script>new Noty({
                    text: 'Uspjesno objavljen proizvod!',
                    layout: 'topRight',
                    type: 'success'
                }).show();</script>";
            header("Refresh: 2");
        }
    } else {
        echo "<h2>Molim popunite sva polja!</h2>";
    }
}

?>
</div>

</body>
</html>
<?php ob_end_flush(); ?>
