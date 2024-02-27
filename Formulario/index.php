<label><?php
session_start();

if(isset($_SESSION['aviso'])){
    $aviso = $_SESSION['aviso'];
    unset($_SESSION['aviso']);

    $tipo = "";
  if (strpos($aviso, "erro") !== false) {
    $tipo = "erro";
  } else if (strpos($aviso, "sucesso") !== false) {
    $tipo = "sucesso";
  } else {
    $tipo = "aviso"; 
  }
}
  $conn = mysqli_connect("127.0.0.1:3306", "root", "", "arquivo_3coq");
  
  // Recuperar os últimos 5 arquivos adicionados do banco de dados
  $sql = "SELECT ID_ARQUIVO, date_format(data_insert,'%d/%m%/%Y %H:%i:%S') data_insert, SESSAO, DEPARTAMENTO, DESCRICAO FROM arquivo ORDER BY ID_ARQUIVO DESC LIMIT 5";
  $result = mysqli_query($conn, $sql);

// Verifique se há erros de conexão com o banco de dados
  if (!$result) {
    echo "Error: " . mysqli_error($conn);
    exit;
}
  mysqli_close($conn);
?></label>


<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="stylesheet" href="style.css">

       
        <title>Formulario Arquivo</title>
        </head> 
        <body>
    
        <section>
            <div class="center-form">
            
                <form method="POST" action="Adicionar_action.php" >
               
                <?php if (isset($aviso) ): ?>
                <div class="<?php echo $tipo; ?>">
                <strong><?php echo htmlspecialchars($aviso); ?></strong>
                </div>
                <?php endif; ?>
                <label><strong>Formulario de arquivo</strong>
                
                <label for="num">ID arquivo :</label>
                <input type="number" name="id" class="coluna" id="id" min="0"  value="" required>
                <label for="dsc">Descrição:</label>
                <input type="text" name="dsc" class="num" id="dsc" maxlength="30" value="" required>
                <label for="ses">Sessão:</label>
                <input type="number" name="ses" class="num" id="ses"  min="0" maxlength="10"  value="" required>
                <label for="menu">Departamento:</label>
                <select id="menu" name="menu" required> 
                    <option value="">Selecione</option>
                    <option value="1">Adiministrativo</option>
                    <option value="2">Agricola</option>
                    <option value="3">Comercial</option>
                    <option value="4">Contabil</option>
                    <option value="5">Financeiro</option>
                    <option value="6">Fiscal/Faturamento</option>
                    <option value="7">RH</option>
                    <option value="8">Suprimento</option>
                </select>
                <input type="submit" value="Adicionar">
                </section></form>
                </label>
                
                <div class="box">
                <h2>Últimos 5 Arquivos Adicionados</h2>

                <?php if (mysqli_num_rows($result) > 0): ?>
                <table class="highlighted">
                <thead>
                  <tr>
                  <th>ID</th>
                  <th>Descrição</th>
                  <th>Sessão</th>
                  <th>Departamento</th>
                  <th>DATA</th>
                  </tr>
                </thead>
                <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?= $row['ID_ARQUIVO']; ?></td>
                   <td class="descricao">
                      <?php
                      $descricao = $row['DESCRICAO'];
                      $limite = 20; // Ajuste o limite de caracteres
                      if (strlen($descricao) > $limite) {
                        echo substr($descricao, 0, $limite) . "...";
                     } else {
                        echo $descricao;
                      }
                      ?>
                      <span class="tooltiptext"><?php echo $descricao; ?></span>
                    </td>
                    <td class="sessao">
                    <?php
                    $sessao = $row['SESSAO'];
                    $limiteSessao = 10; // Ajuste o limite de caracteres
                    if (strlen($sessao) > $limiteSessao) {
                      echo substr($sessao, 0, $limiteSessao) . "...";
                    } else {
                      echo $sessao;
                    }
                    ?>
                    <span class="tooltiptext"><?php echo $sessao; ?></span>
                    </td>
                    <td><?= $row['DEPARTAMENTO']; ?></td>
                    <td><?= $row['data_insert']; ?></td>
                    </tr>
                    <?php endwhile; ?>
                    </tbody>
                    </table>
                    <?php else: ?>
                    <p>Nenhum arquivo foi adicionado ainda.</p>
                    <?php endif; ?>

<style>
  .descricao, .sessao, .ID_ARQUIVO {
    max-width: 150px; /* Limita a largura da descrição e sessão */
    white-space: nowrap; /* Evita quebra de linha */
    overflow: hidden; /* Oculta o texto excedente */
    text-overflow: ellipsis; /* Adiciona reticências "..." */
  }

  .tooltiptext {
    visibility: hidden; /* Oculta o tooltip por padrão */
    position: absolute; /* Posicionamento relativo à célula */
    background-color: #333; /* Cor de fundo */
    color: #fff; /* Cor do texto */
    padding: 5px; /* Espaçamento interno */
    border-radius: 6px; /* Borda arredondada */
    z-index: 1; /* Posiciona o tooltip acima de outros elementos */
  }

  .descricao:hover .tooltiptext,
  .sessao:hover .tooltiptext {
    visibility: visible; /* Mostra o tooltip ao passar o mouse */
  }
</style>
</style> </div>
  </body>

</html>
