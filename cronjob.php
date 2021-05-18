<?php

try {
	$db = new PDO("mysql:host=localhost;dbname=remind;charset=utf8", "eminkurt", "19942151381EminKurt?");
} catch ( PDOException $e ){
	print $e->getMessage();
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/mailer/Exception.php';
require __DIR__ . '/mailer/PHPMailer.php';
require __DIR__ . '/mailer/SMTP.php';

date_default_timezone_set('Europe/Istanbul');

$time = time();
$query = $db->query("
	SELECT * FROM reminder
	LEFT JOIN users 
	ON reminder.user_id = users.user_id 
	WHERE reminder.remind_state = '2'
	ORDER BY reminder.remind_id", PDO::FETCH_ASSOC);

if ( $query->rowCount() > 0 ){
	foreach( $query as $row ){

		if (date("Y-m-d H:i:s", $time) >= gmdate("Y-m-d H:i:s", $row['date_str']) ) {

			try {
				$mail = new PHPMailer(true);
				$mail->isSMTP();                                           
				$mail->Host       = 'smtp.gmail.com';                    
				$mail->SMTPAuth   = true;                                   
				$mail->Username   = 'emailgonderim@gmail.com';                     
				$mail->Password   = '19942151381mm';                              
				$mail->SMTPSecure = 'TLS';         
				$mail->Port       = 587;                      
				$mail->CharSet = 'utf-8';
				$mail->setFrom('emailgonderim@gmail.com', $row['remind_name']);
				$mail->addAddress($row['user_email'], $row['user_name'] . $row['user_surname']);    
				$mail->addReplyTo('emailgonderim@gmail.com', 'Cevap');
				$mail->isHTML(true);                                  
				$mail->Subject = $row['remind_name'];
				$mail->Body    = $row['remind_message'];

				if ($mail->send()) {
					$query = $db->prepare("UPDATE reminder SET
						remind_state = :remind_state
						WHERE remind_id = :remind_id");
					$update = $query->execute(array(
						"remind_state" => 1,
						"remind_id" => $row['remind_id']
					));

					echo 'Hatırlatma E-maili Gönderildi';
				}
			} catch (Exception $e) {
				echo "Bir hata oluştu. Mailer Hatası: {$mail->ErrorInfo}";
			}
		}
	}
}