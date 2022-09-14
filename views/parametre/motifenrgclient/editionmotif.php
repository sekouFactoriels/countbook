<div class="modal fade" id="editionmotifenrgclient" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="newProdCat"><?= Yii::t('app','nmotif')?></h4>
            </div>
            <div class="modal-body">

              <div class="form-group">
                <label class="col-sm-3 control-label"><?= Yii::t('app','statut')?> : <span class="asterisk">*</span>
                </label>
                <div class="col-sm-8">
                  <select class="form-control" name="editedstatutmotif" id="editedstatutmotif">
                    <option value="1"><?= Yii::t('app','active')?></option>
                    <option value="2"><?= Yii::t('app','suprime')?></option>
                  </select>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label"><?= Yii::t('app','libelle')?> : <span class="asterisk">*</span>
                </label>
                <div class="col-sm-8">
                  <input type="text" value="" class="form-control" name="editedlibellemotif" id="editedlibellemotif"  autocomplete="off"/>
                </div>
              </div>
            </div>
            <div class="modal-footer">
                <a href="javascript:;" type="button" onClick="$('#libelleeditedmotif').val($('#editedlibellemotif').val());  $('#statutmotif').val($('#editedstatutmotif').val()); $('#action_key').val('<?= md5("parametre_updatemotif")?>'); $('#motifs_form').submit();" class="btn btn-circle btn-success"><i class="glyphicon glyphicon-saved"></i>&nbsp;Oui</a>
                <button type="button" class="btn btn-circle btn-warning" data-dismiss="modal"><i class="glyphicon glyphicon-remove-circle"></i>&nbsp;Non</button>
            </div>
        </div>
    </div>
</div>
<?php
  require_once('script/newmotif_js.php');
?>
