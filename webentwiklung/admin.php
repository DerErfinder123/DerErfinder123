<?php
include "intro-funktion.php";
include "intro-funktion-intern.php";

const ADMIN_NAME = "samuelroessel";

// Nur dieser Benutzer darf die privaten Chatrooms verwalten.
if ($_SESSION["name"] !== ADMIN_NAME) {
    header("Location: intern.php");
    exit;
}

$meldung = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST["name"] ?? "";
    $raum = (int) ($_POST["raum"] ?? 0);
    $spalte = "is_" . $raum;
    $isZugriff = ($_POST["zugriff"] ?? "false") === "true" ? "true" : "false";

    // Die Raumnummer wird begrenzt, bevor sie als Spaltenname verwendet wird.
    if ($raum >= 1 && $raum <= 10) {
        $abfrage = $datenbank->prepare("UPDATE rights SET $spalte = :zugriff WHERE name = :name");
        $abfrage->bindValue(":zugriff", $isZugriff, SQLITE3_TEXT);
        $abfrage->bindValue(":name", $name, SQLITE3_TEXT);
        $abfrage->execute();
    }

    $meldung = "Die Rechte für " . htmlspecialchars($name, ENT_QUOTES, "UTF-8") . " wurden gespeichert.";
}

$rechte = $datenbank->query("SELECT * FROM rights ORDER BY name");
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="styles.css">
    <title>Admin | Rechte</title>
</head>

<body>
    <div class="text-links">
        <a class="text-weiß" href="intern.php">Zurück</a>
    </div>
    <div class="mitte">
        <div class="text-links">
            <h1>Benutzerrechte</h1>
            <p>Hier kannst du den Zugriff auf alle privaten Chatrooms verwalten.</p>

            <?php if ($meldung !== ""): ?>
                <p><?= $meldung ?></p>
            <?php endif; ?>

            <?php while ($recht = $rechte->fetchArray(SQLITE3_ASSOC)): ?>
                <div class="admin-rechte">
                    <strong><?= htmlspecialchars($recht["name"], ENT_QUOTES, "UTF-8") ?></strong>
                    <?php for ($raum = 1; $raum <= 10; $raum++): ?>
                        <form method="POST">
                            <input type="hidden" name="name" value="<?= htmlspecialchars($recht["name"], ENT_QUOTES, "UTF-8") ?>">
                            <input type="hidden" name="raum" value="<?= $raum ?>">
                            <label>Chat <?= $raum ?></label>
                            <select name="zugriff">
                                <option value="false" <?= $recht["is_$raum"] === "false" ? "selected" : "" ?>>Kein Zugriff</option>
                                <option value="true" <?= $recht["is_$raum"] === "true" ? "selected" : "" ?>>Zugriff erlaubt</option>
                            </select>
                            <button class="button hintergrund-blau" type="submit">Speichern</button>
                        </form>
                    <?php endfor; ?>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>

</html>