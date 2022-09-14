  <fieldset>
    <legend>
      <h3><?= Yii::t('app', 'filialedata') ?></h3>
    </legend>
  </fieldset>
  <div class="form-group">
    <label class="col-sm-4 control-label"><?= Yii::t('app', 'denomination') ?> : <span class="asterisk">*</span>
    </label>
    <div class="col-sm-4 input-group">
      <input class="form-control" autocomplete="off" name="denominationEntite" id="denominationEntite" value="<?= isset($_POST['denominationEntite']) ? $_POST['denominationEntite'] : '' ?>" />
      <span class="input-group-addon hidden-md hidden-lg"></span>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-4 control-label"><?= Yii::t('app', 'tel') ?> : <span class="asterisk">*</span>
    </label>
    <div class="col-sm-4 input-group">
      <input class="form-control" autocomplete="off" name="telEntite" id="telEntite" value="<?= isset($_POST['telEntite']) ? $_POST['telEntite'] : '' ?>" />
      <span class="input-group-addon hidden-md hidden-lg"></span>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-4 control-label"><?= Yii::t('app', 'email') ?> : <span class="asterisk">*</span>
    </label>
    <div class="col-sm-4 input-group">
      <input class="form-control" autocomplete="off" name="emailEntite" id="emailEntite" value="<?= isset($_POST['emailEntite']) ? $_POST['emailEntite'] : '' ?>" />
      <span class="input-group-addon hidden-md hidden-lg"></span>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-4 control-label"><?= Yii::t('app', 'adresse') ?> : <span class="asterisk">*</span>
    </label>
    <div class="col-sm-4 input-group">
      <textarea class="form-control" name="adresseEntite" id="adresseEntite"><?= isset($_POST['adresseEntite']) ? $_POST['adresseEntite'] : '' ?></textarea>
      <span class="input-group-addon hidden-md hidden-lg"></span>
    </div>
  </div>