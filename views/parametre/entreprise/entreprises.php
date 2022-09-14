<div class="row">
  <div class="col-sm-12 col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading">
        <div class="panel-btns">
          <a href="javascript:;" onclick="$('#listeclient_filtre').modal('show');" style="color: #fff;" class="btn btn-circle btn-primary"> <i class="fa fa-filter"></i></a>
          <?php if (isset($iswiz) && $iswiz == 1) { ?>
            <a href="javascript:;" onClick="document.getElementById('action_key').value='<?= md5("entreprises_newentreprise") ?>'; $('#entreprises_form').submit();" style="color: #000;" class="btn btn-circle btn-white" name="newentreprise" id="newentreprise"> <i class="fa fa-plus"></i></a>
          <?php } ?>
        </div>
        <h4 class="panel-title"><i class="fa fa-building-o">&nbsp;</i><?= yii::t('app', 'parametre_entreprises') ?></h4>
      </div>
      <div class="panel-body">
        <form action="<?= Yii::$app->request->baseUrl . '/' . md5("parametre_entreprises") ?>" id="entreprises_form" name="entreprises_form" method="post">
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
                  <th><?= Yii::t('app', 'denomination') ?></th>
                  <th><?= Yii::t('app', 'stok_dtls') ?></th>
                  <th class="text-center"><?= Yii::t('app', 'filial') ?></th>
                  <th class="text-center"><?= Yii::t('app', 'forfait') ?></th>
                  <th style="text-align: center;">Action</th>
                </tr>
                <!-- FIN : LISTE DES COLONNES DE LA TABLE VIEW DES PRODUITS -->
              </thead>

              <tbody>
                <?php
                if (isset($entreprises) && sizeof($entreprises) > 0) {
                  foreach ($entreprises as $key => $entreprise) {
                    $key2 = ++$key;
                    $licence = $entreprise['tl'];
                    $libLicence = "";
                    $addEntite="";

                    if ($licence == 1) {
                      $libLicence = Yii::t('app', 'essentiel');
                    } else if ($licence == 2) {
                      $libLicence = Yii::t('app', 'partenaire');
                    } elseif ($licence == 3) {
                      $libLicence = Yii::t('app', 'premium');
                    } elseif ($licence == 4) {
                      $libLicence = Yii::t('app', 'special');
                    } elseif ($licence == 5) {
                      $libLicence = Yii::t('app', 'standard');
                    } else {
                      $libLicence = "";
                    }

                    // if(($entreprise['nbEntite']==0) || ($entreprise['tl']==3)){
                    //   $addEntite='&nbsp;<a href="javascript:;" Class="btn btn-circle btn-default" onClick="$(\'#newFiliale\').modal(\'show\');"><i class="fa fa-plus"></i>&nbsp;' . Yii::t("app", "ajout") . '</a>';
                    // }
                    echo '<tr>
                          <td>' . $key2 . '</td>
                          <td>' . $entreprise['nom'] . '</td>
                          <td>
                              <table>
                                <tr><td> <b> Num Comm. :</b> ' . $entreprise['nom'] . '</td></tr>
                                <tr><td><b>' . Yii::t("app", "dteCreation") . '</b> :&nbsp;' . $entreprise['dteCreation'] . '</td></tr>
                                <tr><td><b>' . Yii::t("app", "cnt") . '</b> :&nbsp;' . $entreprise['tel'] . '</td></tr>
                                <tr><td><b>' . Yii::t("app", "email") . '</b> :&nbsp;' . $entreprise['email'] . '</td></tr>
                              </table>
                          </td>
                          <td class="text-center">
                            <span class="badge badge-danger">'.$entreprise['nbEntite'].'</span>
                          </td>
                          <td class="text-center"><span class="badge badge-success">' . $libLicence . '</span></td>
                          <td style="text-align: center;"><a href="javascript:;" Class="btn btn-circle btn-primary" onClick="$(\'#action_key\').val(\'' . md5("entreprises_editionentreprise") . '\'); $(\'#action_on_this\').val(\'' . base64_encode($entreprise["id"]) . '\'); $(\'#entreprises_form\').submit();">' . Yii::t("app", "voir") . '&nbsp;<i class="fa fa-plus"></i></a></td>
                        </tr>';
                  }
                } else echo '<td colspan="5">' . Yii::t('app', 'pasEnregistrement') . '</td>';
                ?>
              </tbody>
            </table>
          </div>
        </form>
      </div>
    </div>
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
<?php require_once('filiale.php'); ?>