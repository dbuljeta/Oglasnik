<nav id="header" class="navbar navbar">
    <div class="container">
        <div id="header-container" class="container navbar-container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
                        aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand inactiveLink" style="pointer-events:none;cursor: default;font-size: xx-large;font-style: inherit;color:darkblue" href="">Oglasnik</a>
            </div>
            <div id="navbar" class="collapse navbar-collapse">
                <ul class="nav navbar-nav">
                    <li class="active"><a href="index.php">Naslovna stranica</a></li>
                    <li><a href="onama.php">O nama</a></li>
                    <li><a href="kontakt.php">Kontakt</a></li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                           aria-expanded="false"><?php if (!isset($_GET['kategorija'])) echo "SVE";
                            else echo strtoupper($_GET['kategorija']); ?> <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <?php if (isset($_GET['kategorija'])): ?>
                                <li><a href="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">Sve</a></li>
                            <?php endif; ?>
                            <?php if (!isset($_GET['kategorija']) || $_GET['kategorija'] != 'tehnika'): ?>
                                <li><a href="index.php?kategorija=tehnika">Tehnika</a>
                                </li>
                            <?php endif; ?>
                            <?php if (!isset($_GET['kategorija']) || $_GET['kategorija'] != 'roba'): ?>
                                <li><a href="index.php?kategorija=roba">Roba</a>
                                </li>
                            <?php endif; ?>
                            <?php if (!isset($_GET['kategorija']) || $_GET['kategorija'] != 'obuca'): ?>
                                <li>
                                    <a href="index.php?kategorija=obuca">Obuca</a>
                                </li>
                            <?php endif; ?>
                            <?php if (!isset($_GET['kategorija']) || $_GET['kategorija'] != 'automobili'): ?>
                                <li><a href="index.php?kategorija=automobili">Automobili</a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </li>

                </ul>
                <?php
                require_once('spoj.php');
                if (isset($_SESSION['email']) && !empty($_SESSION['email'])): ?>
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="prijava.php"
                               class="inactiveLink"> <?php echo $_SESSION['f_name'] . " " . $_SESSION['l_name'] ?></a>
                        </li>
                        <li><a href="dodajProizvod.php" class="inactiveLink"> Dodaj proizvod</a></li>
                        <li><a href="odjava.php"> Odjava</a></li>
                    </ul>
                <?php else: ?>
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="prijava.php">Prijavi se</a></li>
                        <li><a href="registracija.php">Registriraj se</a></li>
                    </ul>
                <?php endif; ?>
            </div>

        </div><!-- /.nav-collapse -->
    </div>
</nav><!-- /.navbar -->
