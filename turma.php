<?php 
include "header.php";

if($_SESSION['permissao'] > 0)
{		
	?>
	<script>
		function verificaFormAluno(cad){ // validar
			with(document.formAluno){ // with
				if(ra.value == ''){alert('O preenchimento do ra é obrigatório !');ra.focus();return false;}
				if(senha.value == ''){alert('O preenchimento da senha é obrigatório !');senha.focus();return false;}	
				if(nome.value == ''){alert('O preenchimento do nome é obrigatório !');nome.focus();return false;}
			  	if(email.value == ''){alert('O preenchimento do e-mail é obrigatório !');email.focus();return false;}	
			  	if(curso.value == '0'){alert('O preenchimento do curso é obrigatório !');curso.focus();return false;}	
				return true;
			}
		} 
	</script>

	<?php

	if(isset($_POST['cadastrarAlunos']))
	{
		$turma = $_GET['id'];

		$db = conectaBD();
		foreach($_POST['incluirAluno'] as $ra)
	    {
	        $query = "INSERT INTO `rel_turmas_alunos` (`fk_turma`,`ra`) VALUES ('$turma', '$ra')";
	        $result=mysql_query($query);
	    }
	    desconectaBD($db);
	}



	if(is_numeric($_GET['id']) && !isset($_GET['nova_aula']) && !isset($_GET['adicionar_alunos']))
	{
		$pk_turma = $_GET['id'];

		//Usado para deletar o horário
		if(isset($_GET['del']) && is_numeric($_GET['del']))
		{
			$aviso_erro = "Deseja realmente apagar este horario? <a href='turma.php?id=".$_GET['id']."&deletar=".$_GET['del']."'>Clique aqui</a> para confirmar";
		}
		if(isset($_GET['deletar']) && is_numeric($_GET['deletar']))
		{
			$query = "DELETE FROM `horarios` WHERE pk_horario=".$_GET['deletar']." AND fk_turma = ".$_GET['id']."";
			// $result = mysql_query($query);
			$aviso_sucesso = "[AINDA NÃO MASSSS]Aula deletada com sucesso!";
		}

		//Para exibir os alertas de erro
		if(isset($aviso_erro) && $aviso_erro != "")
			echo '<div class="alert"><button type="button" class="close" data-dismiss="alert">×</button>' . $aviso_erro . '</div>';
		if(isset($aviso_sucesso) && $aviso_sucesso != "")
			echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">×</button>' . $aviso_sucesso . '</div>';
		
		//Para pegar a informação da turma
		$query = "SELECT
					t.numero,
					c.nome curso,
					m.nome materia, m.creditos
				  FROM
				  	turmas t
				  INNER JOIN
				  	cursos c
				  ON
				  	t.fk_curso = c.pk_curso
				  INNER JOIN
				  	materias m
				  ON
				  	t.fk_materia = m.pk_materia
				  WHERE
				  	pk_turma = '$pk_turma'
				  	";

		$db = conectaBD();
		$result = mysql_query($query);
		desconectaBD($db);

		if(mysql_num_rows($result) > 0)
		{
			$row = mysql_fetch_array($result);
			$num_turma = $row['numero'];
			$curso = $row['curso'];
			$materia = $row['materia'];
			$creditos = $row['creditos'];

			?>
				<div class="row">
					<div class="span12">
						<h3>Turma <?php echo $num_turma;?></h3>
						<div class="row">
							<div class="span4">
								<address>
								  	<b>Curso:</b> <?php echo $curso;?><br>
								  	<b>Matéria:</b> <?php echo $materia;?><br>
								  	<b>Créditos:</b> <?php echo $creditos;?><br>
								</address>		
							</div>
							<div class="span4">
								<a href="turma.php?id=<?php echo $pk_turma;?>&nova_aula" class="btn btn-medium btn-primary">Cadastrar Nova Aula</a>
							</div>
							<div class="span4">
								<a href="turma.php?id=<?php echo $pk_turma;?>&adicionar_alunos" class="btn btn-medium btn-primary">Adicionar Alunos</a>
							</div>
						</div>
					</div>
				</div>


				<style>
				.window{
				    display:none;
				    width:300px;
				    height:300px;
				    position:absolute;
				    left:0;
				    top:0;
				    background:#FFF;
				    z-index:9900;
				    padding:10px;
				    border-radius:10px;
				}
				 
				#mascara{
				    display:none;
				    position:absolute;
				    left:0;
				    top:0;
				    z-index:9000;
				    background-color:#0f0f0f;
				}
				 
				.fechar{display:block; text-align:right;}
				</style>

				<script>
				$(document).ready(function(){
				    $("a[rel=modal]").click( function(ev){
				        ev.preventDefault();
				 
				        var id = $(this).attr("href");
				 
				        var alturaTela = $(document).height();
				        var larguraTela = $(window).width();
				     
				        //colocando o fundo preto
				        $('#mascara').css({'width':larguraTela,'height':alturaTela});
				        $('#mascara').fadeIn(200);
				        $('#mascara').fadeTo("slow",0.5);
				 
				        var left = ($(window).width() /2) - ( $(id).width() / 2 );
				        var top = ($(window).height() / 2) - ( $(id).height() / 2 );
				     
				        $(id).css({'top':top,'left':left});
				        $(id).show();  
				    });
				 
				    $("#mascara").click( function(){
				        $(this).hide();
				        $(".window").hide();
				    });
				 
				    $('.fechar').click(function(ev){
				        ev.preventDefault();
				        $("#mascara").hide();
				        $(".window").hide();
				    });
				});
				</script>
				 
				<div class="window" id="indicacoes">
				    <a href="#" class="fechar">Fechar</a>
				    <h4>Indicações</h4>
				   	<?php if($row['indicacao1']!=""){?><p><?php echo $row["indicacao1"]." - ".num_tel($row['fone_indicacao1']);?></p><?php } ?>
				    <?php if($row['indicacao2']!=""){?><p><?php echo $row["indicacao2"]." - ".num_tel($row['fone_indicacao2']);?></p><?php } ?>
				    <?php if($row['indicacao3']!=""){?><p><?php echo $row["indicacao3"]." - ".num_tel($row['fone_indicacao3']);?></p><?php } ?>
				    <?php if($row['indicacao4']!=""){?><p><?php echo $row["indicacao4"]." - ".num_tel($row['fone_indicacao4']);?></p><?php } ?>
				</div>
				 
				 
				<!-- mascara para cobrir o site -->  
				<div id="mascara"></div>


				<div class="row">
					<div class ="span12">
					<?php 


					$aba = array();
					$aba_conteudo = array();
					$i = 0;
					
					$db = conectaBD();	
					$query = "SELECT count(*),date_format(data_inicio, '%m/%Y') as data FROM horarios WHERE fk_turma = ".$_GET['id']." group by date_format(data_inicio, '%m/%Y') order by data_inicio desc";
					$result = mysql_query($query);
				
					while ($row = mysql_fetch_array($result))
					{
						$aba[$i]  = '';
						$aba_conteudo[$i]  = '';

						$aba[$i] .= $row['data']; 


						$query = "SELECT pk_horario, DATE_FORMAT(data_inicio, '%d às %h:%i') as data_inicio FROM horarios WHERE fk_turma = ".$_GET['id']." AND DATE_FORMAT(data_inicio, '%m/%Y') = '".$row['data']."' order by data_inicio desc";
						$result3 = mysql_query($query);

						while ($row2 = mysql_fetch_array($result3))
						{
							$aba_conteudo[$i] .= "<div><a href='turma.php?id=".$_GET['id']."&del=".$row2['pk_horario']."'><i class='icon-remove'></i></a> <a href='presenca.php?id=".$_GET['id']."&horario=".$row2['pk_horario']."'>Aula do dia ".$row2['data_inicio']."";

							$aba_conteudo[$i] .= "</a></div>";
							
						}


						$i++;

					}	
					desconectaBD($db);

						echo '
						<div class="bs-docs-example">
				            <ul id="myTab" class="nav nav-tabs">';

				            foreach ($aba as $key => $value) {
				            	if($key != 0)
				            		echo '<li class=""><a href="#'.$key.'" data-toggle="tab">'.$value.'</a></li>';
				            	else
				            		echo '<li class="active"><a href="#'.$key.'" data-toggle="tab">'.$value.'</a></li>';
				            }
				            echo '
				            </ul>
				            <div id="myTabContent" class="tab-content">';


				            foreach ($aba_conteudo as $key => $value) {
				              if($key != 0)
				              		echo '  <div class="tab-pane fade in" id="'.$key.'">
						                <p>'.$value.'</p>
						              </div>';
						       else 
						       	echo '  <div class="tab-pane in active" id="'.$key.'">
						                <p>'.$value.'</p>
						              </div>';
				            }
				            echo '
				            </div>
				         </div>
				         ';
					
							?>

							<?php 
				
					?>

					</div>
				</div>


			<script>
			  $(function () {
			    $('#myTab a:first').tab('show');
			  })
			</script>
			<?php 
		} // if mysql_num_rows > 0
		else {
			// echo "<meta HTTP-EQUIV='Refresh' CONTENT='0;URL=./'>";
		}
	} 
	// INICIO DO CADASTRO DE NOVA AULA
	else if(is_numeric($_GET['id']) && isset($_GET['nova_aula']) && !isset($_GET['adicionar_alunos']))
	{ 
		$pk_turma = $_GET['id'];
		//Para pegar a informação da turma
			$query = "SELECT
					t.numero
				  FROM
				  	turmas t
				  WHERE
				  	pk_turma = '$pk_turma'";
		
		$db = conectaBD();
		$result = mysql_query($query);
		desconectaBD($db);

		if(mysql_num_rows($result) > 0)
		{
			$row = mysql_fetch_array($result);
			$num_turma = $row['numero'];
			?>
			<form action="turma.php?id=<?php echo $_GET['id'];?>" method="post" id="formMateria" name="formMateria" class="form-horizontal" onSubmit="return verificaFormMateria(this);">
				<div class="row">
					<div class="span6">
						<div class="control-group">
							<label class ="control-label"></label>
							<div class="controls"><h4>Nova aula - Turma <?php echo $row['numero']?></h4></div>
						</div>

						<div class="control-group">
							<label class ="control-label"></label>
							 <div class="controls"><input type="checkbox" name="dia"> Segunda-Feira</div>
							 <div class="controls"><input type="checkbox" name="dia"> Terça-Feira</div>
							 <div class="controls"><input type="checkbox" name="dia"> Quarta-Feira</div>
							 <div class="controls"><input type="checkbox" name="dia"> Quinta-Feira</div>
							 <div class="controls"><input type="checkbox" name="dia"> Sexta-Feira</div>
						</div>

						<div class="control-group">
							<label class ="control-label">Inicio:</label>
							<div class="controls">
								<select class="input-mini" name="hora_inicio">
								    <?php
									for($i = 8; $i<22; $i++){ ?>
								    	<option value="<?php echo $i;?>" <?php if($row['hora_inicio'] == "<?php echo $i;?>") echo 'selected'; ?>><?php echo $i;?></option>
								    <?php 
									}?>
								</select>
								:
								<select class="input-mini" name="minuto_inicio">
									<?php
									for($i = 0; $i<60; $i++){ ?>
								    	<option value="<?php echo $i;?>" <?php if($row['minuto_inicio'] == "<?php echo $i;?>") echo 'selected'; ?>><?php echo $i;?></option>
								    <?php 
									}?>
								</select>
							</div>
						</div>

						<div class="control-group">
							<label class ="control-label">Fim:</label>
							<div class="controls">
								<select class="input-mini" name="hora_fim">
								    <?php
									for($i = 8; $i<23; $i++){ ?>
								    	<option value="<?php echo $i;?>" <?php if($row['hora_fim'] == "<?php echo $i;?>") echo 'selected'; ?>><?php echo $i;?></option>
								    <?php 
									}?>
								</select>
								:
								<select class="input-mini" name="minuto_fim">
									<?php
									for($i = 0; $i<60; $i++){ ?>
								    	<option value="<?php echo $i;?>" <?php if($row['minuto_fim'] == "<?php echo $i;?>") echo 'selected'; ?>><?php echo $i;?></option>
								    <?php 
									}?>
								</select>
							</div>
						</div>
				

						<div class="control-group">
							<label class ="control-label"></label>
							<div class="controls"></div>
						</div>


						<div class="control-group">
							<label class ="control-label"></label>
							<div class="controls"><input class="btn btn-large btn-primary" type="submit" name="cadastrarAlunos" value="Enviar" /></div>
						</div>
					</div> <!-- span6-->

				</div> <!-- row -->
				</form>
		<?php
		} // NUM ROWS > 0
	}
	// INICIO DA ADIÇÃO DE ALUNOS
	else if(is_numeric($_GET['id']) && !isset($_GET['nova_aula']) && isset($_GET['adicionar_alunos']))
	{ 
		?>
		<ul class="pager">
		    <li class="previous">
		      <a href = "turma.php?id=<?php echo $_GET['id']; ?>">&larr; Cancelar</a>
		    </li>
		</ul>
		<br/>
		<br />
		<div class="row">
			<div style="span12">
				<form class="navbar-search pull-left" >
				  <input style="height: 25px;line-height: 20px;" type="text" class="input-xxlarge search-query" placeholder="Pesquisar nas 2 tabelas" onkeyup="BuscaItemTabela(this);">
				  <br/><br/>
				</form>
			</div>
		</div>
					
		<style>
		textarea,
		input[type="text"],
		.uneditable-input {
		  display: inline-block;
		  height: 14px;

		  margin: 0 0 0 0;
		  font-size: 14px;
		  line-height: 2px;
		  color: #555555;
		}
		.btn {

		  display: inline-block;
		  *display: inline;
		  /* IE7 inline-block hack */

		  *zoom: 1;
		  padding: 4px 12px;
		  margin-bottom: 0;
		  font-size: 14px;
		  line-height: 12px;
		  text-align: center;
		  vertical-align: middle;
		  cursor: pointer;
		  
		}
		
		
		</style>

			<div class="row">
				<div class="span6">
					<h4>Lista de todos alunos</h4>
					<table class="table" id="lista">
						
						<thead>
							<tr>
								<th>RA.</th>
								<th>Nome</th>
								<th></th>
							</tr>
						</thead>
						<tbody >
						<?php

						$db = conectaBD();
						$query="SELECT * FROM `alunos` WHERE 1 order by nome";
						$result = mysql_query($query);
						desconectaBD($db);
						

						while($row = mysql_fetch_array($result)) {

						echo '
							<tr id="item_' . $row['ra'] . '" class="itens_tabela">
								<td>' . $row['ra'] .'</td>
								<td class="nome">' . $row['nome'] . '</td>
								<td> <a class="btn btn-small" onClick="insereAlunoTurma(' . $row['ra'] . ', \'' . $row['nome'] . '\');"><i class="icon-chevron-right" ></i></a> </td>
							</tr>
							';
						}
						?>
						</tbody>
					</table>
				</div>

				<div class="span6">
					<h4>Lista dos alunos da turma</h4>
					<form action="turma.php?id=<?php echo $_GET['id'];?>" method="post" id="comboForm">							
						<table class="table" id="lista">
							<thead>
								<tr>
									<th>RA</th>
									<th>Nome</th>
					 			</tr>
							</thead>

							<tbody id="lista_combo">
							
							</tbody>

						</table>


						<br/>
						<div class="well well-small">
						<b>Total de alunos:</b> <span id="qtd_alunos">0</span></td></tr>
						</div>


						<div class="control-group">
							<label class ="control-label"></label>
							<div class="controls"><input class="btn btn-large btn-primary" type="submit" name="cadastrarAlunos" value="Salvar" /></div>
						</div>
						

					</form>
				</div>
			</div>
<?php

	}
	else
	{
			echo "C";
		// echo "<meta HTTP-EQUIV='Refresh' CONTENT='0;URL=./'>";
	}

} // if permissao

include "footer.php";

?>
