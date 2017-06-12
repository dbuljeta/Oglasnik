<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
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
<?php require_once('navbar.php'); ?>

<div class="container">
    <?php
    $stmt = $veza->prepare("SELECT p.naziv as naziv, p.id as id, p.slika as slika FROM proizvodi p INNER JOIN
                                          korisnici k ON p.korisnik_id =  k.id");
    $izabranaKategorija=null;
    if (isset($_GET['kategorija']))
    {
        switch ($_GET['kategorija'])
        {
            case "tehnika":
                $izabranaKategorija="tehnika";
                break;
            case "roba":
                $izabranaKategorija="roba";
                break;
            case "obuca":
                $izabranaKategorija="obuca";
                break;
            case "automobili":
                $izabranaKategorija="automobili";
                break;

        }
        $stmt = $veza->prepare("SELECT p.naziv as naziv, p.id as id, p.slika as slika FROM proizvodi p INNER JOIN
                                          korisnici k ON p.korisnik_id =  k.id WHERE p.kategorija=:izabranaKategorija");
        $stmt->bindParam(':izabranaKategorija', $izabranaKategorija);//SQL injection
    }
    $stmt->execute();
    echo '<div class="row" >';
    while (($row = $stmt->fetch(PDO::FETCH_ASSOC)) !== false):
        ?>
        <div class="col-md-4 " style="padding: 20px" >
            <a href="proizvod.php?id=<?php echo $row['id']?>">
            <h4 class="InlineTitle"><?php echo $row['naziv'] ?></h4>
            <img alt="Slika proizvoda" src="./proizvodi/<?php echo $row['slika'] ?>" height="200px"/>
            </a>
        </div>

    <?php endwhile;
    echo "</div>";
    ?>
</div>
</body>

</html>