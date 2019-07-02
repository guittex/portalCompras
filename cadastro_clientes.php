<?php
include_once("services/usuario.php");



$usuario = new usuario();



?>

<!DOCTYPE html>
<html>

<?php

    include_once("head.php");

?>

<?php
    include_once("header.php");
?>

<body>

<?php

//Verifica se tem a sessão
header("Refresh: 310; url = listar_usuarios.php");
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

if(isset($_SESSION['ID_Perfil'] )){
    if($_SESSION['ID_Perfil'] != 4 and $_SESSION['ID_Perfil']  != 1){
        header('Location: index.php');	
    }
}
?>


    <div class="wrapper">
        <!-- Sidebar  -->
        <?php
            include_once("sidebar.php");
        ?>

        <!-- Page Content  -->
        <div id="content">
            <h1 class="text-center">Cadastro</h1>
            <div class="line"></div>

                <form method="POST">
                    <div class="form-row w-100">

                        <input type="hidden" name="cod_post" value='4'>

                        <div class="form-group col-md-12 col-sm-12" >
                            <label>Razão Social</label>
                            <input type="text" class="form-control" name="DE_RazaoSocial" id="DE_RazaoSocial" placeholder='Exemplo: Fresadora Santana' required>
                        </div>    

                        <div class="form-group col-md-12 col-sm-12">
                            <label>E-mail</label>
                            <input type="text" class="form-control" name="DE_Email" id="DE_Email" placeholder="email@email.com.br" required>
                        </div>

                        
                        <div class="form-group col-md-12 col-sm-12">
                            <label>Tipo do Perfil</label>
                            <?php
                                if($perfil == 4){
                                    echo "<input type='text' class='form-control' name='ID_Perfil' id='ID_Perfil' value=Fornecedor  readonly>";
                                }else{
                                    echo "<input type='text' class='form-control' name='ID_Perfil' id='ID_Perfil' placeholder='1 para Administrador ou 2 para Usuário' required>";
                                }

                            ?>
                            
                        </div>                    

                        <div class="form-group col-md-12 col-sm-12">
                            <label>Senha</label>
                            <?php
                            if($perfil == 4){
                                echo "<input type='password' class='form-control' name='DE_Senha' value=senha@123 id='DE_Senha' placeholder='Senha' readonly>";
                            }else{ 
                                echo "<input type='password' class='form-control' name='DE_Senha' id='DE_Senha' placeholder='Senha' required>";
                            }
                            ?>
                            
                        </div>

                        <div class="text-center" style='margin:0 auto'>                     
                            <button type="submit" name='cadastrar' id='cadastrar' class="btn btn-primary">Cadastrar</button>
                        </div>
                    </div>
                </form>

                <?php
                    $cadastrar = filter_input(INPUT_POST, 'cadastrar', FILTER_SANITIZE_STRING);
                    if ($cadastrar){
                        $usuario->cadastrarCliente();
                    }

                ?>  
        </div>
    </div>

    <!-- jQuery CDN - Slim version (=without AJAX) -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <!-- Popper.JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            $('#sidebarCollapse').on('click', function () {
                $('#sidebar').toggleClass('active');
            });
        });
    </script>
</body>

</html>