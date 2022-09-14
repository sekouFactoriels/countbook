<?php 
if(isset($_POST['activite'])){
  $form_data = $_POST;
}else{
  $form_data = $espace_gestion;
}
?>
<div class="col-sm-6">
  <label class="control-label"><?= Yii::t('app', 'activite') ?> : <span class="asterisk">*</span></label>
  <div>
    <select class="form-control" name="activite" id="activite">
      <option value="0" <?= (isset($form_data['activite']) && $form_data['activite'] == "0") ? "selected" : '' ?>><?= Yii::t('app', 'slectOne') ?>e</option>
      <option value="agro" <?= (isset($form_data['activite']) && $form_data['activite'] == "agro") ? "selected" : '' ?>>Agroalimentaire</option>
      <option value="finance" <?= (isset($form_data['activite']) && $form_data['activite'] == "finance") ? "selected" : '' ?>>Banque / Assurance</option>
      <option value="boisimprimerie" <?= (isset($form_data['activite']) && $form_data['activite'] == "boisimprimerie") ? "selected" : '' ?>>Bois / Papier / Carton / Imprimerie</option>
      <option value="btp" <?= (isset($form_data['activite']) && $form_data['activite'] == "btp") ? "selected" : '' ?>>BTP / Matériaux de construction</option>
      <option value="chimie" <?= (isset($form_data['activite']) && $form_data['activite'] == "chimie") ? "selected" : '' ?>>Chimie / Parachimie</option>
      <option value="commerce" <?= (isset($form_data['activite']) && $form_data['activite'] == "commerce") ? "selected" : '' ?>>Commerce / Négoce / Distribution</option>
      <option value="communication" <?= (isset($form_data['activite']) && $form_data['activite'] == "communication") ? "selected" : '' ?>>Édition / Communication / Multimédia</option>
      <option value="eletricite" <?= (isset($form_data['activite']) && $form_data['activite'] == "eletricite") ? "selected" : '' ?>>Électronique / Électricité</option>
      <option value="etudeconseil" <?= (isset($form_data['activite']) && $form_data['activite'] == "etudeconseil") ? "selected" : '' ?>>Études et conseils</option>
      <option value="immobilier" <?= (isset($form_data['activite']) && $form_data['activite'] == "immobilier") ? "selected" : '' ?>>Immobilier / vente des meubles</option>
      <option value="pharmacie" <?= (isset($form_data['activite']) && $form_data['activite'] == "pharmacie") ? "selected" : '' ?>>Industrie pharmaceutique</option>
      <option value="informatique" <?= (isset($form_data['activite']) && $form_data['activite'] == "informatique") ? "selected" : '' ?>>Informatique / Télécoms</option>
      <option value="machine" <?= (isset($form_data['activite']) && $form_data['activite'] == "machine") ? "selected" : '' ?>>Machines et équipements / Automobile</option>
      <option value="metallurgie" <?= (isset($form_data['activite']) && $form_data['activite'] == "metallurgie") ? "selected" : '' ?>>Métallurgie / Travail du métal</option>
      <option value="plastique" <?= (isset($form_data['activite']) && $form_data['activite'] == "plastique") ? "selected" : '' ?>>Plastique / Caoutchouc</option>
      <option value="sante" <?= (isset($form_data['activite']) && $form_data['activite'] == "sante") ? "selected" : '' ?>>Santé / Médecine</option>
      <option value="prestationservice" <?= (isset($form_data['activite']) && $form_data['activite'] == "prestationservice") ? "selected" : '' ?>>Services aux entreprises</option>
      <option value="vetement" <?= (isset($form_data['activite']) && $form_data['activite'] == "vetement") ? "selected" : '' ?>>Textile / Habillement / Chaussure</option>
      <option value="logistique" <?= (isset($form_data['activite']) && $form_data['activite'] == "logistique") ? "selected" : '' ?>>Transports / Logistique</option>
      <option value="autre">Autre</option>
    </select>
  </div>
</div>