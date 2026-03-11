<?php
	$title = 'Schleswig-Holstein Community - Start';
	$title_team = '[SHC] Das Team';
	// Fehler anzeigen (nur für Entwicklung!)
	ini_set('display_errors', 1);
	error_reporting(E_ALL);

	// Erlaubte Seiten (Whitelist = wichtig für Sicherheit!)
	$allowedPages = [
		'start',
		'about',
		'kontakt',
		'datenschutz',
		'imprint',
		'gserver',
		'faq',
		'team_page'
	];

	// Standardseite
	$site = 'start';

	// Prüfen ob Parameter existiert
	if (isset($_GET['site']) && in_array($_GET['site'], $allowedPages)) {
		$site = $_GET['site'];
	}

	// Datei-Pfad
	$pageFile = "includes/" . $site . ".php";

	// HTML Grundstruktur
?>