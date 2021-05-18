<?php

session_start();
ob_start();

require 'functions.php';

try {
	$db = new PDO("mysql:host=localhost;dbname=remind;charset=utf8", "eminkurt", "19942151381EminKurt?");
} catch ( PDOException $e ){
	print $e->getMessage();
}

