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
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
            integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
            crossorigin="anonymous"></script>

    <link rel="stylesheet" href="./noty.css" />
    <script src="./noty.js"></script>
    <script src="./jquery-3.2.1.min.js"></script>

    <title>Oglasnik</title>
</head>

<body>
<div class="container-fluid">

    <?php require_once('navbar.php');
    $id = $_GET['id'];
    $stmt = $veza->prepare("SELECT * FROM proizvodi p INNER JOIN
                                          korisnici k ON p.korisnik_id =  k.id WHERE p.id=:id");//SQL injection
    $stmt->bindParam(":id", $id);
    $stmt->execute();
    $proizvod = $stmt->fetch(PDO::FETCH_ASSOC);
    ?>

    <div class="row">
        <div class="col-md-8     col-offset-2">
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $id; ?>" />
            <div class="form-group">
                <label for="naziv">Naziv</label>
                <input type="text" class="form-control" id="naziv" name="naziv" placeholder="Naziv" value="<?php echo $proizvod['naziv']?>" required>
            </div>

            <div class="form-group">
                <label for="cijena">Cijena</label>
                <input name="cijena" id="cijena" rows="10" class="form-control" type="number" step="0.01" value="<?php echo $proizvod['cijena']?>" required>
            </div>


            <div class="form-group">
                <label for="opis">Opis</label>
                <textarea name="opis" id="opis" rows="10" class="form-control" placeholder="Opis..."><?php echo $proizvod['opis']?></textarea>
            </div>

            <div class="form-group">
                <label for="stanje">Stanje</label>
                <select name="stanje" class="form-control">
                    <option value="rabljeno" <?php if($proizvod['stanje'] === 'rabljeno') echo " selected"; ?>>Rabljen</option>
                    <option value="novo" <?php if($proizvod['stanje'] === 'novo') echo " selected"; ?>>Novo</option>
                    <option value="nesipravno" <?php if($proizvod['stanje'] === 'neispravno') echo " selected"; ?>>Neispravno</option>
                </select>
            </div>

            <div class="form-group">
                <label for="kategorija">Kategorija</label>
                <select name="kategorija" class="form-control">
                    <option value="tehnika" <?php if($proizvod['kategorija'] === 'tehnika') echo " selected";?>>Tehnika</option>
                    <option value="roba" <?php if($proizvod['kategorija'] === 'roba') echo " selected";?>>Roba</option>
                    <option value="obuca" <?php if($proizvod['kategorija'] === 'obuca') echo " selected";?>>Obuca</option>
                    <option value="automobili" <?php if($proizvod['kategorija'] === 'automobili') echo " selected";?>>Automobili</option>
                </select>
            </div>

            <div class="form-group">
                <label for="slika">Slika</label>
                <input type="file" id="slika" name="slika">
                <p class="help-block">Example block-level help text here.</p>
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
    </div>
</div>

<?php
if (!isset($_SESSION["email"]) && empty($_SESSION["email"]) && $proizvod['korisnik_id'] != $_SESSION['id']) {
    header('Location: index.php');
}
include_once('spoj.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $naziv = validate($_POST['naziv']);
    $opis = validate($_POST['opis']);
    $stanje = validate($_POST['stanje']);
    $kategorija = validate($_POST['kategorija']);
    $cijena = validate($_POST['cijena']);
    $id = validate($_POST['id']);
    if (isset($naziv) && isset($opis) && isset($stanje) && isset($kategorija)) {
        $uploadOk = 1;
        $newfilename = null;

        if(file_exists($_FILES['slika']['tmp_name']) && is_uploaded_file($_FILES['slika']['tmp_name'])) {
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
        }
        if ($uploadOk) {
            if($newfilename == null) {
                $stmt = $veza->prepare("UPDATE proizvodi SET naziv=:naziv, opis=:opis, stanje=:stanje, kategorija=:kategorija, cijena=:cijena, datumobjave=:datumobjave WHERE id=:id");

            } else {
                $stmt = $veza->prepare("UPDATE proizvodi SET naziv=:naziv, opis=:opis, stanje=:stanje, kategorija=:kategorija, cijena=:cijena, slika=:slika, datumobjave=:datumobjave WHERE id=:id");
                $stmt->bindParam(':slika', $newfilename);
            }
            var_dump($stmt);
            $stmt->bindParam(':naziv', $naziv);
            $stmt->bindParam(':opis', $opis);
            $stmt->bindParam(':stanje', $stanje);
            $stmt->bindParam(':kategorija', $kategorija);
            $stmt->bindParam(':cijena', $cijena);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':datumobjave', (new DateTime('now'))->format('Y-m-d H:i:s'));
            $stmt->execute();
             echo "<script>new Noty({
                    text: 'Uspjesno uređen proizvod!',
                    layout: 'topRight',
                    type: 'success'
                }).show();</script>";
            header("Location: index.php");
        }
    } else {
        echo "<h2>Molim popunite sva polja!</h2>";
    }
}
?>
</body>
</html>
<?php ob_end_flush(); ?>