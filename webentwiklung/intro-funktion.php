<?php
$datenbank = new SQLite3("datenbank.db");
$datenbank->exec("CREATE TABLE IF NOT EXISTS nutzer (name, passwort)");

?>