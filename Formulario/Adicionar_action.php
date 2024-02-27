<?php
session_start();
require 'configuracao.php';
$id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
$dsc = filter_input(INPUT_POST, 'dsc', FILTER_SANITIZE_SPECIAL_CHARS);
$ses = filter_input(INPUT_POST, 'ses', FILTER_SANITIZE_NUMBER_INT);
$dep = filter_input(INPUT_POST, 'menu',FILTER_SANITIZE_SPECIAL_CHARS);
    
switch ($dep) {
        case '1':
            $dep ='Adiministrativo';
            break;
        case '2':
            $dep ='Agricola';
            break;
        case '3';
            $dep ='Comercial';
            break;
        case '4':
            $dep ='Contabil';
            break;
        case '5':
            $dep ='Financeiro';
            break;
        case '6':
            $dep ='Fiscal/Faturamento';
            break;
        case '7':
            $dep ='RH';
            break;
        case '8':
            $dep ='Suprimento';
            break;
        }
// Verificar se os campos estão preenchidos 
if ($id && $dsc && $ses && $dep) {

    // Preparar a consulta SQL
    $sql = $pdo->prepare("SELECT * FROM arquivo WHERE ID_ARQUIVO = :id ");
    $sql->bindValue(':id', $id);
    $sql->execute();

    // Verificar se o ID já existe
    if ($sql->rowCount() === 0) {

        // Inserir os dados no banco
        $sql = $pdo->prepare("INSERT INTO arquivo (ID_ARQUIVO, data_insert, SESSAO, DEPARTAMENTO, DESCRICAO) VALUES (:id, now(), :ses, :menu, :dsc)");
        $sql->bindValue(':id',$id);
        $sql->bindValue(':dsc',$dsc);
        $sql->bindValue(':ses',$ses);
        $sql->bindValue(':menu',$dep);
        $sql->execute();
        

        // Verificar se a consulta foi bem-sucedida
        if ($sql->rowCount() > 0) {  
          
            $_SESSION['aviso'] = 'Dados enviados com sucesso! ✓';
            $classe = 'aviso.sucesso';
            header('Location: index.php');
            exit;
        } else {
            
            $_SESSION['aviso'] = 'Erro ao enviar dados! ❌';
            $classe = 'aviso.erro';
            header('Location: index.php');
            exit;
        }

    } else {
        
            $_SESSION['aviso'] = 'Erro ao enviar dados! ❌';
            $classe = 'aviso.erro';
            header('Location: index.php');
            exit;
    }
} 
?>
