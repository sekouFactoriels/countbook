<?php require_once('script/productGroup_js.php'); ?>
<div class="modal fade" id="newProductGroup" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="newProdCat">Nouveau Group</h4>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <label class="col-sm-4 control-label">Nom du Group : <span class="asterisk">*</span>
                </label>
                <div class="col-sm-8">
                  <input type="text" value="" class="form-control" name="productGroupName" id="productGroupName"  autocomplete="off"/>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-4 control-label">Description :
                </label>
                <div class="col-sm-8">
                  <textarea rows="3" type="text" class="form-control" name="productGroupDesc" id="productGroupDesc"  autocomplete="off"></textarea>
                </div>
              </div>

            </div>

            <div class="modal-footer">
                <a href="javascript:;" type="button" onClick="productGroup_np()" class="btn btn-circle btn-success"><i class="glyphicon glyphicon-saved"></i>&nbsp;<?= Yii::t('app','ajouter');?></a>
                <button type="button" onClick="cancel()" class="btn btn-circle btn-warning" data-dismiss="modal"><i class="glyphicon glyphicon-remove-circle"></i>&nbsp;<?= Yii::t('app','fermer');?></button>
            </div>
        </div>
    </div>
</div>
