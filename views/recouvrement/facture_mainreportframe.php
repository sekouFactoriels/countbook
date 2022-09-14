<?php
  use yii\helpers\Html;
  use yii\helpers\Url;
  use yii\bootstrap\ActiveForm;
?>

<div class="panel panel-default">

  <form class="form-horizontal" action="<?= Yii::$app->request->baseUrl.'/'.md5("repport_gene")?>" id="repport_frame" name="repport_frame" method="post">
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
      <input type="hidden" name="sous_action_key" id="sous_action_key" value=""/>

      <!-- AFFICHER MESSAGE -->
      <?= Yii::$app->session->getFlash('flashmsg'); Yii::$app->session->removeFlash('flashmsg');?>

      
      <div class="row">
        <div class="col-md-12">
          <div class="portlet box my-head-table-color">

            <div class="portlet-body form">
                <form class="form-horizontal">
                <div class="form-body">

                  <div class="form-group">
                    <div class="col-md-10">
                        <!-- VISIBLE ONLY ON Larges devices -->
                        <div class="pull-right hidden-xs" style="width: 90%">
                          <a class="btn btn-circle btn-success pull-right" href="javascript:;" style="margin-left: 25px;" data-toggle="modal" onclick="window.printframe.focus(); window.printframe.print();"><i class="fa fa-print">&nbsp;</i>Imprimer</a>&nbsp;
                            <a href="<?= Yii::$app->request->baseUrl.'/'.md5('paiement_themain')?>" style="color: #000;" class="btn btn-circle btn-warning pull-right" name="retour" id="retour"> <i class="fa fa-backward">&nbsp;</i>Retour</a>
                        </div>
                        <!-- VISIBLE ONLY ON SMALL DEVICES -->
                        <div class="pull-right visible-xs" style="width: 90%">
                          <a class="btn btn-circle btn-success pull-right" href="javascript:;" style="margin-left: 25px;" data-toggle="modal" data-target="#submitmsg" onclick="window.printframe.focus();window.printframe.print();"><i class="fa fa-print"></i></a>&nbsp;
                          <a href="<?= Yii::$app->request->baseUrl.'/'.md5('rg_invent')?>" style="color: #000;" class="btn btn-circle btn-warning pull-right" name="retour" id="retour"> <i class="fa fa-backward"></i></a>
                        </div>

                    </div>
                  </div>

                  <div class="form-group">
                    <div align="center">
                      <iframe allowtransparency="1" frameborder="0" src="<?= Yii::$app->getUrlManager()->createAbsoluteUrl($asbsoluteUrlData) ?>" id="printframe" name="printframe" scrolling="auto" width="90%" height="680px"  style="border:1px #000000 solid; padding:4px 4px 4px 4px;"></iframe>
                    </div>
                  </div>
                </div>
              </form>

            </div>
          </div>
        </div>
      </div>

    </div>
  </form>
</div>
