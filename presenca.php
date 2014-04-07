<?php include "header.php";
	if($_SESSION['permissao'] >=2 && is_numeric($_GET['id']) && is_numeric($_GET['horario']))  {


	if(isset($_POST['enviarPresenca'])){
		$horario = $_GET['horario'];

		$db = conectaBD();
		$query = "DELETE FROM presentes WHERE fk_horario = '$horario'";
		$result = mysql_query($query);


		foreach($_POST['alunos'] as $ra){
			$query = "INSERT INTO presentes (ra, fk_horario) VALUES ('$ra', '$horario')";
			$result = mysql_query($query);
		}

		desconectaBD($db);
	}
?>

	<script type="text/javascript">

		function checkAll(){
			console.log("AA");
			document.formPresenca.todos.checked=!document.formPresenca.todos.checked;
			var status = document.formPresenca.todos.checked;
			var alunos = document.formPresenca.elements;

			if(status == true){
				document.getElementById("coletiva").innerHTML = "Falta Coletiva";
			}
			else document.getElementById("coletiva").innerHTML = "Presença Coletiva";

			for(var i = 0; i < alunos.length; i++){
				var x = alunos[i];

				if(x.name == "alunos[]"){
					x.checked = status;
					toogle_class( status, $('table tbody tr') );
				}
			}
		}

		$(document).ready(function(){
			/* ao clicar no checkbox, altera a class da linha */
			$("input[name='alunos[]']").click(function(){
				toogle_class(this.checked, $( this ).parents('tr') );
			});
			$("table tbody tr").click(function( e ){
				if( e.target.tagName!='INPUT' )
				{
					var checkbox = $( this ).find("input[type='checkbox']");
					var ckd = !checkbox.attr('checked');
	 
					checkbox.attr('checked', ckd);
					toogle_class( ckd, $( this ) );
				}
			});
		});
		function toogle_class( ckd, el ){
			if( ckd==true )
			{
				el.addClass('alert alert-success');
				el.removeClass('alert alert-error');
			}
			else
			{
				el.addClass('alert alert-error');
				el.removeClass('alert alert-success');
			}
		}
		</script>


			<ul class="pager">
			    <li class="previous">
			      <a href = "turma.php?id=<?php echo $_GET['id']; ?>">&larr; Voltar</a>
			    </li>
			</ul>
			<h2>Presença</h2>

			<div class="row">
				<div class="span6">

					<form class="navbar-search pull-left">
					  <input type="text" class="search-query" placeholder="Search" onkeyup="BuscaItemTabela(this);">
					</form>

				</div>
				<div class="span6">
					<?php if($_SESSION['permissao'] >= 2){?>
						<ul class="pager">
						  <li class="next">
						    <a href="#" onClick="checkAll();" id="coletiva">Presença Coletiva</a>
						  </li>
						</ul>
					<?php } ?>
				</div>
			</div>

			<form action="presenca.php?id=<?php echo $_GET['id']."&horario=".$_GET['horario'];?>" method="post" id="formPresenca" name="formPresenca" class="form-horizontal" onSubmit="return verificaFormAddAula(this);">
				<input type="checkbox" style="display:none;" name="todos">

				<table class="table">
					<thead>
						<tr>
							<th>Nome</th>
							<th>RA</th>

						</tr>
					</thead>
					<tbody>
						
					<?php
					$id=$_GET['id'];
					$horario=$_GET['horario'];
					$db = conectaBD();
					$query="SELECT a.ra, a.nome, p.ra as presente FROM alunos a 
								INNER JOIN rel_turmas_alunos r ON a.ra = r.ra AND r.fk_turma = $id
								INNER JOIN horarios h ON r.fk_turma = h.fk_turma AND h.pk_horario = $horario
								LEFT JOIN presentes p ON a.ra = p.ra AND p.fk_horario = h.pk_horario
								ORDER BY a.nome";
					$result = mysql_query($query);
					desconectaBD($db);
					while($row = mysql_fetch_array($result)) {
						if($row['presente'] == NULL)
							echo '
									<tr class="itens_tabela alert alert-error">
									<td>' . $row['nome'] . '</td>
									<td>' . $row['ra'] . '<input style="display:none;" type="checkbox" name="alunos[]" value="' . $row['ra'] . '"></td>
								</tr>
								';
						else
							echo '
									<tr class="itens_tabela alert alert-success">
									<td>' . $row['nome'] . '</td>
									<td>' . $row['ra'] . '<input style="display:none;" type="checkbox" name="alunos[]" value="' . $row['ra'] . '" checked></td>
								</tr>
								';
						}
					?>
					</tbody>
				</table>
				<div class="control-group">
					<label class ="control-label"></label>
					<div class="controls"></div>
				</div>


				<div class="control-group">
					<label class ="control-label"></label>
					<div class="controls"><input class="btn btn-medium btn-primary" style="float:right" type="submit" name="enviarPresenca" value="Enviar" /></div>
				</div>
		</form>		
    <?php 
	}else{
		echo 'C';
	}
	include "footer.php";

?>

