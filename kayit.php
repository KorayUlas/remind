<?php 

require 'view/static/header.php';

if (post('submit')) {

	$name = post('name');
	$surname = post('surname');
	$email = post('email');
	$password = post('password');
	$password_again = post('password_again');

	if (!$name) {
		$error  ='Adınızı girmediniz.';
	}
	elseif (!$surname) {
		$error  ='Soyadınızı girmediniz.';
	}
	elseif (!$email) {
		$error  ='Email adresinizi girmediniz.';
	}
	elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$error  ='Lütfen geçerli bir email adresi girin.';
	}
	elseif (!$password || !$password_again) {
		$error  ='Lütfen şifre girin.';
	}
	elseif ($password != $password_again) {
		$error  ='Girilen şifreler birbiriyle uyuşmuyor.';
	}
	else{
		$check = $db->query("SELECT * FROM users WHERE user_email = '{$email}'")->fetch(PDO::FETCH_ASSOC);

		if ($check) {
			$error  ='Bu e-posta adresini başka bir kullanıcı kullanıyor. Lütfen Başka bir e-posta ile kayıt olun.';
		}
		else{
			$query = $db->prepare("INSERT INTO users SET
				user_name = ?,
				user_surname = ?,
				user_email = ?,
				user_password = ?
				");
			$register = $query->execute(array(
				$name, $surname, $email, password_hash($password,PASSWORD_DEFAULT)
			));
			if ($register){
				$success = 'Kayıt işlemi başarılı giriş yapmak için yönlendiriliyorsunuz.';
				header('Refresh: 3; giris.php');
			}
		}
	}
}
?>
<div class="container">
	<form class="form-signin" method="POST" action="">
		<div class="text-center mb-4">
			<img class="mb-4" src="assets/register.png" alt="" width="72" height="72">
			<h1 class="h3 mb-3 font-weight-normal">Kayıt Ol</h1>
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
			<input type="text"  class="form-control" name="name" value="<?=post('name')?>" placeholder="Adınız" required  autofocus>
			<label>Adınız</label>
		</div>

		<div class="form-label-group">
			<input type="text"  class="form-control" name="surname" value="<?=post('surname')?>" placeholder="Soyadınız" required autofocus>
			<label>Soyadınız</label>
		</div>

		<div class="form-label-group">
			<input type="email"  class="form-control" name="email" value="<?=post('email')?>" placeholder="Email Adresi" required autofocus>
			<label>Email Adresi</label>
		</div>

		<div class="form-label-group">
			<input type="password"  class="form-control" name="password" placeholder="Şifre" required>
			<label>Şifre</label>
		</div>

		<div class="form-label-group">
			<input type="password"  class="form-control" name="password_again" placeholder="Şifre Tekrar" required>
			<label>Şifre Tekrar</label>
		</div>

		<div class="checkbox mb-3">
			<label>
				Zaten Hesabın Var mı? <a href="giris.php">Giriş Yap.</a>
			</label>
		</div>
		<button class="btn btn-lg btn-primary btn-block" name="submit" type="submit" value="1">Kayıt Ol</button>
		<p class="mt-5 mb-3 text-muted text-center">&copy; 2021</p>
	</form>
</div>
<?php require 'view/static/footer.php'; ?>