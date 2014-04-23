<?php include "header.php";?>

<script>
	function verificaFormAluno(cad){ // validar
		with(document.formAluno){ // with
			if(ra.value == ''){alert('O preenchimento do ra é obrigatório !');ra.focus();return false;}
			if(nome.value == ''){alert('O preenchimento do nome é obrigatório !');nome.focus();return false;}
		  	if(email.value == ''){alert('O preenchimento do e-mail é obrigatório !');email.focus();return false;}	
		  	if(curso.value == '0'){alert('O preenchimento do curso é obrigatório !');curso.focus();return false;}	
			return true;
		}
	} 
</script>

<?php
if($_SESSION['permissao'] > 0)
{
	if(isset($_POST['enviar']))
	{	
		$nome = $_POST['nome'];
		$sexo = $_POST['sexo'];
		$email = $_POST['email'];
		$curso = $_POST['curso'];

		$p34 = $_POST['p34'];
		$p35 = $_POST['p35'];
		$p36 = $_POST['p36'];
		$p37 = $_POST['p37'];
		$p38 = $_POST['p38'];
		$p39 = $_POST['p39'];
		$p40 = $_POST['p40'];
		$p41 = $_POST['p41'];
		$p42 = $_POST['p42'];
		$p43 = $_POST['p43'];
		$p44 = $_POST['p44'];

		if($_POST['senha'] == "Não Alterar")$senha = "";
		else $senha = md5($_POST['senha']);

		if($_POST['oque'] == "novo"){
			$ra = $_POST['ra'];

			$db = conectaBD();
			$query = "SELECT ra FROM alunos WHERE ra = '$ra'";
			$result = mysql_query($query);

			//Se houver aluno com esse RA, não faz o cadastro
			if(mysql_num_rows($result) > 0){
				desconectaBD($db);
				echo "<script>alert('Este RA já esta cadastrado');</script>";
			}
			else{
				$query = "INSERT INTO alunos (ra, fk_curso, nome, sexo, email ) VALUES ('$ra', '$curso', '$nome', '$sexo', '$email')";
				$result = mysql_query($query);
				if($result){
					$query = "INSERT INTO logins (usuario, senha, permissao) VALUES ('$ra', '$senha', '1')";
					$result = mysql_query($query);

					$query = "INSERT INTO tags (usuario, tipo, p35, p36, p37, p38, p39, p40, p41, p42, p43, p44) VALUES ($ra, 1, $p35, $p36, $p37, $p38, $p39, $p40, $p41, $p42, $p43, $p44)";
					$result = mysql_query($query);

					$aviso_sucesso = "Aluno cadastrado com sucesso!";
				}
				else $aviso_erro = "Houve um erro no cadastro, tente novamente!";
				desconectaBD($db);
			}
		}
		else{
			$id = $_POST['oque'];
			$db = conectaBD();
			$query = "UPDATE alunos SET nome = '$nome', email = '$email', sexo = '$sexo', fk_curso = '$curso' WHERE ra = '$id'";
			$result = mysql_query($query);
			if($senha != ""){
				$query = "UPDATE logins SET senha = '$senha' WHERE usuario = '$id' AND permissao = 1";
				$result2 = mysql_query($query);
			}

			$query = "UPDATE tags SET p35 = $p35, p36 = $p36, p37 = $p37, p38 = $p38, p39 = $p39, p40 = $p40, p41 = $p41, p42 = $p42, p43 = $p43, p44 = $p44 WHERE usuario = '".$_POST['oque']."' AND tipo = 1";
			$result2 = mysql_query($query);

			desconectaBD($db);
			if($result)$aviso_sucesso = "Aluno atualizado com sucesso";
			else $aviso_erro = "Houve um erro na edicão, tente novamente!";
		}
	}

	//Se o ID for numérico, é para edição, portanto faz o select do aluno
	if(is_numeric($_GET['id']))
	{
		$db = conectaBD();
		$query="SELECT * FROM `alunos` INNER JOIN tags ON usuario = ra AND tipo = 1 WHERE `ra` = '".$_GET['id']."' limit 1";
		$result = mysql_query($query);
		$row = mysql_fetch_array($result);
		desconectaBD($db);
	}

?>
	
	<?php if(isset($aviso_sucesso) && $aviso_sucesso != "")
		echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">×</button>' . $aviso_sucesso . '</div>';
	?>

	<?php if(isset($aviso_erro) && $aviso_erro != "")
		echo '<div class="alert"><button type="button" class="close" data-dismiss="alert">×</button>' . $aviso_erro . '</div>';
	?>

			
					
	<?php 	
	if($_GET['id'] != "")
	{

		if($_GET['id'] == "novo")
		{
				echo '<h3>Cadastro de Aluno</h3>';
		}
		else if(is_numeric($_GET['id']))
		{
				echo '<h3>Edição de Aluno</h3>';
		}
	?>
		<br />
				
		<form action="alunos.php" method="post" id="formAluno"  name="formAluno" class="form-horizontal" onSubmit="return verificaFormAluno(this);">
				
			<input type="hidden" name="oque" value="<?php echo $_GET['id']; ?>" />

			<div class="row">
				<div class="span6">
					<div class="control-group">
						<label class ="control-label"></label>
						<div class="controls"><h4>Informações Pessoais</h4></div>
					</div>
					 <div class="control-group">
						<label class ="control-label" >RA:</label>
						<div class="controls"><input class="input-small" type="text" id="ra" name="ra" value="<?php echo $row['ra']; ?>" maxlength="8" <?php if($row['ra'] != "")echo "disabled"; ?> required/></div>
					</div>

					 <div class="control-group">
						<label class ="control-label" >Senha:</label>
						<div class="controls"><input class="input-xlarge" type="text" id="senha" name="senha" value="<?php if($row['ra']!="")echo 'Não Alterar';?>" maxlength="100" required/></div>
					</div>

					 <div class="control-group">
						<label class ="control-label" >Nome:</label>
						<div class="controls"><input class="input-xlarge" type="text" id="nome" name="nome" value="<?php echo $row['nome']; ?>" maxlength="100" required/></div>
					</div>
					<div class="control-group">
						<label class ="control-label">Sexo:</label>
						<div class="controls">
							<select name="sexo">
							    <option value="M" <?php if($row['sexo'] == "M") echo 'selected'; ?>>Masculino</option>
							    <option value="F" <?php if($row['sexo'] == "F") echo 'selected'; ?>>Feminino</option>
							</select>
						</div>
					</div>
					<div class="control-group">
						<label class ="control-label">E-mail:</label>
						<div class="controls"><input class="input-xlarge" type="text" name="email" value="<?php echo $row['email']; ?>" maxlength="100"/></div>
					</div>

					<?php
					$db = conectaBD();
					$query = "SELECT * FROM cursos";
					$result = mysql_query($query);
					?>
					<div class="control-group">
						<label class ="control-label">Curso:</label>
						<div class="controls">
							<select name="curso">
									<option value="0">Selecione um Curso</option>
								<?php
								while($curso = mysql_fetch_array($result)){ ?>
							    	<option value="<?php echo $curso['pk_curso']?>" <?php if($curso['pk_curso'] == $row['fk_curso']) echo 'selected'; ?>><?php echo $curso['nome'];?></option>
							    	<?php
							    } ?>
							</select>
						</div>
					</div>

				</div> <!-- span6-->
				<div class="span6">
					<div class="control-group">
						<label class ="control-label" >P[35]:</label>
						<div class="controls"><input class="input-small" type="text" id="p35" name="p35" value="<?php echo $row['p35']; ?>" required/></div>
					</div>
					<div class="control-group">
						<label class ="control-label" >P[36]:</label>
						<div class="controls"><input class="input-small" type="text" id="p36" name="p36" value="<?php echo $row['p36']; ?>" required/></div>
					</div>
					<div class="control-group">
						<label class ="control-label" >P[37]:</label>
						<div class="controls"><input class="input-small" type="text" id="p37" name="p37" value="<?php echo $row['p37']; ?>" required/></div>
					</div>
					<div class="control-group">
						<label class ="control-label" >P[38]:</label>
						<div class="controls"><input class="input-small" type="text" id="p38" name="p38" value="<?php echo $row['p38']; ?>" required/></div>
					</div>
					<div class="control-group">
						<label class ="control-label" >P[39]:</label>
						<div class="controls"><input class="input-small" type="text" id="p39" name="p39" value="<?php echo $row['p39']; ?>" required/></div>
					</div>
					<div class="control-group">
						<label class ="control-label" >P[40]:</label>
						<div class="controls"><input class="input-small" type="text" id="p40" name="p40" value="<?php echo $row['p40']; ?>" required/></div>
					</div>
					<div class="control-group">
						<label class ="control-label" >P[41]:</label>
						<div class="controls"><input class="input-small" type="text" id="p41" name="p41" value="<?php echo $row['p41']; ?>" required/></div>
					</div>
					<div class="control-group">
						<label class ="control-label" >P[42]:</label>
						<div class="controls"><input class="input-small" type="text" id="p42" name="p42" value="<?php echo $row['p42']; ?>" required/></div>
					</div>
					<div class="control-group">
						<label class ="control-label" >P[43]:</label>
						<div class="controls"><input class="input-small" type="text" id="p43" name="p43" value="<?php echo $row['p43']; ?>" required/></div>
					</div>
					<div class="control-group">
						<label class ="control-label" >P[44]:</label>
						<div class="controls"><input class="input-small" type="text" id="p44" name="p44" value="<?php echo $row['p44']; ?>" required/></div>
					</div>
					
					<div class="control-group">
						<label class ="control-label"></label>
						<div class="controls"></div>
					</div>
					<div class="control-group">
						<label class ="control-label"></label>
						<div class="controls"><input class="btn btn-large btn-primary" type="submit" name="enviar" value="Enviar" /></div>
					</div>
				</div>

			</div> <!-- row -->
			</form>

			<?php
	}else
	{ ?>

			<h2>Alunos</h2>

			<div class="row">
				<div class="span6">

					<form class="navbar-search pull-left">
					  <input type="text" class="search-query" placeholder="Search" onkeyup="BuscaItemTabela(this);">
					</form>

				</div>
				<div class="span6">
					<?php if($_SESSION['permissao'] == 10){?>
						<ul class="pager">
						  <li class="next">
						    <a href="alunos.php?id=novo">Cadastrar novo &rarr;</a>
						  </li>
						</ul>
					<?php } ?>
				</div>
			</div>

			<table class="table table-hover">
				<thead>
					<tr>
						<th>RA</th>
						<th>Nome</th>
						<th>Curso</th>
						<?php
							if($_SESSION['permissao'] == 10)
								echo '<th></th>';
						?>
					</tr>
				</thead>
				<tbody>
				<?php
				$db = conectaBD();
				$query="SELECT a.ra, a.nome, c.nome nome_curso FROM alunos a INNER JOIN cursos c ON a.fk_curso = c.pk_curso WHERE 1 order by c.nome, a.nome";
				$result = mysql_query($query);
				desconectaBD($db);
				while($row = mysql_fetch_array($result)) {

				echo '
						<tr class="itens_tabela">
						<td>' . $row['ra'] . '</td>
						<td>' . $row['nome'] . '</td>
						<td>' . $row['nome_curso'] . '</td>
						';
						if($_SESSION['permissao'] == 10)
							echo '<td><a href="alunos.php?id=' . $row['ra'] . '"><i class = "icon-pencil"></i></a></td>
					</tr>
					';
				}
				?>
				</tbody>
			</table>

    <?php 
    }
}
	include "footer.php";
?>

