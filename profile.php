<?php
require 'view/static/header.php';

if (empty($_SESSION['user_email'])) {
	header('Location: giris.php');
	exit();
}
?> 
<div class="container-fluid">
	<div class="row">
		<?php require 'view/profile.php'; ?>
		<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
			<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
				<h1 class="h2">Hatırlatıcılarım</h1>
			</div>
			<div class="table-responsive">
				<table class="table table-striped table-sm">
					<thead>
						<tr>
							<th>Hatırlatıcı Adı</th>
							<th>Hatırlatıcı Mesajı</th>
							<th>Hatırlatma Tarihi</th>
							<th>Hatırlatma Saati</th>
							<th>Durum</th>
							<th>İşlem</th>
						</tr>
					</thead>
					<tbody>
						<?php 
						$query = $db->query("SELECT * FROM reminder WHERE user_id = '{$_SESSION['user_id']}' ORDER BY remind_id DESC", PDO::FETCH_ASSOC);
						if ( $query->rowCount() ){
							foreach( $query as $row ){

								?>
								<tr>
									<td><?=$row['remind_name']?></td>
									<td><?=$row['remind_message']?></td>
									<td><?=$row['remind_date']?></td>
									<td><?=$row['remind_time']?></td>
									<td><?php if ($row['remind_state'] == 2) {
										echo "Bekleniyor...";
									}
									elseif ($row['remind_state'] == 1) {
										echo "Hatırlatma Yapıldı.";
									}?></td>
									<td>
										<a class="nav-link" href="edit-remind.php?id=<?=$row['remind_id']?>">
											<span data-feather="edit"></span>
											Güncelle
										</a>
										<a onclick="return confirm('Silmek istediğinden emin misin?')" class="nav-link" href="delete.php?id=<?=$row['remind_id']?>">
											<span data-feather="trash-2"></span>
											Sil
										</a></td>
									</tr>
									<?php
								}
							}
							else{
								$error = '<p><center>Hiçbir Hatırlatıcı Eklemediniz</center></p>';
							}
							?>
						</tbody>
					</table>
					<?php if (isset($error)): ?>
							<?=$error?>
						<?php endif ?>
				</div>
				<center><img style="margin-top: 339px;" class="img-responsive" height="300" src="assets/remind-walktrough.png"></center>
			</main>
		</div>
	</div>
	<?php require 'view/static/footer.php';  ?>