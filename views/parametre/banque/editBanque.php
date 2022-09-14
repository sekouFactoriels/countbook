<div class="modal fade" id="editBanque" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="licenceCreatAssociatLabel"><?php echo Yii::t('app', 'modifBanque'); ?></h4>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    <label class="col-sm-3 control-label"><?= Yii::t('app', 'numBanque') ?> : <span class="asterisk">*</span>
                    </label>
                    <div class="col-sm-8">
                        <input type="text" value="" class="form-control" name="editnumero" id="editnumero" autocomplete="off" />
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label"><?= Yii::t('app', 'libelle') ?> : <span class="asterisk">*</span>
                    </label>
                    <div class="col-sm-8">
                        <input type="text" value="" class="form-control" name="editlibelle" id="editlibelle" autocomplete="off" />
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label"><?= Yii::t('app', 'addresse') ?> : <span class="asterisk">*</span>
                    </label>
                    <div class="col-sm-8">
                        <textarea class="form-control" autocomplete="off" name="editadresse" id="editadresse"> </textarea>
                    </div>
                </div>

            </div>

            <div class="modal-footer">
                <a href="javascript:;" type="button" onClick="$('#editelibelle').val($('#editlibelle').val()); $('#editenumero').val($('#editnumero').val()); $('#editeadresse').val($('#editadresse').val()); $('#action_key').val('<?= md5("parametre_update_bank")?>'); $('#banque_form').submit();" class="btn btn-circle btn-success"><i class="glyphicon glyphicon-saved"></i>&nbsp;Enregistrer</a>
                <button type="button" class="btn btn-circle btn-warning" data-dismiss="modal"><i class="glyphicon glyphicon-remove-circle"></i>&nbsp;Annuler</button>
            </div>
        </div>
    </div>
</div>