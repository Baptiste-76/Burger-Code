<?php
    session_start();    
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
                <h1>
                    <strong>Liste des items</strong>
                    <a href="insert.php" class="btn btn-success btn-lg">
                        <i class="fas fa-plus mr-1"></i>
                        Ajouter
                    </a>
                    <a href="logout.php" class="btn btn-danger btn-lg">
                        <i class="fas fa-power-off mr-1"></i>
                        Déconnexion
                    </a>
                </h1>

                <div class="table-responsive-lg">
                    <table class="table table-bordered table-hover mt-3">
                        <thead class="thead-dark lead">
                            <tr>
                                <th scope="col">Nom</th>
                                <th scope="col">Description</th>
                                <th scope="col">Prix</th>
                                <th scope="col">Catégorie</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
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
                                $statement = $db->query('
                                    SELECT items.id, items.name, items.description, items.price, categories.name AS category 
                                    FROM items LEFT JOIN categories
                                    ON items.category = categories.id
                                    ORDER BY items.id DESC
                                ');

                                while ($item = $statement->fetch()) {                               
                                    echo '<tr>';
                                        echo '<th scope="row">' . $item['name'] .'</th>';
                                        echo '<td>' . $item['description'] .'</td>';
                                        echo '<td>' . number_format((float)$item['price'], 2, '.', ' ') .'</td>';
                                        echo '<td>' . $item['category'] .'</td>';
                                        echo '<td style="width: 350px" class="text-center">';
                                            echo '<a href="/admin/view.php?id=' . $item['id'] . '" class="btn btn-secondary">';
                                                echo '<i class="far fa-eye mr-1"></i>';
                                                echo 'Voir';
                                            echo '</a>';
                                            echo ' ';
                                            echo '<a href="/admin/update.php?id=' .$item['id'] . '" class="btn btn-primary">';
                                                echo '<i class="fas fa-pencil-alt mr-1"></i>';
                                                echo 'Modifier';
                                            echo '</a>';
                                            echo ' ';
                                            echo '<a href="/admin/delete.php?id=' . $item['id'] . '" class="btn btn-danger">';
                                                echo '<i class="fas fa-trash mr-1"></i>';
                                                echo 'Suppr.';
                                            echo '</a>';
                                        echo '</td>';
                                    echo '</tr>';
                                }
                            ?>
                        </tbody>
                    </table>
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