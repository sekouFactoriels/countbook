<div class="modal fade" id="fournisseur_form_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">NOUVEAU FOURNISSEUR</h4>
            </div>
            <div class="modal-body">
                <!-- formulaire -->
                <div class="form-group">
                    <label class="col-sm-4 control-label">Raison Sociale : <span class="asterisk">*</span>
                    </label>
                    <div class="col-sm-6 input-group">
                        <select name="raison_sociale" id="raison_sociale" class="form-control">
                            <option value="1" <?= isset($request['raison_sociale']) && $request['raison_sociale'] == 1  ? 'selected' : '' ?>><?= Yii::t('app', 'particulier') ?></option>
                            <option value="2" <?= isset($request['raison_sociale']) && $request['raison_sociale'] == 2  ? 'selected' : '' ?>><?= Yii::t('app', 'entreprise') ?></option>
                        </select>
                        <span class="input-group-addon hidden-md hidden-lg"></span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label">Dénomination : <span class="asterisk">*</span>
                    </label>
                    <div class="col-sm-6 input-group">
                        <input type="text" class="form-control" name="denomination" id="denomination" value="<?= isset($request['denomination']) ? $request['denomination'] : '' ?>" autocomplete="off">
                    </div>
                </div>


                <div class="form-group">
                    <label class="col-sm-4 control-label">Téléphone (Ex: 224624000000) : <span class="asterisk">*</span>
                    </label>
                    <div class="col-sm-6 input-group">
                        <input type="text" class="form-control" name="telephone" id="telephone" value="<?= isset($request['telephone']) ? $request['telephone'] : '' ?>" autocomplete="off">
                    </div>
                </div>


                <div class="form-group">
                    <label class="col-sm-4 control-label">Email : <span class="asterisk"> </span>
                    </label>
                    <div class="col-sm-6 input-group">
                        <input type="text" class="form-control" name="email" id="email" value="<?= isset($request['telephone']) ? $request['telephone'] : '' ?>" autocomplete="off">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label">Site web : <span class="asterisk"> </span>
                    </label>
                    <div class="col-sm-6 input-group">
                        <input type="text" class="form-control" name="siteweb" id="siteweb" value="<?= isset($request['siteweb']) ? $request['siteweb'] : '' ?>" autocomplete="off">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label">Adresse physique : <span class="asterisk"> </span>
                    </label>
                    <div class="col-sm-6 input-group">
                        <textarea rows="3" name="adresse_physique" id="adresse_physique" class="form-control"><?= isset($request['adresse_physique']) ? $request['adresse_physique'] : '' ?></textarea>
                    </div>
                </div>
                <!-- fin formulaire -->
            </div>
            <div class="modal-footer">
                <a href="javascript:;" type="button" onclick="document.getElementById('action_key').value='<?= md5("enregistrer_fournisseur") ?>'; document.getElementById('action_on_this').value='<?= md5("1") ?>'; document.getElementById('countbook_form').submit();" class="btn btn-circle btn-success"><i class="glyphicon glyphicon-saved"></i>&nbsp;<?= Yii::t('app', 'oui') ?></a>

                <button type="button" class="btn btn-circle btn-warning" data-dismiss="modal"><i class="glyphicon glyphicon-remove-circle"></i>&nbsp;<?= Yii::t('app', 'non') ?></button>
            </div>
        </div>
    </div>
</div>