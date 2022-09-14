<div class="row">
  <div class="col-sm-12 col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading">
        <div class="panel-btns">
          <a href="javascript:;" onclick="$('#listeclient_filtre').modal('show');" style="color: #fff;" class="btn btn-circle btn-primary" > <i class="fa fa-filter">&nbsp;</i></a>
          <a href="javascript:;" onClick="$('#newmotifenrgclient').modal('show');" style="color: #000;" class="btn btn-circle btn-white" name="motifsenrgclient_form" id="motifsenrgclient_form"> <i class="fa fa-plus">&nbsp;</i></a>
        </div>
        <h4 class="panel-title"><i class="glyphicon glyphicon-list-alt">&nbsp;</i><?= yii::t('app','motifsenregcnt')?></h4>
      </div>
      <div class="panel-body">
        <form action="<?= Yii::$app->request->baseUrl.'/'.md5("parametre_motifsenrgclient")?>" id="motifs_form" name="motifs_form" method="post">
          <?= Yii::$app->nonSqlClass->getHiddenFormTokenField();
                $token2 = Yii::$app->getSecurity()->generateRandomString();
                $token2 = str_replace('+','.',base64_encode($token2));
          ?>
          <input type="hidden" name="_csrf" value="<?=yii::$app->request->getcsrftoken()?>">
          <input type="hidden" name="action_key" id="action_key" value="">
          <input type="hidden" name="action_on_this" id="action_on_this" value="">
          <input type="hidden" name="libelleeditedmotif" id="libelleeditedmotif" value="">
          <input type="hidden" name="statutmotif" id="statutmotif" value="">
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
                      <th style="width: 500px;">Libell&#233;</th>
                      <th>Inser&#233; le</th>
                      <th>Inser&#233; Par</th>
                      <th style="text-align: center;">Action</th>
                    </tr>
                    <!-- FIN : LISTE DES COLONNES DE LA TABLE VIEW DES PRODUITS -->
                  </thead>

                  <tbody>
                      <?php
                      if(is_array($motifs) && sizeof($motifs) > 0){
                        foreach ($motifs as $key => $value) {
                          $key2 = ++$key;
                          $bcolor = ($value['statut'] == '2') ? '#990000' : '';
                          $color = ($value['statut'] == '2') ? '#fff' : '';
                          echo '<tr>
                            <td style="background-color: '.$bcolor.'; color: '.$color.';">'.$key2.'</td>
                            <td>'.$value['libelle'].'</td>
                            <td>'.$value['insertLe'].'</td>
                            <td>'.Yii::$app->mainCLass->getUserFullnameBaseId($value['insertBy']).'</td>
                            <td style="text-align: center;"><a href="javascript:;" Class="btn btn-circle btn-primary" onClick="$(\'#editedlibellemotif\').val(\''.$value["libelle"].'\'); $(\'#action_on_this\').val(\''.base64_encode($value["id"]).'\'); $(\'#editionmotifenrgclient\').modal(\'show\');"><i class="fa fa-indent"></i>&nbsp;'.Yii::t("app","edit").'</a></td>
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


<div class="modal fade" id="take_we_to_success_form" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="licenceCreatAssociatLabel"><?php echo Yii::t('app','valid');?></h4>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <p><?= Yii::t('app','enrgSuccess')?></p>
              </div>
            </div>

            <div class="modal-footer">
                <button type="button" onclick="$('#motifs_form').submit();" class="btn btn-circle btn-success" data-dismiss="modal"><?= Yii::t('app','ok');?></button>
            </div>
        </div>
    </div>
</div>
<?php
  require_once('newmotif.php');
  require_once('editionmotif.php');
?>
