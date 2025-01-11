<?php
session_start();

// Zugangsdaten für Benutzer
$validUsername = "user";
$validPassword = "password";

// Login-Überprüfung
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'], $_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username === $validUsername && $password === $validPassword) {
        $_SESSION['logged_in'] = true; // Benutzer ist eingeloggt
        header('Location: index.php?page=start'); // Weiterleitung zur Startseite
        exit;
    } else {
        $loginError = "Benutzername oder Passwort ist falsch!";
    }
}

// Prüfen, ob Benutzer eingeloggt ist
$isLoggedIn = $_SESSION['logged_in'] ?? false;

// Logout-Logik
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: index.php?page=start'); // Weiterleitung zur Startseite
    exit;
}

// meine Variablen bezüglich Text
$needLogin = "Fehlende Berechtigung für diesen Bereich.<br>
                <br>
                Bitte geben Sie im  Menü unter dem Punkt <b>Einloggen</b> Ihre Nutzerdaten ein um vollen Zugriff zu erhalten."
              

?>

<?php
$startseite = <<<HTML
<div class="content">
    <h1>Willkommen zu deinem digitalen Kontaktbuch!</h1>
    <p>Hier kannst du deine Kontakte einfach und übersichtlich verwalten. Die Funktionen umfassen:</p>
    <ul>
        <li><b>Startseite:</b> Begrüßung und Erklärung der Funktionen.</li>
        <li><b>Login/Logout:</b> 
            <ul>
                <li>Einloggen, um auf geschützte Funktionen zuzugreifen.</li>
                <li>Nach dem Logout sind alle Rechte entzogen, erneutes Einloggen ist jederzeit möglich.</li>
            </ul>
        </li>
        <li><b>Kontakte verwalten:</b> 
            <ul>
                <li><b>Kontakte anzeigen:</b> Einsicht in eine Liste deiner gespeicherten Kontakte.</li>
                <li><b>Kontakte hinzufügen:</b> Möglichkeit, neue Kontakte mit Namen und Telefonnummer zu speichern.</li>
                <li><b>Kontakte löschen:</b> Entferne Kontakte, die nicht mehr benötigt werden.</li>
            </ul>
        </li>
        <li><b>Zugriffsbeschränkung:</b> Ohne Login kannst du keine geschützten Funktionen nutzen. Stattdessen erhältst du eine Meldung, dich einzuloggen.</li>
    </ul>
    <p>Viel Spaß bei der Nutzung deines digitalen Kontaktbuchs!</p>
</div>
HTML;
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marcus Moser</title>

    <style>
        .menubar {
            color: white;
            background-color: #343434;
            position: absolute;
            left: 0px;
            right: 0px;
            top: 0px;
            height: 80px;
            display: flex;
            justify-content: space-between;
            margin-left: 40px;
        }

        body {
            font-family: "Arial";
            background-color: #EAEDF8;
            margin: 0px;
        }

        .main {
            display: flex;
        }

        .menu {
            width: 20%;
            background-color: #343434;
            margin-right: 32px;
            padding-top: 150px;
            height: 100vh;
        }

        .menu a {
            display: block;
            text-decoration: none;
            color: white;
            padding: 8px;
            display: flex;
            align-items: center;
        }

        .menu img {
            margin-right: 8px;
        }

        .menu a:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .content {
            width: 80%;
            margin-top: 100px;
            margin-right: 32px;
            background-color: white;
            border-radius: 8px;
            padding: 16px;
        }

        .footer {
            padding: 100px;
            text-align: center;
            background-color: #343434;
            color: white;
            margin-top: 300px;
        }

        .avatar {
            color: white;
            font-size: 20px;
            border-radius: 100%;
            background-color: navy;
            padding: 16px;
            width: 24px;
            height: 24px;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-right: 8px;

        }

        .myname {
            display: flex;
            margin-right: 50px;
            align-items: center;
        }

        .card {
            background-color: rgba(0, 0, 0, 0.05);
            margin-bottom: 16px;
            border-radius: 8px;
            padding: 8px;
            padding-left: 64px;
            position: relative;
        }

        .profile-picture {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            border: 2px solid white;
            position: absolute;
            left: 8px;
        }

        .phonebtn {
            font-size: 12px;
            background-color: darkgreen;
            border-radius: 5%;
            padding: 4px;
            color: white;
            text-decoration: none;
            position: absolute;
            right: 8px;
            top: 3px;
        }

        .phonebtn:hover {
            color: black;
            background-color: #7CFC00;
            transform: scale(1.1);
            transition: transform 0.2s ease-in-out;
        }

        .deletebtn {
            font-size: 10px;
            background-color: darkred;
            border-radius: 5%;
            padding: 4px;
            color: white;
            text-decoration: none;
            position: absolute;
            right: 8px;
            bottom: 3px;
        }

        .deletebtn:hover {
            color: black;
            background-color: red; 
            transform: scale(1.1);
            transition: transform 0.2s ease-in-out;          
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 16px;
            padding: 16px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 16px;
        }

        form input {
            padding: 8px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 100%;
        }

        form button {
            padding: 8px 16px;
            font-size: 16px;
            color: white;
            background-color: navy;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        form button:hover {
            background-color: blue;
        }

    </style>
</head>
<body>
    <div class="menubar">
        <h1>Mein Kontakt-Buch</h1>
        <div class="myname">
            <div class="avatar"><h2>M</h2></div><h3>Marcus Moser</h3>
        </div>
    </div>

    <div class="main">
        <div class="menu">
            <!-- Menüpunkte -->
            <a href="index.php?page=start"><img src="img/home.svg">Start</a>
            <?php if ($isLoggedIn): ?>
                <a href="index.php?logout=true"><img src="img/logout.svg">Ausloggen</a>
            <?php else: ?>
                <a href="index.php?page=login"><img src="img/login.svg">Einloggen</a>
            <?php endif; ?>
            <a href="index.php?page=contacts"><img src="img/book.svg">Kontakte</a>
            <a href="index.php?page=addcontact"><img src="img/add.svg">Kontakt hinzufügen</a>
            <a href="index.php?page=legal"><img src="img/legal.svg">Impressum</a>
        </div>

        <div class="content">
        <?php
            $page = $_GET['page'] ?? 'start'; // Standard-Seite ist Startseite
            $contacts = [];

            // Kontakte aus der Datei laden
            if (file_exists('contacts.txt')) {
                $text = file_get_contents('contacts.txt', true);
                $contacts = json_decode($text, true);
            }

            if ($page === 'login') {
                // Login-Formular anzeigen
                echo "<h1>Login</h1>";
                if (isset($loginError)) {
                    echo "<p style='color: red;'>$loginError</p>";
                }
                echo "
                <form method='POST'>
                    <input type='text' name='username' placeholder='Benutzername eingeben: user' required>
                    <input type='password' name='password' placeholder='Passwort eingeben: password' required>
                    <button type='submit'>Einloggen</button>
                </form>";

            } elseif ($page === 'contacts') {
                echo "<h1>Deine Kontakte</h1>";
                if (!$isLoggedIn) {
                    echo "<p>$needLogin</p>";
                } else {
                    foreach ($contacts as $index => $contact) {
                        $name = htmlspecialchars($contact['name']);
                        $phone = htmlspecialchars($contact['phone']);
                        echo "
                        <div class='card'>
                            <img class='profile-picture' src='img/profile-picture.png'>
                            <b>$name</b><br>
                            $phone
                            <a class='phonebtn' href='tel:$phone'>Anrufen</a> 
                            <a class='deletebtn' href='index.php?page=delete&delete=$index'>Löschen</a>
                        </div>";
                    }
                }
            } elseif ($page === 'addcontact') {
                echo "<h1>Kontakt hinzufügen</h1>";
                if (!$isLoggedIn) {
                    echo "<p>$needLogin</p>";
                } else {
                    echo "
                    <form method='POST' action='index.php?page=contacts'>
                        <div>
                            <input placeholder='Name eingeben:' name='name' required>
                        </div>
                        <div>
                            <input placeholder='Telefonnummer eingeben:' name='phone' required>
                        </div>
                        <button type='submit'>Hinzufügen</button>
                    </form>";
                }
            } elseif ($page === 'legal') {
                echo "<h1>Impressum</h1><p>Hier steht das Impressum.</p>";
            } else {
                echo "<h1>Willkommen zu deinem digitalen Kontaktbuch!</h1>
        <p>Hier kannst du deine Kontakte einfach und übersichtlich verwalten. Die Funktionen umfassen:</p>
        <ul>
            <li><b>Startseite:</b> 
                <ul>
                    <li>Begrüßung und Erklärung der Funktionen.</li>
                </ul><br>
            <li><b>Login/Logout:</b> 
                <ul>
                    <li>Einloggen, um auf geschützte Funktionen zuzugreifen.</li>
                    <li>Nach dem Logout sind alle Rechte entzogen, erneutes Einloggen ist jederzeit möglich.</li>
                </ul><br>
            </li>
            <li><b>Kontakte verwalten:</b> 
                <ul>
                    <li><b>Kontakte anzeigen:</b> Einsicht in eine Liste deiner gespeicherten Kontakte.</li>
                    <li><b>Kontakte hinzufügen:</b> Möglichkeit, neue Kontakte mit Namen und Telefonnummer zu speichern.</li>
                    <li><b>Kontakte löschen:</b> Entferne Kontakte, die nicht mehr benötigt werden.</li>
                </ul><br>
            </li>
            <li><b>Zugriffsbeschränkung:</b>
                <ul>
                    <li>Ohne Login kannst du keine geschützten Funktionen nutzen. Stattdessen erhältst du eine Meldung, dich einzuloggen.</li>
                </ul><br>
        </ul>
        <p>Viel Spaß bei der Nutzung deines digitalen Kontaktbuchs!</p>";
            }
        ?>
        </div>
    </div>

    <div class="footer">
    <img src="img/copyright.svg"> 2025 Marcus Moser
    </div>
</body>
</html>
