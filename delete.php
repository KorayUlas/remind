<?php
require 'view/static/header.php'; 

$id = get('id');

if (!$id) {
	header('Location: profile.php');
	exit();
}

$row = $db->query("SELECT * FROM reminder WHERE user_id = '{$_SESSION['user_id']}' AND remind_id = '{$id}'")->fetch(PDO::FETCH_ASSOC);
if (!$row){
	header('Location: profile.php');
	exit();
}

$query = $db->prepare("DELETE FROM reminder WHERE user_id = :user_id AND remind_id= :remind_id");
$delete = $query->execute(array('user_id' => $_SESSION['user_id'], 'remind_id' => $id));

if ($delete) {
	header('Location: profile.php');
}
else{
	print_r($query->errorInfo());
}