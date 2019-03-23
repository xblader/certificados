<?php
// começar ou retomar uma sessão
session_start();
 
require '../public/verificarAdmin.php';
// se vier um pedido para login
if (!empty($_POST)) {
 
 
 try  {
        
        require "../config.php";
        
        // receber o pedido de login com segurança
        $codigo = (isset($_POST['codigo'])) ? $_POST['codigo'] : '';
  	$usuario = (isset($_POST['username'])) ? $_POST['username'] : '';
  	$email = (isset($_POST['email'])) ? $_POST['email'] : '';
  	$empresa = (isset($_POST['empresa'])) ? $_POST['empresa'] : '';
  	$senha = (isset($_POST['senha'])) ? $_POST['senha'] : '';
  	$confirmasenha = (isset($_POST['confirmasenha'])) ? $_POST['confirmasenha'] : '';
  	
  	if($senha != $confirmasenha){
  		echo "Senha está diferente da confirmação.";
  		exit;
  	}
        
        $connection = new PDO($dsn, $username, $password, $options);
        
        if($codigo == ''){        	        	
        	$sql = "INSERT INTO USUARIO(USERNAME, EMAIL, PASSWORD, PERFIL_ID) VALUES (:username, :email, :password, 2)";
        	$statement = $connection->prepare($sql);
        	$statement->bindParam(':username', $usuario, PDO::PARAM_STR);
        	$statement->bindParam(':email', $email, PDO::PARAM_STR);
        	$statement->bindParam(':password', $senha, PDO::PARAM_STR);      
        	$statement->execute();  	
        	$codigo = $connection->lastInsertId();
        	
        	$sqlempresa = "INSERT INTO USUARIO_EMPRESA(EMPRESA_ID, USUARIO_ID) VALUES (:empresa, :usuario)";
        	$statementempresa = $connection->prepare($sqlempresa);
        	$statementempresa->bindParam(':empresa', $empresa, PDO::PARAM_STR);
        	$statementempresa->bindParam(':usuario', $codigo, PDO::PARAM_STR);
        	$statementempresa->execute();  
        	
        }else{        	
        	$sql = "UPDATE USUARIO SET USERNAME = :username, EMAIL = :email, PASSWORD = :password WHERE ID = :codigo";
        	$statement = $connection->prepare($sql);
        	$statement->bindParam(':codigo', $codigo, PDO::PARAM_STR);
        	$statement->bindParam(':username', $usuario, PDO::PARAM_STR);
        	$statement->bindParam(':email', $email, PDO::PARAM_STR);
        	$statement->bindParam(':password', $senha, PDO::PARAM_STR);
        	$statement->execute(); 
        	
        	$sqlempresa = "UPDATE USUARIO_EMPRESA SET EMPRESA_ID = :empresa WHERE USUARIO_ID = :usuario";
        	$statementempresa = $connection->prepare($sqlempresa);
        	$statementempresa->bindParam(':empresa', $empresa, PDO::PARAM_STR);
        	$statementempresa->bindParam(':usuario', $codigo, PDO::PARAM_STR);
        	$statementempresa->execute();
        } 
        
        header("Location: read.php");
        
    } catch(PDOException $error) {
        echo $sql . "<br>" . $error->getMessage();
    }
	
}
?>