
<div class="panel panel-default">
  <div class="panel-heading">
    
    <h4 class="panel-title"><i class="fa fa-list">&nbsp;</i><?= Yii::t('app','listGroups')?></h4>
  </div>
  <div class="panel-body">
    <form action="<?= Yii::$app->request->baseUrl.'/'.md5("inventaire_groups")?>" id="listeGroup" name="listeGroup" method="post">
      <?=
            Yii::$app->nonSqlClass->getHiddenFormTokenField();
            $token2 = Yii::$app->getSecurity()->generateRandomString();
            $token2 = str_replace('+','.',base64_encode($token2));
      ?>
          <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>"/>
          <input type="hidden" name="token2" value="<?= $token2 ?>"/>
          <input type="hidden" name="action_key" id="action_key" value="<?= md5('listGroups')?>"/>
          <input type="hidden" name="action_on_this" id="action_on_this" value=""/>
          <input type="hidden" name="msg" id="msg" value=""/>

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
                  if(is_array($entrepriseGroups) && sizeof($entrepriseGroups) > 0){
                    foreach ($entrepriseGroups as $key => $data) {
                      $key2 = $key +1;
                      echo '
                      <tr>
                        <td>'.$key2.'</td>
                        <td>'.$data["nom"].'</td>
                        <td>'.$data["description"].'</td>
                        <td>
                          <a href="javascript:;" Class="btn btn-circle btn-warning"><i class="fa fa-edit"></i> Editer</a>
                        </td>
                      </tr>
                      ';
                    }
                  }else{
                    echo '<tr><td colspan="11">'.Yii::t('app','pasEnregistrement').'</td></tr>';
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
require_once('productGroup.php');
?>
