<?php
session_start();

$datenbank = new SQLite3("datenbank.db");

$datenbank->exec("CREATE TABLE IF NOT EXISTS nutzer (name, passwort, notiz)");
$datenbank->exec("CREATE TABLE IF NOT EXISTS nachrichten (name, zeit, inhalt)");
$datenbank->exec("CREATE TABLE IF NOT EXISTS narchichten2 (name, zeit, inhalt)");
$datenbank->exec("CREATE TABLE IF NOT EXISTS  rights(name, is_2)");

// Ergänzt bei bestehenden Datenbanken die Rechte für die Räume 1 bis 10.
for ($raum = 1; $raum <= 10; $raum++) {
	$spalte = "is_" . $raum;
	$vorhanden = $datenbank->querySingle("SELECT COUNT(*) FROM pragma_table_info('rights') WHERE name = '$spalte'");
	if (!$vorhanden) {
		$datenbank->exec("ALTER TABLE rights ADD COLUMN $spalte TEXT NOT NULL DEFAULT 'false'");
	}
}

// Raum 1 und 2 verwenden die bereits vorhandenen Tabellen.
$datenbank->exec("CREATE TABLE IF NOT EXISTS nachrichten1 (name, zeit, inhalt)");
// Für die Räume 3 bis 10 wird jeweils eine eigene Nachrichtentabelle angelegt.
for ($raum = 3; $raum <= 10; $raum++) {
	$datenbank->exec("CREATE TABLE IF NOT EXISTS nachrichten$raum (name, zeit, inhalt)");
}
