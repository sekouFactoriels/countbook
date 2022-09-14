<?php require_once('script/productUdms_js.php'); ?>
<div class="modal fade" id="updateProductUdm" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="newProdCat">Modification U.V.</h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label class="col-sm-4 control-label">Nom de l'U.V. : <span class="asterisk">*</span>
          </label>
          <div class="col-sm-8">
            <input type="text" value="" class="form-control" name="productUdmNameUpdate" id="productUdmNameUpdate" autocomplete="off" />
          </div>
        </div>

        <div class="form-group">
          <label class="col-sm-4 control-label">Description :
          </label>
          <div class="col-sm-8">
            <textarea rows="3" type="text" class="form-control" name="productUdmDescUpdate" id="productUdmDescUpdate" autocomplete="off"></textarea>
          </div>
        </div>

      </div>

      <div class="modal-footer">
        <a href="javascript:;" type="button" onClick="$('#productUdmName').val($('#productUdmNameUpdate').val());  $('#productUdmDesc').val($('#productUdmDescUpdate').val()); $('#action_key').val('<?= md5("inventaire_updateUdm") ?>'); $('#listeUdms').submit();" class="btn btn-circle btn-success"><i class="glyphicon glyphicon-saved"></i>&nbsp;Oui</a>
        <button type="button" onClick="cancel()" class="btn btn-circle btn-warning" data-dismiss="modal"><i class="glyphicon glyphicon-remove-circle"></i>&nbsp;<?= Yii::t('app', 'fermer'); ?></button>
      </div>
    </div>
  </div>
</div>