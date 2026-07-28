<?php
include "intro-funktion.php";
include "intro-funktion-intern.php";

$name = $_SESSION["name"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $zeit = time();
    $inhalt = $_POST["inhalt"];

    $datenbank->exec("insert into narchichten values ('$name',$zeit,'$inhalt')");
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
            <a class="button-link text-weiß" href="intern.php">
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
        <div class="notiz-container">
            <div class="notiz-header">
                <h1>Hallo <?php echo htmlspecialchars($_SESSION["name"], ENT_QUOTES, 'UTF-8'); ?>!</h1>
                <p>Hier kannst du deine Notiz schreiben und später wieder laden.</p>
            </div>
            <form method="POST" class="notiz-form">
                <textarea id="notiz" name="notiz" class="notiz-textarea"><?php echo htmlspecialchars($datenbank->querySingle("SELECT notiz FROM nutzer WHERE name='$name'"), ENT_QUOTES, 'UTF-8'); ?></textarea>
                <div class="notiz-actions">
                    <button class="button hintergrund-blau" type="submit">Speichern</button>
                </div>
            </form>
        </div>

    </div>
    <audio autoplay>
        <source src="musik.mp3" type="audio/mpeg">
    </audio>
    <!--<script>
        const notiz = document.getElementById("notiz");

        notiz.value = localStorage.getItem("notiz" + localStorage.getItem("name"));

        function notizSpeichern() {
            localStorage.setItem("notiz" + localStorage.getItem("name"), notiz.value);
        }

        notiz.addEventListener("input", notizSpeichern);
    </script>-->
</body>

</html>