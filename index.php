<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Open Graph -->
    <meta property="og:site_name" content="Burger Code">
    <meta property="og:url" content="https://burger-code.herokuapp.com">
    <meta property="og:title" content="Burger Code" name="title">
    <meta property="og:type" content="website">
    <meta property="og:image" content="http://burger-code.herokuapp.com/images/burgercode.jpg" name="image">
    <meta property="og:image:type" content="image/jpeg" />
    <meta property="og:image:width" content="1921" />
    <meta property="og:image:height" content="1263" />
    <meta property="og:description" content="Concept de menu réalisé en PHP & MySQL." name="description">
    <meta name="author" content="Baptiste Bidaux">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">

    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/dbc1e24743.js" crossorigin="anonymous"></script>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Holtwood+One+SC&display=swap" rel="stylesheet">

    <!-- Stylesheet -->
    <link rel="stylesheet" href="css/style.css">

    <title>Burger Code</title>
</head>
<body>
    <div class="container site" id="home">
        <h1 class="text-logo">
            <i class="fas fa-utensils"></i> Burger Code <i class="fas fa-utensils"></i>
        </h1>

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

            $statement = $db->query("SELECT * FROM categories");

            $categories = $statement->fetchAll();

            // Menu
            echo '<nav>
                        <ul class="nav nav-pills nav-fill" role="tablist">';
            foreach ($categories as $category) {
                if ($category['id'] == 1) {
                    echo '<li role="presentation" class="nav-item mr-2">
                                <a href="#' . $category['name'] . '" class="nav-link active" role="tab" data-toggle="pill" aria-controls="pills-menus" aria-selected="true">' . $category['name'] . '</a>
                            </li>';
                } else {
                    echo '<li role="presentation" class="nav-item mr-2">
                                <a href="#' . $category['name'] . '" class="nav-link" role="tab" data-toggle="pill" aria-controls="pills-menus" aria-selected="true">' . $category['name'] . '</a>
                            </li>';
                }
            }
            echo '    </ul>
                    </nav>';

            // Panels
            echo '<div class="tab-content">';
            foreach ($categories as $category) {
                if ($category['id'] == 1) {
                    echo ' <div class="tab-pane fade show active" id="' . $category['name'] . '" role="tabpanel" aria-labelledby="pills-' . strtolower($category['name']) . '-tab">';
                } else {
                    echo ' <div class="tab-pane fade" id="' . $category['name'] . '" role="tabpanel" aria-labelledby="pills-' . strtolower($category['name']) . '-tab">';
                }
                echo '<div class="row">';

                $statement = $db->prepare("SELECT * FROM items WHERE items.category = ?");
                $statement->execute(array($category['id']));
                while($item = $statement->fetch()) {
                    echo '<div class="col-md-6 col-lg-4">
                                <div class="img-thumbnail">
                                    <img src="images/' . $item['image'] . '" alt="' . $item['name'] . '" class="img-fluid">
                                    <div class="price">' . number_format($item['price'], 2, '.', ' ') . ' €</div>
                                    <div class="caption">
                                        <h4>' . $item['name'] . '</h4>
                                         <p>' . $item['description'] . '</p>
                                            <a href="#" class="btn btn-order" role="button">
                                                <i class="fas fa-cart-arrow-down mr-1"></i>
                                                Commander
                                            </a>
                                    </div>
                                </div>
                            </div>';
                }
                echo '</div>
                    </div>';
            }

            // On se déconnecte
            $db = null;

            echo '</div>';
        ?>
    </div>

    <footer>
        <a href="#home">
            <i class="fas fa-chevron-up"></i>
        </a>
    </footer>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</body>
</html>