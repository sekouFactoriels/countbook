<div class="modal fade" id="addBanque" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="licenceCreatAssociatLabel">Nouveau Compte</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="col-sm-3 control-label"><?= Yii::t('app', 'numBanque') ?> : <span class="asterisk">*</span>
                    </label>
                    <div class="col-sm-8">
                        <input type="text" value="" class="form-control" name="numero" id="numero" autocomplete="off" />
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label"><?= Yii::t('app', 'libelle') ?> : <span class="asterisk">*</span>
                    </label>
                    <div class="col-sm-8">
                        <input type="text" value="" class="form-control" name="libelle" id="libelle" autocomplete="off" />
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label"><?= Yii::t('app', 'addresse') ?> : <span class="asterisk">*</span>
                    </label>
                    <div class="col-sm-8">
                        <textarea class="form-control" autocomplete="off" name="adresse" id="adresse"> </textarea>
                    </div>
                </div>

            </div>

            <div class="modal-footer">
                <a href="javascript:;" type="button" onclick="newBanque()" class="btn btn-circle btn-success"><i class="glyphicon glyphicon-saved"></i>&nbsp;Enregistrer</a>
                <button type="button" class="btn btn-circle btn-warning" data-dismiss="modal"><i class="glyphicon glyphicon-remove-circle"></i>&nbsp;Annuler</button>
            </div>
        </div>
    </div>
</div>
<?php require_once('script/newBanque_js.php'); ?>