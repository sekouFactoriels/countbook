<p class="mb30">
<div class="modal fade" id="do_slogan_updater" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <h4><?= yii::t('app', 'nouveau_slogan') ?></h4>
                <div class="form-group">
                    <p>
                        <input type="text" class="form-control" name="slogan" id="slogan" value="<?= $espace_gestion['slogan'] ?>">
                    </p>
                </div>
            </div>
            <div class="modal-footer">
                <a href="javascript:;" type="button" onClick="document.getElementById('action_key').value='<?= md5("update_slogan") ?>'; $('#countbook_form').submit();" class="btn btn-circle btn-success"><i class="fa fa-save"></i>&nbsp;<?= Yii::t('app', 'enregistrer') ?></a>

                <button type="button" class="btn btn-circle btn-warning" data-dismiss="modal"><i class="fa fa-arrow-circle-left"></i>&nbsp;<?= Yii::t('app', 'rtour') ?></button>
            </div>
        </div>
    </div>
</div>
</p>