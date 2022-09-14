<div class="modal fade" id="newFiliale" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="newProdCat"><?= Yii::t('app', 'nfiliale') ?></h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label class="col-sm-3 control-label"><?= Yii::t('app', 'filial') ?> : <span class="asterisk">*</span>
          </label>
          <div class="col-sm-8">
            <select class="form-control" name="forfait" id="forfait">
              <option value="0"><?= Yii::t('app', 'slectOne') ?></option>
              <option value="1"><?= Yii::t('app', 'essentiel') ?></option>
              <option value="2"><?= Yii::t('app', 'partenaire') ?></option>
              <option value="3"><?= Yii::t('app', 'premium') ?></option>
              <option value="4"><?= Yii::t('app', 'special') ?></option>
              <option value="5"><?= Yii::t('app', 'standard') ?></option>
            </select>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <a href="javascript:;" type="button" onClick="enrgmotifenrgclient()" class="btn btn-circle btn-success"><i class="glyphicon glyphicon-saved"></i>&nbsp;Oui</a>
        <button type="button" class="btn btn-circle btn-warning" data-dismiss="modal"><i class="glyphicon glyphicon-remove-circle"></i>&nbsp;Non</button>
      </div>
    </div>
  </div>
</div>
<?php
require_once('script/newmotif_js.php');
?>