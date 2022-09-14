<?php
# RECUPERATION DES INFOS DU TABLEAU DE MESSAGE
$msg = (!empty($msg)) ? unserialize($msg) : $msg;
?>
<div class="panel panel-default">
  <div class="panel-heading">
    <small class="panel-title"><i class="glyphicon glyphicon-exchange"></i>&nbsp;Rapport / Vente</small>
  </div>

  <form class="form-horizontal" action="<?= Yii::$app->request->baseUrl . '/' . md5("rg_vente") ?>" id="rg_vente" name="rg_vente" method="post">
    <!-- DEBUT PANEL BODY -->
    <div class="panel-body">
      <?=
      Yii::$app->nonSqlClass->getHiddenFormTokenField();
      $token2 = Yii::$app->getSecurity()->generateRandomString();
      $token2 = str_replace('+', '.', base64_encode($token2));
      ?>
      <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>" />
      <input type="hidden" name="token2" value="<?= $token2 ?>" />
      <input type="hidden" name="action_key" id="action_key" value="" />
      <input type="hidden" name="action_on_this" id="action_on_this" value="" />
      <input type="hidden" name="msg" id="msg" value="" />

      <!-- Rapport sur l'inventaire -->
      <div class="row">
        <!-- Liste des articles -->
        <div class="col-sm-5 col-md-4">
          <a href="javascript:;" onclick="document.getElementById('action_key').value='<?= md5('rg_vente_venteparproduit') ?>'; document.getElementById('rg_vente').submit()">
            <div class="panel panel-okay panel-stat">
              <div class="panel-heading">
                <div class="stat">
                  <div class="row">
                    <div class="col-xs-2">
                      <span class="fa fa-money" style="font-size: 200%"></span>
                    </div>
                    <div class="col-xs-8 widthpanel">
                      <small class="stat-label"><?= Yii::t('app', 'ventes') ?></small>
                      <h4><?= Yii::t('app', 'ca_produit') ?></h4>
                    </div>
                  </div><!-- row -->
                </div><!-- stat -->
              </div><!-- panel-heading -->
            </div><!-- panel -->
          </a>
        </div><!-- col-sm-6 -->

        <!-- Analyse des articles -->
        <div class="col-sm-5 col-md-4">
          <a href="javascript:;" onclick="document.getElementById('action_key').value='<?= md5('rg_vente_lignesdevente') ?>'; document.getElementById('rg_vente').submit()">
            <div class="panel panel-okay panel-stat">
              <div class="panel-heading">
                <div class="stat">
                  <div class="row">
                    <div class="col-xs-2">
                      <span class="fa fa-money" style="font-size: 200%"></span>
                    </div>
                    <div class="col-xs-8 widthpanel">
                      <small class="stat-label"><?= Yii::t('app', 'ventes') ?></small>
                      <h4><?= Yii::t('app', 'historiq_ca') ?></h4>
                    </div>
                  </div><!-- row -->
                </div><!-- stat -->
              </div><!-- panel-heading -->
            </div><!-- panel -->
          </a>
        </div><!-- fin du widget -->

        <div class="col-sm-5 col-md-4">
          <a href="javascript:;" onclick="document.getElementById('action_key').value='<?= md5('rg_vente_margeventeparproduit') ?>'; document.getElementById('rg_vente').submit()">
            <div class="panel panel-okay panel-stat">
              <div class="panel-heading">
                <div class="stat">
                  <div class="row">
                    <div class="col-xs-2">
                      <span class="fa fa-money" style="font-size: 200%"></span>
                    </div>
                    <div class="col-xs-8 widthpanel">
                      <small class="stat-label">Vente</small>
                      <h4><?= Yii::t('app', 'bnef_brut') ?></h4>
                    </div>
                  </div><!-- row -->
                </div><!-- stat -->
              </div><!-- panel-heading -->
            </div><!-- panel -->
          </a>
        </div><!-- col-sm-6 -->
      </div><!-- row-->

      <div class="row">
        <!-- Liste des articles -->
        <div class="col-sm-5 col-md-4">
          <a href="javascript:;" onclick="document.getElementById('action_key').value='<?= md5('rg_vente_margenetventeparproduit') ?>'; document.getElementById('rg_vente').submit()">
            <div class="panel panel-okay panel-stat">
              <div class="panel-heading">
                <div class="stat">
                  <div class="row">
                    <div class="col-xs-2">
                      <span class="fa fa-money" style="font-size: 200%"></span>
                    </div>
                    <div class="col-xs-8 widthpanel">
                      <small class="stat-label">Vente</small>
                      <h4><?= Yii::t('app', 'bnef_net') ?></h4>
                    </div>
                  </div><!-- row -->
                </div><!-- stat -->
              </div><!-- panel-heading -->
            </div><!-- panel -->
          </a>
        </div><!-- col-sm-6 -->


        <!-- Liste des articles -->
        <div class="col-sm-5 col-md-4">
          <a href="javascript:;" onclick="document.getElementById('action_key').value='<?= md5('rg_vente_fondroulement') ?>'; document.getElementById('rg_vente').submit()">
            <div class="panel panel-okay panel-stat">
              <div class="panel-heading">
                <div class="stat">
                  <div class="row">
                    <div class="col-xs-2">
                      <span class="fa fa-money" style="font-size: 200%"></span>
                    </div>
                    <div class="col-xs-8 widthpanel">
                      <small class="stat-label">Vente</small>
                      <h4><?= Yii::t('app', 'fondroulement') ?></h4>
                    </div>
                  </div><!-- row -->
                </div><!-- stat -->
              </div><!-- panel-heading -->
            </div><!-- panel -->
          </a>
        </div><!-- col-sm-6 -->
      </div><!-- col-sm-6 -->


  </form>
</div>