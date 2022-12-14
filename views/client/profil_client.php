
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
  <input type="hidden" name="action_on_this_val" id="action_on_this_val" value=""/>

  <div class="row">
    <div class="col-sm-6 col-md-6">
      <div class="panel panel-success panel-stat">
        <div class="panel-heading">
          <div class="stat">
            <div class="row">
              <div class="col-xs-3">
                <span class="fa fa-money" style="font-size: 400%"></span>
              </div>
              <div class="col-xs-9">
                <small class="stat-label">TOTAL MONTANT PAYE (GNF)</small>
                <h1 style="font-size: 28px;"><?= number_format($total_montant_paid[0]['montantpaye']);?></h1>
              </div>
            </div><!-- row -->
          </div><!-- stat -->
        </div><!-- panel-heading -->
      </div><!-- panel -->
    </div>
    <!-- col-sm-6 -->


    <div class="col-sm-6 col-md-6">
        <div class="panel panel-danger panel-stat">
          <div class="panel-heading">
            <div class="stat">
              <div class="row">
                <div class="col-xs-3">
                  <span class="fa fa-money" style="font-size: 400%"></span>
                </div>
                <div class="col-xs-9">
                  <small class="stat-label">TOTAL MONTANT NON PAYE (GNF)</small>
                  <h1 style="font-size: 28px;"><?= number_format($total_montant_unpaid);?></h1>
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
      <h4 class="panel-title"><i class="fa fa-link"></i>&nbsp;&nbsp;RENSEIGNEMENT GENERAUX</h4>
    </div>
    <!-- LIBELLE FORMULAIRE -->

    <!-- CORPS DU FORMULAIRE -->
    <div class="panel-body">
      <!-- AFFICHER MESSAGE -->
      <?= Yii::$app->session->getFlash('flashmsg'); Yii::$app->session->removeFlash('flashmsg');?>

      <div class="form-group">
        <label class="col-sm-4 control-label"><?= Yii::t('app','nomappelation')?> : <span class="asterisk">*</span>
        </label>
        <div class="col-sm-4 input-group">
          <input class="form-control" autocomplete="off" name="appelation" id="appelation" value="<?= isset($client['nom_appellation']) ? $client['nom_appellation'] : '' ?>"/>
          <span class="input-group-addon hidden-md hidden-lg"></span>
        </div>
      </div>

      <div class="form-group">
        <label class="col-sm-4 control-label"><?= Yii::t('app','tel')?> : <span class="asterisk">*</span>
        </label>
        <div class="col-sm-4 input-group">
          <input class="form-control" autocomplete="off" name="tel" id="tel" value="<?= isset($client['tel1']) ? $client['tel1'] : '' ?>"/>
          <span class="input-group-addon hidden-md hidden-lg"></span>
        </div>
      </div>

      <div class="form-group">
        <label class="col-sm-4 control-label"><?= Yii::t('app','email')?> : <span class="asterisk">&nbsp;</span>
        </label>
        <div class="col-sm-4 input-group">
          <input class="form-control" name="email" placeholder="Ex : test@test.com" autocomplete="off" id="email" value="<?= isset($client['email']) ? $client['email'] : '' ?>"/>
          <span class="input-group-addon hidden-md hidden-lg"></span>
        </div>
      </div>


    </div>

    <!-- PIEDS DU FORMULAIRE -->
    <div class="panel-footer">
      <div class="row">
        <div class="col-sm-12 col-sm-offset-4">
          <a class="btn btn-circle btn-success" onclick="$('#countbook_form_modal').modal('show');"><i class="glyphicon glyphicon-save"></i>&nbsp;Enregistrer</a>
          <span>&nbsp;</span>
          <a href="javascript:;" onclick="$('#action_key').val('<?= md5("lister_clients")?>'); $('#countbook_form').attr('action', '<?= md5("client_themain")?>'); $('#countbook_form').submit();" type="reset" class="btn btn-circle btn-warning"><i class="glyphicon glyphicon-remove-circle"></i>&nbsp;Retour</a>
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
              <th>No. Facture</th>
              <th>Prix TT Achat</th>
              <th>Remise</th>
              <th>TT ?? payer</th>
              <th>TT Pay??</th>
              <th>Reste ?? payer</th>
              <th style="text-align: center;">[-]</th>
              <th  style="text-align: center;">[-]</th>
            </tr>
          </thead>
          <tbody>
            <?php
            if(is_array($bills) && sizeof($bills) > 0)
            {
              foreach ($bills as $key => $each_bill) 
              {
                $badge = "success";
                $tt_paid = $dette = Null;
                $key2 = $key +1;
                $paiement_btn = '';

                if(isset($each_bill_ttpaid))
                {
                  foreach($each_bill_ttpaid as $data_ttpaid)
                  {
                    if(isset($data_ttpaid['bill_id']) && $data_ttpaid['bill_id'] == $each_bill["id"])
                    { 
                      $tt_paid = $data_ttpaid['tt_paid'];
                      $dette = $each_bill["montantTotalPayer"] - $tt_paid;
                      if(isset($dette) && $dette > 0)
                      {
                        $badge = "danger";

                        $paiement_btn = '<a href="javascript:;" Class="btn btn-circle btn-success" onclick="document.getElementById(\'action_key\').value=\''.md5(strtolower("preparer_rembourssement_client")).'\';  document.getElementById(\'action_on_this\').value=\''.$each_bill["bill_number"].'\'; document.getElementById(\'action_on_this_val\').value=\''.$each_bill["id"].'\'; $(\'#countbook_form\').attr(\'action\',\''.md5("paiement_themain").'\'); $(\'#countbook_form\').submit();">Paiement</a>';
                      }
                    }
                  }
                }
                $voirplus_btn = '<a href="javascript:;" Class="btn btn-circle btn-primary" onclick="document.getElementById(\'action_key\').value=\''.md5(strtolower("charger_facture_data")).'\';  document.getElementById(\'action_on_this\').value=\''.$each_bill["id"].'\';  document.getElementById(\'action_on_this_val\').value=\''."1".'\'; $(\'#countbook_form\').attr(\'action\',\''.md5("paiement_themain").'\'); $(\'#countbook_form\').submit();">Voir plus</a>';

                echo '
                  <tr>
                  <td>'.$key2.'</td>
                  <td>'.Yii::$app->nonSqlClass->convert_date_to_sql_form($each_bill["date_topup"], 'Y-M-D','D/M/Y').'</td>
                  <td style="font-size: 16px; font-weight: bold;">'.$each_bill["bill_number"].'</td>
                  <td>'.number_format($each_bill["billAmount"]).'</td>
                  <td>'.number_format($each_bill["remiseMonetaire"]).'</td>
                  <td>'.number_format($each_bill["montantTotalPayer"]).'</td>
                  <td>'.number_format($tt_paid).'</td>
                  <td><span class="badge badge-'.$badge.'">'.number_format($dette).'</span></td>
                  <td>'.$voirplus_btn.'</td>
                  <td>'.$paiement_btn.'</td>
                  ';
              }
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
    <!-- //CORPS DU FORMULAIRE -->
  </div>

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
                <a href="javascript:;" type="button" onClick="$('#action_key').val('<?= md5("modifier_client") ?>'); $('#action_on_this').val('<?= $client_id ?>'); $('#countbook_form').attr('action','<?= md5("client_themain") ?>');  document.getElementById('countbook_form').submit();" class="btn btn-circle btn-success"><i class="glyphicon glyphicon-saved"></i>&nbsp;<?= Yii::t('app','oui')?></a>

                <button type="button" class="btn btn-circle btn-warning" data-dismiss="modal"><i class="glyphicon glyphicon-remove-circle"></i>&nbsp;<?= Yii::t('app','non')?></button>
            </div>
        </div>
    </div>
</div>

</form>
