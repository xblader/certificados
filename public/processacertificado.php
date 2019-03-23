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
  	$emissao = (isset($_POST['emissao'])) ? $_POST['emissao'] : '';
  	$validade = (isset($_POST['validade'])) ? $_POST['validade'] : '';
  	$empregado = (isset($_POST['empregado'])) ? $_POST['empregado'] : '';
  	$temanexo = (isset($_POST['anexo'])) ? $_POST['anexo'] : '';
  	$curso = (isset($_POST['curso'])) ? $_POST['curso'] : '';       
        
        $connection = new PDO($dsn, $username, $password, $options);
        
        if($codigo == ''){        	        	
        	$sql = "INSERT INTO PESSOA_CURSO(CURSO_ID, PESSOA_ID, DATA_EMISSAO, DATA_VALIDADE) VALUES (:curso, :empregado, :emissao, :validade)";
        	$statement = $connection->prepare($sql);
        	$statement->bindParam(':curso', $curso, PDO::PARAM_STR);
        	$statement->bindParam(':empregado', $empregado, PDO::PARAM_STR);
        	$statement->bindParam(':emissao', $emissao, PDO::PARAM_STR);
        	$statement->bindParam(':validade', $validade, PDO::PARAM_STR);
        	$statement->execute();
        	$codigo = $connection->lastInsertId();
        }else{        	
        	$sql = "UPDATE PESSOA_CURSO SET CURSO_ID = :curso, PESSOA_ID = :empregado, DATA_EMISSAO = :emissao, DATA_VALIDADE = :validade WHERE ID = :codigo";
        	$statement = $connection->prepare($sql);
        	$statement->bindParam(':codigo', $codigo, PDO::PARAM_STR);
        	$statement->bindParam(':curso', $curso, PDO::PARAM_STR);
        	$statement->bindParam(':empregado', $empregado, PDO::PARAM_STR);
        	$statement->bindParam(':emissao', $emissao, PDO::PARAM_STR);
        	$statement->bindParam(':validade', $validade, PDO::PARAM_STR);
        	$statement->execute();
        }               
        
        if($temanexo != ''){
        
	        // Pasta onde o arquivo vai ser salvo
	        $_UP['pasta'] = '../uploads/' . $codigo . '/';
	        
	        if (!file_exists($_UP['pasta'])) {
		    mkdir($_UP['pasta'], 0777, true);
		}else{
		    $files = glob($_UP['pasta'] . '*'); // get all file names
		    foreach($files as $file){ // iterate files
		  	if(is_file($file))
		    	unlink($file); // delete file
		    }
		}
	        
		// Tamanho máximo do arquivo (em Bytes)
		$_UP['tamanho'] = 1024 * 1024 * 2; // 2Mb
		// Array com as extensões permitidas
		$_UP['extensoes'] = array('jpg', 'png', 'gif');
		// Renomeia o arquivo? (Se true, o arquivo será salvo como .jpg e um nome único)
		$_UP['renomeia'] = false;
		// Array com os tipos de erros de upload do PHP
		$_UP['erros'][0] = 'Não houve erro';
		$_UP['erros'][1] = 'O arquivo no upload é maior do que o limite do PHP';
		$_UP['erros'][2] = 'O arquivo ultrapassa o limite de tamanho especifiado no HTML';
		$_UP['erros'][3] = 'O upload do arquivo foi feito parcialmente';
		$_UP['erros'][4] = 'Não foi feito o upload do arquivo';
		// Verifica se houve algum erro com o upload. Se sim, exibe a mensagem do erro
		if ($_FILES['arquivo']['error'] != 0) {
		  die("Não foi possível fazer o upload, erro:" . $_UP['erros'][$_FILES['arquivo']['error']]);
		  exit; // Para a execução do script
		}
		
		// Faz a verificação do tamanho do arquivo
		if ($_UP['tamanho'] < $_FILES['arquivo']['size']) {
		  echo "O arquivo enviado é muito grande, envie arquivos de até 2Mb.";
		  exit;
		}
		
		$nome_final = $_FILES['arquivo']['name'];
	        
	        // Depois verifica se é possível mover o arquivo para a pasta escolhida
		if (move_uploaded_file($_FILES['arquivo']['tmp_name'], $_UP['pasta'] . $nome_final)) {		
		  	$sql1 = "UPDATE PESSOA_CURSO C SET C.TEM_ANEXO = 'S' WHERE C.ID = :codigo";
	        	$statement1 = $connection->prepare($sql1);
	        	$statement1->bindParam(':codigo', $codigo, PDO::PARAM_STR);
	        	$statement1->execute();
		} else {
		  // Não foi possível fazer o upload, provavelmente a pasta está incorreta
		  echo "Não foi possível enviar o arquivo, tente novamente";		
		}		
	
	}
        
        header("Location: read.php");
        
    } catch(PDOException $error) {
        echo $sql . "<br>" . $error->getMessage();
    }
	
}
?>