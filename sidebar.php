<?php

include_once("services/usuario.php");
$usuario = new usuario();
$id = $_SESSION['ID'];
$email = $_SESSION['email'];
$perfil = $_SESSION['ID_Perfil'];
?>

<nav id="sidebar">

    <ul class="list-unstyled components">      
        <li>
            <?php if($perfil != 4 ){ ?>
            <a href="index.php" ><i class="fas fa-shopping-cart" aria-hidden="true" style="margin-right:10px;"></i>Orçamentos</a>
            <!--<ul class="collapse list-unstyled" id="vendas">
                <li>
                    <a href="#">Vizualizar</a>
                </li>
                <li>
                    <a href="#">Exemplo 2</a>
                </li>
                <li>
                    <a href="#">Exemplo 3</a>
                </li>
            </ul>-->
            <a href="#relatorio" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"> <i class="fas fa-sticky-note" aria-hidden="true" style="margin-right:10px;"></i>Relatório</a>
            <ul class="collapse list-unstyled" id="relatorio">
                <li>
                    <a href="#" data-toggle="modal" data-target="#gerar_pdf">Para PDF</a>
                </li>
                <li>
                    <a href="#" data-toggle="modal" data-target="#gerar_excel">Para Excel</a>
                </li>
                
            </ul>
            <?php } ?>

            <a href="#myconta" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"> <i class="fas fa-user-cog" aria-hidden="true" style="margin-right:10px;"></i>Minha conta</a>
            <ul class="collapse list-unstyled" id="myconta">
                <li>
                    <a href='editar_dados.php?id=<?php echo base64_encode($id)  ?>'>Alterar minha senha</a>
                    
                </li>               
                <li>
                    <a href="mailto:example@email.com" >Enviar e-mail</a>
                </li>
                <li>
                    <a href='suporte_msg.php?mail=<?php echo base64_encode($email)  ?>'>Suporte</a>
                    
                </li>
                
            </ul>

            <?php
                if($perfil == 1 or $perfil == 4){
            ?>
                <a href="#admin" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"> <i class="fas fa-laptop" aria-hidden="true" style="margin-right:10px;"></i>Administração</a>
                <ul class="collapse list-unstyled" id="admin">
                    <li>

                        <a href='cadastro_clientes.php'>Cadastro de Usuários</a>
                        <a href='listar_usuarios.php'>Listagem de Usuários</a>
                        
                        
                    </li>               
                    
                </ul>

            <?php } ?>       

            
            
        </li>       
    </ul>

    <!--- <ul class="list-unstyled CTAs">
        <li>
            <a href="https://bootstrapious.com/tutorial/files/sidebar.zip" class="download">Download source</a>
        </li>
        <li>
            <a href="https://bootstrapious.com/p/bootstrap-sidebar" class="article">Back to article</a>
        </li>
    </ul>-->
</nav>

<!-- Modal GERAR PDF-->
<div class="modal fade" id="gerar_pdf" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form action='gerar_pdf.php' method='POST' >
            <div class="modal-content">            
                <div class="modal-header">
                    <h5 class="modal-title text-center" id="exampleModalLongTitle"><strong>Selecione</strong></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    
                        <label for="exampleInputEmail1">Digite a quantidade de registro que deseja ver</label>
                        <input type="text" class="form-control" name='qtd_registro' id="qtd_registro" placeholder="Digite aqui" required>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
                    <input type="submit" class="btn btn-success" id='editar' value="Selecionar">
                </div>            
            </div>
        </form>
    </div>
</div>

<!-- Modal GERAR EXCEL -->
<div class="modal fade" id="gerar_excel" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form action='gerar_excell.php' method='POST' >
            <div class="modal-content">            
                <div class="modal-header">
                    <h5 class="modal-title text-center" id="exampleModalLongTitle"><strong>Selecione</strong></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    
                        <label for="exampleInputEmail1">Digite a quantidade de registro que deseja ver</label>
                        <input type="text" class="form-control" name='qtd_excel' id="qtd_excel" placeholder="Digite aqui" required>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
                    <input type="submit" class="btn btn-success" id='editar' value="Selecionar">
                </div>            
            </div>
        </form>
    </div>
</div>
