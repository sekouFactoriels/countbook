<?php
$msg = (!empty($msg)) ? unserialize($msg) : $msg;
?>
<form action="<?= Yii::$app->request->baseUrl . '/' . md5("diver_charge") ?>" id="listeCharge" name="listeCharge" method="post">
  <?= Yii::$app->nonSqlClass->getHiddenFormTokenField();
  $token2 = Yii::$app->getSecurity()->generateRandomString();
  $token2 = str_replace('+', '.', base64_encode($token2));
  ?>
  <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>" />
  <input type="hidden" name="token2" value="<?= $token2 ?>" />
  <input type="hidden" name="action_key" id="action_key" value="" />
  <input type="hidden" name="action_on_this" id="action_on_this" value="" />
  <input type="hidden" name="msg" id="msg" value="" />
  <div class="panel panel-default">
    <div class="panel-heading">
      <div class="panel-btns">
        <a href="javascript:;" onclick="$('#action_key').val('<?= md5("ncharge")?>'); $('#listeCharge').submit();" style="color: #000;" class="btn btn-circle btn-white" name="newCharge" id="newCharge"> <i class="fa fa-plus">&nbsp;</i><?= Yii::t('app', 'diver_charge') ?></a>
      </div>
      <h4 class="panel-title"><i class="fa fa-list">&nbsp;</i><?= Yii::t('app', 'command_liste') . ' ' . Yii::t('app', 'diver_charge') ?></h4>
    </div>
    <div class="panel-body">

      <?php $msg = (!empty($msg['type'])) ? $msg : null; ?>
      <div class="<?= $msg['type'] ?>">
        <strong><?= $msg['strong'] ?></strong> <?= $msg['text'] ?>
      </div>

      <div class="table-responsive" id="listtable">
        <table class="table">
          <thead>
            <tr>
              <th colspan="2">
                <div class="form-group">
                  <label><?= Yii::t('app', 'trierSelon') ?> <span> : </span> </label>
                  <select class="form-control" id="selectCriteria" name="selectCriteria">
                    <option><?= Yii::t('app', 'slectOne') ?></option>
                    <?php
                    if (is_array($listMotif) && sizeof($listMotif) > 0) {
                      foreach ($listMotif as $key => $motifs) {
                        echo '<option value="' . $motifs['id'] . '">' . $motifs['nom'] . '</option>';
                      }
                    }
                    ?>
                  </select>
                </div>
              </th>

              <th colspan="1">
                <div class="form-group">
                  <label><?= Yii::t('app', 'donneeRecherche') ?> <span> : </span> </label>
                  <input class="form-control" type="text" id="donneeRecherche" name="donneeRecherche" value="<?= isset($_POST[Yii::$app->params['donneeRecherche']]) ? $_POST[Yii::$app->params['donneeRecherche']] : Null ?>" />
                </div>
              </th>

              <th colspan="2">
                <div class="form-group">
                  <label><?= Yii::t('app', 'margeLigne') ?> <span> : </span> </label>

                  <select class="form-control" id="limit" name="limit">
                    <option value="1" <?= isset($_POST[Yii::$app->params['limit']]) ? $_POST[Yii::$app->params['limit']] == 1 ? 'selected' : '' : '' ?>>1 - 10</option>
                    <option value="2" <?= isset($_POST[Yii::$app->params['limit']]) ? $_POST[Yii::$app->params['limit']] == 2 ? 'selected' : '' : '' ?>>1 - 20</option>
                    <option value="3" <?= isset($_POST[Yii::$app->params['limit']]) ? $_POST[Yii::$app->params['limit']] == 3 ? 'selected' : '' : '' ?>>1 - 30</option>
                    <option value="4" <?= isset($_POST[Yii::$app->params['limit']]) ? $_POST[Yii::$app->params['limit']] == 4 ? 'selected' : '' : '' ?>>1 - 40</option>
                    <option value="5" <?= isset($_POST[Yii::$app->params['limit']]) ? $_POST[Yii::$app->params['limit']] == 5 ? 'selected' : '' : '' ?>>1 - 50</option>
                    <option value="6" <?= isset($_POST[Yii::$app->params['limit']]) ? $_POST[Yii::$app->params['limit']] == 6 ? 'selected' : '' : '' ?>>1 - 50 +</option>
                  </select>
                  
                </div>
              </th>

              <th>
                <div class="form-group">
                  <a href="javascript:;" onclick="submitFilter()" class="btn btn-circle btn-primary" name="afficheBtn" id="afficheBtn" onclick="javascript:;"> <i class="fa fa-eye">&nbsp;</i><?= Yii::t('app', 'afficher') ?></a>
                  <span>&nbsp;</span>
                  <a href="javascript:;" class="btn btn-circle btn-default" name="" id=""> <i class="fa fa-refresh">&nbsp;</i><?= Yii::t('app', 'reset') ?></a>
                </div>
              </th>
              <th colspan="1">&nbsp;</th>
            </tr>
            <!-- DEBUT : LISTE DES COLONNES DE LA TABLE VIEW DES PRODUITS -->
            <tr>
              <th>#</th>
              <th>Motif</th>
              <th>Date Opération</th>
              <th style="width: 300px;">Montant</th>
              <th>Description</th>
              <th colspan="4" style="text-align: center; width: 150px;">Actions</th>
            </tr>
            <!-- FIN : LISTE DES COLONNES DE LA TABLE VIEW DES PRODUITS -->
          </thead>

          <tbody>
            <?php
            if (is_array($listeCharges) && sizeof($listeCharges) > 0) {
              foreach ($listeCharges as $key => $data) {
                $catName = $udmName = $markName = $productQte =  Null;
                $key2 = $key + 1;
                echo '
                      <tr>
                        <td>' . $key2 . '</td>
                        <td>' . $data['motifname'] . '</td>
                        <td>' . Yii::$app->nonSqlClass->convert_date_to_sql_form($data["dateOperation"], 'Y-M-D', 'D/M/Y') . '</td>
                        <td>' . number_format($data['montant']) . '</td>
                        <td>' . $data['description'] . '</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
                      ';
              }
            } else {
              echo '<tr><td colspan="11">' . Yii::t('app', 'pasEnregistrement') . '</td></tr>';
            }
            ?>
          </tbody>
        </table>
      </div>

    </div>
  </div>
</form>