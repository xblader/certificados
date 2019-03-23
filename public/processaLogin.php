<?php
// começar ou retomar uma sessão
session_start();
 
// se vier um pedido para login
if (!empty($_POST)) {
 
 
 try  {
        
        require "../config.php";
        
        // receber o pedido de login com segurança
        $userid = (isset($_POST['username'])) ? $_POST['username'] : '';
  	$senha = (isset($_POST['password'])) ? $_POST['password'] : '';
        
        $connection = new PDO($dsn, $username, $password, $options);
        $sql = "SELECT U.ID, U.USERNAME, P.NOME AS PERFIL, EM.EMPRESA_ID
		FROM USUARIO U 
		INNER JOIN PERFIL P 
		ON U.PERFIL_ID = P.ID
	        LEFT JOIN USUARIO_EMPRESA EM
	        ON U.ID = EM.USUARIO_ID
		WHERE USERNAME = :usuario AND PASSWORD = :senha";
			
	$statement = $connection->prepare($sql);
        $statement->bindParam(':usuario', $userid, PDO::PARAM_STR);
        $statement->bindParam(':senha', $senha, PDO::PARAM_STR);
        $statement->execute();
        
        $result = $statement->fetchAll();
        
        if ($result && $statement->rowCount() == 1) { 
        	$_SESSION['id'] = $result[0]['USERNAME'];
		$_SESSION['perfil'] = $result[0]['PERFIL'];
		$_SESSION['empresa'] = $result[0]['EMPRESA_ID'];
		header("Location: read.php");
        }else { 
        	header("Location: login.php");
        } 
    } catch(PDOException $error) {
        echo $sql . "<br>" . $error->getMessage();
    }
	
}
?>