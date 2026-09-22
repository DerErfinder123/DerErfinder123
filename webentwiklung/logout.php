<?php
// Beendet die Session und schickt den Benutzer zurück zur Startseite.
session_start();
session_destroy();
header("Location: index.php");