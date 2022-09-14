<div class="panel panel-default">
  <div class="panel-heading">
    <div class="panel-btns">
      <a onClick="$('#newProductUdm').modal('show')" style="color: #000;" class="btn btn-circle btn-white" name="newUdm" id="newUdm"> <i class="fa fa-plus">&nbsp;</i><?= Yii::t('app', 'newProductLabel') ?></a>
    </div>
    <h4 class="panel-title"><i class="fa fa-list">&nbsp;</i><?= Yii::t('app', 'listudms') ?></h4>
  </div>
  <div class="panel-body">
    <form action="<?= Yii::$app->request->baseUrl . '/' . md5("inventaire_udms") ?>" id="listeUdms" name="listeUdms" method="post">
      <?=
      Yii::$app->nonSqlClass->getHiddenFormTokenField();
      $token2 = Yii::$app->getSecurity()->generateRandomString();
      $token2 = str_replace('+', '.', base64_encode($token2));
      ?>
      <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>" />
      <input type="hidden" name="token2" value="<?= $token2 ?>" />
      <input type="hidden" name="action_key" id="action_key" value="<?= md5('listGroups') ?>" />
      <input type="hidden" name="action_on_this" id="action_on_this" value="" />
      <input type="hidden" name="msg" id="msg" value="" />
      <input type="hidden" name="productUdmName" id="productUdmName" value="">
      <input type="hidden" name="productUdmDesc" id="productUdmDesc" value="">

      <div class="table-responsive" id="listtable">
        <table class="table">
          <thead>
            <!-- DEBUT : LISTE DES COLONNES DE LA TABLE VIEW DES PRODUITS -->
            <tr>
              <th>#</th>
              <th>Nom</th>
              <th>Description</th>
              <th>Action</th>
            </tr>
            <!-- FIN : LISTE DES COLONNES DE LA TABLE VIEW DES PRODUITS -->
          </thead>

          <tbody>
            <?php
            if (is_array($entrepriseUdms) && sizeof($entrepriseUdms) > 0) {
              foreach ($entrepriseUdms as $key => $data) {
                $key2 = $key + 1;

                $btnEdit = '<a href="javascript:;" Class="btn btn-circle btn-warning" onClick="$(\'#productUdmNameUpdate\').val(\'' . $data["nom"] . '\'); $(\'#productUdmDescUpdate\').val(\'' . ($data["description"]) . '\'); $(\'#action_on_this\').val(\'' . base64_encode($data["id"]) . '\'); $(\'#updateProductUdm\').modal(\'show\');"><i class="fa fa-indent"></i>&nbsp;' . Yii::t("app", "edit") . '</a>';
                
                echo '
                      <tr>
                        <td>' . $key2 . '</td>
                        <td>' . $data["nom"] . '</td>
                        <td>' . $data["description"] . '</td>
                        <td>' . $btnEdit . '</td>
                         
                      </tr>
                      ';
              }
            } else {
              echo '<tr><td colspan="11">' . Yii::t('app', 'no_data_found') . '</td></tr>';
            }
            
            ?>
          </tbody>
        </table>
      </div>

    </form>
  </div>
</div>
<?php
# INCLUDE PRODUCT CATEGORY
require_once('productUdm.php');
require_once('productUdmUpdate.php');
?>





