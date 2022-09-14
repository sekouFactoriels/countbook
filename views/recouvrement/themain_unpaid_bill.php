
<!-- BEGIN FORM-->
<form class="form-horizontal" action="" name="countbook_form" id="countbook_form" action="<?=yii::$app->request->baseurl.'/'.md5('client_themain') ?>" method="post">
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


  <div class="panel panel-default">
    <!-- LIBELLE TABLEAU -->
    <div class="panel-heading">
      <h4 class="panel-title"><i class="fa fa-link"></i>&nbsp;&nbsp;FACTURES NON SOLDEES</h4>
    </div>
    <!-- LIBELLE TABLEAU -->

    <div class="panel-body">
      <!-- AFFICHER MESSAGE -->
    <?= yii::$app->session->getFlash('flashmsg'); yii::$app->session->removeFlash('flashmsg');?>
    
      <div class="table-responsive" id="listtable">
        <table class="table">
          <thead>
            <tr>
              <th>#</th>
              <th>Date</th>
              <th><?= isset($recouvrement_sur) ? $recouvrement_sur : '' ?></th>
              <th>Prix TT Achat</th>
              <th>Remise</th>
              <th>TT à payer</th>
              <th>TT Payé</th>
              <th>Reste à payer</th>
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
                $action_btn = Null;

                if(isset($each_bill_ttpaid))
                {
                  foreach($each_bill_ttpaid as $data_ttpaid)
                  {
                    if(isset($data_ttpaid['bill_id']) && $data_ttpaid['bill_id'] == $each_bill["id"])
                    { 
                      $tt_paid = $data_ttpaid['tt_paid'];
                      //$montantTotalPayer = $each_bill["montantTotalPayer"];

                      $dette = $each_bill["montantTotalPayer"] - $tt_paid;
                      if(isset($dette) && $dette > 0)
                      {
                        $badge = "danger";
                        $denomination = '';
                        //Personnaliser le btn de rembourssement
                        switch($each_bill['categorie_autre_partie'])
                        {
                          case 1:
                            $clientdata = yii::$app->clientClass->getclientdata($each_bill["autre_partie_id"]);
                            $denomination = $clientdata['nom_appellation'];

                            $action_btn = '<a href="javascript:;" Class="btn btn-circle btn-success" onclick="document.getElementById(\'action_key\').value=\''.md5(strtolower("preparer_rembourssement_client")).'\';  document.getElementById(\'action_on_this\').value=\''.$each_bill["bill_number"].'\'; document.getElementById(\'action_on_this_val\').value=\''.$each_bill["id"].'\'; $(\'#countbook_form\').attr(\'action\',\''.md5("paiement_themain").'\'); $(\'#countbook_form\').submit();">Ajouter paiement</a>';
                          break;


                          case 2:
                            $fournisseur_data = yii::$app->fournisseurClass->get_fournisseur($each_bill["autre_partie_id"]);
                            if(isset($fournisseur_data))
                            {
                              foreach($fournisseur_data as $each_fournisseur)
                              {
                                $denomination = $each_fournisseur['denomination'];
                              }
                            }
                            $action_btn = '<a href="javascript:;" Class="btn btn-circle btn-success" onclick="document.getElementById(\'action_key\').value=\''.md5(strtolower("preparer_rembourssement_fournisseur")).'\';  document.getElementById(\'action_on_this\').value=\''.$each_bill["bill_number"].'\'; document.getElementById(\'action_on_this_val\').value=\''.$each_bill["id"].'\'; $(\'#countbook_form\').attr(\'action\',\''.md5("paiement_themain").'\'); $(\'#countbook_form\').submit();">Ajouter paiement</a>';
                          break;
                        }
                      }else{
                        $action_btn = '<a href="javascript:;" Class="btn btn-circle btn-primary" onclick="document.getElementById(\'action_key\').value=\''.md5(strtolower("mettre_a_jour_le_statut_de_la_facture")).'\';  document.getElementById(\'action_on_this\').value=\''.$each_bill["bill_number"].'\'; document.getElementById(\'action_on_this_val\').value=\''.$each_bill["id"].'\'; $(\'#countbook_form\').attr(\'action\',\''.md5("paiement_themain").'\'); $(\'#countbook_form\').submit();">Retirer de la liste</a>';
                      }
                    }
                  }
                }

                echo '
                  <tr>
                  <td>'.$key2.'</td>
                  <td>'.Yii::$app->nonSqlClass->convert_date_to_sql_form($each_bill["date_topup"], 'Y-M-D','D/M/Y').'</td>
                  <td style="font-weight: bold;">'.$denomination.' </br>[ '. $each_bill["bill_number"].' ]</td>
                  <td>'.number_format($each_bill["billAmount"]).'</td>
                  <td>'.number_format($each_bill["remiseMonetaire"]).'</td>
                  <td>'.number_format($each_bill["montantTotalPayer"]).'</td>
                  <td>'.number_format($tt_paid).'</td>
                  <td><span class="badge badge-'.$badge.'">'.number_format($dette).'</span></td>
                  <td>'.$action_btn.'</td>
                  ';
              }
            }else{
              echo '<tr><td colspan="9">'.yii::t('app','nonEnre').'</td></tr>';
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>

  </div>
</form>