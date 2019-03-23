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
  	$nome = (isset($_POST['nome'])) ? $_POST['nome'] : '';
  	$cpf = (isset($_POST['cpf'])) ? $_POST['cpf'] : '';
  	$empresa = (isset($_POST['empresa'])) ? $_POST['empresa'] : '';
        
        $connection = new PDO($dsn, $username, $password, $options);
        
        if($codigo == ''){        	        	
        	$sql = "INSERT INTO PESSOA(NOME, CPF, EMPRESA_ID) VALUES (:nome, :cpf, :empresa)";
        	$statement = $connection->prepare($sql);
        	$statement->bindParam(':nome', $nome, PDO::PARAM_STR);
        	$statement->bindParam(':cpf', $cpf, PDO::PARAM_STR);
        	$statement->bindParam(':empresa', $empresa, PDO::PARAM_STR);
        }else{        	
        	$sql = "UPDATE PESSOA SET NOME = :nome, CPF = :cpf, EMPRESA_ID = :empresa WHERE ID = :codigo";
        	$statement = $connection->prepare($sql);
        	$statement->bindParam(':codigo', $codigo, PDO::PARAM_STR);
        	$statement->bindParam(':nome', $nome, PDO::PARAM_STR);
        	$statement->bindParam(':cpf', $cpf, PDO::PARAM_STR);
        	$statement->bindParam(':empresa', $empresa, PDO::PARAM_STR);
        }        
        
        
        $statement->execute();
        
        header("Location: read.php");
        
    } catch(PDOException $error) {
        echo $sql . "<br>" . $error->getMessage();
    }
	
}
?>