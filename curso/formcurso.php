<?php
// pagina protegida, incluir script de verificação de login
require '../public/verificarLogin.php';
require '../public/verificarAdmin.php';
?>

<?php include '../header.php'; ?>
<div class="container">
<?php 
 try  {
        
        require "../config.php";
        
        $connection = new PDO($dsn, $username, $password, $options);
        
        $novo = $_GET["id"];
        $form = "SELECT C.ID, C.DESCRICAO FROM CURSO C WHERE C.ID = :codigo";
        
        $statementform = $connection->prepare($form);
        $statementform->bindParam(':codigo', $novo, PDO::PARAM_STR);
        $statementform->execute();
        $resultform = $statementform->fetchAll();
        
    } catch(PDOException $error) {
        echo $sql . "<br>" . $error->getMessage();
    }
 ?>
 
<?php 
if ($resultform && $statementform->rowCount() > 0) { 
    $nomeform = $resultform[0]['DESCRICAO'];
}else{
    $nomeform = '';
}

?>
<h2><?php if($novo == '') { echo 'Novo '; } else {echo 'Altera '; } ?>Curso</h2>
<form action="processacurso.php" method="POST">
  <input type="hidden" id="codigo" name="codigo" value="<?php echo $novo ?>">
  <div class="form-group">
    <label for="exampleInputEmail1">Descrição</label>
    <input type="input" class="form-control" id="descricao" name="descricao" maxlength="100" placeholder="Entre com a descrição" value="<?php echo $nomeform ?>">
    <small id="emailHelp" class="form-text text-muted"></small>
  </div>
  <button type="submit" class="btn btn-primary">Salvar</button>
  <button type="button" class="btn btn-primary" onclick="location.href='./read.php'";>Cancelar</button>
</form>
  
</div>
</body>
</html>
