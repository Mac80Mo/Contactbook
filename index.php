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
            color: #343434;
            font-size: 20px;
            border-radius: 100%;
            background-color: #EAEDF8;
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
            <a href="index.php?page=start"><img src="img/home.svg">Start</a>
            <a href="index.php?page=contacts"><img src="img/book.svg">Kontakte</a>
            <a href="index.php?page=addcontact"><img src="img/add.svg">Kontakt hinzufügen</a>
            <a href="index.php?page=legal"><img src="img/legal.svg">Impressum</a>
        </div>

        <div class="content">
        <?php
            $headline = 'Herzlich willkommen';
            $contacts = [];

            // Kontakte aus der Datei laden 
            if(file_exists('contacts.txt')) {
                $text = file_get_contents('contacts.txt', true);
                $contacts = json_decode($text, true);
            }

            // Kontakte hinzufügen
            if(isset($_POST['name']) && isset($_POST['phone'])) {
                echo 'Kontakt <b>' . $_POST['name'] . '</b> wurde hinzugefügt';
                $newContact = [
                    'name' => htmlspecialchars($_POST['name']),
                    'phone' => htmlspecialchars($_POST['phone'])
                ];
                array_push($contacts, $newContact);
                file_put_contents('contacts.txt', json_encode($contacts, JSON_PRETTY_PRINT));
            }

            // Standartwert für 'page', falls Parameter nicht existiert => start
            $page = $_GET['page'] ?? 'start'; 

            # Löschen von Kontakten
            if($page === 'delete') {
                $headline = 'Kontakt gelöscht';
            }

            if($page === 'contacts') {
                $headline = 'Deine Kontakte';
            }

            if($page === 'legal') {
                $headline = 'Impressum';
            }

            if($page === 'addcontact') {
                $headline = 'Kontakt hinzufügen';
            }

            echo '<h1>' . $headline . '</h1>';

            if ($page === 'delete') {
                echo '<p>Dein Kontakt wurde gelöscht</p>';

                # Wir laden die Nummer der Reihe aus den URL Parametern
                $index = $_GET['delete'];

                # wir löschen die Stelle aus dem Array
                unset($contacts[$index]);

                # Tabelle erneut speichern in Datei contacts.txt
                file_put_contents('contacts.txt', json_encode($contacts, JSON_PRETTY_PRINT));

            } else if($page === 'contacts') {

                echo "
                    <p>Auf dieser Seite hast Du einen Überblick über Deine <b>Kontakte</b></p>
                ";

                foreach ($contacts as $index=>$row) {
                    $name = htmlspecialchars($row['name']);
                    $phone = htmlspecialchars($row['phone']);

                    echo "
                    <div class='card'>
                        <img class='profile-picture' src='img/profile-picture.png'>
                        <b>$name</b><br>
                        $phone

                        <a class='phonebtn' href='tel:$phone'>Anrufen</a> 
                        <a class='deletebtn' href='?page=delete&delete=$index'>Löschen</a>
                    </div>
                    ";
                }

            } else if($page === 'legal') {

                echo "
                    <p>Hier kommt das <b>Impressum</b> hin.</p>
                ";

            } else if($page === 'addcontact') {

                echo "
                <div>
                    Auf dieser Seite kannst Du weitere Kontakte hinzufügen.
                </div>
                
                <form action='?page=contacts' method='POST'>
                    <div>
                        <input placeholder='Namen eingeben:' name='name'>
                    </div>

                    <div>
                        <input placeholder='Telefonnummer eingeben:' name='phone'>
                    </div>

                    <button type='submit'>Absenden</button>
                </form>
                ";

            } else {

                echo '<p>Du bist auf der <b>Startseite</b>!</p>';
            }
        ?>
        </div>
    </div>

    <div class="footer">
            (C) 2025 Marcus Moser
    </div>

    


</body>
</html>