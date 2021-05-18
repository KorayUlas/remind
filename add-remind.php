<?php 

require 'view/static/header.php'; 

if (!isset($_SESSION['user_email'])) {
	header('Location: giris.php');
}

if (post('submit')) {

	$remind_name = post('remind_name');
	$remind_date = post('remind_date');
	$remind_time = post('remind_time');
	$remind_message = post('remind_message');
	$date_str = strtotime($remind_date . $remind_time );

	if (!$remind_name) {
		$error = 'Hatırlatıcı adı girmelisiniz.';
	}
	elseif (!$remind_date) {
		$error = 'Hatırlatıcı tarihi girmelisiniz.';
	}
	elseif (!$remind_time) {
		$error = 'Hatırlatıcı saati girmelisiniz.';
	}
	elseif (!$remind_message) {
		$error = 'Hatırlatıcı mesajı girmelisiniz.';
	}
	else{

		$query = $db->prepare("INSERT INTO reminder SET
			user_id = ?,
			remind_name = ?,
			remind_date = ?,
			remind_time = ?,
			remind_message = ?,
			date_str = ?
			");
		$add_remind = $query->execute(array(
			$_SESSION['user_id'], $remind_name, $remind_date,$remind_time,$remind_message,$date_str
		));
		if ($add_remind){
			$success = 'Hatırlatıcı ekleme başarılı';
			header('Refresh: 2; profile.php?=success');
		}
		else{
			$error = 'Hatırlatıcı ekleme başarısız. Tekrar deneyin';
			header('Refresh: 2; profile.php?=error');
		}
	}
}
?>

<div class="container-fluid">
	<div class="row">
		<?php require 'view/profile.php'; ?>

		<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
			<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
				<h1 class="h2">Yeni Ekle</h1>
			</div>
			<?php if (isset($error)): ?>
				<div class="alert alert-danger" role="alert">
					<?=$error?>
				</div>
			<?php endif ?>
			<?php if (isset($success)): ?>
				<div class="alert alert-success" role="alert">
					<?=$success?>
				</div>
			<?php endif ?>
			<form method="POST" action="">
				<div class="form-group">
					<label>Hatırlatıcı Adı</label>
					<input type="text" class="form-control" name="remind_name" value="<?=post('remind_name')?>" placeholder="Hatırlatıcı Adı" required>
					<small  class="form-text text-muted">Hatırlatıcınıza Bir isim verin</small>
				</div>

				<div class="form-group">
					<label>Tarih</label>
					<input type="date" name="remind_date" value="<?=post('remind_date')?>" class="form-control" required>
				</div>

				<div class="form-group">
					<label>Tarih</label>
					<input type="time" name="remind_time" value="<?=post('remind_time')?>" class="form-control" required>
				</div>

			
				<div class="form-group">
					<label>Hatırlatıcı Mesajı</label>
					<textarea type="text" class="form-control" rows="5" name="remind_message" placeholder="Hatırlatıcı Mesajı" required><?=post('remind_message')?></textarea>
					<small  class="form-text text-muted">Hatırlatılacak Bilgi</small>
				</div>
				<button type="submit" name="submit" value="1" class="btn btn-primary">Kaydet</button>
			</form>


			<center><img style="margin-top: 25px;" class="img-responsive" height="300" src="assets/add-remind.png"></center>
		</main>
	</div>
</div>








<?php require 'view/static/footer.php'; ?>