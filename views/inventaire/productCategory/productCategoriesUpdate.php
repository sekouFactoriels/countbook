<?php require_once('script/productCategory_js.php'); ?>
<div class="modal fade" id="updateProductCategory" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="newProdCat">Modification Categorie</h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label class="col-sm-4 control-label"><?= Yii::t('app', 'statut') ?> : <span class="asterisk">*</span>
          </label>
          <div class="col-sm-8">
            <select class="form-control" name="statutCatUpdate" id="statutCatUpdate">
              <option value="1"><?= Yii::t('app', 'active') ?></option>
              <option value="2"><?= Yii::t('app', 'suprime') ?></option>
            </select>
          </div>
        </div>

        <div class="form-group">
          <label class="col-sm-4 control-label">Nom de Categorie : <span class="asterisk">*</span>
          </label>
          <div class="col-sm-8">
            <input type="text" value="" class="form-control" name="productCatNameUpdate" id="productCatNameUpdate" autocomplete="off" />
          </div>
        </div>

        <div class="form-group">
          <label class="col-sm-4 control-label">Description :
          </label>
          <div class="col-sm-8">
            <textarea rows="3" type="text" class="form-control" name="productCatDescUpdate" id="productCatDescUpdate" autocomplete="off"></textarea>
          </div>
        </div>

      </div>

      <div class="modal-footer">
        <a href="javascript:;" type="button" onClick="$('#productCatName').val($('#productCatNameUpdate').val());  $('#productCatDesc').val($('#productCatDescUpdate').val()); $('#statutCat').val($('#statutCatUpdate').val()); $('#action_key').val('<?= md5("inventaire_updateCat") ?>'); $('#listeCategories').submit();" class="btn btn-circle btn-success"><i class="glyphicon glyphicon-saved"></i>&nbsp;Oui</a>
        <button type="button" onClick="cancel()" class="btn btn-circle btn-warning" data-dismiss="modal"><i class="glyphicon glyphicon-remove-circle"></i>&nbsp;<?= Yii::t('app', 'fermer'); ?></button>
      </div>
    </div>
  </div>
</div>