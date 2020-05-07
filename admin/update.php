<?php
    session_start();

    if (!empty($_GET['id'])) {
        $id = checkInput($_GET['id']);
    }

    $name = $description = $price = $category = $image = "";
    $nameError = $descriptionError = $priceError = $categoryError = $imageError = "";

    // Si j'envoie des modifications (submit du formulaire) :
    if (!empty($_POST)) {
        $name = checkInput($_POST['name']);
        $description = checkInput($_POST['description']);
        $price = checkInput($_POST['price']);
        $category = checkInput($_POST['category']);
        $image = checkInput($_FILES['image']['name']);
        $imagePath = "../images/" . basename($image);
        $imageExtension = pathinfo($imagePath, PATHINFO_EXTENSION);
        $isSuccess = true;

        if (empty($name)) {
            $nameError = "Ce champ ne peut pas être vide !";
            $isSuccess = false;
        }

        if (empty($description)) {
            $descriptionError = "Ce champ ne peut pas être vide !";
            $isSuccess = false;
        } 

        if (empty($price)) {
            $priceError = "Ce champ ne peut pas être vide !";
            $isSuccess = false;
        } 

        if ($price <= 0 ) {
            $priceError = "Le prix doit être supérieur à 0 !";
            $isSuccess = false;
        }

        if (empty($category)) {
            $categoryError = "Ce champ ne peut pas être vide !";
            $isSuccess = false;
        } 

        if (empty($image)) {
            $isImageUpdated = false;
        } else {
            $isImageUpdated = true;
            $isUploadSuccess = true;
            if ($imageExtension != "jpg" && $imageExtension != "png" && $imageExtension != "jpeg" && $imageExtension != "gif") {
                $imageError = "Les fichiers autorisés sont: .gif, .jpg, .jpeg, .png";
                $isUploadSuccess = false;
            }
            if (file_exists($imagePath)) {
                $imageError = "Une image porte déjà le même nom !";
                $isUploadSuccess = false;
            }
            if ($_FILES["image"]["size"] > 500000) {
                $imageError = "Le fichier ne doit pas dépasser les 500KB";
                $isUploadSuccess = false;
            }
            if ($isUploadSuccess) {
                if (!move_uploaded_file($_FILES["image"]["tmp_name"], $imagePath)) {
                    $imageError = "Attention, une erreur est survenue lors du téléchargement de l'image !";
                    $isUploadSuccess = false;
                }
            }
        }
        // Si l'image n'a pas été modifiée ou si elle a été modifiée avec succès :
        if (($isSuccess && $isImageUpdated && $isUploadSuccess) || ($isSuccess && !$isImageUpdated)) {
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

            if ($isImageUpdated) {
                $statement = $db->prepare("
                    UPDATE items 
                    SET name = ?, description = ?, price = ?, category = ?, image = ?)
                    WHERE id = ?
                ");
    
                $statement->execute(array($name, $description, $price, $category, $image, $id));
            } else {
                $statement = $db->prepare("
                    UPDATE items 
                    SET name = ?, description = ?, price = ?, category = ?
                    WHERE id = ?
                ");

                $statement->execute(array($name, $description, $price, $category, $id));
            }

            // On se déconnecte
            $db = null;

            header("Location: index.php");

        // Sinon si l'upload de l'image a échoué :
        } elseif ($isImageUpdated && !$isUploadSuccess) {
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

            $statement = $db->prepare("SELECT image FROM items WHERE id = ?");

            $statement->execute(array($id));

            $item = $statement->fetch();
            $image = $item['image'];

            // On se déconnecte
            $db = null;
        }
    // Si j'arrive pour la première fois sur la page de modifications (après avoir cliqué sur le bouton "modifier" depuis la liste des items) :
    } else {
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

        $statement = $db->prepare("SELECT * FROM items WHERE id = ?");

        $statement->execute(array($id));

        $item = $statement->fetch();
        $name = $item['name'];
        $description = $item['description'];
        $price = $item['price'];
        $category = $item['category'];
        $image = $item['image'];

        // On se déconnecte
        $db = null;
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
            <div class="row">
                <div class="col-md-6">
                    <h1>
                        <strong>Modifier un item</strong>
                    </h1>
                    <br>
                    <form class="form" role="form" action="<?php echo 'update.php?id=' . $id ;?>" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="name"><strong>Nom :</strong></label>
                            <input type="text" class="form-control" name="name" id="name" placeholder="Nom" value="<?php echo $name; ?>">
                            <span class="help-inline">
                                <?php echo $nameError; ?>
                            </span>
                        </div>    
                        <div class="form-group">
                            <label for="description"><strong>Description :</strong></label>
                            <input type="text" class="form-control" name="description" id="description" placeholder="Description" value="<?php echo $description; ?>">
                            <span class="help-inline">
                                <?php echo $descriptionError; ?>
                            </span>
                        </div>    
                        <div class="form-group">
                            <label for="price"><strong>Prix (en €):</strong></label>
                            <input type="number" step="0.01" class="form-control" name="price" id="price" placeholder="Prix" value="<?php echo $price; ?>">
                            <span class="help-inline">
                                <?php echo $priceError; ?>
                            </span>
                        </div>    
                        <div class="form-group">
                            <label for="category"><strong>Catégorie :</strong></label>
                            <select class="form-control" name="category" id="category">
                                <?php
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

                                    foreach ($db->query('SELECT * FROM categories') as $row) {
                                        if ($row['id'] == $category) {
                                            echo '<option selected="selected" value="' . $row['id'] . '">' . $row['name'] . '</option>';
                                        } else {
                                            echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
                                        }
                                    }
                                    
                                    // On se déconnecte
                                    $db = null;
                                ?>
                            </select>
                            <span class="help-inline">
                                <?php echo $categoryError; ?>
                            </span>
                        </div>    
                        <div class="form-group">
                            <label><strong>Image :</strong></label>
                            <p><?php echo $image ?></p>
                            <label for="image"><strong>Sélectionner une image :</strong></label>
                            <input type="file" class="form-control" name="image" id="image">
                            <span class="help-inline">
                                <?php echo $imageError; ?>
                            </span>
                        </div>    
                        <br>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-pencil-alt mr-1"></i>
                                Modifier
                            </button>
                            <a href="index.php" class="btn btn-primary">
                                <i class="fas fa-arrow-left mr-1"></i>
                                Retour
                            </a>
                        </div>
                    </form>
                </div>
                <div class="col-md-6">
                    <div class="img-thumbnail">
                        <img src="<?php echo '../images/' . $image ?>" alt="<?php echo $name ?>" class="img-fluid">
                        <div class="price"><?php echo number_format((float)$price, 2, '.', ' ') . ' €'; ?></div>
                        <div class="caption">
                            <h4><?php echo $name; ?></h4>
                            <p><?php echo $description; ?></p>
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