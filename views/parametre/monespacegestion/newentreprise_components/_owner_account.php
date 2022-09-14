<fieldset>
  <legend>
    <h3><?= Yii::t('app', 'ownergeneraldata') ?></h3>
  </legend>
</fieldset>

<div class="form-group required-container">
  <label class="col-sm-4 control-label"><?= Yii::t('app','pNom')?> : <span class="asterisk">*</span></label>
  <div class="col-sm-4 input-group">
    <input type="text" value="<?= isset($_POST['pnom']) ? $_POST['pnom'] : ''?>" class="form-control" name="pnom" id="pnom" autocomplete="off"/>
    <span class="input-group-addon hidden-md hidden-lg"></span>
  </div>
</div>

<div class="form-group required-container">
  <label class="col-sm-4 control-label"><?= Yii::t('app','nom')?> : <span class="asterisk">*</span>
  </label>
  <div class="col-sm-4 input-group">
    <input type="text" value="<?= isset($_POST['nom']) ? $_POST['nom'] : ''?>" class="form-control" name="nom" id="nom" autocomplete="off"/>
    <span class="input-group-addon hidden-md hidden-lg"></span>
  </div>
</div>


<div class="form-group">
  <label class="col-sm-4 control-label">T&#233;l&#233;phone : <span class="asterisk">*</span></label>
  <div class="col-sm-4 input-group">
      <div class="col-sm-3">
        <select class="form-control" name="country_tel_code" id="country_tel_code">
          <option value="224">+224</option>
        </select>    
      </div>

      <div class="col-sm-9">
          <input class="form-control" autocomplete="off" name="tel_owner" id="tel_owner" placeholder="6########" value="<?= isset($_POST['tel_owner']) ? $_POST['tel_owner'] : ''?>"/>
      </div>
  </div>
</div>

<div class="form-group">
  <label class="col-sm-4 control-label"><?= Yii::t('app','typeuser')?> : <span class="asterisk">*</span>   </label>
  <div class="col-sm-4 input-group">
    <select name="typeUsr" id="typeUsr" class="form-control">
      <option value="9"><?= Yii::t('app','proprietaire')?></option>
    </select>
    <span class="input-group-addon hidden-md hidden-lg"></span>
  </div>
</div>

<div class="form-group required-container">
  <label class="col-sm-4 control-label"><?= Yii::t('app','nomuser')?> : <span class="asterisk">*</span>
  </label>
  <div class="col-sm-4 input-group">
    <input type="text" value="<?= isset($_POST['nomuser']) ? $_POST['nomuser'] : "" ?>" class="form-control" name="nomuser" id="nomuser" autocomplete="off"/>
    <span class="input-group-addon hidden-md hidden-lg"></span>
  </div>
</div>
<div class="form-group required-container">
  <label class="col-sm-4 control-label"><?= Yii::t('app', 'motpassetemporaire')?> : <span class="asterisk">*</span>
  </label>
  <div class="col-sm-4 input-group">
    <input type="password" value="<?= isset($_POST['motpass']) ? $_POST['motpass'] : "" ?>" class="form-control" name="motpass" id="motpass" autocomplete="off"/>
    <span class="input-group-addon hidden-md hidden-lg"></span>
  </div>
</div>

