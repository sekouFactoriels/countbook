<?php
unset($_SESSION['msg']);
require_once 'script/_boss_js.php';
?>

<fieldset>
  <legend><?= Yii::t('app', 'stat_mois') . ' ' . strtoupper(date('M')) ?> </legend>
</fieldset>

<div class="row">

  <!-- CUMUL RECETTES -->
  <div class="col-sm-12 col-md-4">
    <div class="panel panel-white panel-stat">
      <div class="panel-heading">
        <div class="stat">
          <div class="row">
            <div class="col-xs-4">
              <span class="fa fa-money" style="font-size: 400%;"></span>
            </div>
            <div class="col-xs-8">
              <small class="stat-label"><?= yii::t('app', 'chffr_aff_mois')  ?></small>
              <h1 style="font-size: 28px;"><?= number_format($montlysaleviewbyadmin); ?></h1>
            </div>
          </div>
          <div class="mb15"></div>
          <div class="row">
            <div class="col-xs-8">
              <small class="stat-label"><?= yii::t('app', 'obj_mois') ?></small>
              <h4><?= number_format($monthlysaletargetpoint); ?></h4>
            </div>
            <div class="col-xs-3">
              <h4 style="color: #3c763d;"><?= number_format($montlysalegrowthbyadmin, 2, '.', '') ?>%</h4>
            </div>
            <div class="col-xs-1">
              <a href="javascript:;" onclick="$('#action_key').val('<?= md5("vente")?>'); $('#countbook_form').submit();" style="color: #000;" class="btn btn-circle btn-white btn-xs" name="newProduct" id="newProduct">Parcourir&nbsp;
                <span class="fa fa-arrow-circle-right"></span></a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- // CUMUL RECETTES // -->

  <!-- CUMUL CHARGES -->
  <div class="col-sm-12 col-md-4">
    <div class="panel panel-white panel-stat">
      <div class="panel-heading">
        <div class="stat">
          <div class="row">
            <div class="col-xs-4">
              <span class="fa fa-money" style="font-size: 400%;"></span>
            </div>
            <div class="col-xs-8">
              <small class="stat-label"><?= yii::t('app', 'cuml_charges_mois')  ?></small>
              <h1 style="font-size: 28px;"><?= number_format($cumulCharge); ?></h1>
            </div>
          </div>
          <div class="mb15"></div>
          <div class="row">
            <div class="col-xs-10">
              <small class="stat-label">&nbsp;Valeur Net du Stock</small>
              <h4>&nbsp;<?= number_format($valeurStock); ?></h4>
            </div>
            <div class="col-xs-1">
              <a href="javascript:;" onclick="$('#action_key').val('<?= md5("charge")?>'); $('#countbook_form').submit();" style="color: #000;" class="btn btn-circle btn-white btn-xs" name="newProduct" id="newProduct">Parcourir&nbsp;
                <span class="fa fa-arrow-circle-right"></span></a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- // CUMUL CHARGES // -->
  <?php
  if ($solde >= 0) {
  ?>
    <!-- SOLDE -->
    <div class="col-sm-12 col-md-4">
      <div class="panel panel-default widget-weather">
        <div class="panel-body">
          <div class="row">
            <div class="col-xs-12 temp text-center solde-widget">
              <h5><?= yii::t('app', 'marge_mois')  ?></h5>
              <h2><?= number_format($solde); ?></h2>
              <h5>&nbsp;</h5>
            </div>
          </div>
        </div><!-- panel-body -->
      </div><!-- panel -->
    </div>
  <?php
  } else {

  ?>
    <!-- SOLDE -->
    <div class="col-sm-12 col-md-4">
      <div class="panel panel-default widget-weather">
        <div class="panel-body">
          <div class="row">
            <div style="color: red;" class="col-xs-12 temp text-center solde-widget">
              <h5>Solde</h5>
              <h2><?= number_format($solde); ?></h2>
              <h5>&nbsp;</h5>
            </div>
          </div>
        </div><!-- panel-body -->
      </div><!-- panel -->
    </div>
  <?php
  }
  ?>
 

</div><!-- col-sm-6 -->

<!-- Diagramme
<div class="row">
  <div class="col-sm-12 col-md-12">
    <div class="panel panel-default">
      <div class="panel-body">
        <div class="row">
          <div class="col-sm-12">
            <h5 class="subtitle mb5">Network Performance</h5>
            <p class="mb15">Duis autem vel eum iriure dolor in hendrerit in vulputate...</p>
            <canvas id="bar-chart-grouped" width="800" height="350"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>
</div> 
-->


<!-- TABLEAU DE CREANCES ET DETTES // -->
<div class="row">
  <?php 
    require_once('content/tableau_creance_dette.php');
  ?>
</div>
  <!-- // TABLEAU DE CREANCES ET DETTES // -->



<!-- TOP 10  // -->
<div class="row">
  <?php 
    require_once('content/top_10_clients.php');
    require_once('content/top_10_articles.php');
  ?>
</div>
<!-- // TOP 10 // -->


<!-- THIS VIEW IS FOR MAIN USER -->
<fieldset>
  <legend>Vue globale sur le stock :<?= '&nbsp;' . date('M') . '&nbsp;' . Yii::t('app', 'parutilisateur') ?> </legend>
</fieldset>

<div class="row">

  <div class="col-md-12">
    <div class="panel panel-default panel-alt widget-messaging">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-exchange" style="font-size: 13pt;"></i>&nbsp;&nbsp;<?= Yii::t('app', 'detailProd') ?></h3>
      </div>
      <div class="panel-body" id="detailArticle">
        <!-- FORMULONS UNE REQUETTE AJAX POUR RECUPERER CES VALEURS -->
        <div style="text-align:center;color:#000;line-height:100px;width:100%;"><i class="fa fa-spin fa-spinner" style="font-size: 35px;"></i> <span style="font-size: 20px;">&nbsp;<?= Yii::t('app', 'analsecour') ?></span></div>
        <!-- FORMULONS UNE REQUETTE AJAX POUR RECUPERER CES VALEURS -->
      </div><!-- panel-body -->
    </div><!-- panel -->
  </div><!-- col-md-6 -->
</div>