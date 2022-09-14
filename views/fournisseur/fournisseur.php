
<!-- BEGIN FORM-->
<form class="form-horizontal" action="" name="countbook_form" id="countbook_form" action="<?=yii::$app->request->baseurl.'/'.md5('fournisseur_themain') ?>" method="post">
<?=
  Yii::$app->nonSqlClass->getHiddenFormTokenField();
  $token2 = Yii::$app->getSecurity()->generateRandomString();
  $token2 = str_replace('+','.',base64_encode($token2));
  ?>
  <!-- DEBUT : BASIC HIDDEN IMPUT FOR THE FORM -->
  <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>"/>
  <input type="hidden" name="token2" value="<?= $token2 ?>"/>
  <input type="hidden" name="action_key" id="action_key" value=""/>
  <input type="hidden" name="action_on_this" id="action_on_this" value=""/>

  <div class="panel panel-default">
    <!-- LIBELLE FORMULAIRE -->
    <div class="panel-heading">
      <h4 class="panel-title"><i class="fa fa-link"></i>&nbsp;&nbsp;NOUVEAU FOURNISSEUR</h4>
    </div>
    <!-- LIBELLE FORMULAIRE -->

    <!-- CORPS DU FORMULAIRE -->
    <div class="panel-body">
      
      <!-- AFFICHER MESSAGE -->
      <?= Yii::$app->session->getFlash('flashmsg'); Yii::$app->session->removeFlash('flashmsg');?>

      <div class="form-group">
        <label class="col-sm-4 control-label">Raison Sociale : <span class="asterisk">*</span>
        </label>
        <div class="col-sm-4 input-group">
          <select name="raison_sociale" id="raison_sociale" class="form-control">
            <option value="1" <?= isset($request['raison_sociale']) && $request['raison_sociale'] == 1  ? 'selected' : '' ?> ><?= Yii::t('app','particulier') ?></option>
            <option value="2" <?= isset($request['raison_sociale']) && $request['raison_sociale'] == 2  ? 'selected' : '' ?> ><?= Yii::t('app','entreprise') ?></option>
          </select>
          <span class="input-group-addon hidden-md hidden-lg"></span>
        </div>
      </div>

      <div class="form-group">
        <label class="col-sm-4 control-label">Dénomination : <span class="asterisk">*</span>
        </label>
        <div class="col-sm-4 input-group">
          <input type="text" class="form-control" name="denomination" id="denomination" value="<?= isset($request['denomination']) ? $request['denomination'] : '' ?>" autocomplete="off">
        </div>
      </div>


      <div class="form-group">
        <label class="col-sm-4 control-label">Téléphone (Ex: 224624000000) : <span class="asterisk">*</span>
        </label>
        <div class="col-sm-4 input-group">
          <input type="text" class="form-control" name="telephone" id="telephone" value="<?= isset($request['telephone']) ? $request['telephone'] : '' ?>"  autocomplete="off">
        </div>
      </div>


      <div class="form-group">
        <label class="col-sm-4 control-label">Email : <span class="asterisk"> </span>
        </label>
        <div class="col-sm-4 input-group">
          <input type="text" class="form-control" name="email" id="email" value="<?= isset($request['telephone']) ? $request['telephone'] : '' ?>"  autocomplete="off">
        </div>
      </div>

      <div class="form-group">
        <label class="col-sm-4 control-label">Site web : <span class="asterisk"> </span>
        </label>
        <div class="col-sm-4 input-group">
          <input type="text" class="form-control" name="siteweb" id="siteweb" value="<?= isset($request['siteweb']) ? $request['siteweb'] : '' ?>"  autocomplete="off">
        </div>
      </div>

      <div class="form-group">
        <label class="col-sm-4 control-label">Adresse physique : <span class="asterisk"> </span>
        </label>
        <div class="col-sm-4 input-group">
          <textarea rows="3" name="adresse_physique" id="adresse_physique" class="form-control"><?= isset($request['adresse_physique']) ? $request['adresse_physique'] : '' ?></textarea>
        </div>
      </div>

    </div>
    <!-- .CORPS DU FORMULAIRE -->

    <!-- PIEDS DU FORMULAIRE -->
    <div class="panel-footer">
      <div class="row">
        <div class="col-sm-9 col-sm-offset-3">
          <a class="btn btn-circle btn-success" onclick="$('#countbook_form_modal').modal('show');"><i class="glyphicon glyphicon-save"></i>&nbsp;Enregistrer</a>
          <span>&nbsp;</span>
          <a href="<?= yii::$app->request->baseUrl.'/'.md5('fournisseur_themain')?>" type="reset" class="btn btn-circle btn-warning"><i class="glyphicon glyphicon-remove-circle"></i>&nbsp;Anuler</a>
        </div>
      </div>
    </div>
    <!-- .PIEDS DU FORMULAIRE -->

  </div>

</form>

<div class="modal fade" id="countbook_form_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="newProdCat"><i class="fa fa-exclamation-circle">&nbsp;</i>ENREGISTREMENT</h4>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <p>ETES-VOUS SUR DE CONTINUER CETTE OPERATION ?</p>
              </div>
            </div>
            <div class="modal-footer">
                <a href="javascript:;" type="button" onClick="$('#action_key').val('<?= md5("enregistrer_fournisseur") ?>'); $('#countbook_form').attr('action','<?= md5("fournisseur_themain") ?>'); document.getElementById('countbook_form').submit();" class="btn btn-circle btn-success"><i class="glyphicon glyphicon-saved"></i>&nbsp;<?= Yii::t('app','oui')?></a>

                <button type="button" class="btn btn-circle btn-warning" data-dismiss="modal"><i class="glyphicon glyphicon-remove-circle"></i>&nbsp;<?= Yii::t('app','non')?></button>
            </div>
        </div>
    </div>
</div>