<?php
// pagina protegida, incluir script de verificação de login
require '../public/verificarLogin.php';
require '../public/verificarAdmin.php';
?>

<?php include '../header.php'; ?>
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
        $sql = "SELECT C.ID, C.NOME_FANTASIA FROM EMPRESA C";
			
	$statement = $connection->prepare($sql); 	     
        $statement->execute();
        
        $result = $statement->fetchAll();
    } catch(PDOException $error) {
        echo $sql . "<br>" . $error->getMessage();
    }

?> 
  <div class="row" style="margin-bottom: 10px">
	<div class="col-md-12">
	  <h2>Empresas</h2>
	</div>
  </div>
<div class="row" style="margin-bottom: 10px">
	<div class="col-md-9">
	  <div class="input-group">
	    <input type="text" class="form-control" id="inputpesquisa" name="inputpesquisa" placeholder="Pesquise por empresa...">
	    <div class="input-group-append">
	      <button class="btn btn-secondary" type="button">
	        <i class="fa fa-search"></i>
	      </button>
	    </div>
	  </div>
	</div>
	<div class="col-md-2">
		<button class="btn btn-primary" onclick="location.href='./formempresa.php'";>Nova Empresa</button>
	</div>
</div>
<div class="row">
<div class="col-md-12">
<?php 
if ($result && $statement->rowCount() > 0) { ?>
	 

        <table class="table table-striped table-bordered table-hover" id="empresatable" name="empresatable">
            <thead>
                <tr>
                    <th></th>
                    <th>NOME</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($result as $row) { ?>
            <tr>
                <td><button class="btn btn-primary btn-sm" onclick="location.href='./formempresa.php?id=<?php echo $row["ID"]; ?>'";>Alterar Empresa</button></td>
                <td><?php echo $row["NOME_FANTASIA"]; ?></td>
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
                                    $("#empresatable tbody tr").filter(function () {
                                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                                    });
                                });
                            });
                        </script>
