
<div class="row">
  <div class="col-sm-12 col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading">
        <div class="panel-btns">
          <!-- <a href="javascript:;" onclick="$('#addBanque').modal('show');" style="color: #fff;" class="btn btn-circle btn-primary" > <i class="fa fa-filter">&nbsp;</i></a> -->
          <a href="javascript:;" onClick="$('#addBanque').modal('show');" style="color: #000;" class="btn btn-circle btn-white" name="newBanque_form" id="newBanque_form"> <i class="fa fa-plus">&nbsp;</i></a>
        </div>
        <h4 class="panel-title"><i class="glyphicon glyphicon-list-alt">&nbsp;</i><?= yii::t('app','comptbancaire')?></h4>
      </div>
      <div class="panel-body">
        <form action="<?= Yii::$app->request->baseUrl.'/'.md5("parametre_comptebancaire")?>" id="banque_form" name="banque_form" method="post">
          <?= Yii::$app->nonSqlClass->getHiddenFormTokenField();
                $token2 = Yii::$app->getSecurity()->generateRandomString();
                $token2 = str_replace('+','.',base64_encode($token2));
          ?>
          <input type="hidden" name="_csrf" value="<?=yii::$app->request->getcsrftoken()?>">
          <input type="hidden" name="action_key" id="action_key" value="">
          <input type="hidden" name="action_on_this" id="action_on_this" value="">
          <input type="hidden" name="editelibelle" id="editelibelle" value="">
          <input type="hidden" name="editenumero" id="editenumero" value="">
          <input type="hidden" name="editeadresse" id="editeadresse" value="">

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
                      <th >Banque</th>
                      <th style="width: 300px;">Numéro du Compte</th>
                      <th>Adresse</th>
                      <th>Inser&#233; Par</th>
                      <th>Date de Création</th>
                      <th style="text-align: center;">Action</th>
                    </tr>
                    <!-- FIN : LISTE DES COLONNES DE LA TABLE VIEW DES PRODUITS -->
                  </thead>

                  <tbody>
                  <?php
                      if(is_array($banques) && sizeof($banques) > 0){
                        foreach ($banques as $key => $value) {
                          $key2 = ++$key;
                          $bcolor = ($value['statut'] == '2') ? '#990000' : '';
                          $color = ($value['statut'] == '2') ? '#fff' : '';
                          echo '<tr>
                            <td style="background-color: '.$bcolor.'; color: '.$color.';">'.$key2.'</td>
                            <td>'.$value['banque'].'</td>
                            <td>'.$value['numero_compte'].'</td>
                            <td>'.$value['adresse'].'</td>
                            <td>'.Yii::$app->mainCLass->getUserFullnameBaseId($value['idActeur']).'</td>
                            <td>'.$value['dte_maj'].'</td>
                            
                            <td style="text-align: center;"><a href="javascript:;" Class="btn btn-circle btn-primary" onClick="$(\'#editnumero\').val(\''.$value["numero_compte"].'\'); $(\'#editlibelle\').val(\''.$value["banque"].'\'); $(\'#editadresse\').val(\''.$value["adresse"].'\'); $(\'#action_on_this\').val(\''.base64_encode($value["id"]).'\'); $(\'#editBanque\').modal(\'show\');"><i class="fa fa-indent"></i>&nbsp;'.Yii::t("app","edit").'</a></td>
                          </tr>';
                        }
                      }else{
                        echo '<td colspan="5">'.Yii::t('app','pasEnregistrement').'</td>';
                      }
                      ?>
                  </tbody>
                </table>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>

<?php require_once('addBanque.php');?>
<?php require_once('editBanque.php');?>
