<?php
    session_start();
    session_destroy();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/dbc1e24743.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Holtwood+One+SC&display=swap" rel="stylesheet"> 
    <link rel="stylesheet" href="../css/style.css">

    <title>Burger Code</title>
</head>
<body>
        <h1 class="text-logo">
            <i class="fas fa-utensils"></i> Burger Code <i class="fas fa-utensils"></i>
        </h1>

        <div class="container admin">
            <div class="alert alert-success">
                <h4 class="alert-heading">Vous avez bien été déconnecté !</h4>
                <p>Vous pouvez vous <a href="/admin/login.php" class="alert-link">reconnecter</a> ou <a href="/" class="alert-link">retourner au site</a>.</p>
                <hr>
                <a href="/admin/login.php" class="btn btn-success">
                    <i class="fas fa-sign-in-alt mr-1"></i>
                    Connexion
                </a>
                <a href="/" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-1"></i>
                    Retour au site
            </a>
            </div>
        </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</body>
</html>