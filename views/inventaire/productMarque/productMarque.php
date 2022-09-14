<?php require_once('script/productMarque_js.php'); ?>
<imput type="hidden" name="fabriquantKeyInfo" id="fabriquantKeyInfo" value=""/>
<div class="modal fade" id="newProductMarque" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="newProdCat">Nouvelle Marque</h4>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <label class="col-sm-4 control-label">Fabricants : <span class="asterisk">*</span>
                </label>
                <div class="col-sm-8">
                  <select onChange="fabriquantIdSelected()" name="fabriquantId" id="fabriquantId" class="form-control">
                    <option value="0">Nouveau</option>
                    <?php
                      if(is_array($productFabricant) && sizeof($productFabricant) > 0){
                        foreach ($productFabricant as $fabricantData) {
                          echo '<option value="'.$fabricantData['id'].'">'.$fabricantData['nomFabricant'].'</option>';
                        }
                      }
                    ?>
                  </select>
                </div>
              </div>

              <div class="form-group" id="nouveauFabriquant">
                <label class="col-sm-4 control-label">Nom (Nouveau Fabricant):<span class="asterisk">*</span>
                </label>
                <div class="col-sm-8">
                  <input type="text" value="" class="form-control" name="nomNouveauFabriquant" id="nomNouveauFabriquant"  autocomplete="off"/>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-4 control-label">Nom de la Marque : <span class="asterisk">*</span>
                </label>
                <div class="col-sm-8">
                  <input type="text" value="" class="form-control" name="productMarqueName" id="productMarqueName"  autocomplete="off"/>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-4 control-label">Description :
                </label>
                <div class="col-sm-8">
                  <textarea rows="3" type="text" class="form-control" name="productMarqueDesc" id="productMarqueDesc"  autocomplete="off"></textarea>
                </div>
              </div>

            </div>

            <div class="modal-footer">
                <?php if(Yii::$app->controller->action->id == 'nproduit' ) { ?>
                <a href="javascript:;" type="button" onClick="productMarque_np()" class="btn btn-circle btn-success"><i class="glyphicon glyphicon-saved"></i>&nbsp;<?= Yii::t('app','ajouter');?></a>
                <?php } ?>
                <button type="button" onClick="cancel()" class="btn btn-circle btn-warning" data-dismiss="modal"><i class="glyphicon glyphicon-remove-circle"></i>&nbsp;<?= Yii::t('app','fermer');?></button>
            </div>
        </div>
    </div>
</div>
