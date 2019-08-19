<?php
include_once("sql_conexao.php"); 
include_once("suporteController.php");


    class usuario extends conexao{ 

        public $query;
        public $registro;
        Public $Nivel;
        public $Acessos; 
        public $sql;
        public $DE_RazaoSocial;
        public $DE_Email; 
        public $DE_Senha ;  
        public $ID_Perfil;
        public $IS_Ativo;   
        public $ID_Usuario;
        public $sql_orcamento;
        public $registro_orcamento;
        public $query_orcamento;
        public $ID_VendFor; 
        public $query_usuario;
        public $sql_usuario;
        public $registro_usuario;
        

        public function login(){            
            $this->sql_conexao();           
            $this->DE_Email = $_POST['DE_Email'];
            $this->DE_Senha = $_POST['DE_Senha'];    
            
            //Remove os espaçps
            $this->DE_Senha = trim($this->DE_Senha);

            $this->sql = "SELECT * FROM dbo.PT_Usuario WHERE DE_Email = '$this->DE_Email' and DE_Senha = '$this->DE_Senha' "; 
            //Query da consulta sql            
            $this->query = sqlsrv_query($this->con, $this->sql);
            //Array da query
            $this->registro = sqlsrv_fetch_array($this->query);  
            


            if($this->registro['DE_Senha'] === 'senha@123'){
                header('Location: alterar_senha.php?id=' . base64_encode($this->registro['ID_Usuario']) . ' ');exit;
            }               
            
            //var_dump($this->registro['IS_Ativo'], $this->DE_Email, $this->DE_Senha);

            //Verificar se a query tem uma linha afetada
            if($this->registro['IS_Ativo'] == 0 and $this->registro['DE_Email'] == $this->DE_Email and $this->registro['DE_Senha'] == $this->DE_Senha){
                header('Location: login.php?error=2 ');
                                
            }    
            elseif (sqlsrv_has_rows($this->query) >= 1){   

                if (!isset($_SESSION)) {
                    session_start();
                    $_SESSION["Nome_portal"] = " Sr. " . $this->registro['DE_RazaoSocial'];
                    $_SESSION['ID'] = $this->registro['ID_Usuario'];
                    $_SESSION['ID_VenFor'] = $this->registro['ID_VenFor'];
                    $_SESSION['ID_Perfil'] = $this->registro['ID_Perfil'];
                    $_SESSION['IS_Ativo'] = $this->registro['IS_Ativo'];
                    $_SESSION['email'] = $this->registro['DE_Email'];
                    $_SESSION["timer_portal"]= time() + 600;   
                    if($this->registro['ID_Perfil'] == 4 or  $this->registro['ID_Perfil'] == 1 ){
                        header('Location: listar_usuarios.php');exit;    
                            
                    }                 
                    header('Location: index.php');exit;                                    
                }
            }
            else{
                header('Location: login.php?error=1 '); 
                

            }

            
            
        }

        public function get_IdVend(){
            $this->ID_VenFor = $_SESSION['ID_VenFor'];

            return $this->ID_VenFor;
            
        }

        public function listar_orcamento(){
            $this->sql_conexao(); 
            $this->get_IdVend();            

            $this->sql_orcamento =  "SELECT top 50 * FROM  dbo.PT_Cotacao WHERE ID_Fornecedor =  $this->ID_VenFor AND DE_SolicitacaoStatus = 1";

            $this->query_orcamento= sqlsrv_query($this->con, $this->sql_orcamento); 
            //$this->registro_orcamento = sqlsrv_fetch_array($this->query_orcamento);

            $img = $this->registro_orcamento['DE_Img'];
            
            $this->loop_orcamento(); 

            //Resultado Sql do listar banco
            //print_r($this->sql_orcamento);
            
            
        } 

        public function loop_orcamento(){

            while($this->registro_orcamento = sqlsrv_fetch_array($this->query_orcamento)){
                
                
                    echo "<tr>";
                        echo '<td style=width:40%;>';
                        echo "<span  style='font-weight:bold;color:#3760d6'>ID Cotação:</span>" . $this->registro_orcamento['ID_Cotacao'].  "</br>";
                        echo  $this->registro_orcamento['DE_Descricao'];
                        echo "<td>";
                        if(empty($this->registro_orcamento['DE_Img'])){
                            echo "<img src='img/foto.jpg' style=width:55px;cursor:no-drop;filter:grayscale(100%);>";                    
                            echo $this->registro_orcamento['DE_Img'];
                        }else{
                            $img = $this->registro_orcamento['DE_Img'];
                            echo "<a href='http://www.fresadorasantana.com.br/Portal.APP.web/Imagens/$img' download='$img'> <img src='img/foto.jpg' style=width:55px;cursor:pointer;> </a>";

                        }     
                        echo '</td>';
                        echo "<td>";
                        if($this->registro_orcamento['DE_SolicitacaoStatus'] == 5){
                            echo "<span style='color:red'>Produto Declinado</span>";
                        }else{                            
                            echo "<button type=button class='btn btn-danger' data-toggle='modal' data-target=#oi".$this->registro_orcamento['ID_Cotacao'].">Não</button>";
                        }
                        /*echo "<select class=form-control data-toggle='modal' data-target=#oi".$this->registro_orcamento['ID_Cotacao'].">";                

                            echo "<option value='declinado_nao'>Não</option>";
                            echo "<option value='sim' selected>Sim</option>";                    
                        
                        echo "</select>" ;  */
                        echo "</td>";
                        echo "<td>" . $this->registro_orcamento['DE_Qtde'] . "</td>";
                        //echo "<td style=width:15%;>";                        
                        //echo "<input class='form-control' name='preco' id='preco' style='width:80px;height: 40px;' value = " . $this->registro_orcamento['DE_Preco'] . ">";
                        //echo "</td>";
                        //echo "<td style=width:10%;>";
                        //echo "<input class='form-control' style='width:50%;height: 40px;' value=" . $this->registro_orcamento['DE_EntregaDias'] . "> ";
                        //echo "</td>";
                        //echo "<td style=width:9%;>";
                        //echo "<span >IPI</span> </br>";
                        ///echo "<input class='form-control' style='width:100%;height: 40px;' placeholder='IPI' value=" . $this->registro_orcamento['DE_Ipi'] . " ></br>";
                        //echo "<span>ICMS/ISS</span> </br>";
                        //echo "<input class='form-control' style='width:100%;height: 40px;' placeholder='ICMS/ISS' value=" . $this->registro_orcamento['DE_ICMS'] . ">";
                        //echo "</td>";
                        echo "<td>" . $this->registro_orcamento['DE_NCM'] . "</td>";
                        /*echo "<td>" . $this->registro_orcamento['ID_ProdForn'] . "</td>";


                            echo "<tr>";

                            echo "<td>";
                                echo "<div class='input-group date'  id='datetimepicker1'>";
                                    echo "<input type='date' class='form-control' style='width:100%;height: 40px;' placeholder='Validade da Cotação'>";
                                echo "</div>";
                            echo '</td>';

                            echo "<td>";
                            echo "<select class=form-control style=width:150px;>";                

                                echo "<option name='A VISTA'>A VISTA</option>";
                                echo "<option name='10 DDL'>10 DDL</option>";
                                echo "<option name='90 DDL'>90 DDL</option>";
                                echo "<option name='14/21 DDL'>14/21 DDL</option>";
                                echo "<option name='14/21/42 DDL'>14/21/42 DDL</option>";
                                echo "<option name='14/28 DDL'>14/28 DDL</option>";
                                echo "<option name='15 DDL'>15 DDL</option>";
                                echo "<option name='15/30 DDL'>15/30 DDL</option>";
                                echo "<option name='15/30/45 DDL'>15/30/45 DDL</option>";
                                echo "<option name='15/30/45/60 DDL'>15/30/45/60 DDL</option>";
                                echo "<option name='21/28 DDL'>21/28 DDL</option>";
                                echo "<option name='21/35 DDL'>21/35 DDL</option>";
                                echo "<option name='21/28/35 DDL'>21/28/35 DDL</option>";
                                echo "<option name='21/28/35/42 DDL'>21/28/35/42 DDL</option>";
                                echo "<option name='21/35/42 DDL'>21/35/42 DDL</option>";
                                echo "<option name='21/35/45 DDL'>21/35/45 DDL</option>";
                                echo "<option name='21/35/56 DDL'>21/35/56 DDL</option>";
                                echo "<option name='28/42/56 DDL'>28/42/56 DDL</option>";
                                echo "<option name='28 DDL'>28 DDL</option>";
                                echo "<option name='45 DDL'>45 DDL</option>";
                                echo "<option name='30/60 DDL'>30/60 DDL</option>";
                                echo "<option name='30 DDL'>30 DDL</option>";
                                echo "<option name='30/45 DDL'>30/45 DDL</option>";
                                echo "<option name='30/60/90 DDL'>30/60/90 DDL</option>";
                                echo "<option name='40 DDL'>40 DDL</option>";
                                echo "<option name='28/42 DDL'>28/42 DDL</option>";
                                echo "<option name='30/40 DDL'>30/40 DDL</option>";
                                echo "<option name='45/60 DDL'>45/60 DDL</option>";
                                echo "<option name='30/40 DDL'>30/40 DDL</option>";
                                echo "<option name='28/35 DDL'>28/35 DDL</option>";
                                echo "<option name='30/40/50 DDL'>30/40/50 DDL</option>";
                                echo "<option name='30/45/60 DDL'>30/45/60 DDL</option>";
                                echo "<option name='15/30/45/60/75 DDL'>15/30/45/60/75 DDL</option>";
                                echo "<option name='21 DDL'>21 DDL</option>";
                                echo "<option name='28/35/42 DDL'>28/35/42 DDL</option>";
                                echo "<option name='7 DDL'>7 DDL</option>";
                                echo "<option name='40/60 DDL'>40/60 DDL</option>";
                                echo "<option name='5 DDL'>5 DDL</option>";
                                echo "<option name='30/40/50/60 DDL'>30/40/50/60 DDL</option>";
                                echo "<option name='30/60/90/120 DDL'>30/60/90/120 DDL</option>";
                                echo "<option name='60/90 DDL'>60/90 DDL</option>";





                            
                            echo "</select>" ;
                            echo '</td>';

                            echo "<td colspan='8'>";
                            echo "<input class='form-control' style='width:100%;height: 40px;' name='inf' id='inf' placeholder='Informações ao comprador'>";

                            echo '</td>';
                            */
                            if($this->registro_orcamento['DE_SolicitacaoStatus'] == 5){
                                echo '<td></td>';
                            }elseif($this->registro_orcamento['DE_SolicitacaoStatus'] == 2){
                                echo "<td><button type=button class='btn btn-success' data-toggle=modal data-target='#Ver". $this->registro_orcamento['ID_Cotacao']."'>Ver</button></td>";         

                            }
                            else{
                                echo "<td><button type=button class='btn btn-success' data-toggle=modal data-target='#Cotar". $this->registro_orcamento['ID_Cotacao']."'>Cotar</button></td>";         
                            }
                            
                            echo '</tr>';   
                    echo "</tr>";
                    
                    //Modal cotação feita
                   /* echo "<div class='modal fade bd-example-modal-lg' id=Ver".$this->registro_orcamento['ID_Cotacao']." tabindex='-1' role='dialog' aria-labelledby='myModalLabel'>";
                    echo    '<div class="modal-dialog modal-lg" role="document">';
                    echo        '<form action=services/cotacao.php type=POST>';
                    echo         '<div class="modal-content br-15">';
                    echo            '<div class="modal-header">';
                    echo                '<h4 class="modal-title" id="myModalLabel" style="text-align:center!important;">ID:'.$this->registro_orcamento['ID_Cotacao'].'</h4>';
                    echo               '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                    echo            '</div>';
                    echo            '<div class="modal-body">';
                    echo                    "<input type=hidden name='idFornecedor' value=".$this->ID_VenFor." >";
                    echo                '<input type=hidden name=ID_Cotacao value='.$this->registro_orcamento['ID_Cotacao'].'>';
                    echo                '<p class="m-b-10"><b style="font-weight: bold">Descrição: </b> ';
                    echo                    '<span style="color:black;font-weight:normal">'.$this->registro_orcamento['DE_Descricao'].' reais </span>';
                    echo                '</p>';
                    echo                '<p class="m-b-10"><b style="font-weight: bold">Quantidade:</b> ';
                    echo                     '<span style="color:black;font-weight:normal">'.$this->registro_orcamento['DE_Qtde'].' reais </span>';
                    echo                '</p>';
                    echo                '<div class="form-row">';
                    echo                    '<div class="col">';
                    echo                        '<p class="m-b-10" style="font-weight:bold">Preço: ';
                    echo                            '<span style="color:black;font-weight:normal">'.$this->registro_orcamento['DE_Preco'].' reais </span>';
                    echo                        '</p>';
                    echo                     '</div>';
                    echo                      '<div class="col">';
                    echo                         '<p class="m-b-10" style="font-weight:bold">Entrega dias: ';
                    echo                            '<span style="color:black;font-weight:normal">'.$this->registro_orcamento['DE_EntregaDias'].' dias </span>';
                    echo                          '</p>';
                    echo                       '</div>';
                    echo                 '</div>';
                    echo                '<div class="form-row">';
                    echo                    '<div class="col">';
                    echo                        '<p class="m-b-10" style="font-weight:bold">IPI: ';
                    echo                            '<span style="color:black;font-weight:normal">'.$this->registro_orcamento['DE_Ipi'].'% </span>';
                    echo                        '</p>';
                    echo                     '</div>';
                    echo                      '<div class="col">';
                    echo                         '<p class="m-b-10" style="font-weight:bold">ICMS/ISS: ';
                    echo                            '<span style="color:black;font-weight:normal">'.$this->registro_orcamento['DE_ICMS'].'% </span>';
                    echo                         '</p>';
                    echo                       '</div>';
                    echo                 '</div>';
                    echo                '<div class="input-group date form-row"  id="datetimepicker1">';
                    echo                        '<div class="col">';
                    echo                            '<p class="m-b-10" style="font-weight:bold">Validade Cotação: ';
                    echo                            '<span style="color:black;font-weight:normal">'.$this->registro_orcamento['DT_DataValidade']->format('d-m-Y').' </span>';
                    echo                            '</p>';                                                    
                    echo                        '</div>';
                    echo                    '</div>';
                    echo                '<div class="form-row">';
                    echo                    '<div class="col">';
                    echo                        '<p class="m-b-10" style="font-weight:bold">Informações ao comprador: ';
                    echo                            '<span style="color:black;font-weight:normal">'.$this->registro_orcamento['DE_InfComprador'].' </span>';
                    echo                        '</p>';
                    echo                     '</div>';
                    echo                '</div>';
                    echo                '<div class="form-row">';
                    echo                    '<div class="col">';
                    echo                        '<p class="m-b-10" style="font-weight:bold">Condição de Pagamento:</p>';
                    echo                        "<select name=condPagamento class=form-control>";
                    echo                            "<option selected>".$this->registro_orcamento['DE_FormaPagamento']."</option>";
                    echo                        "</select>" ;
                    echo                    '</div>';
                    echo                '</div>';
                    echo            '<div class="modal-footer">';
                    echo                '<button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>';
                    echo            '</div> '; 
                    echo            '</div>';
                    echo         '</div>';
                    echo      '</form>';
                    echo    '</div>';
                    echo '</div>';*/

                    //Modal cotação não cotada
                    echo "<div class='modal fade bd-example-modal-lg' id=Cotar".$this->registro_orcamento['ID_Cotacao']." tabindex='-1' role='dialog' aria-labelledby='myModalLabel'>";
                    echo    '<div class="modal-dialog modal-lg" role="document">';
                    echo        '<form action=services/cotacao.php type=POST>';
                    echo         '<div class="modal-content br-15">';
                    echo            '<div class="modal-header">';
                    echo                '<h4 class="modal-title" id="myModalLabel" style="text-align:center!important;">ID:'.$this->registro_orcamento['ID_Cotacao'].'</h4>';
                    echo               '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                    echo            '</div>';
                    echo            '<div class="modal-body">';
                    echo                    "<input type=hidden name='idFornecedor' value=".$this->ID_VenFor." >";
                    echo                '<input type=hidden name=ID_Cotacao value='.$this->registro_orcamento['ID_Cotacao'].'>';
                    echo                '<p class="m-b-10"><b style="font-weight: bold">Descrição:</b> '.$this->registro_orcamento['DE_Descricao'].'</p>';
                    echo                '<p class="m-b-10"><b style="font-weight: bold">Quantidade:</b> '.$this->registro_orcamento['DE_Qtde'].'</p>';
                    echo                '<div class="form-row">';
                    echo                    '<div class="col">';
                    echo                        '<p class="m-b-10" style="font-weight:bold">Preço</p>';
                    echo                        '<input type="text" name=precoCotacao class="form-control cotacaoInput" placeholder="Preço do material" required>';
                    echo                     '</div>';
                    echo                      '<div class="col">';
                    echo                         '<p class="m-b-10" style="font-weight:bold">Entrega dias</p>';
                    echo                         '<input type="text" name=diasEntrega class="form-control cotacaoInput" placeholder="Dias para ser entregue" required>';
                    echo                       '</div>';
                    echo                 '</div>';
                    echo                '<div class="form-row">';
                    echo                    '<div class="col">';
                    echo                        '<p class="m-b-10" style="font-weight:bold">IPI</p>';
                    echo                        '<input type="text" name=ipi class="form-control cotacaoInput" placeholder="Digite o IPI" >';
                    echo                     '</div>';
                    echo                      '<div class="col">';
                    echo                         '<p class="m-b-10" style="font-weight:bold">ICMS/ISS</p>';
                    echo                         '<input type="text" name=icms class="form-control cotacaoInput" placeholder="Digite o ICMS/ISS" >';
                    echo                       '</div>';
                    echo                 '</div>';
                    echo                '<div class="input-group date form-row"  id="datetimepicker1">';
                    echo                        '<div class="col">';
                    echo                            '<p class="m-b-10" style="font-weight:bold">Validade Cotação</p>';
                    echo                            '<input type="date" name="dataValidade" class="form-control cotacaoInput" placeholder="Validade da Cotação" required> ';
                    echo                        '</div>';
                    echo                    '</div>';
                    echo                '<div class="form-row">';
                    echo                    '<div class="col">';
                    echo                        '<p class="m-b-10" style="font-weight:bold">Informações ao comprador</p>';
                    echo                        '<input type="text" name=infComprador class="form-control cotacaoInput" placeholder="Digite informações ao comprador">';
                    echo                     '</div>';
                    echo                '</div>';
                    echo                '<div class="form-row">';
                    echo                    '<div class="col">';
                    echo                        '<p class="m-b-10" style="font-weight:bold">Condição de Pagamento</p>';
                    echo                        "<select name=condPagamento class=form-control>";
                    echo                            "<option name='A VISTA'>A VISTA</option>";
                    echo                            "<option name='10 DDL'>10 DDL</option>";
                    echo                            "<option name='90 DDL'>90 DDL</option>";
                    echo                            "<option name='14/21 DDL'>14/21 DDL</option>";
                    echo                            "<option name='14/21/42 DDL'>14/21/42 DDL</option>";
                    echo                            "<option name='14/28 DDL'>14/28 DDL</option>";
                    echo                            "<option name='15 DDL'>15 DDL</option>";
                    echo                            "<option name='15/30 DDL'>15/30 DDL</option>";
                    echo                            "<option name='15/30/45 DDL'>15/30/45 DDL</option>";
                    echo                            "<option name='15/30/45/60 DDL'>15/30/45/60 DDL</option>";
                    echo                            "<option name='21/28 DDL'>21/28 DDL</option>";
                    echo                            "<option name='21/35 DDL'>21/35 DDL</option>";
                    echo                            "<option name='21/28/35 DDL'>21/28/35 DDL</option>";
                    echo                            "<option name='21/28/35/42 DDL'>21/28/35/42 DDL</option>";
                    echo                            "<option name='21/35/42 DDL'>21/35/42 DDL</option>";
                    echo                            "<option name='21/35/45 DDL'>21/35/45 DDL</option>";
                    echo                            "<option name='21/35/56 DDL'>21/35/56 DDL</option>";
                    echo                            "<option name='28/42/56 DDL'>28/42/56 DDL</option>";
                    echo                            "<option name='28 DDL'>28 DDL</option>";
                    echo                            "<option name='45 DDL' selected>45 DDL</option>";
                    echo                            "<option name='30/60 DDL'>30/60 DDL</option>";
                    echo                            "<option name='30 DDL'>30 DDL</option>";
                    echo                            "<option name='30/45 DDL'>30/45 DDL</option>";
                    echo                            "<option name='30/60/90 DDL'>30/60/90 DDL</option>";
                    echo                            "<option name='40 DDL'>40 DDL</option>";
                    echo                            "<option name='28/42 DDL'>28/42 DDL</option>";
                    echo                            "<option name='30/40 DDL'>30/40 DDL</option>";
                    echo                            "<option name='45/60 DDL'>45/60 DDL</option>";
                    echo                            "<option name='30/40 DDL'>30/40 DDL</option>";
                    echo                            "<option name='28/35 DDL'>28/35 DDL</option>";
                    echo                            "<option name='30/40/50 DDL'>30/40/50 DDL</option>";
                    echo                            "<option name='30/45/60 DDL'>30/45/60 DDL</option>";
                    echo                            "<option name='15/30/45/60/75 DDL'>15/30/45/60/75 DDL</option>";
                    echo                            "<option name='21 DDL'>21 DDL</option>";
                    echo                            "<option name='28/35/42 DDL'>28/35/42 DDL</option>";
                    echo                            "<option name='7 DDL'>7 DDL</option>";
                    echo                            "<option name='40/60 DDL'>40/60 DDL</option>";
                    echo                            "<option name='5 DDL'>5 DDL</option>";
                    echo                            "<option name='30/40/50/60 DDL'>30/40/50/60 DDL</option>";
                    echo                            "<option name='30/60/90/120 DDL'>30/60/90/120 DDL</option>";
                    echo                            "<option name='60/90 DDL'>60/90 DDL</option>";                            
                    echo                        "</select>" ;
                    echo                    '</div>';
                    echo                '</div>';
                    echo            '<div class="modal-footer">';
                    echo                '<button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>';
                    echo                '<button type="submit" class="btn btn-success" id="a" value="Salvar">Salvar</button>';
                    echo            '</div> '; 
                    echo            '</div>';
                    echo         '</div>';
                    echo      '</form>';
                    echo    '</div>';
                    echo '</div>';

                    //Modal Declinado
                    echo '<div class="modal fade" id=oi'.$this->registro_orcamento['ID_Cotacao'].' tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">';
                    echo    '<div class="modal-dialog modal-dialog-centered" role="document">';
                    echo        '<form action=services/cotacao.php type=POST>';
                    echo            '<div class="modal-content">';            
                    echo                '<div class="modal-header">';
                    echo                    '<h5 class="modal-title text-center" id="exampleModalLongTitle"><strong>Declinação de Produto</strong></h5>';
                    echo                    '<button type="button" class="close" data-dismiss="modal" aria-label="Close">';
                    echo                    '<span aria-hidden="true">&times;</span>';
                    echo                    '</button>';
                    echo            '</div>';
                    echo            '<div class="modal-body">';
                    echo                    "<input type=hidden name='codPost' value='10'>";
                    echo                    "<input type=hidden name='idCotacao' value=".$this->registro_orcamento['ID_Cotacao']." >";
                    echo                    "<input type=hidden name='idFornecedor' value=".$this->ID_VenFor." >";
                    echo                    '<label for="exampleInputEmail1">Por que está Declinando?</label>';
                    echo                    '<input type="text" class="form-control text-black" name="declinadoTexto" id="declinadoTexto" value="Não Comercializamos este material" required>';
                    echo            '</div>';
                    echo            '<div class="modal-footer">';
                    echo                '<button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>';
                    echo                '<button type="submit" class="btn btn-success" name="salvarDeclinado" id="salvarDeclinado" value="Salvar">Salvar</button>';
                    echo            '</div> ';           
                    echo           '</div>';
                    echo      '</form>';
                    echo    '</div>';
                    echo '</div>';      
                
            }    
            
        }

        public function pesquisar_orcamento(){
            $this->sql_conexao();
            $this->get_IdVend();
            //$this->listar();

            $ID_Solicitacao = '';
            $DE_Descricao = '';

            if(!empty($_POST['ID_Solicitacao'])){
                $ID_Solicitacao = $_POST['ID_Solicitacao'];
                //print_r("cheguei no id ");
            }

            if(!empty($_POST['DE_Descricao'])){
                $DE_Descricao = $_POST['DE_Descricao'];
                //print_r("cheguei na descricao " . $DE_Descricao);
            }
            
            
            if(!empty($ID_Solicitacao)){
                $this->sql_orcamento = "SELECT * from dbo.PT_Cotacao WHERE ID_Cotacao = '$ID_Solicitacao' and ID_Fornecedor =  $this->ID_VenFor "; 

            }elseif(!empty($DE_Descricao)){
                $this->sql_orcamento = "SELECT * from dbo.PT_Cotacao WHERE DE_Descricao like '%$DE_Descricao%' AND DE_SolicitacaoStatus = 1 and ID_Fornecedor =  $this->ID_VenFor  "; 

            }

            $this->query_orcamento = sqlsrv_query($this->con, $this->sql_orcamento); 

            //Resultado Sql do listar banco
            //print_r($this->sql_orcamento);
            //var_dump($this->query_orcamento );
            
            $this->loop_orcamento();


        }
        
        public function editarMeusDados(){
            $this->sql_conexao();

            $this->ID_Usuario = base64_decode($_POST['ID_Usuario']);
            $this->DE_Senha = $_POST['DE_Senha'];

            $sql = "UPDATE dbo.PT_Usuario  SET  DE_Senha='$this->DE_Senha' WHERE ID_Usuario='$this->ID_Usuario'";

            $result = sqlsrv_query($this->con, $sql);
            

            $linha = sqlsrv_rows_affected($result);             
            
            
    
            if($linha == true ) {
                header('Location: editar_dados.php?status=1&id=' . base64_encode($this->ID_Usuario) . ' ');exit;
            
            
            }else{
                echo
                "<script>   
                    alert('Falha ao alterar!');
                    
                </script>";
            
            }
        }

        public function listar_usuario(){
            $this->sql_conexao(); 
            $this->get_IdVend();
            $nome = '';
            $email = '';

            if(!empty($_POST['nome'])){
                $nome = $_POST['nome'];
                //print_r("cheguei no nome ");
            }

            if(!empty($_POST['email'])){
                $email = $_POST['email'];
                //print_r("cheguei no email ");
            }
            
            
            if(!empty($nome)){
                $this->sql_usuario = "SELECT * from dbo.PT_Usuario WHERE DE_RazaoSocial like '$nome%'"; 

            }elseif(!empty($email)){
                $this->sql_usuario = "SELECT * from dbo.PT_Usuario WHERE DE_Email like '$email%'"; 

            }else{
                $this->sql_usuario =  "SELECT TOP 100 * FROM  dbo.PT_Usuario";

            }
            $this->query_usuario = sqlsrv_query($this->con, $this->sql_usuario); 

            //Resultado Sql do listar banco
            //var_dump($this->query_usuario);
            //print_r($_SESSION['ID_Perfil']);
            
            while($this->registro_usuario = sqlsrv_fetch_array($this->query_usuario)){
                
                
                echo "<tr>";
                
                echo "<td>" . $this->registro_usuario['DE_RazaoSocial'] . "</td>";
                echo "<td>" . $this->registro_usuario['DE_Email'] . "</td>";
                
                if ($_SESSION['ID_Perfil'] == 4){
                    echo "<td> ***** </td>";
                }elseif($_SESSION['ID_Perfil'] == 1){
                    echo "<td>" . $this->registro_usuario['DE_Senha'] . "</td>";

                }else{
                    "<td> ***** </td>";
                }
                
                if($this->registro_usuario['IS_Ativo'] == 1){
                    echo "<td>Ativo </td>";
                }else{
                    echo "<td>Desativado </td>";
                }
                
                echo "<td>";
                if($_SESSION['ID_Perfil'] == 1){
                    echo "<button type=button class='btn btn-xs btn-warning' '> <a href=editar_cliente.php?ID_Usuario=" . $this->registro_usuario['ID_Usuario'] . "  style='color: inherit;'</a> Editar</button>";

                }else{
                    echo "<a href=services/envia_senha.php?ID=" . $this->registro_usuario['ID_Usuario'] .  " ><button class='btn btn-xs btn-success'>Enviar Senha</button> </a> ";

                }
                echo "</td>";
                
                echo "<td>" .  "</td>";
                
                echo "</tr>";
            }  
            
            
        } 

        public function cadastrarCliente(){
            $this->sql_conexao();
            $this->DE_RazaoSocial = $_POST['DE_RazaoSocial'];
            $this->DE_Email = $_POST['DE_Email'];
            $this->ID_Perfil = $_POST['ID_Perfil'];
            $this->DE_Senha = $_POST['DE_Senha'];
            
            if($this->ID_Perfil  == 0){
                $this->ID_Perfil = 2;
            }
            
            $sql  = " DECLARE @ID INT  SELECT @ID = MAX(ID_Usuario)+1 FROM dbo.PT_Usuario  ";
            $sql .= "DECLARE @NOW DATETIME SET @NOW = GETDATE()	";
            $sql .= "DECLARE @ID_VENFOR INT SELECT @ID_VENFOR = MAX(ID_VenFor)+1 FROM dbo.PT_Usuario";
            $sql  .= " INSERT INTO dbo.PT_Usuario(ID_Usuario,IS_Ativo,DT_Inclusao, DE_RazaoSocial, DE_Email, ID_Perfil, DE_Senha, ID_VenFor) VALUES ( @ID, 1, @NOW,  '$this->DE_RazaoSocial', '$this->DE_Email', '$this->ID_Perfil', '$this->DE_Senha', @ID_VENFOR) ";
            
            $result = sqlsrv_query($this->con, $sql);

            //PRINT_R($sql);



            if($result == true){
                echo
                "<script>  
                
                    alert('Cadastrado com sucesso! Os dados foram enviados para o e-mail cadastrado');
                    window.location.href='cadastro_clientes.php';
                    
                </script>";
                
                
                $msg = "    
                    Parabens! Voce agora tem acesso ao portal de Compras

                    Para ser feito o primeiro acesso sera necessario inserir uma nova senha apos o Login

                    Seu e-mail = $this->DE_Email
                    Sua senha = $this->DE_Senha
                    
                    Para acessar acesse este link: http://localhost:8089/portal/portal_php/portal/login.php.";

                
                
                mail("$this->DE_Email","Acesso ao Portal de Compras",$msg, "From: sistema@fresadorasantana.com.br");

            }else{
                echo
                "<script>   
                    alert('Falha ao cadastrar!');

                </script>";
            }

        }
        
        public function editarCliente(){
            $this->sql_conexao();
            $this->ID_Usuario = $_POST['ID_Usuario'];
            $this->DE_RazaoSocial = $_POST['DE_RazaoSocial'];
            $this->DE_Email = $_POST['DE_Email'];
            $this->ID_Perfil = $_POST['ID_Perfil'];
            $this->IS_Ativo = $_POST['IS_Ativo'];
            $this->DE_Senha = $_POST['DE_Senha'];
            
            
            $sql = "UPDATE dbo.PT_Usuario  SET DE_RazaoSocial = '$this->DE_RazaoSocial', DE_Email = '$this->DE_Email', ID_Perfil = '$this->ID_Perfil', 
            IS_Ativo = '$this->IS_Ativo', DE_Senha='$this->DE_Senha' WHERE ID_Usuario='$this->ID_Usuario'";

            
            //print_r($sql);
            
            $result = sqlsrv_query($this->con, $sql);
            $linha = sqlsrv_rows_affected($result);             
            
    
            if($linha == true ) {
                echo
                "<script>   
                    alert('Alterado com sucesso!');
                    window.location.href='editar_cliente.php?ID_Usuario=$this->ID_Usuario';

                </script>";
                
            
            
            }else{
                echo
                "<script>   
                    alert('Falha ao alterar!');        
                </script>";
            
            }
        }

        public function criar_senha($id){
            $this->sql_conexao();

            $senha = $_POST['senha'];
            $confirmar_senha = $_POST['confirmar_senha'];
            
            if(!empty($id)){
                $id = base64_decode($_GET['id']);
            }                        

            //print_r($id);
            //print_r($senha);
            //print_r($confirmar_senha);

            if($senha != $confirmar_senha ){                
                header('Location: alterar_senha.php?erro=1&id=' . base64_encode($id) .'');exit;
            }

            $sql =  "UPDATE dbo.PT_Usuario  SET DE_Senha = '$senha', DE_Status = 1 WHERE ID_Usuario = '$id' ";
            //print_r($sql);

            $result = sqlsrv_query($this->con, $sql);
            $linha = sqlsrv_rows_affected($result);             
            
    
            if($linha == true ) {
                echo
                "<script>   
                    alert('Alterado com sucesso!');
                    window.location.href='login.php';

                </script>";
            
            }else{
                echo
                "<script>   
                    //alert('Falha ao alterar!');        
                </script>";
            
            }
        }

        public function msg_suporte(){
            $suporte = new SuporteController();
            $email = $_POST['email'];
            $assunto = $_POST['assunto_mensagem'];
            $mensagem = $_POST['corpo_mensagem'];
            $idFornecedor = $_POST['idFornecedor'];
            
            //print_r($assunto);
            //print_r($mensagem);
            //print_r($email);

            $msg = $mensagem;                       
            $msg .= " Para responder esta mensagem envie o e-mail para este destinatario= " . $email ;
        
            mail('sistema@fresadorasantana.com.br',$assunto,$msg, "From: sistema@fresadorasantana.com.br");

            $adicionarChamado = $suporte->AdicionarChamado($idFornecedor,$assunto,$mensagem);

            if($adicionarChamado == TRUE);{
                header('Location: suporte.php?idFornecedor='.base64_encode($idFornecedor).'&status=1&mail= ' . base64_encode($email) . ' ');
            }


            


        }

        
        
        public function getNome(){
            //Retornando o nome
            $this->Nome = " Sr. " . $this->registro['DE_RazaoSocial'];
            return $this->Nome;

            var_dump($this->Nome);


        }

        public function getEmail(){
            $this->Login = $this->registro['DE_Email'];
            return $this->Login;

        } 

        public function getSenha(){
            $this->Senha = $this->registro['DE_Senha'];
            return $this->Senha;

        }  

        public function logoff(){
            session_start();
            session_destroy();
            header('Location: login.php');

        }
        
        
        
    
}


if(!empty($_POST['cod_post'])){    

    $cod_post = $_POST['cod_post'];    
    $usuario = new usuario(); 
    if($cod_post == "2"){        
        $usuario->editarMeusDados();

    }elseif($cod_post == "3"){
        $usuario->editarCliente();

    }elseif($cod_post == "4"){
        $usuario->cadastrarCliente();
    }elseif($cod_post == "7"){
        $usuario->msg_suporte();
    }
    

    

}




?>


