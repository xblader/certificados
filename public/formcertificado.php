<?php
// pagina protegida, incluir script de verificação de login
require '../public/verificarLogin.php';
require '../public/verificarAdmin.php';

?>
<style>
fieldset.scheduler-border {
    border: 1px groove #ddd !important;
    padding: 0 1.4em 1.4em 1.4em !important;
    margin: 0 0 1.5em 0 !important;
    -webkit-box-shadow:  0px 0px 0px 0px #000;
            box-shadow:  0px 0px 0px 0px #000;
}

    legend.scheduler-border {
        font-size: 1.2em !important;
        font-weight: bold !important;
        text-align: left !important;
        width:auto;
        padding:0 10px;
        border-bottom:none;
    }
</style>
<?php include '../header.php'; ?>
<?php

    try  {
        
        require "../config.php";
        
        $connection = new PDO($dsn, $username, $password, $options);
        
        $sql = "SELECT C.ID, C.DESCRICAO FROM CURSO C";			
	$statement = $connection->prepare($sql); 	     
        $statement->execute();        
        $result = $statement->fetchAll();
        
        $sqlcombopessoa = "SELECT P.ID, P.NOME, E.NOME_FANTASIA FROM PESSOA P INNER JOIN EMPRESA E ON P.EMPRESA_ID = E.ID ORDER BY E.NOME_FANTASIA, P.NOME";			
	$statementcombopessoa = $connection->prepare($sqlcombopessoa); 	     
        $statementcombopessoa->execute();        
        $resultcombopessoa = $statementcombopessoa->fetchAll();
        
        $novo = $_GET["id"];
        $form = "SELECT C.CURSO_ID, C.PESSOA_ID, C.DATA_EMISSAO, C.DATA_VALIDADE FROM PESSOA_CURSO C WHERE C.ID = :codigo";
        
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
    $cursoform = $resultform[0]['CURSO_ID'];
    $pessoaform = $resultform[0]['PESSOA_ID'];
    $dataemissaoform = $resultform[0]['DATA_EMISSAO'];
    $datavalidadeform = $resultform[0]['DATA_VALIDADE'];
}else{
    $cursoform = '';
    $pessoaform = '';
    $dataemissaoform = '';
    $datavalidadeform = '';
}

?>
<h2><?php if($novo == '') { echo 'Novo '; } else {echo 'Altera '; } ?>Certificado</h2>
<form action="processacertificado.php" method="POST" enctype="multipart/form-data">
  <input type="hidden" id="codigo" name="codigo" value="<?php echo $novo ?>">
  <div class="form-group">
    <label for="exampleInputEmail1">Data de Emissão</label>
    <input type="date" class="form-control" id="emissao" name="emissao" maxlength="8" placeholder="Entre com a data de emissão" value="<?php echo $dataemissaoform ?>">
  </div>
  <div class="form-group">
    <label for="exampleInputEmail1">Data de Validade</label>
    <input type="date" class="form-control" id="validade" name="validade" maxlength="8" placeholder="Entre com a data de validade" value="<?php echo $datavalidadeform ?>">
  </div>
  <div class="form-group">
    <label for="exampleInputEmail1">Empregado</label>
    <select id="empregado" name="empregado" class="custom-select">
    <?php
     if ($resultcombopessoa && $statementcombopessoa->rowCount() > 0) { 
     	 foreach ($resultcombopessoa as $row) { 
     	   if($row['ID'] == $pessoaform) { ?>
     	     <option selected='selected' value="<?php echo $row["ID"]; ?>"><?php echo $row["NOME"]; ?> - <?php echo $row["NOME_FANTASIA"]; ?></option>
     	   <?php } else { ?>
     	     <option value="<?php echo $row["ID"]; ?>"><?php echo $row["NOME"]; ?> - <?php echo $row["NOME_FANTASIA"]; ?></option>
           <?php } ?>
     <?php } } ?>
    </select>
  </div>
  <div class="form-group">
    <label for="exampleInputEmail1">Curso</label>
    <select id="curso" name="curso" class="custom-select">
    <?php
     if ($result && $statement->rowCount() > 0) { 
     	 foreach ($result as $row) { 
     	   if($row['ID'] == $cursoform) { ?>
     	     <option selected='selected' value="<?php echo $row["ID"]; ?>"><?php echo $row["DESCRICAO"]; ?></option>
     	   <?php } else { ?>
     	     <option value="<?php echo $row["ID"]; ?>"><?php echo $row["DESCRICAO"]; ?></option>
           <?php } ?>
     <?php } } ?>
    </select>
  </div>
  <div class="form-group">
  <fieldset class="scheduler-border">
    <legend class="scheduler-border">Anexar Arquivo</legend>    
      <div class="custom-control custom-checkbox">
    <input type="checkbox" class="custom-control-input" id="anexo" name="anexo">
    <label class="custom-control-label" for="anexo">Anexar Certificado?</label>
   </div>
   <input type="file" name="arquivo" />
   </fieldset>
  
  </div>
  <button type="submit" class="btn btn-primary">Salvar</button>
  <button type="button" class="btn btn-primary" onclick="location.href='./read.php'";>Cancelar</button>
</form>
  
</div>
</body>
</html>
