<div class="form-group">
  <label class="col-sm-4 control-label"><?= Yii::t('app', 'raisonsociale') ?> : <span class="asterisk">*</span>
  </label>
  <div class="col-sm-4 input-group">
    <input class="form-control" autocomplete="off" name="denomination" id="denomination" value="<?= isset($_POST['denomination']) ? $_POST['denomination'] : '' ?>" />
    <span class="input-group-addon hidden-md hidden-lg"></span>
  </div>
</div>
<div class="form-group">
  <label class="col-sm-4 control-label"><?= Yii::t('app', 'activite') ?> : <span class="asterisk">*</span>
  </label>
  <div class="col-sm-4 input-group">
    <select class="form-control" name="activite" id="activite">
      <option value="0" <?= (isset($_POST['activite']) && $_POST['activite'] == "0") ? "selected" : '' ?>><?= Yii::t('app', 'slectOne') ?>e</option>
      <option value="agro" <?= (isset($_POST['activite']) && $_POST['activite'] == "agro") ? "selected" : '' ?>>Agroalimentaire</option>
      <option value="finance" <?= (isset($_POST['activite']) && $_POST['activite'] == "finance") ? "selected" : '' ?>>Banque / Assurance</option>
      <option value="boisimprimerie" <?= (isset($_POST['activite']) && $_POST['activite'] == "boisimprimerie") ? "selected" : '' ?>>Bois / Papier / Carton / Imprimerie</option>
      <option value="btp" <?= (isset($_POST['activite']) && $_POST['activite'] == "btp") ? "selected" : '' ?>>BTP / Matériaux de construction</option>
      <option value="chimie" <?= (isset($_POST['activite']) && $_POST['activite'] == "chimie") ? "selected" : '' ?>>Chimie / Parachimie</option>
      <option value="commerce" <?= (isset($_POST['activite']) && $_POST['activite'] == "commerce") ? "selected" : '' ?>>Commerce / Négoce / Distribution</option>
      <option value="communication" <?= (isset($_POST['activite']) && $_POST['activite'] == "communication") ? "selected" : '' ?>>Édition / Communication / Multimédia</option>
      <option value="eletricité" <?= (isset($_POST['activite']) && $_POST['activite'] == "eletricité") ? "selected" : '' ?>>Électronique / Électricité</option>
      <option value="etudeconseil" <?= (isset($_POST['activite']) && $_POST['activite'] == "etudeconseil") ? "selected" : '' ?>>Études et conseils</option>
      <option value="immobilier" <?= (isset($_POST['activite']) && $_POST['activite'] == "immobilier") ? "selected" : '' ?>>Immobilier / vente des meubles</option>
      <option value="pharmacie" <?= (isset($_POST['activite']) && $_POST['activite'] == "pharmacie") ? "selected" : '' ?>>Industrie pharmaceutique</option>
      <option value="informatique" <?= (isset($_POST['activite']) && $_POST['activite'] == "informatique") ? "selected" : '' ?>>Informatique / Télécoms</option>
      <option value="machine" <?= (isset($_POST['activite']) && $_POST['activite'] == "machine") ? "selected" : '' ?>>Machines et équipements / Automobile</option>
      <option value="metallurgie" <?= (isset($_POST['activite']) && $_POST['activite'] == "metallurgie") ? "selected" : '' ?>>Métallurgie / Travail du métal</option>
      <option value="plastique" <?= (isset($_POST['activite']) && $_POST['activite'] == "plastique") ? "selected" : '' ?>>Plastique / Caoutchouc</option>
      <option value="sante" <?= (isset($_POST['activite']) && $_POST['activite'] == "sante") ? "selected" : '' ?>>Santé / Médecine</option>
      <option value="prestationservice" <?= (isset($_POST['activite']) && $_POST['activite'] == "prestationservice") ? "selected" : '' ?>>Services aux entreprises</option>
      <option value="vetement" <?= (isset($_POST['activite']) && $_POST['activite'] == "vetement") ? "selected" : '' ?>>Textile / Habillement / Chaussure</option>
      <option value="logistique" <?= (isset($_POST['activite']) && $_POST['activite'] == "logistique") ? "selected" : '' ?>>Transports / Logistique</option>
      <option value="autre">Autre</option>
    </select>
    <span class="input-group-addon hidden-md hidden-lg"></span>
  </div>
</div>

<div class="form-group">
  <label class="col-sm-4 control-label">T&#233;l&#233;phone : <span class="asterisk">*</span></label>
  <div class="col-sm-4 input-group">
      <div class="col-sm-3">
        <select class="form-control" name="tel_country_code" id="tel_country_code">
          <option value="224">+224</option>
        </select>    
      </div>

      <div class="col-sm-9">
          <input class="form-control" autocomplete="off" name="tel" id="tel" placeholder="6########" value="<?= isset($_POST['tel']) ? $_POST['tel'] : ''?>"/>
      </div>
  </div>
</div>


<div class="form-group">
  <label class="col-sm-4 control-label"><?= Yii::t('app', 'email') ?> : <span class="asterisk">*</span>
  </label>
  <div class="col-sm-4 input-group">
    <input class="form-control" autocomplete="off" name="email" id="email" value="<?= isset($_POST['email']) ? $_POST['email'] : '' ?>" />
    <span class="input-group-addon hidden-md hidden-lg"></span>
  </div>
</div>

<div class="form-group">
  <label class="col-sm-4 control-label"><?= Yii::t('app', 'adresse') ?> : <span class="asterisk">*</span>
  </label>
  <div class="col-sm-4 input-group">
    <textarea class="form-control" name="adresse" id="adresse"><?= isset($_POST['adresse']) ? $_POST['adresse'] : '' ?></textarea>
    <span class="input-group-addon hidden-md hidden-lg"></span>
  </div>
</div>