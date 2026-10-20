<?php
session_start();

$datenbank = new SQLite3("datenbank.db");

$datenbank->exec("CREATE TABLE IF NOT EXISTS nutzer (name, passwort, notiz)");
$datenbank->exec("CREATE TABLE IF NOT EXISTS nachrichten (name, zeit, inhalt)");
$datenbank->exec("CREATE TABLE IF NOT EXISTS narchichten1 (name, zeit, inhalt)");
$datenbank->exec("CREATE TABLE IF NOT EXISTS  rights(name, is_1, is_2, is_3, is_4, is_5, is_6, is_7, is_8, is_9, is_10)");


// Raum 1 und 2 verwenden die bereits vorhandenen Tabellen.
$datenbank->exec("CREATE TABLE IF NOT EXISTS nachrichten1 (name, zeit, inhalt)");
// Für die Räume 3 bis 10 wird jeweils eine eigene Nachrichtentabelle angelegt.
for ($raumNr = 2; $raumNr <= 10; $raumNr++) {
	$datenbank->exec("CREATE TABLE IF NOT EXISTS nachrichten$raumNr (name, zeit, inhalt)");
}
