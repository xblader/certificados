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
        $form = "SELECT C.ID, C.USERNAME, C.EMAIL, C.PASSWORD, U.EMPRESA_ID AS EMPRESA
        FROM USUARIO C 
        LEFT JOIN USUARIO_EMPRESA U 
        ON U.USUARIO_ID = C.ID         
        WHERE C.ID = :codigo";
        
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
    $usernameform = $resultform[0]['USERNAME'];
    $emailform = $resultform[0]['EMAIL'];
    $senhaform = $resultform[0]['PASSWORD'];
    $empresaform = $resultform[0]['EMPRESA'];
}else{
    $usernameform = '';
    $emailform = '';
    $senhaform = '';
    $empresaform = '';
}

?>
<h2><?php if($novo == '') { echo 'Novo '; } else {echo 'Altera '; } ?>Usuário</h2>
<form action="processausuario.php" method="POST" id="formulario" name="formulario">
  <input type="hidden" id="codigo" name="codigo" value="<?php echo $novo ?>">
  <div class="form-group">
    <label for="exampleInputEmail1">UserName</label>
    <input type="input" class="form-control" id="username" name="username" maxlength="100" placeholder="Entre com o username" value="<?php echo $usernameform ?>">
  </div>
  <div class="form-group">
    <label for="exampleInputEmail1">Senha</label>
    <input type="password" class="form-control" id="senha" name="senha" maxlength="100" placeholder="Entre com a senha" value="<?php echo $senhaform ?>">
  </div>
  <div class="form-group">
    <label for="exampleInputEmail1">Confirmar Senha</label>
    <input type="password" class="form-control" id="confirmasenha" name="confirmasenha" maxlength="100" placeholder="Confirme a senha" value="<?php echo $senhaform ?>">
  </div>
  <div class="form-group">
    <label for="exampleInputEmail1">Email</label>
    <input type="input" class="form-control" id="email" name="email" aria-describedby="cpf" maxlength="100" placeholder="Entre com o email" value="<?php echo $emailform ?>">
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
  <button class="btn btn-primary salvar" onclick="return false;">Salvar</button>
  <button type="button" class="btn btn-primary" onclick="location.href='./read.php'";>Cancelar</button>
</form>
  
</div>
</body>
</html>
<script>
$(".salvar").on("click", function(e){
	e.preventDefault();
	e.stopPropagation();
	var senha = $("#senha").val();
	var confirmasenha = $("#confirmasenha").val();
	if(senha != confirmasenha){
		alert("Senha não confere com a confirmação");
	}else{
		$("#formulario").submit();
	}
});

</script>
