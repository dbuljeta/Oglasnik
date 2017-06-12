<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
            integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
            crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="stil.css">
    <link rel="stylesheet" href="./font-awesome-4.7.0/css/font-awesome.min.css">
    <title>Oglasnik</title>
</head>
<body>
<?php require_once('navbar.php'); ?>

<div class="container">

    <?php
    $id = $_GET['id'];
    $stmt = $veza->prepare("SELECT * FROM proizvodi p INNER JOIN
                                          korisnici k ON p.korisnik_id =  k.id WHERE p.id=:id");//SQL injection
    $stmt->bindParam(":id", $id);
    $stmt->execute();
    $proizvod = $stmt->fetch(PDO::FETCH_ASSOC);

    ?>
    <div class="card">
        <div class="container1">
            <img src="./proizvodi/<?php echo $proizvod['slika'] ?>" alt="Avatar" height="200px">
            <h4>Naziv proizvoda :<b><?php echo $proizvod['naziv'] ?></b></h4>
            <pre style="overflow:auto">Opis: <?php echo $proizvod['opis'] ?></pre>
            <pre>Kontakt:<?php echo $proizvod['kontakt'] ?> </pre>
            <pre>Autor: <?php echo $proizvod['firstname'] . " " . $proizvod['lastname'] ?></pre>
            <pre>Cijena[EUR]: <?php echo $proizvod['cijena'] ?></pre>
        </div>
        <?php if ($_SESSION['id'] != $proizvod['korisnik_id']) {
            $stmt = $veza->prepare("SELECT * FROM ocjene WHERE korisnik_id=:k AND ocjenivac_id=:o");//SQL injection
            $stmt->bindParam(':k', $proizvod['korisnik_id']);
            $stmt->bindParam(':o', $_SESSION['id']);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            $count = $stmt->rowCount();
            if ($count === 0):
                ?>
                <i class="fa fa-star-o fa-4x" id="ocjeni" data-param="<?php echo $id; ?>"
                   data-param1="<?php echo $_SESSION['id']; ?>"
                   data-param2="<?php echo $proizvod['korisnik_id']; ?>" aria-hidden="true"></i>
            <?php else: ?>
                <i class="fa fa-star fa-4x" id="ocjeni" data-param="<?php echo $id; ?>"
                   data-param1="<?php echo $_SESSION['id']; ?>"
                   data-param2="<?php echo $proizvod['korisnik_id']; ?>" aria-hidden="true"></i>
            <?php endif;
        } ?>

    </div>
    <?php
    if (isset($_SESSION['id']) && !empty($_SESSION['id']) && $_SESSION['id'] === $proizvod['korisnik_id']) {
        //var_dump($id);
        echo '<a href="urediproizvod.php?id=' . $id . '"><button type="button" class="btn btn-info">promjeni oglas</button></a>';
        echo '<button id="obrisi" data-param="' . $id . '" type="button" class="btn btn-danger">obrisi</button>';
    }
    ?>
    <script src="./jquery-3.2.1.min.js"></script>
    <script src="./script.js"></script>
</div>
</body>

</html>