<div class="panel panel-default panel-lista-permissoes" id="permissao-panel-relatorio">
    <div class="panel-heading">
    <h3 class="panel-title">Relatório</h3>
    </div>
    <div class="panel-body">
    <table class="table table-bordered table-hover" id="lista-relatorios">
    <thead>
        <tr>
            <th width="60%">Descrição</th>				
            <th width="10%">
                <center>								
                    <button data-op="menu-menu" type="button" class="btn btn-default btn-all-tela" aria-label="Left Align">
                        <span class="fas fa-check-square" aria-hidden="true"></span>
                    </button>								
                </center>
            </th>
        </tr>
    </thead>
        <tbody>
            <?php
                $sql = "
                    SELECT a.id,b.descricao 
                    FROM td_menu a
                    INNER JOIN td_relatorio b ON a.entidade = b.entidade
                    WHERE a.tipomenu = 'relatorio'
                ;";
                $rs = $conn->query($sql);
                while ($linha = $rs->fetch()){
                    $descricao = utf8_encode($linha["descricao"]);
                    echo "	<tr menuid='".$linha["id"]."'>";
                    echo "		<td><small>".$descricao."</small></td>";
                    echo "		<td><center><input type='checkbox' onclick=setaPermissao(this,'menu'); id='".$linha["id"]."' data-op='menu' /></center></td>";
                    echo "	</tr>";
                }
            ?>
        </tbody>
    </table>
    </div>
</div>