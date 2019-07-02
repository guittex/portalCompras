<?php
include_once("services/usuario.php");
$usuario = new usuario(); 
?>
<!DOCTYPE html>
<html lang="pt">
<?php
    //Cabeçalho
    include_once("head.php");
    //Menu
    include_once("header.php");
?>
<body>
<?php

//Verifica se tem a sessão
header("Refresh: 310; url = index.php");
if ( isset( $_SESSION["timer_portal"] ) ) { 
	if ($_SESSION["timer_portal"] < time() ) { 
        session_destroy();		
        header('Location: login.php');
	} else {
		//Seta mais tempo para o timer
        $_SESSION["timer_portal"] = time() + 600;
        
	}
} else { 
    session_destroy();
    header('Location: login.php');	
	//Redireciona para login
}
?>

<div class="wrapper">
    <!-- Sidebar  -->
    <?php
        include_once("sidebar.php");
    ?>
        <!-- Page Content  -->
    <div id="content">
        <h1 class="text-center">Orçamentos</h1>
        <div class="line"></div>
    <?php 
    if(!empty($_GET['msg'])){
        ?>
    <div id='containerMsg' class="alert alert-<?php if($_GET['alert']){echo $_GET['alert'];}?> alert-dismissible fade show text-center" role="alert" style=padding:10px;font-size:14px;>
                
        <?php if(!empty($_GET['msg'])){echo $_GET['msg'];}?>
        <div>
            <button id='fecharMsg' type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>   
        </div>
    </div>                
    <?php } ?>
    <!--PESQUISAR POR ID-->
    <div class="row" style="display: inherit; margin-top: 40px">
        <div class="col-12">
                <form method="POST">                
                    <input type="hidden" name="cod_post">
                    <input type="text" name="ID_Solicitacao" placeholder="Digite o ID da Solicitação para pesquisar" style="padding: 0%; width: 33%;height: 37px;border-radius: 5px;border: 1px solid #ced4da;">
                    <input type="text" name="DE_Descricao" placeholder="Digite alguma palavra da Descrição para pesquisar" style="padding: 0%; width: 33%;height: 37px;border-radius: 5px;border: 1px solid #ced4da;">
                    <button name="SendPesqUser" id="SendPesqUser" class="btn btn-xs btn-dark"  value="Pesquisar"> Pesquisar</button>                    
                </form>	 
            </div>            
        </div>
        <!--TABELA LISTAR -->
        <div class="row" id="tabela_listar_rhs" STYLE="display: inherit;">        
            <div class="col-md-12 table-striped table-responsive shadow p-3 mb-5 bg-white rounded">
                <table class="table">
                    <thead class="">
                        <tr>
                            <th>Descrição</th>
                            <th>Imagem</th>
                            <th>Declinado</th>
                            <th>Qtde</th>
                            <th>NCM</th>
                            <th>Ação</th>              
                        </tr>                    
                    </thead>
                    <tbody>
                    <!--Inicio Loop com pesquisar-->
                    <?php
                    $SendPesqUser = filter_input(INPUT_POST, 'SendPesqUser', FILTER_SANITIZE_STRING);
                    if($SendPesqUser){     
                        $ID_Solicitacao = filter_input(INPUT_POST, 'ID_Solicitacao', FILTER_SANITIZE_NUMBER_INT);
                        $DE_Descricao= filter_input(INPUT_POST, 'DE_Descricao', FILTER_SANITIZE_STRING);       
                        if($ID_Solicitacao == "" && $DE_Descricao == ""){
                            echo
                            "<script>
                                alert('Digite algo para pesquisar');
                                window.location.href=' index.php';
                            </script>";
                        } else{
                            $usuario->pesquisar_orcamento();
                        }
                    }

                    ?>	                
                    <!-- Inicio Loop sem pesquisar-->                 
                    <tr>
                        <?php
                            if(!$SendPesqUser){                                   
                                $usuario->listar_orcamento();        
                                
                            }
                        ?>
                    </tr>       

                    <!-- Modal Declinado-->
                    <?php 
                        /*$salvarDeclinado = filter_input(INPUT_POST, 'salvarDeclinado', FILTER_SANITIZE_STRING);
                        if($salvarDeclinado){
                            $textoDeclinado = filter_input(INPUT_POST, 'declinadoTexto', FILTER_SANITIZE_STRING);
                            echo $textoDeclinado;
                        }*/
                        
                    ?>              
                    </tbody>
                </table> 
            </div>
        </div>
    </div>
</div>

<!--Importação do Ajax-->
<script src="public/js/bootstrap-4.1.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#sidebarCollapse').on('click', function () {
            $('#sidebar').toggleClass('active');
        });
    });
    /* var vendaMediaMensal = $("#inf");
    vendaMediaMensal.focusout( function(){
            alert(vendaMediaMensal.val());
    });*/

    $(document).ready(function () {

        $( "#fecharMsg" ).click(function() {
            $("#containerMsg").fadeOut();
        });
    });

    $('select').on('change', function () {
        if ($(this).val() == "declinado_nao") {
        $('#cria-recinto').modal('show');
        }
    });
</script>
</body>

</html>