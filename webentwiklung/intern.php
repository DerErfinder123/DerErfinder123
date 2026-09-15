<?php
include "intro-funktion.php";
include "intro-funktion-intern.php";

$name = $_SESSION["name"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $notiz = $_POST["notiz"];

    $datenbank->exec("UPDATE nutzer SET notiz='$notiz' WHERE name='$name'");
}
?>
<!DOCTYPE html>

<html>

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="styles.css">
    <title>Chat</title>
</head>

<body>
    <div class="text-links">
        <form method="POST" action="logout.php">
            <button class="button-link text-weiß" type="submit">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-box-arrow-left" viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                        d="M6 12.5a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5h-8a.5.5 0 0 0-.5.5v2a.5.5 0 0 1-1 0v-2A1.5 1.5 0 0 1 6.5 2h8A1.5 1.5 0 0 1 16 3.5v9a1.5 1.5 0 0 1-1.5 1.5h-8A1.5 1.5 0 0 1 5 12.5v-2a.5.5 0 0 1 1 0z" />
                    <path fill-rule="evenodd"
                        d="M.146 8.354a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L1.707 7.5H10.5a.5.5 0 0 1 0 1H1.707l2.147 2.146a.5.5 0 0 1-.708.708z" />
                </svg>
                Logout
            </button>
        </form>
    </div>
    <div class="mitte">
        <div>
            <h1>Hallo <?php echo $_SESSION["name"]; ?>!</h1>
        </div>
        <div>
            <form method="POST">
                <textarea id="notiz" name="notiz"><?= $datenbank->querySingle("SELECT notiz FROM nutzer WHERE name='$name'"); ?></textarea>
                <button class="button hintergrund-blau" type="submit">Speichern</button>
            </form>
        </div>
        <a class="button" href="chat.php">Zum Chat</a>
    </div>
    <audio autoplay>
        <source src="musik.mp3" type="audio/mpeg">
    </audio>
</body>

</html>