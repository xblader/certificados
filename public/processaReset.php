<?php
// começar ou retomar uma sessão
session_start();

require_once("../lib/phpmailer/PHPMailerAutoload.php");
//require_once("../lib/phpmailer/SMTP.php");

// se vier um pedido para login
if (!empty($_POST)) {
 
 
 try  {
        
        require "../config.php";
        
        // receber o pedido de login com segurança
        $userid = (isset($_POST['username'])) ? $_POST['username'] : '';
        
        $connection = new PDO($dsn, $username, $password, $options);
        $sql = "SELECT U.ID, U.EMAIL, U.PASSWORD, U.USERNAME
		FROM USUARIO U 
		WHERE USERNAME = :usuario";
			
	$statement = $connection->prepare($sql);
        $statement->bindParam(':usuario', $userid, PDO::PARAM_STR);
        $statement->execute();
        
        $result = $statement->fetchAll();
        
        if ($result && $statement->rowCount() == 1) {         	
		# Inclui o arquivo class.phpmailer.php localizado na pasta phpmailer	
		
		# Inicia a classe PHPMailer
		$mail = new PHPMailer();
		
		# Define os dados do servidor e tipo de conexão
		$mail->isSMTP(); // Define que a mensagem será SMTP
		$mail->Host = "xxxxxxxxxx"; # Endereço do servidor SMTP
		//$mail->SMTPDebug=3; para logar caso ocorra algum erro
		//$mail->Debugoutput='error_log';
		$mail->Port = 465;
		$mail->SMTPAuth = true;
		$mail->SMTPSecure = "ssl";
		$mail->Username = "xxxxxxx"; # Usuário de e-mail
		$mail->Password = "xxxxxx"; // # Senha do usuário de e-mail
		
		# Define o remetente (você)
		$mail->From = "xxxxxxxxxx"; # Seu e-mail
		$mail->FromName = "xxxxxxxxxx"; // Seu nome
		
		# Define os destinatário(s)
		$mail->AddAddress($result[0]["EMAIL"]); # Os campos podem ser substituidos por variáveis
		//#$mail->AddAddress('webmaster@nomedoseudominio.com'); # Caso queira receber uma copia
		//#$mail->AddCC('ciclano@site.net', 'Ciclano'); # Copia
		//#$mail->AddBCC('fulano@dominio.com.br', 'Fulano da Silva'); # Cópia Oculta
		
		# Define os dados técnicos da Mensagem
		$mail->IsHTML(true); # Define que o e-mail será enviado como HTML
		$mail->CharSet = 'utf-8'; # Charset da mensagem (opcional)
		
		# Define a mensagem (Texto e Assunto)
		$mail->Subject = "Reenvio de senha"; # Assunto da mensagem
		$mail->Body = "Este é um e-mail automático, favor não responder. Sua senha no sistema é <b>".$result[0]["PASSWORD"]."</b>";
		//$mail->AltBody = "Este é o corpo da mensagem de teste, somente Texto! \r\n :)";
		
		# Define os anexos (opcional)
		#$mail->AddAttachment("c:/temp/documento.pdf", "documento.pdf"); # Insere um anexo
		
		# Envia o e-mail
		$enviado = $mail->Send();
		
		if($enviado){
		 $_SESSION["emailsenha"] = $result[0]["EMAIL"];
		 header("Location: confirmaReset.php");
		}else{
		echo  $mail->ErrorInfo;
		}
		
		# Limpa os destinatários e os anexos
		//$mail->ClearAllRecipients();
		//$mail->ClearAttachments();	
		
   		//header("Location: reset.php");
        }else { 
                echo "Usuário não localizado.";
        	//header("Location: login.php");
        } 
    } catch(PDOException $error) {
        echo $sql . "<br>" . $error->getMessage();
    }
	
}
?>