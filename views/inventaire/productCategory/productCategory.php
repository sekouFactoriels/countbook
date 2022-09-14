<?php require_once('script/productCategory_js.php'); ?>
<div class="modal fade" id="newProductCategory" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="newProdCat"><?= yii::t("app","new_cat")?></h4>
            </div>

            <div class="modal-body">
              <div class="form-group">
                <label class="col-sm-4 control-label"><?= yii::t("app","lbl")?> : <span class="asterisk">*</span>
                </label>
                <div class="col-sm-8">
                  <input type="text" value="" class="form-control" name="productCatNames" id="productCatNames"   autocomplete="off"/>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-4 control-label"><?= yii::t("app","desc")?> :
                </label>
                <div class="col-sm-8">
                  <textarea rows="3" type="text" class="form-control" name="productCatDescs" id="productCatDescs"  autocomplete="off"></textarea>
                </div>
              </div>

            </div>

            <div class="modal-footer">
                <a href="javascript:;" type="button" onClick="productCategorie_np()" class="btn btn-circle btn-success"><i class="glyphicon glyphicon-saved"></i>&nbsp;<?= Yii::t('app','ajouter');?></a>

                <button type="button" onClick="cancel()" class="btn btn-circle btn-warning" data-dismiss="modal"><i class="glyphicon glyphicon-remove-circle"></i>&nbsp;<?= Yii::t('app','fermer');?></button>
            </div>

        </div>
    </div>
</div>
