<?php
if (isset($validated_bill) && is_array($validated_bill)) {
  foreach ($validated_bill as $each_validated_bill) {
    $dette  = $each_validated_bill['montantTotalPayer'] - $tt_paid;
?>
    <!-- BEGIN FORM-->
    <form class="form-horizontal" action="" name="countbook_form" id="countbook_form" action="<?= yii::$app->request->baseurl . '/' . md5('paiement_themain') ?>" method="post">
      <?=
      Yii::$app->nonSqlClass->getHiddenFormTokenField();
      $token2 = Yii::$app->getSecurity()->generateRandomString();
      $token2 = str_replace('+', '.', base64_encode($token2));
      ?>
      <!-- DEBUT : BASIC HIDDEN IMPUT FOR THE FORM -->
      <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>" />
      <input type="hidden" name="token2" value="<?= $token2 ?>" />
      <input type="hidden" name="action_key" id="action_key" value="" />

      <input type="hidden" name="action_on_this" id="action_on_this" value="<?= (isset($each_validated_bill['bill_number'])) ? $each_validated_bill['bill_number'] : '' ?>" />

      <input type="hidden" name="action_on_this_val" id="action_on_this_val" value="<?= (isset($each_validated_bill['id'])) ? $each_validated_bill['id'] : '' ?>" />

      <input type="hidden" name="categorie_autre_partie" id="categorie_autre_partie" value="<?= (isset($each_validated_bill['categorie_autre_partie'])) ? $each_validated_bill['categorie_autre_partie'] : '' ?>" />


      <div class="row">
        <h2 style="text-align: center; font-weight: bold;">FACTURE : [ <?= (isset($each_validated_bill['bill_number'])) ? $each_validated_bill['bill_number'] : '' ?>&nbsp;]</h2>
      </div>


      <?php
      if (isset($each_validated_bill['categorie_autre_partie'])) {
        $label_categorie_autre_partie = '';
        switch ($each_validated_bill['categorie_autre_partie']) {
          case 1:
            $label_categorie_autre_partie = md5('enregistrer_rembourssement_client');
      ?>
            <div class="row" style="padding-bottom: 20px;">
              <h2 style="text-align: center; font-weight: bold;">Client : <?= $client['nom_appellation'] ?></h2>
            </div>
            <?php
            break;


          case 2:
            $label_categorie_autre_partie = md5('enregistrer_rembourssement_fournisseur');
            if (isset($fournisseur)) {
              foreach ($fournisseur as $key => $each_fournisseur) {
            ?>
                <div class="row" style="padding-bottom: 20px;">
                  <h2 style="text-align: center; font-weight: bold;">Fournisseur : <?= $each_fournisseur['denomination'] ?></h2>
                </div>
      <?php
              }
            }
            break;
        }
      }


      ?>

      <div class="row">
        <div class="col-sm-6 col-md-4">
          <div class="panel panel-primary panel-stat">
            <div class="panel-heading">
              <div class="stat">
                <div class="row">
                  <div class="col-xs-3">
                    <span class="fa fa-money" style="font-size: 400%"></span>
                  </div>
                  <div class="col-xs-9">
                    <small class="stat-label">TT. MONTANT FACTURE (GNF)</small>
                    <h1 style="font-size: 28px;"><?= number_format($each_validated_bill['montantTotalPayer']); ?></h1>
                  </div>
                </div><!-- row -->
              </div><!-- stat -->
            </div><!-- panel-heading -->
          </div><!-- panel -->
        </div>
        <!-- col-sm-6 -->


        <div class="col-sm-6 col-md-4">
          <div class="panel panel-success panel-stat">
            <div class="panel-heading">
              <div class="stat">
                <div class="row">
                  <div class="col-xs-3">
                    <span class="fa fa-money" style="font-size: 400%"></span>
                  </div>
                  <div class="col-xs-9">
                    <small class="stat-label">TT. MONTANT PAYE (GNF)</small>
                    <h1 style="font-size: 28px;"><?= number_format($tt_paid); ?></h1>
                  </div>
                </div><!-- row -->
              </div><!-- stat -->
            </div><!-- panel-heading -->
          </div><!-- panel -->
        </div>
        <!-- col-sm-6 -->

        <div class="col-sm-6 col-md-4">
          <div class="panel panel-danger panel-stat">
            <div class="panel-heading">
              <div class="stat">
                <div class="row">
                  <div class="col-xs-3">
                    <span class="fa fa-money" style="font-size: 400%"></span>
                  </div>
                  <div class="col-xs-9">
                    <small class="stat-label">RESTER A PAYER (GNF)</small>
                    <h1 style="font-size: 28px;"><?= number_format($dette); ?></h1>
                  </div>
                </div><!-- row -->
              </div><!-- stat -->
            </div><!-- panel-heading -->
          </div><!-- panel -->
        </div>
        <!-- col-sm-6 -->

      </div><!-- row -->


      <div class="panel panel-default">
        <!-- LIBELLE FORMULAIRE -->
        <div class="panel-heading">
          <h4 class="panel-title"><i class="fa fa-link"></i>&nbsp;&nbsp;FACTURE : [ <?= (isset($each_validated_bill['bill_number'])) ? $each_validated_bill['bill_number'] : '' ?>&nbsp;]</h4>
        </div>
        <!-- LIBELLE FORMULAIRE -->

        <!-- CORPS DU FORMULAIRE -->
        <div class="panel-body">

          <!-- AFFICHER MESSAGE -->
          <?= Yii::$app->session->getFlash('flashmsg');
          Yii::$app->session->removeFlash('flashmsg'); ?>

          <div class="form-group">
            <label class="col-sm-4 control-label">Ancien montant restant : <span class="asterisk">*</span>
            </label>
            <div class="col-sm-4 input-group">
              <input type="text" class="form-control" name="ancien_mont_rest" id="ancien_mont_rest" value="<?= (isset($dette)) ? $dette : '' ?>" autocomplete="off" readonly="readonly">
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-4 control-label">Montant payé : <span class="asterisk">*</span>
            </label>
            <div class="col-sm-4 input-group">
              <input type="number" onkeyup="calculDetteVente();" onchange="positifMontantParcu()" class="form-control" name="mont_payer" id="mont_payer" value="" autocomplete="off">
            </div>
          </div>


          <div class="form-group">
            <label class="col-sm-4 control-label">Nouveau montant restant : <span class="asterisk">*</span>
            </label>
            <div class="col-sm-4 input-group">
              <input type="text" class="form-control" name="nouveau_mont_rest" id="nouveau_mont_rest" value="" autocomplete="off" readonly="readonly">
            </div>
          </div>


          <div class="form-group">
            <label class="col-sm-4 control-label">Mode de paiement :<span class="asterisk">*</span> </label>
            <div class="col-sm-4">
              <select class="form-control chosen-select" name="mode_paiement" id="mode_paiement" onchange="mode_paiement_selected()">
                <option value="1">Cash</option>
                <option value="2">Chèque</option>
                <option value="3">Virement Bancaire</option>
              </select>
            </div>
          </div>

          <div class="form-group" id="div_banque" style="display: none;">
            <label class="col-sm-4 control-label">Banque :<span class="asterisk">*</span> </label>
            <div class="col-sm-4">
              <select class="form-control chosen-select" name="banque_denomination" id="banque_denomination">
                <option value="0">Faites le bon choix</option>
                <?php
                if (sizeof($banques) > 0) {
                  foreach ($banques as $each_banque) :
                    echo '<option value="' . $each_banque['id'] . '">' . $each_banque["numero_compte"] . ' : ' . $each_banque["banque"] . ' </option>';
                  endforeach;
                }
                ?>
              </select>
            </div>
          </div>

          <div class="form-group" id="div_cheque" style="display: none;">
            <label class="col-sm-4 control-label">Numéro du chèque : </label>
            <div class="col-sm-4">
              <input autocomplete="off" class="form-control" id="numero_cheque" name="numero_cheque" value="">
            </div>
          </div>


          <div class="form-group" style="display: none;" id="nextpaiement_div">
            <label class="col-sm-4 control-label">Date Prochain paiement :<span class="asterisk">*</span></label>
            <div class="col-sm-4">
              <input class="form-control datepicker" autocomplete="off" id="datefrom" name="nextpaiementdate" value="<?= date('m/d/Y') ?>">
            </div>
          </div>

        </div>


        <!-- PIEDS DU FORMULAIRE -->
        <div class="panel-footer">
          <div class="row">
            <div class="col-sm-12 col-sm-offset-4">
              <a class="btn btn-circle btn-success" onclick="submit_form()"><i class="glyphicon glyphicon-save"></i>&nbsp;Enregistrer</a>
              <span>&nbsp;</span>
              <a href="<?= yii::$app->request->baseUrl . '/' . md5('paiement_themain') ?>" type="reset" class="btn btn-circle btn-warning"><i class="glyphicon glyphicon-remove-circle"></i>&nbsp;Retour</a>
            </div>
          </div>
        </div>
        <!-- .PIEDS DU FORMULAIRE -->
      </div>

      <div class="panel panel-default">
        <!-- LIBELLE FORMULAIRE -->
        <div class="panel-heading">
          <h4 class="panel-title"><i class="fa fa-money"></i>&nbsp;&nbsp;FACTURIER</h4>
        </div>
        <!-- LIBELLE FORMULAIRE -->

        <!-- CORPS DU FORMULAIRE -->
        <div class="panel-body">
          <div class="table-responsive" id="listtable">
            <table class="table">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Date</th>
                  <th>Montant payé</th>
                  <th>Solde</th>
                  <th>Mode de paiement</th>
                  <th>No Chèque / Banque</th>
                </tr>
              </thead>
              <tbody>
                <?php
                if (is_array($historique_paiement) && sizeof($historique_paiement) > 0) {
                  foreach ($historique_paiement as $key => $each_historique_paiement) {
                    $key2 = $key + 1;
                    $mode_paiement = yii::$app->nonSqlClass->libeller_mode_paiement($each_historique_paiement["mode_paiement"]);
                    echo '
                  <tr>
                  <td>' . $key2 . '</td>
                  <td>' . $each_historique_paiement["date_maj"] . '</td>
                  <td>' . number_format($each_historique_paiement["montantpaye"]) . '</td>
                  <td>' . number_format($each_historique_paiement["montantrestant"]) . '</td>
                  <td>' . $mode_paiement . '</td>
                  <td>' . $each_historique_paiement["pj_mode_paiement"] . '</td>
                  ';
                  }
                }
                ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>


      <div class="modal fade" id="countbook_form_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="newProdCat">ENREGISTREMENT</h4>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <p>ETES-VOUS SUR DE CONTINUER CETTE OPERATION ?</p>
              </div>
            </div>
            <div class="modal-footer">
              <a href="javascript:;" type="button" onClick="$('#action_key').val('<?= $label_categorie_autre_partie ?>');  $('#countbook_form').attr('action','<?= md5("paiement_themain") ?>');  document.getElementById('countbook_form').submit();" class="btn btn-circle btn-success"><i class="glyphicon glyphicon-saved"></i>&nbsp;<?= Yii::t('app', 'oui') ?></a>

              <button type="button" class="btn btn-circle btn-warning" data-dismiss="modal"><i class="glyphicon glyphicon-remove-circle"></i>&nbsp;<?= Yii::t('app', 'non') ?></button>
            </div>
          </div>
        </div>
      </div>
      <?php
      require_once('modalAlerte.php');
      ?>
    </form>
<?php
  }
}
require_once('script/recouvrement_form_js.php');
?>