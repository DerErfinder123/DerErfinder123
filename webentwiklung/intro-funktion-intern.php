<?php
// Gemeinsamer Schutz für alle Seiten, die nur eingeloggte Benutzer sehen dürfen.
if (!isset($_SESSION["name"])) {
    header("Location: index.php");
    exit;
}