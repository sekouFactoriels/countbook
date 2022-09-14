<?php
  # RECUPERATION DES INFOS DU TABLEAU DE MESSAGE
  $msg = (!empty($msg)) ? unserialize($msg) : $msg;
?>
<div class="panel panel-default">
  <div class="panel-heading">
    <small class="panel-title"><i class="glyphicon glyphicon-exchange"></i>&nbsp;<?= Yii::t('app','rapport_diver')?></small>
  </div>

  <form class="form-horizontal" action="<?= Yii::$app->request->baseUrl.'/'.md5("rg_diver")?>" id="rg_diver" name="rg_diver" method="post">
    <!-- DEBUT PANEL BODY -->
    <div class="panel-body">
      <?=
        Yii::$app->nonSqlClass->getHiddenFormTokenField();
        $token2 = Yii::$app->getSecurity()->generateRandomString();
        $token2 = str_replace('+','.',base64_encode($token2));
      ?>
      <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>"/>
      <input type="hidden" name="token2" value="<?= $token2 ?>"/>
      <input type="hidden" name="action_key" id="action_key" value=""/>
      <input type="hidden" name="action_on_this" id="action_on_this" value=""/>
      <input type="hidden" name="msg" id="msg" value=""/>

      <!-- Rapport sur les evenements -->
      <div class="row">
      <!-- Liste des articles -->
      <div class="col-sm-5 col-md-4">
        <a href="javascript:;" onclick="document.getElementById('action_key').value='<?= md5('rg_diver_event')?>'; document.getElementById('rg_diver').submit()">
          <div class="panel panel-okay panel-stat">
            <div class="panel-heading">
              <div class="stat">
                <div class="row">
                  <div class="col-xs-2">
                    <span class="fa fa-exchange" style="font-size: 200%"></span>
                  </div>
                  <div class="col-xs-8 widthpanel">
                    <small class="stat-label"><?= Yii::t('app','diver')?></small>
                    <h4><?= Yii::t('app','rap_evenement')?></h4>
                  </div>
                </div><!-- row -->
              </div><!-- stat -->
            </div><!-- panel-heading -->
          </div><!-- panel -->
        </a>
        </div><!-- col-sm-6 -->

        <!-- rapport des differents utulisateurs -->
        <div class="col-sm-5 col-md-4">
          <a href="javascript:;" onclick="document.getElementById('action_key').value='<?= md5('rg_diver_user')?>'; document.getElementById('rg_diver').submit()">
            <div class="panel panel-okay panel-stat">
              <div class="panel-heading">
                <div class="stat">
                  <div class="row">
                    <div class="col-xs-2">
                      <span class="fa fa-exchange" style="font-size: 200%"></span>
                    </div>
                    <div class="col-xs-8 widthpanel">
                      <small class="stat-label"><?= Yii::t('app','diver')?></small>
                      <h4><?= Yii::t('app','rap_utulisateur')?></h4>
                    </div>
                  </div><!-- row -->
                </div><!-- stat -->
              </div><!-- panel-heading -->
            </div><!-- panel -->
          </a>
        </div><!-- fin du widget -->

        <!-- depenses effectués -->
        <div class="col-sm-5 col-md-4">
          <a href="javascript:;" onclick="document.getElementById('action_key').value='<?= md5('rg_diver_depense') ?>'; document.getElementById('rg_diver').submit()">
            <div class="panel panel-okay panel-stat">
              <div class="panel-heading">
                <div class="stat">
                  <div class="row">
                    <div class="col-xs-2">
                      <span class="fa fa-exchange" style="font-size: 200%"></span>
                    </div>
                    <div class="col-xs-8 widthpanel">
                      <small class="stat-label"><?= Yii::t('app','diver')?></small>
                      <h4><?= Yii::t('app','rap_depense');?></h4>
                    </div>
                  </div><!-- row -->
                </div><!-- stat -->
              </div><!-- panel-heading -->
            </div><!-- panel -->
          </a>
        </div><!-- fin du widget -->
      </div><!-- row-->


      </div><!-- row-->




  </form>
</div>
