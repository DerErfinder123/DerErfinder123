<?php
session_start();

$datenbank = new SQLite3("datenbank.db");

$datenbank->exec("CREATE TABLE IF NOT EXISTS nutzer (name, passwort, notiz)");
$datenbank->exec("CREATE TABLE IF NOT EXISTS nachrichten (name, zeit, inhalt)");
$datenbank->exec("CREATE TABLE IF NOT EXISTS narchichten2 (name, zeit, inhalt)");
$datenbank->exec("CREATE TABLE IF NOT EXISTS  rights(name, is_2)");
