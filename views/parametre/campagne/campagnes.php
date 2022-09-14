<div class="row">
  <div class="col-sm-12 col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading">
        <div class="panel-btns">
          <a href="javascript:;" onclick="$('#listeclient_filtre').modal('show');" style="color: #fff;" class="btn btn-circle btn-primary"> <i class="fa fa-filter"></i></a>
          <a href="javascript:;" onClick="document.getElementById('action_key').value='<?= md5("campagnes_newcampagne") ?>'; $('#campagne_form').submit();" style="color: #000;" class="btn btn-circle btn-white" name="motifsenrgclient_form" id="motifsenrgclient_form"> <i class="fa fa-plus"></i></a>
        </div>
        <h4 class="panel-title"><i class="glyphicon glyphicon-list-alt">&nbsp;</i><?= yii::t('app', 'parametre_campagnes') ?></h4>
      </div>
      <div class="panel-body">
        <form action="<?= Yii::$app->request->baseUrl . '/' . md5("parametre_campagnes") ?>" id="campagne_form" name="campagne_form" method="post">
          <?= Yii::$app->nonSqlClass->getHiddenFormTokenField();
          $token2 = Yii::$app->getSecurity()->generateRandomString();
          $token2 = str_replace('+', '.', base64_encode($token2));
          ?>
          <input type="hidden" name="token2" value="<?= $token2 ?>" />
          <input type="hidden" name="_csrf" value="<?= yii::$app->request->getcsrftoken() ?>">
          <input type="hidden" name="action_key" id="action_key" value="">
          <input type="hidden" name="action_on_this" id="action_on_this" value="">
          <!-- DEBUT CONTENEUR DE MESSAGE  -->
          <?php
          $msg = (!empty($msg)) ? unserialize($msg) : $msg;
          $msg = (!empty($msg['type'])) ? $msg : null;
          ?>
          <div class="<?= $msg['type'] ?>">
            <strong><?= $msg['strong'] ?></strong> <?= $msg['text'] ?>
          </div>
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
                  <th style="width: 500px;"><?= Yii::t('app', 'msg') ?></th>
                  <th><?= Yii::t('app', 'statut') ?></th>
                  <th style="text-align: center;">Action</th>
                </tr>
                <!-- FIN : LISTE DES COLONNES DE LA TABLE VIEW DES PRODUITS -->
              </thead>

              <tbody>
                <?php
                if (isset($naturecampagnes) && sizeof($naturecampagnes) > 0) {
                  foreach ($naturecampagnes as $key => $nature) {
                    $statut = ($nature['statut'] == '1') ? Yii::t("app", "lance") : Yii::t("app", "nonlance");
                    $suppbtn = '<a href="javascript:;" Class="btn btn-circle btn-danger" onclick="document.getElementById(\'action_key\').value=\'' . md5("suppNature") . '\'; document.getElementById(\'action_on_this\').value=\'' . base64_encode($nature["id"]) . '\'; document.getElementById(\'campagne_form\').submit();">Supprimer</a> ';
                    $key2 = ++$key;
                    echo '<tr>
                          <td>' . $key2 . '</td>
                          <td>' . $nature['denomination'] . '</td>
                          <td>' . $nature['msg'] . '</td>
                          <td>' . $statut . '</td>
                          <td style="text-align: center;">
                          <a href="javascript:;" Class="btn btn-circle btn-primary" onClick="$(\'#action_key\').val(\'' . md5("campagnes_editioncampagne") . '\'); $(\'#action_on_this\').val(\'' . base64_encode($nature["id"]) . '\'); $(\'#campagne_form\').submit();"><i class="fa fa-indent"></i>&nbsp;' . Yii::t("app", "edit") . '</a>
                          '.$suppbtn.'
                          </td>
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