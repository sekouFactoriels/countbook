<div class="row">
  <div class="col-sm-12 col-md-12">
    <form action="<?= Yii::$app->request->baseUrl . '/' . md5("devis_themain") ?>" id="devis_form" name="devis_form" method="post">
      <?= Yii::$app->nonSqlClass->getHiddenFormTokenField();
      $token2 = Yii::$app->getSecurity()->generateRandomString();
      $token2 = str_replace('+', '.', base64_encode($token2));
      ?>
      <input type="hidden" name="_csrf" value="<?= yii::$app->request->getcsrftoken() ?>">
      <input type="hidden" name="token2" value="<?= $token2 ?>" />
      <input type="hidden" name="action_key" id="action_key" value="" />
      <input type="hidden" name="action_on_this" id="action_on_this" value="" />
      <input type="hidden" name="action_on_this_val" id="action_on_this_val" value="" />
      <div class="panel panel-default">
        <div class="panel-heading">
          <div class="panel-btns">
            <!-- <a href="javascript:;" onclick="$('#listeclient_filtre').modal('show');" style="color: #fff;" class="btn btn-circle btn-primary" > <i class="fa fa-filter">&nbsp;</i></a> -->
            <a href="javascript:;" onclick="$('#action_key').val('<?= md5("new_devis") ?>'); $('#devis_form').submit();" style="color: #000;" class="btn btn-circle btn-white" name="devis_form" id="devis_form"> <i class="fa fa-plus">&nbsp;</i></a>
          </div>
          <h4 class="panel-title"><i class="glyphicon glyphicon-list-alt">&nbsp;</i>Devis</h4>
        </div>
        <div class="panel-body">

          <div class="table-responsive" id="listtable">
            <table class="table">
              <thead>

              </thead>
            </table>
            <table class="table table-bordered table-stripped">
              <thead>
                <!-- DEBUT : LISTE DES COLONNES DE LA TABLE VIEW DES PRODUITS -->
                <tr>
                  <th>#</th>
                  <th style="width: 150px;">Date Devis</th>
                  <th style="width: 150px;">No Devis</th>
                  <th style="width: 250px;">Compte Client</th>
                  <th style="width: 100px;">Facture globale</th>
                  <th style="width: 100px;">Remise Mon&#233;taire</th>
                  <th style="width: 120px;">Montant Final (+Remise)</th>
                  <th style="width: 120px;">Fait par</th>
                  <th style="text-align: center;">Action</th>
                </tr>
                <!-- FIN : LISTE DES COLONNES DE LA TABLE VIEW DES PRODUITS -->
              </thead>

              <tbody>
                <?php
                if (is_array($devis) && sizeof($devis) > 0) {
                  foreach ($devis as $key => $value) {
                    $key2 = ++$key;

                    $facture_id = yii::$app->devisClass->getDevisId($value['codeDevis']);

                    $facture = '<a href="javascript:;" onclick="document.getElementById(\'action_key\').value=\'' . md5(("charger_facture_data")) . '\';  document.getElementById(\'action_on_this\').value=\'' . $facture_id . '\';  document.getElementById(\'action_on_this_val\').value=\'' . "1" . '\'; $(\'#devis_form\').attr(\'action\',\'' . md5("devis_themain") . '\'); $(\'#devis_form\').submit();" Class="btn btn-circle btn-info">Imprimer</a>';

                    echo '<tr>
                            <td>' . $key2 . '</td>
                            <td>' . $value['dateDevis'] . '</td>
                            <td>' . $value['codeDevis'] . '</td>
                            <td>' . Yii::$app->venteClass->getClientname($value["idClient"]) . '</td>
                            <td>' . number_format($value["montantGlobal"]) . '</td>
                            <td>' . number_format($value["montantRemise"]) . '</td>
                            <td>' . number_format($value["prixTotal"]) . '</td>
                            <td>' . Yii::$app->mainCLass->getUserFullnameBaseId($value['idActeur']) . '</td>
                            <td style="text-align: center;">' . $facture . '</td>
                          </tr>';
                  }
                } else {
                  echo '<td colspan="5">' . Yii::t('app', 'pasEnregistrement') . '</td>';
                }
                ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>


<div class="modal fade" id="take_we_to_success_form" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="licenceCreatAssociatLabel"><?php echo Yii::t('app', 'valid'); ?></h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <p><?= Yii::t('app', 'enrgSuccess') ?></p>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" onclick="$('#motifs_form').submit();" class="btn btn-circle btn-success" data-dismiss="modal"><?= Yii::t('app', 'ok'); ?></button>
      </div>
    </div>
  </div>
</div>