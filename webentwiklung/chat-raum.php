<?php
// Lädt die SQLite-Verbindung und startet die Session, damit der aktuelle
// Benutzername aus $_SESSION gelesen werden kann. Die zweite Datei beendet
// den Seitenaufruf, wenn noch niemand angemeldet ist.
include "intro-funktion.php";
include "intro-funktion-intern.php";

// Jede Einstiegsdatei, zum Beispiel chat3.php, setzt vorher CHAT_ROOM. Dadurch
// kann dieselbe Datei für alle zehn Räume verwendet werden, statt die komplette
// Chatlogik zehnmal zu kopieren.
$raum = defined("CHAT_ROOM") ? CHAT_ROOM : 0;
if ($raum < 1 || $raum > 10) {
    // Erlaubt sind ausschließlich die Räume 1 bis 10. Diese Prüfung verhindert,
    // dass ein ungültiger Wert als Tabellen- oder Spaltenname verwendet wird.
    header("Location: intern.php");
    exit;
}

$name = $_SESSION["name"];

// In rights besitzt jeder Raum eine eigene Spalte, zum Beispiel is_3 für Chat 3.
// Die Abfrage liest genau das Recht des angemeldeten Benutzers für diesen Raum.
$recht = $datenbank->prepare("SELECT is_$raum FROM rights WHERE name = :name");
$recht->bindValue(":name", $name, SQLITE3_TEXT);
if ($recht->execute()->fetchArray(SQLITE3_NUM)[0] !== "true") {
    // Ohne passendes Recht wird weder die Mitgliederliste noch der Chat geladen.
    header("Location: intern.php");
    exit;
}

// Chat 1 und Chat 2 stammen aus der ursprünglichen Anwendung und behalten ihre
// bisherigen Tabellennamen. Für Chat 3 bis Chat 10 gilt das neue Schema
// nachrichten3 bis nachrichten10.
$nachrichtenTabelle = $raum === 1 ? "nachrichten" : ($raum === 2 ? "narchichten2" : "nachrichten$raum");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Nur ein abgeschicktes Formular erzeugt eine neue Nachricht. Die Tabelle
    // wurde vorher anhand der geprüften Raumnummer ausgewählt.
    // Platzhalter trennen Benutzereingaben vom SQL-Befehl und verhindern,
    // dass der Nachrichteninhalt die SQL-Anweisung verändert.
    $abfrage = $datenbank->prepare("INSERT INTO $nachrichtenTabelle (name, zeit, inhalt) VALUES (:name, :zeit, :inhalt)");
    $abfrage->bindValue(":name", $name, SQLITE3_TEXT);
    $abfrage->bindValue(":zeit", time(), SQLITE3_INTEGER);
    $abfrage->bindValue(":inhalt", $_POST["inhalt"] ?? "", SQLITE3_TEXT);
    $abfrage->execute();
}

// Die Mitgliederliste nutzt dieselbe Rechte-Spalte wie die Zugriffskontrolle.
// Dadurch sieht ein Benutzer genau die Personen, die für diesen Raum freigeschaltet sind.
$mitglieder = $datenbank->query("SELECT name FROM rights WHERE is_$raum = 'true' ORDER BY name");
// Die Nachrichten werden nach ihrem Unix-Zeitstempel sortiert, damit ältere
// Nachrichten vor neueren Nachrichten erscheinen.
$nachrichten = $datenbank->query("SELECT name, zeit, inhalt FROM $nachrichtenTabelle ORDER BY zeit");
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="styles.css">
    <title>Chat <?= $raum ?></title>
</head>

<body>
    <div class="text-links">
        <a class="button-link text-weiß" href="intern.php">Zurück</a>
    </div>
    <div class="mitte">
        <div>
            <h1>Chat <?= $raum ?></h1>
            <!-- Dieser Hinweis erklärt Besuchern den Zweck des privaten Raums. -->
            <p>Dieser Chatroom ist privat. Nur freigeschaltete Mitglieder können Nachrichten lesen und schreiben.</p>
            <div class="chat2-mitglieder">
                 <!-- fetchArray() liefert pro Schleifendurchlauf den nächsten
                     freigeschalteten Benutzer aus dem SQLite-Ergebnis. -->
                <strong>Mitglieder von Chat <?= $raum ?></strong>
                <ul>
                    <?php while ($mitglied = $mitglieder->fetchArray(SQLITE3_ASSOC)): ?>
                        <li><?= htmlspecialchars($mitglied["name"], ENT_QUOTES, "UTF-8") ?></li>
                    <?php endwhile; ?>
                </ul>
            </div>
            <div id="nachrichten">
                 <!-- htmlspecialchars behandelt Namen und Nachrichten als Text.
                     Dadurch kann eingegebener HTML- oder JavaScript-Code nicht
                     als Teil der Seite ausgeführt werden. -->
                <?php while ($zeile = $nachrichten->fetchArray(SQLITE3_ASSOC)): ?>
                    <?= date("H:i:s", $zeile["zeit"]) ?>
                    <?= htmlspecialchars($zeile["name"], ENT_QUOTES, "UTF-8") ?>:
                    <?= htmlspecialchars($zeile["inhalt"], ENT_QUOTES, "UTF-8") ?><br>
                <?php endwhile; ?>
            </div>
              <!-- Beim Absenden wird die Seite per POST erneut geladen. Der PHP-Teil
                  oben speichert dann den Inhalt in der Tabelle dieses Raums. -->
            <form method="POST">
                <input id="eingabefeld" name="inhalt" required>
                <button class="button hintergrund-blau" type="submit">Senden</button>
            </form>
        </div>
    </div>
</body>

</html>
