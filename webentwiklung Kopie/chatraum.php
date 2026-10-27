<?php
include "intro-funktion.php";
include "intro-funktion-intern.php";

$raumId = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
$raumAbfrage = $datenbank->prepare(
    "SELECT chat_rooms.id, chat_rooms.title
    FROM chat_rooms
    INNER JOIN chat_room_users ON chat_room_users.room_id = chat_rooms.id
    WHERE chat_rooms.id = :id
    AND (chat_room_users.user_name = :name OR chat_rooms.created_by = :name)"
);
$raumAbfrage->bindValue(":id", $raumId === false || $raumId === null ? 0 : $raumId, SQLITE3_INTEGER);
$raumAbfrage->bindValue(":name", $_SESSION["name"], SQLITE3_TEXT);
$raum = $raumAbfrage->execute()->fetchArray(SQLITE3_ASSOC);

if ($raum === false) {
    header("Location: intern.php");
    exit;
}

$fehler = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $inhaltInput = $_POST["inhalt"] ?? "";
    $inhalt = is_string($inhaltInput) ? trim($inhaltInput) : "";
    if ($inhalt !== "" && strlen($inhalt) <= 1000) {
        $nachricht = $datenbank->prepare(
            "INSERT INTO chat_room_messages (room_id, name, zeit, inhalt)
            VALUES (:room_id, :name, :zeit, :inhalt)"
        );
        $nachricht->bindValue(":room_id", $raum["id"], SQLITE3_INTEGER);
        $nachricht->bindValue(":name", $_SESSION["name"], SQLITE3_TEXT);
        $nachricht->bindValue(":zeit", time(), SQLITE3_INTEGER);
        $nachricht->bindValue(":inhalt", $inhalt, SQLITE3_TEXT);
        $nachricht->execute();
        header("Location: chatraum.php?id=" . (int) $raum["id"]);
        exit;
    } else {
        $fehler = "Nachrichten dürfen nicht leer sein und höchstens 1000 Zeichen enthalten.";
    }
}

$nachrichtenAbfrage = $datenbank->prepare(
    "SELECT name, zeit, inhalt FROM chat_room_messages WHERE room_id = :room_id ORDER BY id"
);
$nachrichtenAbfrage->bindValue(":room_id", $raum["id"], SQLITE3_INTEGER);
$nachrichten = $nachrichtenAbfrage->execute();
$esc = static fn(string $wert): string => htmlspecialchars($wert, ENT_QUOTES, "UTF-8");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="styles.css">
    <title><?= $esc($raum["title"]) ?></title>
</head>
<body>
    <div class="text-links">
        <a class="text-weiß" href="intern.php">Zurück</a>
    </div>
    <div class="mitte">
        <div>
            <h1><?= $esc($raum["title"]) ?></h1>
            <?php if ($fehler !== ""): ?>
                <p><?= $esc($fehler) ?></p>
            <?php endif; ?>
            <div id="nachrichten">
                <?php while ($nachricht = $nachrichten->fetchArray(SQLITE3_ASSOC)): ?>
                    <?= date("H:i:s", (int) $nachricht["zeit"]) ?>
                    <?= $esc($nachricht["name"]) ?>:
                    <?= nl2br($esc($nachricht["inhalt"])) ?><br>
                <?php endwhile; ?>
            </div>
            <form method="POST">
                <input id="eingabefeld" name="inhalt" maxlength="1000" required>
                <button class="button hintergrund-blau" type="submit">Senden</button>
            </form>
        </div>
    </div>
</body>
</html>
