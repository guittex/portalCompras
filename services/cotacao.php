<?php
include_once("sql_conexao.php");


class cotacao extends conexao
{
    public function declinadoCotacao($declinadoTexto,$idCotacao,$idFornecedor)
    {
        $this->sql_conexao();

        $sql = "UPDATE dbo.PT_Cotacao SET DE_SolicitacaoStatus = 5 ,DE_DeclinadoTexto = '$declinadoTexto', DT_DataRespondido = GETDATE(),
        DE_Preco = 0, DE_Ipi = 0,  DE_DeclinadoSite = 1,  DE_Portal = 0 where ID_Cotacao = '$idCotacao' and ID_Fornecedor = '$idFornecedor'  ";

        $query = sqlsrv_query($this->con, $sql);
        
        if($query == true)
        {
            header('Location: http://localhost:8089/portal/portal_php/portal/index.php?alert=success&msg=DECLINADO COM SUCESSO');
            exit();

        }else{
            header('Location: http://localhost:8089/portal/portal_php/portal/index.php?alert=danger&msg=ERRO AO DECLINAR');
            exit();


        }
    }

    public function validateCotacao($ID_Fornecedor,$ID_Cotacao,$precoCotacao, $diasEntrega, $ipi, $icms, $dataValidade, $infComprador, $condPagamento)
    {
        date_default_timezone_set('America/Sao_Paulo');

        $date = date_create($dataValidade);
        $dateToValidate = date_format($date, 'Ymd');
        $dataAtual = date('Ymd', time());
        
        //echo $dataAtual, $dateToValidate;

        $arrayValidate = [
            'PrecoCotacao' => $precoCotacao, 
            'DiasEntrega' => $diasEntrega, 
            'CondPagamento' => $condPagamento
        ];
        
        $arrayValidateType = [
            'Preco da Cotacao' => floatval($precoCotacao), 
            'Entrega dias' => floatval($diasEntrega), 

        ];

        if($dateToValidate < $dataAtual){
            header('Location: http://localhost:8089/portal/portal_php/portal/index.php?alert=danger&msg=ERRO! Data de validade de cotação menor que a atual ');
            exit();
        }

        foreach($arrayValidate as $key => $value )
        {
            if($value == null){
                header('Location: http://localhost:8089/portal/portal_php/portal/index.php?alert=danger&msg=EXISTEM CAMPOS VAZIOS NÃO PREENCHIDO NA COTAÇÃO ID = '.$ID_Cotacao.' ');
                exit();
            }
        
        }

        foreach($arrayValidateType as $key => $value ){
            //echo $key . ' = '  .$value. "</br>";
            if($value == 0){
                //echo $key . ' = '  .$value. "</br>";
                header('Location: http://localhost:8089/portal/portal_php/portal/index.php?alert=danger&msg=O campo "'.$key.'" não pode conter letras na cotação ');
                exit();
            }
        }
        
        cotacao::inserirCotacao($ID_Fornecedor,$ID_Cotacao,floatval($precoCotacao), floatval($diasEntrega), floatval($ipi), floatval($icms), $dataValidade, $infComprador, $condPagamento);
        
        
    }

    public function inserirCotacao($ID_Fornecedor,$ID_Cotacao,$precoCotacao, $diasEntrega, $ipi, $icms, $dataValidade, $infComprador, $condPagamento)
    {
        //echo($idFornecedor);
        $this->sql_conexao();

        $sql = "UPDATE dbo.PT_Cotacao SET DE_SolicitacaoStatus = 2 ,DT_DataRespondido = GETDATE(), DE_Preco = '$precoCotacao',DE_Ipi = '$ipi',
        DE_ICMS = '$icms',  DE_DeclinadoSite = 0,  DE_Portal = 0, DE_EntregaDias = '$diasEntrega', DT_DataValidade = '$dataValidade', 
        DE_InfComprador = '$infComprador', DE_FormaPagamento = '$condPagamento'
        where ID_Cotacao = '$ID_Cotacao' and ID_Fornecedor = '$ID_Fornecedor'  ";

        $query = sqlsrv_query($this->con, $sql);
        
        if($query == true)
        {
            header('Location: http://localhost:8089/portal/portal_php/portal/index.php?alert=success&msg=COTAÇÃO FEITA COM SUCESSO');

        }else{
            header('Location: http://localhost:8089/portal/portal_php/portal/index.php?alert=danger&msg=ERRO AO EFETUAR A COTAÇÃO, ENTRE EM CONTATO COM O TI');

        }
    }

}

$cotacao = new cotacao();


if(!empty($_GET['idCotacao'])){
    $idCotacao = $_GET['idCotacao'];

    $declinadoTexto = $_GET['declinadoTexto'];
    
    $idFornecedor = $_GET['idFornecedor'];

    $cotacao->declinadoCotacao($declinadoTexto,$idCotacao, $idFornecedor);
}

if(!empty($_GET['ID_Cotacao'])){
    $ID_Fornecedor = $_GET['idFornecedor'];
    $ID_Cotacao = $_GET['ID_Cotacao'];
    $precoCotacao = $_GET['precoCotacao'];
    $diasEntrega = $_GET['diasEntrega'];
    $ipi = $_GET['ipi'];
    $icms = $_GET['icms'];
    $dataValidade = $_GET['dataValidade'];
    $infComprador = $_GET['infComprador'];
    $condPagamento = $_GET['condPagamento'];

    $cotacao->validateCotacao($ID_Fornecedor,$ID_Cotacao,$precoCotacao,$diasEntrega,$ipi,$icms,$dataValidade,$infComprador,$condPagamento);
}

?>
