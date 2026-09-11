<?php
session_start();

$datenbank = new SQLite3("datenbank.db");

$datenbank->exec("CREATE TABLE IF NOT EXISTS nutzer (name, passwort, notiz)");
$datenbank->exec("CREATE TABLE IF NOT EXISTS nachricht (name, zeit, narchicht)");