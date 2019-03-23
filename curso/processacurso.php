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
  	$descricao = (isset($_POST['descricao'])) ? $_POST['descricao'] : '';
        
        $connection = new PDO($dsn, $username, $password, $options);
        
        if($codigo == ''){        	        	
        	$sql = "INSERT INTO CURSO(DESCRICAO) VALUES (:descricao)";
        	$statement = $connection->prepare($sql);
        	$statement->bindParam(':descricao', $descricao, PDO::PARAM_STR);
        }else{        	
        	$sql = "UPDATE CURSO SET DESCRICAO = :descricao WHERE ID = :codigo";
        	$statement = $connection->prepare($sql);
        	$statement->bindParam(':codigo', $codigo, PDO::PARAM_STR);
        	$statement->bindParam(':descricao', $descricao, PDO::PARAM_STR);
        }        
        
        
        $statement->execute();
        
        header("Location: read.php");
        
    } catch(PDOException $error) {
        echo $sql . "<br>" . $error->getMessage();
    }
	
}
?>