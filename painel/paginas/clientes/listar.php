<?php 
$tabela = 'clientes';
require_once("../../../conexao.php");

$query = $pdo->query("SELECT * from $tabela order by id desc");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);
if($linhas > 0){
echo <<<HTML

	<table class="table table-bordered text-nowrap border-bottom dt-responsive" id="tabela">
	<thead> 
	<tr> 
	<th align="center" width="5%" class="text-center">Selecionar</th>
	<th>Nome</th>	
	<th >Telefone</th>	
	<th >Email</th>			
	<th >CPF / CNPJ</th>
	<th >Tipo Pessoa</th>
	<th >Data Cadastro</th>	
	<th>Ações</th>
	</tr> 
	</thead> 
	<tbody>	
HTML;

for($i=0; $i<$linhas; $i++){
	$id = $res[$i]['id'];
	$nome = $res[$i]['nome'];
	$telefone = $res[$i]['telefone'];
	$email = $res[$i]['email'];	
	$endereco = $res[$i]['endereco'];
	$tipo_pessoa = $res[$i]['tipo_pessoa'];
	$cpf = $res[$i]['cpf'];
	
	$data_cad = $res[$i]['data_cad'];
	$data_nasc = $res[$i]['data_nasc'];

	$data_cadF = implode('/', array_reverse(@explode('-', $data_cad)));
	$data_nascF = implode('/', array_reverse(@explode('-', $data_nasc)));

	$tel_whatsF = '55'.preg_replace('/[ ()-]+/' , '' , $telefone);

	

echo <<<HTML
<tr>
<td align="center">
<div class="custom-checkbox custom-control">
<input type="checkbox" class="custom-control-input" id="seletor-{$id}" onchange="selecionar('{$id}')">
<label for="seletor-{$id}" class="custom-control-label mt-1 text-dark"></label>
</div>
</td>
<td>{$nome}</td>
<td>{$telefone}</td>
<td>{$email}</td>
<td>{$cpf}</td>
<td><span class="badge bg-primary me-1 my-2 p-1"><big>{$tipo_pessoa}</big></span></td>
<td>{$data_cadF}</td>
<td>
	<a class="icones_mobile" href="#" onclick="editar('{$id}','{$nome}','{$email}','{$telefone}','{$endereco}','{$cpf}','{$tipo_pessoa}','{$data_nasc}')" title="Editar Dados"><i class="fa fa-edit text-primary"></i></a>

	<div class="dropdown" style="display: inline-block;">                      
                        <a class="icones_mobile" href="#" aria-expanded="false" aria-haspopup="true" data-bs-toggle="dropdown" class="dropdown"><i class="fa fa-trash text-danger"></i> </a>
                        <div  class="dropdown-menu tx-13">
                        <div class="dropdown-item-text botao_excluir">
                        <p>Confirmar Exclusão? <a href="#" onclick="excluir('{$id}')"><span class="text-danger">Sim</span></a></p>
                        </div>
                        </div>
                        </div>

<a class="icones_mobile" href="#" onclick="mostrar('{$nome}','{$email}','{$telefone}','{$endereco}', '{$data_cadF}','{$cpf}','{$tipo_pessoa}','{$data_nascF}')" title="Mostrar Dados"><i class="fa fa-info-circle text-primary"></i></a>


<a class="icones_mobile" href="#" onclick="arquivo('{$id}', '{$nome}')" title="Inserir / Ver Arquivos"><i class="fa fa-file-o " style="color:#22146e"></i></a>

<a class="icones_mobile" href="#" onclick="mostrarContas('{$nome}','{$id}')" title="Mostrar Contas"><i class="fa fa-money text-verde"></i></a>

<a class="icones_mobile" class="" href="http://api.whatsapp.com/send?1=pt_BR&phone={$tel_whatsF}" title="Whatsapp" target="_blank"><i class="fa fa-whatsapp " style="color:green"></i></a>


</td>
</tr>
HTML;

}

}else{
	echo 'Não possui nenhum cadastro!';
}


echo <<<HTML
</tbody>
<small><div align="center" id="mensagem-excluir"></div></small>
</table>
HTML;
?>



<script type="text/javascript">
	$(document).ready( function () {		
    $('#tabela').DataTable({
    	"language" : {
            //"url" : '//cdn.datatables.net/plug-ins/1.13.2/i18n/pt-BR.json'
        },
        "ordering": false,
		"stateSave": true
    });
} );
</script>

<script type="text/javascript">
	function editar(id, nome, email, telefone, endereco, cpf, tipo_pessoa, data_nasc){
		$('#mensagem').text('');
    	$('#titulo_inserir').text('Editar Registro');

    	$('#id').val(id);
    	$('#nome').val(nome);
    	$('#email').val(email);
    	$('#telefone').val(telefone);
    	$('#endereco').val(endereco);    	
    	$('#cpf').val(cpf);
    	$('#tipo_pessoa').val(tipo_pessoa).change();
    	$('#data_nasc').val(data_nasc);

    	$('#modalForm').modal('show');
	}


	function mostrar(nome, email, telefone, endereco, data, cpf, tipo_pessoa, data_nasc){
		    	
    	$('#titulo_dados').text(nome);
    	$('#email_dados').text(email);
    	$('#telefone_dados').text(telefone);
    	$('#endereco_dados').text(endereco);
    	$('#cpf_dados').text(cpf);
    	$('#data_dados').text(data);
    	$('#pessoa_dados').text(tipo_pessoa);
    	$('#data_nasc_dados').text(data_nasc);
    	
    	$('#modalDados').modal('show');
	}

	function limparCampos(){
		$('#id').val('');
    	$('#nome').val('');
    	$('#email').val('');
    	$('#telefone').val('');
    	$('#endereco').val('');
    	$('#cpf').val('');
    	$('#tipo_pessoa').val('Física').change();
    	$('#data_nasc').val('');

    	$('#ids').val('');
    	$('#btn-deletar').hide();	
	}

	function selecionar(id){

		var ids = $('#ids').val();

		if($('#seletor-'+id).is(":checked") == true){
			var novo_id = ids + id + '-';
			$('#ids').val(novo_id);
		}else{
			var retirar = ids.replace(id + '-', '');
			$('#ids').val(retirar);
		}

		var ids_final = $('#ids').val();
		if(ids_final == ""){
			$('#btn-deletar').hide();
		}else{
			$('#btn-deletar').show();
		}
	}

	function deletarSel(){
		var ids = $('#ids').val();
		var id = ids.split("-");
		
		for(i=0; i<id.length-1; i++){
			excluirMultiplos(id[i]);			
		}

		setTimeout(() => {
		  	listar();	
		}, 1000);

		limparCampos();
	}

	function arquivo(id, nome){
	    $('#id-arquivo').val(id);    
	    $('#nome-arquivo').text(nome);
	    $('#modalArquivos').modal('show');
	    $('#mensagem-arquivo').text(''); 
	    $('#arquivo_conta').val('');
	    listarArquivos();   
	}	



	function mostrarContas(nome, id){
		    	
    	$('#titulo_contas').text(nome); 
    	$('#id_contas').val(id); 	
    	    	
    	$('#modalContas').modal('show');
    	listarDebitos(id);
    	
	}


	function listarDebitos(id){

		 $.ajax({
        url: 'paginas/' + pag + "/listar_debitos.php",
        method: 'POST',
        data: {id},
        dataType: "html",

        success:function(result){
            $("#listar_debitos").html(result);           
        }
    });
	}
</script>