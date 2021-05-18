<?php 

require 'view/static/header.php';

if (isset($_SESSION['user_name'])) {
	header('Location: profil.php');
	exit();
}

if (post('submit')) {

	$email = post('email');
	$password = post('password');

	if (!$email) {
		$error  ='Email adresinizi girmediniz.';
	}
	elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$error  ='Lütfen geçerli bir email adresi girin.';
	}
	elseif (!$password) {
		$error  ='Şifrenizi girmediniz';
	}
	else{
		$row = $db->query("SELECT * FROM users WHERE user_email = '{$email}'")->fetch(PDO::FETCH_ASSOC);
		if ($row) {
			$password_verify= password_verify($password, $row['user_password']);
			if ($password_verify) {
				$success = 'Giriş Başarılı. Yönlendiriliyorsunuz';
				$_SESSION['user_name'] = $row['user_name'];
				$_SESSION['user_surname'] = $row['user_surname'];
				$_SESSION['user_email'] = $row['user_email'];
				$_SESSION['user_date'] = $row['user_date'];
				$_SESSION['user_id'] = $row['user_id'];
				header('Refresh: 3; profile.php');
			}
			else{
				$error = 'Şifreniz Yanlış.';
			}
		}
		else{
			$error = 'Böyle bir kullanıcı bulunamadı.';
		}
	}
}
?>
<div class="container">
	<form class="form-signin" method="POST" action="">
		<div class="text-center mb-4">
			<img class="mb-4" src="assets/login.png" alt="" width="72" height="72">
			<h1 class="h3 mb-3 font-weight-normal">Giriş Yap</h1>
			<p>Hatırlatıcı hizmetinden faydalanabilmek için sisteme kayıt ol ve sonrasında hatırlatıcı oluşturmak için profile girip bir hatırlatıcı oluştur </p>
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
		</div>

		<div class="form-label-group">
			<input type="email"  class="form-control" name="email" placeholder="Email Adresi" required autofocus>
			<label for="inputEmail">Email Adresi</label>
		</div>

		<div class="form-label-group">
			<input type="password"  class="form-control" name="password" placeholder="Şifre" required>
			<label for="inputPassword">Şifre</label>
		</div>

		<div class="checkbox mb-3">
			<label>
				Bir hesabın yok mu? <a href="kayit.php">Kayıt Ol.</a>
			</label>
		</div>


		<button class="btn btn-lg btn-primary btn-block" name="submit" value="1" type="submit">Giriş Yap</button>
		<p class="mt-5 mb-3 text-muted text-center">&copy; 2021</p>
	</form>
</div>
<?php require 'view/static/footer.php'; ?>