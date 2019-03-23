<?php
// pagina protegida, incluir script de verificação de login
require 'verificarLogin.php';
?>

<?php include '../header.php'; ?>
<style>
#example-link a { 
  padding-left: 15px;   
  width: 34px;
  display:block;
  height: 34px;
  background: url(../public/images/downloadfile.png) 3px 1px no-repeat; 
} 
</style>
<div class="container">
<?php
/**
 * Function to query information based on 
 * a parameter: in this case, location.
 *
 */

    try  {
        
        require "../config.php";
        
        $connection = new PDO($dsn, $username, $password, $options);
        $sql = "SELECT CR.ID, P.NOME, P.CPF, C.DESCRICAO, DATE_FORMAT(CR.DATA_EMISSAO,'%d/%m/%Y') as DATA_EMISSAO, DATE_FORMAT(CR.DATA_VALIDADE,'%d/%m/%Y') as DATA_VALIDADE ,
			CASE
			    WHEN CR.DATA_VALIDADE > SYSDATE() and DATE_ADD(SYSDATE(), INTERVAL 30 DAY) < CR.DATA_VALIDADE THEN 'VALIDO'
                	    WHEN CR.DATA_VALIDADE > SYSDATE() and DATE_ADD(SYSDATE(), INTERVAL 30 DAY) >= CR.DATA_VALIDADE THEN 'VENCENDO'
			    WHEN CR.DATA_VALIDADE < SYSDATE() THEN 'VENCIDO'
			END SITUACAO,
			CR.TEM_ANEXO,
            		EMPR.NOME_FANTASIA as EMPRESA
			FROM PESSOA_CURSO CR 
			INNER JOIN PESSOA P ON P.ID = CR.PESSOA_ID
			INNER JOIN CURSO C ON C.ID = CR.CURSO_ID
            		INNER JOIN EMPRESA EMPR ON P.EMPRESA_ID = EMPR.ID";
			
	if($_SESSION['perfil'] != 'ADMIN'){
	  $sql = $sql." WHERE P.EMPRESA_ID = :empresa";
	  $empresa = $_SESSION['empresa'];	  
	}
			
	$statement = $connection->prepare($sql); 
	$statement->bindParam(':empresa', $empresa, PDO::PARAM_STR);       
        $statement->execute();
        
        $result = $statement->fetchAll();
    } catch(PDOException $error) {
        echo $sql . "<br>" . $error->getMessage();
    }

?> 
  <div class="row" style="margin-bottom: 10px">
	<div class="col-md-12">
	  <h2>Certificados</h2>
	</div>
  </div>
  <div class="row" style="margin-bottom: 10px">
<div class="col-md-9">
  <div class="input-group">
    <input type="text" class="form-control" id="inputpesquisa" name="inputpesquisa" placeholder="Pesquise por nome, curso...">
    <div class="input-group-append">
      <button class="btn btn-secondary" type="button">
        <i class="fa fa-search"></i>
      </button>
    </div>
  </div>
</div>
<div class="col-md-2">
<?php if($_SESSION['perfil'] == 'ADMIN') {?>
	<button class="btn btn-primary" onclick="location.href='./formcertificado.php'";>Novo Certificado</button>
<?php } ?>
</div>
</div>
<div class="row">
<div class="col-md-12">
<?php 
if ($result && $statement->rowCount() > 0) { ?>
	 

        <table class="table table-striped table-bordered table-hover" id="cursostable" name="cursostable">
            <thead>
                <tr>
                    <th></th>
                    <th>NOME</th>
                    <th>CPF</th>
                    <th>CURSO</th>
                    <th>DATA DE EMISSÃO</th>
                    <th>DATA DE VALIDADE</th>
                    <th>SITUAÇÃO</th>
                    <th>EMPRESA</th>                    
                    <th></th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($result as $row) { ?>
            <tr>
                <td>
                <?php if($_SESSION['perfil'] == 'ADMIN') {?>
                <button class="btn btn-primary btn-sm" onclick="location.href='./formcertificado.php?id=<?php echo $row["ID"]; ?>'";>Alterar</button>
                <?php } ?>               
                </td>                
                <td><?php echo $row["NOME"]; ?></td>
                <td><?php echo $row["CPF"]; ?></td>
                <td><?php echo $row["DESCRICAO"]; ?></td>
                <td><?php echo $row["DATA_EMISSAO"]; ?></td>
                <td><?php echo $row["DATA_VALIDADE"]; ?></td>
                <?php if($row["SITUACAO"] == 'VALIDO') { ?>
                	<td class="table-success">Valido</td>
                <?php } else if($row["SITUACAO"] == 'VENCENDO') {?>
                	<td class="table-warning">Menos de 30 dias</td>
                <?php } else {?>
                	<td class="table-danger">Vencido</td>
                <?php } ?>
                <td><?php echo $row["EMPRESA"]; ?></td>
                <td>
                <?php if($row["TEM_ANEXO"] == 'S') {?>
			<a href="./download.php?id=<?php echo $row["ID"]; ?>" class="acao">
				<img src="../public/images/downloadfile.png" width="25" height="25" alt="Baixar Certificado" title="Baixar Certificado"/>
			</a>
			<!--<a href="./download.php?id=<?php echo $row["ID"]; ?>" class="acao">
				<img src="../public/images/documentdelete.png" width="25" height="25" alt="Baixar Certificado" title="Remover Certificado"/>
			</a>-->
			      
                <?php }?>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
<?php
} 
?> 
</div>
</div>
</div>
</body>
</html>
<script>
                            $(document).ready(function () {
                                $("#inputpesquisa").on("keyup", function () {
                                    var value = $(this).val().toLowerCase();
                                    $("#cursostable tbody tr").filter(function () {
                                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                                    });
                                });
                            });
                        </script>
