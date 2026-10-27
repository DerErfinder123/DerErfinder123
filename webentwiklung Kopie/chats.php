<?php
include "intro-funktion.php";
include "intro-funktion-intern.php";

const ADMIN_NAME = "samuelroessel";
$name = $_SESSION["name"];
$istGlobalAdmin = $name === ADMIN_NAME;

$meldung = "";
$fehler = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $aktion = $_POST["aktion"] ?? "";

    if ($aktion === "erstellen") {
        $titelInput = $_POST["titel"] ?? "";
        $titel = is_string($titelInput) ? trim($titelInput) : "";

        if ($titel === "" || strlen($titel) > 80) {
            $fehler = "Der Chatname muss zwischen 1 und 80 Zeichen lang sein.";
        } else {
            $datenbank->exec("BEGIN");
            $raum = $datenbank->prepare("INSERT INTO chat_rooms (title, created_by) VALUES (:title, :created_by)");
            $raum->bindValue(":title", $titel, SQLITE3_TEXT);
            $raum->bindValue(":created_by", $_SESSION["name"], SQLITE3_TEXT);
            $raum->execute();
            $raumId = $datenbank->lastInsertRowID();

            $mitglied = $datenbank->prepare("INSERT INTO chat_room_users (room_id, user_name) VALUES (:room_id, :user_name)");
            $mitglied->bindValue(":room_id", $raumId, SQLITE3_INTEGER);
            $mitglied->bindValue(":user_name", $_SESSION["name"], SQLITE3_TEXT);
            $mitglied->execute();
            $datenbank->exec("COMMIT");

            header("Location: chats.php?erstellt=1");
            exit;
        }
    } elseif ($aktion === "rechte") {
        $raumId = filter_var($_POST["raum_id"] ?? null, FILTER_VALIDATE_INT);
        $raumAbfrage = $datenbank->prepare(
            "SELECT id FROM chat_rooms
            WHERE id = :id AND (created_by = :name OR :is_admin = 1)"
        );
        $raumAbfrage->bindValue(":id", $raumId === false || $raumId === null ? 0 : $raumId, SQLITE3_INTEGER);
        $raumAbfrage->bindValue(":name", $name, SQLITE3_TEXT);
        $raumAbfrage->bindValue(":is_admin", $istGlobalAdmin, SQLITE3_INTEGER);
        $raum = $raumAbfrage->execute()->fetchArray(SQLITE3_ASSOC);

        if ($raumId === false || $raumId === null || $raum === false) {
            $fehler = "Der Chat wurde nicht gefunden.";
        } else {
            $ausgewaehlteNutzer = $_POST["zugriff"] ?? [];
            if (!is_array($ausgewaehlteNutzer)) {
                $ausgewaehlteNutzer = [];
            }

            $datenbank->exec("BEGIN");
            $loeschen = $datenbank->prepare("DELETE FROM chat_room_users WHERE room_id = :room_id");
            $loeschen->bindValue(":room_id", $raumId, SQLITE3_INTEGER);
            $loeschen->execute();

            $speichern = $datenbank->prepare(
                "INSERT OR IGNORE INTO chat_room_users (room_id, user_name)
                SELECT :room_id, name FROM nutzer WHERE name = :name"
            );
            foreach ($ausgewaehlteNutzer as $nutzerName) {
                if (!is_string($nutzerName)) {
                    continue;
                }
                $speichern->bindValue(":room_id", $raumId, SQLITE3_INTEGER);
                $speichern->bindValue(":name", $nutzerName, SQLITE3_TEXT);
                $speichern->execute();
            }

            $mitgliedSpeichern = $datenbank->prepare(
                "INSERT OR IGNORE INTO chat_room_users (room_id, user_name)
                SELECT :room_id, name FROM nutzer WHERE name = :name"
            );
            $mitgliedSpeichern->bindValue(":room_id", $raumId, SQLITE3_INTEGER);
            $mitgliedSpeichern->bindValue(":name", $_SESSION["name"], SQLITE3_TEXT);
            $mitgliedSpeichern->execute();
            $datenbank->exec("COMMIT");

            header("Location: chats.php?gespeichert=1");
            exit;
        }
    }
}

if (isset($_GET["erstellt"])) {
    $meldung = "Der neue Chat wurde erstellt.";
} elseif (isset($_GET["gespeichert"])) {
    $meldung = "Die Zugriffsrechte wurden gespeichert.";
}

$nutzerResult = $datenbank->query("SELECT name FROM nutzer ORDER BY name");
$nutzer = [];
while ($zeile = $nutzerResult->fetchArray(SQLITE3_ASSOC)) {
    $nutzer[] = $zeile["name"];
}

$raeumeAbfrage = $datenbank->prepare(
    "SELECT id, title, created_by FROM chat_rooms
    WHERE created_by = :name OR :is_admin = 1
    ORDER BY id"
);
$raeumeAbfrage->bindValue(":name", $name, SQLITE3_TEXT);
$raeumeAbfrage->bindValue(":is_admin", $istGlobalAdmin, SQLITE3_INTEGER);
$raeumeResult = $raeumeAbfrage->execute();
$raeume = [];
$mitgliedschaften = [];
while ($raum = $raeumeResult->fetchArray(SQLITE3_ASSOC)) {
    $raeume[] = $raum;
    $zugriffResult = $datenbank->prepare("SELECT user_name FROM chat_room_users WHERE room_id = :room_id");
    $zugriffResult->bindValue(":room_id", $raum["id"], SQLITE3_INTEGER);
    $zugriffZeilen = $zugriffResult->execute();
    $mitgliedschaften[$raum["id"]] = [];
    while ($zugriff = $zugriffZeilen->fetchArray(SQLITE3_ASSOC)) {
        $mitgliedschaften[$raum["id"]][] = $zugriff["user_name"];
    }
}

$esc = static fn(string $wert): string => htmlspecialchars($wert, ENT_QUOTES, "UTF-8");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="styles.css">
    <title>Chats verwalten</title>
</head>
<body>
    <div class="text-links">
        <form method="POST" action="logout.php">
            <button class="button-link text-weiß" type="submit">Logout</button>
        </form>
    </div>
    <div class="mitte">
        <div class="text-links">
            <h1>Chats verwalten</h1>
            <p>Erstelle private Chats. Als Ersteller bist du Admin und kannst die Zugriffsrechte verwalten.</p>
            <a class="text-weiß" href="intern.php">Zurück</a>

            <?php if ($meldung !== ""): ?>
                <p><?= $esc($meldung) ?></p>
            <?php endif; ?>
            <?php if ($fehler !== ""): ?>
                <p><?= $esc($fehler) ?></p>
            <?php endif; ?>

            <h2>Neuen Chat erstellen</h2>
            <form method="POST">
                <input type="hidden" name="aktion" value="erstellen">
                <label for="titel">Chatname:</label>
                <input id="titel" name="titel" type="text" maxlength="80" required>
                <button class="button hintergrund-blau" type="submit">Chat erstellen</button>
            </form>

            <h2>Deine Chats</h2>
            <?php if ($raeume === []): ?>
                <p>Es wurden noch keine zusätzlichen Chats erstellt.</p>
            <?php endif; ?>
            <?php foreach ($raeume as $raum): ?>
                <section class="admin-rechte">
                    <h3><?= $esc($raum["title"]) ?></h3>
                    <p>Admin: <?= $esc($raum["created_by"]) ?></p>
                    <p><a class="text-weiß" href="chatraum.php?id=<?= (int) $raum["id"] ?>">Chat öffnen</a></p>
                    <form method="POST">
                        <input type="hidden" name="aktion" value="rechte">
                        <input type="hidden" name="raum_id" value="<?= (int) $raum["id"] ?>">
                        <fieldset>
                            <legend>Zugriff erlauben</legend>
                            <?php foreach ($nutzer as $nutzerName): ?>
                                <label>
                                    <input type="checkbox" name="zugriff[]" value="<?= $esc($nutzerName) ?>"
                                        <?= in_array($nutzerName, $mitgliedschaften[$raum["id"]], true) ? "checked" : "" ?>>
                                    <?= $esc($nutzerName) ?>
                                </label><br>
                            <?php endforeach; ?>
                        </fieldset>
                        <button class="button hintergrund-blau" type="submit">Rechte speichern</button>
                    </form>
                </section>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
