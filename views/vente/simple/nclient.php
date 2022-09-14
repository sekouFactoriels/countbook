<div class="modal fade" id="client_form_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">NOUVEAU CLIENT</h4>
            </div>
            <div class="modal-body">
                <!-- formulaire -->
                <div class="form-group">
                    <label class="col-sm-4 control-label"><?= Yii::t('app', 'stat_client') ?> : <span class="asterisk">*</span>
                    </label>
                    <div class="col-sm-6 input-group">
                        <select name="statutClient" id="statutClient" class="form-control chosen-select">
                            <option value="0">>-- <?= Yii::t('app', 'slectOne') ?> --<< /option>
                            <option value="1"><?= Yii::t('app', 'particulier') ?></option>
                            <option value="2"><?= Yii::t('app', 'entreprise') ?></option>
                        </select>
                        <span class="input-group-addon hidden-md hidden-lg"></span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label"><?= Yii::t('app', 'segment_client') ?> : <span class="asterisk">*</span>
                    </label>
                    <div class="col-sm-6 input-group">
                        <select name="segmentClient" id="segmentClient" class="form-control chosen-select">
                            <option value="0">>-- <?= Yii::t('app', 'slectOne') ?> --<< /option>
                            <option value="1"><?= Yii::t('app', 'prospect_froid') ?></option>
                            <option value="2"><?= Yii::t('app', 'prospect_tiede') ?></option>
                            <option value="3"><?= Yii::t('app', 'prospect_chaud') ?></option>
                            <option value="4"><?= Yii::t('app', 'client') ?></option>
                        </select>
                        <span class="input-group-addon hidden-md hidden-lg"></span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label"><?= Yii::t('app', 'nomappelation') ?> : <span class="asterisk">*</span>
                    </label>
                    <div class="col-sm-6 input-group">
                        <input class="form-control" autocomplete="off" name="appelation" id="appelation" value="<?= isset($_POST['appelation']) ? $_POST['appelation'] : '' ?>" />
                        <span class="input-group-addon hidden-md hidden-lg"></span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label"><?= Yii::t('app', 'dteNaiss') ?> : <span class="asterisk">&nbsp;</span>
                    </label>
                    <div class="col-sm-6 input-group">
                        <input type="text" data-provide="datepicker" autocomplete="off" value="" class="form-control datepicker" placeholder="dd/mm/yyyy" id="dtebirth" name="dtebirth">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label"><?= Yii::t('app', 'tel') ?> : <span class="asterisk">&nbsp;</span>
                    </label>
                    <div class="col-sm-6 input-group">
                        <input class="form-control" name="tel" placeholder="Ex : 224624123456" autocomplete="off" id="tel" value="<?= isset($_POST['tel']) ? $_POST['tel'] : '' ?>" />
                        <span class="input-group-addon hidden-md hidden-lg"></span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label"><?= Yii::t('app', 'email') ?> : <span class="asterisk">&nbsp;</span>
                    </label>
                    <div class="col-sm-6 input-group">
                        <input class="form-control" name="email" placeholder="Ex : test@test.com" autocomplete="off" id="email" value="<?= isset($_POST['email']) ? $_POST['email'] : '' ?>" />
                        <span class="input-group-addon hidden-md hidden-lg"></span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label"><?= Yii::t('app', 'ergmotif') ?> : <span class="asterisk">*</span>
                    </label>
                    <div class="col-sm-6 input-group">
                        <select name="enrgmotif" id="enrgmotif" class="form-control chosen-select">
                            <option value="0">>-- <?= Yii::t('app', 'slectOne') ?> --<< /option>
                                    <?php
                                    if (isset($clientEnrgMotifs) && sizeof($clientEnrgMotifs) > 0) {
                                        foreach ($clientEnrgMotifs as $key => $value) {
                                            echo '<option value="' . $value['id'] . '">' . $value['libelle'] . '</option>';
                                        }
                                    } else echo '<option value="0">' . Yii::t("app", "pasEnregistrement") . '</option>';
                                    ?>
                        </select>
                        <a href="javascript:;" class="btn btn-circle btn-primary input-group-addon" onclick="$('#newmotifenrgclient').modal('show');"><i class="fa fa-plus-circle"></i></a>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label"><?= Yii::t('app', 'dtlsmotif') ?> :<span class="asterisk">&nbsp;</span>
                    </label>
                    <div class="col-sm-6 input-group">
                        <textarea class="form-control" rows="3" name="dtlsmotif" id="dtlsmotif"><?= isset($_POST['dtlsmotif']) ? $_POST['dtlsmotif'] : '' ?></textarea>
                        <span class="input-group-addon hidden-md hidden-lg"></span>
                    </div>
                </div>
                <!-- fin formulaire -->
            </div>
            <div class="modal-footer">
                <a href="javascript:;" type="button" onclick="document.getElementById('action_key').value='<?= md5("enreg_client") ?>'; document.getElementById('action_on_this').value='<?= md5("1") ?>'; document.getElementById('nventesimpleForm').submit();" class="btn btn-circle btn-success"><i class="glyphicon glyphicon-saved"></i>&nbsp;<?= Yii::t('app', 'oui') ?></a>

                <button type="button" class="btn btn-circle btn-warning" data-dismiss="modal"><i class="glyphicon glyphicon-remove-circle"></i>&nbsp;<?= Yii::t('app', 'non') ?></button>
            </div>
        </div>
    </div>
</div>