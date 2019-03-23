<?php
// pagina protegida, incluir script de verificação de login
require '../public/verificarLogin.php';
require '../public/verificarAdmin.php';
?>

<?php include '../header.php'; ?>
<?php

    try  {
        
        require "../config.php";
        
        $connection = new PDO($dsn, $username, $password, $options);
        $sql = "SELECT E.ID, E.NOME_FANTASIA FROM EMPRESA E";
			
	$statement = $connection->prepare($sql); 	     
        $statement->execute();
        
        $result = $statement->fetchAll();
        
        $novo = $_GET["id"];
        $form = "SELECT C.ID, C.NOME, C.CPF, C.EMPRESA_ID AS EMPRESA FROM PESSOA C WHERE ID = :codigo";
        
        $statementform = $connection->prepare($form);
        $statementform->bindParam(':codigo', $novo, PDO::PARAM_STR);
        $statementform->execute();
        $resultform = $statementform->fetchAll();
        
    } catch(PDOException $error) {
        echo $sql . "<br>" . $error->getMessage();
    }

?> 
<div class="container">
<?php 
if ($resultform && $statementform->rowCount() > 0) { 
    $nomeform = $resultform[0]['NOME'];
    $cpfform = $resultform[0]['CPF'];
    $empresaform = $resultform[0]['EMPRESA'];
}else{
    $nomeform = '';
    $cpfform = '';
    $empresaform = '';
}

?>
<h2><?php if($novo == '') { echo 'Novo '; } else {echo 'Altera '; } ?>Empregado</h2>
<form action="processapessoa.php" method="POST">
  <input type="hidden" id="codigo" name="codigo" value="<?php echo $novo ?>">
  <div class="form-group">
    <label for="exampleInputEmail1">Nome</label>
    <input type="input" class="form-control" id="nome" name="nome" aria-describedby="descricao" maxlength="100" placeholder="Entre com o nome" value="<?php echo $nomeform ?>">
  </div>
  <div class="form-group">
    <label for="exampleInputEmail1">CPF</label>
    <input type="input" class="form-control" id="cpf" name="cpf" aria-describedby="cpf" maxlength="11" placeholder="Entre com o CPF" value="<?php echo $cpfform ?>">
  </div>
  <div class="form-group">
    <label for="exampleInputEmail1">Empresa</label>
    <select id="empresa" name="empresa" class="custom-select">
    <?php
     if ($result && $statement->rowCount() > 0) { 
     	 foreach ($result as $row) { 
     	   if($row['ID'] == $empresaform) { ?>
     	     <option selected='selected' value="<?php echo $row["ID"]; ?>"><?php echo $row["NOME_FANTASIA"]; ?></option>
     	   <?php } else { ?>
     	     <option value="<?php echo $row["ID"]; ?>"><?php echo $row["NOME_FANTASIA"]; ?></option>
           <?php } ?>
     <?php } } ?>
    </select>
  </div>
  <button type="submit" class="btn btn-primary">Salvar</button>
  <button type="button" class="btn btn-primary" onclick="location.href='./read.php'";>Cancelar</button>
</form>
  
</div>
</body>
</html>
