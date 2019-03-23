<?php
// pagina protegida, incluir script de verificação de login
require 'verificarLogin.php';
?>
<?php
 
session_start();
ignore_user_abort(true);
set_time_limit(0); // disable the time limit for this script

$codigo = (isset($_GET['id'])) ? $_GET['id'] : '';


    try  {
        
        require "../config.php";
        
        $connection = new PDO($dsn, $username, $password, $options);
        $sql = "SELECT COUNT(*) AS TOTAL
		FROM PESSOA_CURSO CR
		INNER JOIN PESSOA P
		ON CR.PESSOA_ID = P.ID
		WHERE CR.ID = :codigo";
			
	if($_SESSION['perfil'] != 'ADMIN'){
	  $sql = $sql." AND P.EMPRESA_ID = :empresa";
	  $empresa = $_SESSION['empresa'];	  
	}
			
	$statement = $connection->prepare($sql); 
	$statement->bindParam(':codigo', $codigo, PDO::PARAM_STR);   
	
	if($_SESSION['perfil'] != 'ADMIN'){
		$statement->bindParam(':empresa', $empresa , PDO::PARAM_STR);  
	}     
        $statement->execute();        
        $result = $statement->fetchAll();
        if($result[0]["TOTAL"] == 0){
        	header("HTTP/1.1 401 Unauthorized");
    		exit;
        }
    } catch(PDOException $error) {
        echo $sql . "<br>" . $error->getMessage();
        exit;
    }


// Pasta onde o arquivo vai ser salvo
$path = '../uploads/' . $codigo . '/';
 
 if (!file_exists($path)) {
 	echo "nao existe";
 	exit;
 }else{
	$files = glob($path . '*'); 
        $fullPath = $files[0];
 
	if ($fd = fopen ($fullPath, "r")) {
	    $fsize = filesize($fullPath);
	    $path_parts = pathinfo($fullPath);
	    $ext = strtolower($path_parts["extension"]);
	    switch ($ext) {
	        case "pdf":
	        header("Content-type: application/pdf");
	        header("Content-Disposition: attachment; filename=\"".$path_parts["basename"]."\""); // use 'attachment' to force a file download
	        break;
	        // add more headers for other content types here
	        default;
	        header("Content-type: application/octet-stream");
	        header("Content-Disposition: filename=\"".$path_parts["basename"]."\"");
	        break;
	    }
	    header("Content-length: $fsize");
	    header("Cache-control: private"); //use this to open files directly
	    while(!feof($fd)) {
	        $buffer = fread($fd, 2048);
	        echo $buffer;
	    }
	}
}
?>