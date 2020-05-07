<?php
    session_start();

    $mail = "";
    $password = null;
    $mailError = $passwordError = $authError = "";

    if (!empty($_POST)) {
        $mail = checkInput($_POST['mail']);
        $password = checkInput($_POST['password']);
        $isSuccess = true;

        if (empty($mail)) {
            $mailError = "Ce champ ne peut pas être vide !";
            $isSuccess = false;
        }

        if (empty($password)) {
            $passwordError = "Ce champ ne peut pas être vide !";
            $isSuccess = false;
        } else {
            $hash = sha1($password);
        }

        if ($isSuccess) {
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

            $statement = $db->prepare("
                SELECT count(*) AS count FROM users WHERE email = ? AND password = ?
            ");

            $statement->execute(array($mail, $hash));

            $user = $statement->fetch();
            $count = $user['count'];

            // On se déconnecte
            $db = null;

            if ($count != 0) {
                $_SESSION['username'] = $mail;
                $_SESSION['logged_in'] = true;
                header("Location: admin");
            } else {
                $authError = "Identifiant ou mot de passe incorrects !";
            }
        }
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

        <div class="container admin">
            <div class="row flex-column">
                    <h1>
                        <strong>Connexion</strong>
                    </h1>
                    <span class="help-inline">
                        <?php echo $authError; ?>
                    </span>
                    <br>
                    <form class="form" role="form" action="login.php" method="POST">
                        <div class="form-group">
                            <label for="mail"><strong>Mail :</strong></label>
                            <input type="text" name="mail" id="mail" placeholder="Votre mail" class="form-control" value="<?php echo $mail ?>">
                            <span class="help-inline">
                                <?php echo $mailError; ?>
                            </span>
                        </div>
                        <div class="form-group">
                            <label for="password"><strong>Mot de passe :</strong></label>
                            <input type="password" name="password" id="password" placeholder="Votre mot de passe" class="form-control" value="<?php echo $password ?>">
                            <span class="help-inline">
                                <?php echo $passwordError; ?>
                            </span>
                        </div>
                        <br>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-check mr-1"></i>
                                Valider
                            </button>
                        </div>
                    </form>
            </div>
        </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</body>
</html>