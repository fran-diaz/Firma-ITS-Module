<!-- Modal -->
<div class="modal fade" data-backdrop="static" id="firma__modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Añadir firma <?=$_REQUEST['type']?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
			<canvas class="firma__canvas w-100"></canvas>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-secondary firma__btn-clear" data-action="clear">Limpiar</button>
        <button type="button" class="btn btn-primary firma__btn-save"  data-action="save-png" data-type="<?=$_REQUEST['type']?>">Guardar firma</button>
      </div>
    </div>
  </div>
</div>