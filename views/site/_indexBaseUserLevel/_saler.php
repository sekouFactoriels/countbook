<?php
 unset($_SESSION['msg']);
 require_once 'script/_saler_js.php';
?>
<!-- THIS VIEW IS FOR MAIN USER -->
<fieldset><legend><?= Yii::t('app','statsdumois') .'&nbsp;'.date('M').'&nbsp;'.Yii::t('app','parvendeur')?>  </legend></fieldset>
<div class="row">
  <div class="col-sm-6 col-md-6">
      <div class="panel panel-success panel-stat">
        <div class="panel-heading">
          <div class="stat">
            <div class="row">
              <div class="col-xs-4">
                <span class="fa fa-money" style="font-size: 400%"></span>
              </div>
              <div class="col-xs-8">
                <small class="stat-label"><?= yii::t('app','cumul_mes_ventes_mois')   ?></small>
                <h1 style="font-size: 28px;"><?= number_format($montlysalebysaler);?></h1>
              </div>
            </div><!-- row -->
          </div><!-- stat -->
        </div><!-- panel-heading -->
      </div><!-- panel -->
  </div>
  <!-- col-sm-6 -->


  <!-- Commande clients -->
  <div class="col-sm-6 col-md-6">
      <div class="panel panel-danger panel-stat">
        <div class="panel-heading">
          <div class="stat">
            <div class="row">
              <div class="col-xs-4">
                <span class="fa fa-bar-chart-o" style="font-size: 400%"></span>
              </div>
              <div class="col-xs-8">
                <small class="stat-label"><?= yii::t('app','ma_position_obj_vente')?></small>
                <h1 style="font-size: 28px;"><?= $montlysalegrowthbysaler ?></h1>
              </div>
            </div><!-- row -->
          </div><!-- stat -->
        </div><!-- panel-heading -->
      </div><!-- panel -->
  </div><!-- col-sm-6 -->

</div><!-- row -->

<div class="row">&nbsp;</div>

 <form action="<?=yii::$app->request->baseurl.'/'.md5('vente_simple') ?>" method="POST" name="vente_simple_frm" id="vente_simple_frm" >
 <input type="hidden" name="_csrf" value="<?=yii::$app->request->getcsrftoken()?>">
 <input type="hidden" name="action_key" id="action_key" value="">
 </form>

<div class="row">

  <div class="col-md-6">
    <div class="panel panel-default panel-alt widget-messaging">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-exchange" style="font-size: 13pt;"></i>&nbsp;&nbsp;<?= Yii::t('app','detailProd')?></h3>
      </div>
      <div class="panel-body" id="detailArticle">
        <!-- FORMULONS UNE REQUETTE AJAX POUR RECUPERER CES VALEURS -->
        <div style="text-align:center;color:#000;line-height:100px;width:100%;"><i class="fa fa-spin fa-spinner" style="font-size: 35px;"></i> <span style="font-size: 20px;">&nbsp;<?= Yii::t('app','analsecour')?></span></div>
        <!-- FORMULONS UNE REQUETTE AJAX POUR RECUPERER CES VALEURS -->
      </div><!-- panel-body -->
    </div><!-- panel -->
</div><!-- col-md-6 -->

<div class="col-md-6" >
  <div class="panel panel-default panel-alt widget-messaging">
    <div class="panel-heading">
      <h3 class="panel-title"><i class="fa fa-money" style="font-size: 13pt;"></i>&nbsp;&nbsp;<?= Yii::t('app','top10articlevente')?></h3>
    </div>
    <div class="table-responsive" id="top5articlevente">
      <!-- FORMULONS UNE REQUETTE AJAX POUR RECUPERER CES VALEURS -->
      <div style="text-align:center;color:#000;line-height:100px;width:100%;"><i class="fa fa-spin fa-spinner" style="font-size: 35px;"></i> <span style="font-size: 20px;">&nbsp;<?= Yii::t('app','analsecour')?></span></div>
      <!-- FORMULONS UNE REQUETTE AJAX POUR RECUPERER CES VALEURS -->
    </div><!-- table-responsive -->
  </div>
</div><!-- col-md-6 -->
</div>
