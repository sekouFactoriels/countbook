<fieldset>
  <legend>
    <h3><?= Yii::t('app', 'forfaitdata') ?></h3>
  </legend>
</fieldset>
<div class="form-group">
  <label class="col-sm-4 control-label">Type de <?= Yii::t('app', 'forfait') ?> : <span class="asterisk">*</span> </label>
  <div class="col-sm-4 input-group">
    <select class="form-control" name="forfait" id="forfait" onchange="filiale_feild()">
      <option value="0"><?= Yii::t('app', 'slectOne') ?></option>
      <option value="essentiel" <?= (isset($_POST['forfait']) && $_POST['forfait'] == "essentiel") ? "selected" : '' ?>><?= Yii::t('app', 'essentiel') ?></option>
      <option value="standard" <?= (isset($_POST['forfait']) && $_POST['forfait'] == "standard") ? "selected" : '' ?>><?= Yii::t('app', 'standard') ?></option>
      <option value="premium" <?= (isset($_POST['forfait']) && $_POST['forfait'] == "premium") ? "selected" : '' ?>><?= Yii::t('app', 'premium') ?></option>
      <option value="partenaire" <?= (isset($_POST['forfait']) && $_POST['forfait'] == "partenaire") ? "selected" : '' ?>><?= Yii::t('app', 'partenaire') ?></option>
      <option value="special" <?= (isset($_POST['forfait']) && $_POST['forfait'] == "special") ? "selected" : '' ?>><?= Yii::t('app', 'special') ?></option>
     
    </select>
    <span class="input-group-addon hidden-md hidden-lg"></span>
  </div>
</div>
<div class="form-group">
  <label class="col-sm-4 control-label"><?= Yii::t('app', 'dtedebutforfait') ?> : <span class="asterisk">*</span>
  </label>
  <div class="col-sm-4 input-group">
    <input type="text" data-provide="datepicker" autocomplete="off" value="<?= isset($_POST['dtedebutforfait']) ? $_POST['dtedebutforfait'] : '' ?>" class="form-control datepicker" placeholder="dd/mm/yyyy" id="dtedebutforfait" name="dtedebutforfait">
    <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
  </div>
</div>