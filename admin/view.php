<?php
    session_start();

    if (!empty($_GET['id'])) {
        $id = checkInput($_GET['id']);
    }

    // On se connecte à la BDD
    $url = getenv('JAWSDB_URL');
    $dbparts = parse_url($url);

    $hostname = $dbparts['host'];
    $username = $dbparts['user'];
    $password = $dbparts['pass'];
    $database = ltrim($dbparts['path'],'/');

    try {
        $db = new PDO("mysql:host=$hostname;dbname=$database", $username, $password);
    } catch(PDOException $e) {
        die();
    }

    $statement = $db->prepare('
        SELECT items.id, items.name, items.description, items.price, categories.name AS category, items.image 
        FROM items LEFT JOIN categories
        ON items.category = categories.id
        WHERE items.id = ?
    ');

    $statement->execute(array($id));

    $item = $statement->fetch();

    // On se déconnecte
    $db = null;

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
            <div class="row">
                <div class="col-md-6">
                    <h1>
                        <strong>Voir un item</strong>
                    </h1>
                    <br>
                    <form>
                        <div class="form-group">
                            <label><strong>Nom :</strong></label>
                            <?php echo ' ' . $item['name']; ?>
                        </div>
                        <div class="form-group">
                            <label><strong>Description :</strong></label>
                            <?php echo ' ' . $item['description']; ?>
                        </div>
                        <div class="form-group">
                            <label><strong>Prix :</strong></label>
                            <?php echo ' ' . number_format((float)$item['price'], 2, '.', ' ') . ' €'; ?>
                        </div>
                        <div class="form-group">
                            <label><strong>Catégorie :</strong></label>
                            <?php echo ' ' . $item['category']; ?>
                        </div>
                        <div class="form-group">
                            <label><strong>Image :</strong></label>
                            <?php echo ' ' . $item['image']; ?>
                        </div>
                    </form>
                    <br>
                    <div class="form-actions">
                        <a href="index.php" class="btn btn-primary">
                            <i class="fas fa-arrow-left mr-1"></i>
                            Retour
                        </a>
                    </div>
                </div>

                <div class="col-md-6 site">
                    <div class="img-thumbnail">
                        <img src="<?php echo '../images/' . $item['image'] ?>" alt="<?php echo $item['name'] ?>" class="img-fluid">
                        <div class="price"><?php echo number_format((float)$item['price'], 2, '.', ' ') . ' €'; ?></div>
                        <div class="caption">
                            <h4><?php echo $item['name']; ?></h4>
                            <p><?php echo $item['description']; ?></p>
                            <a href="#" class="btn btn-order" role="button">
                                <i class="fas fa-cart-arrow-down mr-1"></i>
                                Commander
                            </a>
                        </div>
                    </div>
                </div> 
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