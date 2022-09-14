<?php
  # RECUPERATION DES INFOS DU TABLEAU DE MESSAGE
  $msg = (!empty($msg)) ? unserialize($msg) : $msg;
?>
<div class="panel panel-default">
  <div class="panel-heading">
    <small class="panel-title"><i class="glyphicon glyphicon-exchange"></i>&nbsp;<?= Yii::t('app','rapport_invent')?></small>
  </div>

  <form class="form-horizontal" action="<?= Yii::$app->request->baseUrl.'/'.md5("rg_invent")?>" id="rg_invent" name="rg_invent" method="post">
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

      <!-- Rapport sur l'inventaire -->
      <div class="row">
      <!-- Liste des articles -->
      <div class="col-sm-5 col-md-4">
        <a href="javascript:;" onclick="document.getElementById('action_key').value='<?= md5('rg_invent_listarticle')?>'; document.getElementById('rg_invent').submit()">
          <div class="panel panel-okay panel-stat">
            <div class="panel-heading">
              <div class="stat">
                <div class="row">
                  <div class="col-xs-2">
                    <span class="fa fa-exchange" style="font-size: 200%"></span>
                  </div>
                  <div class="col-xs-8 widthpanel">
                    <small class="stat-label"><?= Yii::t('app','inventaire')?></small>
                    <h4><?= Yii::t('app','liste_produit')?></h4>
                  </div>
                </div><!-- row -->
              </div><!-- stat -->
            </div><!-- panel-heading -->
          </div><!-- panel -->
        </a>
      </div><!-- col-sm-6 -->

        <!-- Analyse des articles -->
        <div class="col-sm-5 col-md-4">
          <a href="javascript:;" onclick="document.getElementById('action_key').value='<?= md5('rg_invent_analysearticle')?>'; document.getElementById('rg_invent').submit()">
            <div class="panel panel-okay panel-stat">
              <div class="panel-heading">
                <div class="stat">
                  <div class="row">
                    <div class="col-xs-2">
                      <span class="fa fa-exchange" style="font-size: 200%"></span>
                    </div>
                    <div class="col-xs-8 widthpanel">
                      <small class="stat-label"><?= Yii::t('app','stok_prod')?></small>
                      <h4><?= Yii::t('app','invent_produit')?></h4>
                    </div>
                  </div><!-- row -->
                </div><!-- stat -->
              </div><!-- panel-heading -->
            </div><!-- panel -->
          </a>
        </div><!-- fin du widget -->


        <!-- Analyse des articles -->
        <div class="col-sm-5 col-md-4">
          <a href="javascript:;" onclick="document.getElementById('action_key').value='<?= md5('rg_invent_valeurdetailleestock')?>'; document.getElementById('rg_invent').submit()">
            <div class="panel panel-okay panel-stat">
              <div class="panel-heading">
                <div class="stat">
                  <div class="row">
                    <div class="col-xs-2">
                      <span class="fa fa-exchange" style="font-size: 200%"></span>
                    </div>
                    <div class="col-xs-8 widthpanel">
                      <small class="stat-label"><?= Yii::t('app','stok_prod')?></small>
                      <h4><?= Yii::t('app','invent_valeurdetailleestock')?></h4>
                    </div>
                  </div><!-- row -->
                </div><!-- stat -->
              </div><!-- panel-heading -->
            </div><!-- panel -->
          </a>
        </div><!-- fin du widget -->


        
      </div><!-- row-->

      <!-- Rapport sur l'inventaire line 2 -->
      <div class="row">
        <!-- Liste des articles -->
        <div class="col-sm-5 col-md-4">
          <a href="javascript:;" onclick="document.getElementById('action_key').value='<?= md5('rg_invent_articlealertstok')?>'; document.getElementById('rg_invent').submit()">
            <div class="panel panel-okay panel-stat">
              <div class="panel-heading">
                <div class="stat">
                  <div class="row">
                    <div class="col-xs-2">
                      <span class="fa fa-exchange" style="font-size: 200%"></span>
                    </div>
                    <div class="col-xs-8 widthpanel">
                      <small class="stat-label"><?= Yii::t('app','stocksecurity')?></small>
                      <h4><?= Yii::t('app','produit_court_stock')?></h4>
                    </div>
                  </div><!-- row -->
                </div><!-- stat -->
              </div><!-- panel-heading -->
            </div><!-- panel -->
          </a>
        </div><!-- col-sm-6 -->

          <!-- Categories des articles -->
          <div class="col-sm-5 col-md-4">
            <a href="javascript:;" onclick="document.getElementById('action_key').value='<?= md5('rg_invent_historiquearticle') ?>'; document.getElementById('rg_invent').submit()">
              <div class="panel panel-okay panel-stat">
                <div class="panel-heading">
                  <div class="stat">
                    <div class="row">
                      <div class="col-xs-2">
                        <span class="fa fa-exchange" style="font-size: 200%"></span>
                      </div>
                      <div class="col-xs-8 widthpanel">
                        <small class="stat-label"><?= Yii::t('app','stok_prod')?></small>
                        <h4><?= Yii::t('app','historic_produit');?></h4>
                      </div>
                    </div><!-- row -->
                  </div><!-- stat -->
                </div><!-- panel-heading -->
              </div><!-- panel -->
            </a>
          </div><!-- fin du widget -->



      </div><!-- row-->


      <!-- Rapport sur l'inventaire line 2 -->
      <div class="row">
      <!-- Liste des articles -->


      </div><!-- row-->


  </form>
</div>
