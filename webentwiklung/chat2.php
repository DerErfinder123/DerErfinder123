<?php
include "intro-funktion.php";
include "intro-funktion-intern.php";

$name = $_SESSION["name"];
if($datenbank->querySingle("SELECT is_2 FROM rights WHERE name='$name'") == "false"){
    header("Location: index.php");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $zeit = time();
    $inhalt = $_POST["inhalt"];

    $datenbank->exec("INSERT INTO nachrichten2 VALUES ('$name', $zeit, '$inhalt')");
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
        <form method="POST">
            <a class="button-link text-weiß" href="intern.php">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-box-arrow-left" viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                        d="M6 12.5a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5h-8a.5.5 0 0 0-.5.5v2a.5.5 0 0 1-1 0v-2A1.5 1.5 0 0 1 6.5 2h8A1.5 1.5 0 0 1 16 3.5v9a1.5 1.5 0 0 1-1.5 1.5h-8A1.5 1.5 0 0 1 5 12.5v-2a.5.5 0 0 1 1 0z" />
                    <path fill-rule="evenodd"
                        d="M.146 8.354a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L1.707 7.5H10.5a.5.5 0 0 1 0 1H1.707l2.147 2.146a.5.5 0 0 1-.708.708z" />
                </svg>
                Zurück
            </a>
        </form>
    </div>
    <div class="mitte">
        <div>
            <div id="nachrichten"><?php $res = $datenbank->query("SELECT * FROM nachrichten2");
             while ($zeile = $res->fetchArray(SQLITE3_ASSOC)) {
                echo date("H:i:s", $zeile["zeit"]) . " ";
                echo $zeile["name"] . ": ";
                echo $zeile["inhalt"];
                echo "<br>";
             }
             ?></div>
            <form method="POST">
                <input id="eingabefeld" name="inhalt">
                <button class="button hintergrund-blau" type="submit">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-send-fill" viewBox="0 0 16 16">
                        <path d="M15.964.686a.5.5 0 0 0-.65-.65L.767 5.855H.766l-.452.18a.5.5 0 0 0-.082.887l.41.26.001.002 4.995 3.178 3.178 4.995.002.002.26.41a.5.5 0 0 0 .886-.083zm-1.833 1.89L6.637 10.07l-.215-.338a.5.5 0 0 0-.154-.154l-.338-.215 7.494-7.494 1.178-.471z" />
                    </svg>
                </button>
            </form>
        </div>
    </div>
    <audio autoplay>
        <source src="musik.mp3" type="audio/mpeg">
    </audio>
</body>

</html>