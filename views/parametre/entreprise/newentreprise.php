<style>
  .holder {
    height: 300px;
    width: 300px;
    border: 2px solid black;
  }

  img {
    max-width: 300px;
    max-height: 300px;
    min-width: 300px;
    min-height: 300px;
  }

  input[type="file"] {
    margin-top: 5px;
  }

  .heading {
    font-family: Montserrat;
    font-size: 45px;
    color: green;
  }
</style>

<div class="panel panel-default">
  <div class="panel-heading">
    <div class="panel-btns">
      <a href="<?= Yii::$app->request->baseUrl . '/' . md5('parametre_entreprises') ?>" style="color: #000;" class="btn btn-circle btn-white" name="newentreprise" id="newentreprise"> <i class="fa fa-arrow-circle-left"></i></a>
    </div>
    <h4 class="panel-title"><i class="fa fa-building-o">&nbsp;</i><?= yii::t('app', 'parametre_entreprise') ?></h4>
  </div>
  <form action="<?= Yii::$app->request->baseUrl . '/' . md5("parametre_entreprises") ?>" class="form-horizontal" id="entreprise_form" name="entreprise_form" method="post" enctype="multipart/form-data">
    <div class="panel-body">
      <?= Yii::$app->nonSqlClass->getHiddenFormTokenField();
      $token2 = Yii::$app->getSecurity()->generateRandomString();
      $token2 = str_replace('+', '.', base64_encode($token2));
      ?>
      <input type="hidden" name="token2" value="<?= $token2 ?>" />
      <input type="hidden" name="_csrf" value="<?= yii::$app->request->getcsrftoken() ?>">
      <input type="hidden" name="action_key" id="action_key" value="">
      <input type="hidden" name="action_on_this" id="action_on_this" value="">
      <!-- DEBUT CONTENEUR DE MESSAGE  -->
      <?= Yii::$app->session->getFlash('flashmsg'); Yii::$app->session->removeFlash('flashmsg');?>
      <!-- FIN CONTENEUR DE MESSAGE -->
      
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
        <label class="col-sm-4 control-label"><?= Yii::t('app', 'tel') ?> : <span class="asterisk">*</span>
        </label>
        <div class="col-sm-4 input-group">
          <input class="form-control" autocomplete="off" name="tel" id="tel" value="<?= isset($_POST['tel']) ? $_POST['tel'] : '' ?>" />
          <span class="input-group-addon hidden-md hidden-lg"></span>
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

      <div class="form-group" id="div_filiale" style="display: none;">
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

      </div>
      <!-- logo -->
      <div class="form-group">
        <label class="col-sm-4 control-label">Logo : <span class="asterisk"></span>
        </label>
        <div class="col-sm-4 input-group">
          <div class="holder">
            <img id="imgPreview" name="imgPreview" src="#" alt="pic" />
          </div>
          <input type="file" name="logo" id="photo" required="true" />
        </div>
      </div>
      <!-- /logo -->

    </div>

    

    <!-- DEBUT : FOOTER -->
    <div class="panel-footer">
      <div class="row">
        <div class="col-sm-9 col-sm-offset-3">
          <a class="btn btn-circle btn-success" onclick="$('#beforesave_newentreprise').modal('show');"><i class="glyphicon glyphicon-save"></i>&nbsp;<?= yii::t('app', 'enrgtrer') ?></a>
          <span>&nbsp;</span>
          <a href="<?= Yii::$app->request->baseUrl . '/' . md5('parametre_entreprises') ?>" type="reset" class="btn btn-circle btn-warning"><i class="glyphicon glyphicon-remove-circle"></i>&nbsp;<?= yii::t('app', 'annul') ?></a>
        </div>
      </div>
    </div>
    <!-- FIN : FOOTER -->
  </form>
</div>

<div class="modal fade" id="beforesave_newentreprise" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="newProdCat"><?= Yii::t('app', 'enrg') . '&nbsp;' . Yii::t('app', 'parametre_entreprise') ?></h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <p><?= Yii::t('app', 'msg_continueroperation') ?></p>
        </div>
      </div>
      <div class="modal-footer">
        <a href="javascript:;" type="button" onClick="document.getElementById('action_key').value='<?= md5("entreprises_saveentreprise") ?>'; $('#entreprise_form').submit();" class="btn btn-circle btn-success"><i class="glyphicon glyphicon-saved"></i>&nbsp;<?= Yii::t('app', 'oui') ?></a>
        <button type="button" onClick="cancel()" class="btn btn-circle btn-warning" data-dismiss="modal"><i class="glyphicon glyphicon-remove-circle"></i>&nbsp;<?= Yii::t('app', 'non') ?></button>
      </div>
    </div>
  </div>
</div>
<?php require_once('script/newentreprise_js.php'); ?>