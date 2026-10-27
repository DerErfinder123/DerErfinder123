<?php
session_start();

$datenbank = new SQLite3("datenbank.db");

$datenbank->exec("CREATE TABLE IF NOT EXISTS nutzer (name, passwort, notiz)");
$datenbank->exec("CREATE TABLE IF NOT EXISTS nachrichten (name, zeit, inhalt)");
$datenbank->exec("CREATE TABLE IF NOT EXISTS narchichten1 (name, zeit, inhalt)");
$datenbank->exec("CREATE TABLE IF NOT EXISTS  rights(name, is_1, is_2, is_3, is_4, is_5, is_6, is_7, is_8, is_9, is_10)");
$datenbank->exec("CREATE TABLE IF NOT EXISTS chat_rooms (id INTEGER PRIMARY KEY AUTOINCREMENT, title TEXT NOT NULL, created_by TEXT NOT NULL)");
$datenbank->exec("CREATE TABLE IF NOT EXISTS chat_room_users (room_id INTEGER NOT NULL, user_name TEXT NOT NULL, PRIMARY KEY (room_id, user_name))");
$datenbank->exec("CREATE TABLE IF NOT EXISTS chat_room_messages (id INTEGER PRIMARY KEY AUTOINCREMENT, room_id INTEGER NOT NULL, name TEXT NOT NULL, zeit INTEGER NOT NULL, inhalt TEXT NOT NULL)");


// Raum 1 und 2 verwenden die bereits vorhandenen Tabellen.
$datenbank->exec("CREATE TABLE IF NOT EXISTS nachrichten1 (name, zeit, inhalt)");
// Für die Räume 3 bis 10 wird jeweils eine eigene Nachrichtentabelle angelegt.
for ($raumNr = 2; $raumNr <= 10; $raumNr++) {
	$datenbank->exec("CREATE TABLE IF NOT EXISTS nachrichten$raumNr (name, zeit, inhalt)");
}
