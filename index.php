<?php
session_start(); // Startet die Session für den Benutzer

// Zugangsdaten für Benutzer
$validUsername = "user";
$validPassword = "password";

// Login-Überprüfung
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'], $_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Überprüfung der Zugangsdaten
    if ($username === $validUsername && $password === $validPassword) {
        $_SESSION['logged_in'] = true; // Login-Status speichern
        header('Location: index.php?page=start'); // Weiterleitung zur Startseite
        exit;
    } else {
        $loginError = "Benutzername oder Passwort ist falsch!";
    }
}

// Prüfen, ob Benutzer eingeloggt ist
$isLoggedIn = $_SESSION['logged_in'] ?? false; // Standardwert ist `false`

// Logout-Logik
if (isset($_GET['logout'])) {
    session_destroy(); // Session beenden
    header('Location: index.php?page=start'); // Zur Startseite weiterleiten
    exit;
}

// Meldung für nicht eingeloggte Benutzer
$needLogin = "Fehlende Berechtigung für diesen Bereich.<br>
                <br>
                Bitte gebe im Menü unter dem Punkt <b>Einloggen</b> Deine Nutzerdaten ein, um vollen Zugriff zu erhalten.";

// Standard-Seite ist Startseite
$page = $_GET['page'] ?? 'start'; 

// Kontakte initialisieren
$contacts = [];
if (file_exists('contacts.txt')) {
    $contacts = json_decode(file_get_contents('contacts.txt'), true) ?: [];
} else {
    $contacts = []; // Leeres Array, wenn die Datei nicht existiert
}

// *** Fix: Kontakt hinzufügen ***
if ($page === 'contacts' && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'], $_POST['phone'])) {
    $newContact = [
        'name' => htmlspecialchars($_POST['name']), // Schutz vor XSS
        'phone' => htmlspecialchars($_POST['phone']),
    ];

    // Kontakt zur Liste hinzufügen
    $contacts[] = $newContact;
    file_put_contents('contacts.txt', json_encode($contacts)); // Kontaktliste speichern

    // Weiterleitung zur Kontaktseite, um doppeltes Absenden zu vermeiden
    header('Location: index.php?page=contacts');
    exit;
}

// *** Fix: Kontakt löschen ***
if ($page === 'delete' && isset($_GET['delete'])) {
    $indexToDelete = (int) $_GET['delete']; // Index des zu löschenden Kontakts

    // Kontakt entfernen, falls der Index existiert
    if (isset($contacts[$indexToDelete])) {
        unset($contacts[$indexToDelete]); // Entfernt den Kontakt
        $contacts = array_values($contacts); // Indizes neu ordnen
        file_put_contents('contacts.txt', json_encode($contacts)); // Aktualisierte Liste speichern
    }

    // Weiterleitung zur Kontaktseite nach dem Löschen
    header('Location: index.php?page=contacts');
    exit;
}
?>

<?php
// HTML-Inhalt für die Startseite
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

<?php
// HTML-Inhalt für das Impressum
$impressum = "Vorname Nachname<br>
                Strasse Hausnummer<br>
                PLZ Ort<br>
                <br>
                Tel.:<br>
                Fax.:<br>
                eMail:<br>
                <br>
                Haftungsausschluss für Inhalte von Drittanbietern."
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kontaktbuch</title>
    <link rel="stylesheet" href="style.css"> <!-- Verknüpfung externer CSS-Datei -->
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
            // Seitenlogik basierend auf `$page`
            if ($page === 'login') {
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
                echo "<h1>Impressum</h1><p>$impressum</p>";
            } else {
                echo $startseite;
            }
        ?>
        </div>
    </div>

    <div class="footer">
        <img src="img/copyright.svg"> 2025 Marcus Moser
    </div>
</body>
</html>
