<?php
    session_start();

    require 'database.php';

    if (!empty($_GET['id'])) {
        $id = checkInput($_GET['id']);
    }

    if(!empty($_POST)) {
        $id = checkInput($_POST['id']);

        $db = Database::connect();

        // On supprime l'image
        $statement = $db->prepare('SELECT image FROM items WHERE id = ?');
        $statement->execute(array($id));
        $item = $statement->fetch();
        unlink("../images/" . $item['image']);

        // On supprime l'item
        $statement = $db->prepare("DELETE FROM items WHERE id = ?");
        $statement->execute(array($id));

        Database::disconnect();

        header("Location: index.php");
    }

    function checkInput($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
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

        <?php 
            if (!empty($_SESSION['logged_in'])) {
        ?>

        <div class="container admin">
            <div class="row flex-column">
                <h1>
                    <strong>Supprimer un item</strong>
                </h1>
                <br>
                <form class="form" role="form" action="delete.php" method="POST">
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <div class="alert alert-warning my-3" role="alert">Êtes-vous certain de vouloir supprimer ?</div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-danger">
                            Oui
                        </button>
                        <a href="index.php" class="btn btn-secondary">
                            Non
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <?php
            } else {
        ?>

        <div class="container admin">
            <div class="alert alert-danger">
                <h4 class="alert-heading">Attention, vous n'êtes pas connecté !</h4>
                <p>Merci de bien vouloir vous <a href="/admin/login.php" class="alert-link">connecter</a>.</p>
                <hr>
                <a href="/admin/login.php" class="btn btn-success">
                    <i class="fas fa-sign-in-alt mr-1"></i>
                    Connexion
                </a>
            </div>
        </div>

        <?php
            }
        ?>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</body>
</html>