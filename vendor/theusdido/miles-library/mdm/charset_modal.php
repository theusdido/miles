<!-- Button trigger modal -->
<a type="button" class="btn btn-link" data-toggle="modal" data-target="#<?=$id_modal?>">Mostrar arquivos</a>

<!-- Modal -->
<div class="modal fade" id="<?=$id_modal?>" tabindex="-1" role="dialog" aria-labelledby="<?=$id_modal?>Label">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="<?=$id_modal?>lLabel"><?=$local?></h4>
      </div>
      <div class="modal-body">
        <ul class="list-group">
          <?php
            $sql = "SELECT path,file FROM td_charsetfiles WHERE charset = $id;";
            $dataset    = $conn->query($sql);
            while($row = $dataset->fetch(PDO::FETCH_ASSOC)){
              echo '<li class="list-group-item">' . $row['path'] . '  <span class="badge">' . $row['file'] . '</span></li>';
            }
          ?>
        </ul>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>