<?php
use yii\helpers\Html;
use app\assets\ReportAsset;

$autre_partie_denomination = $autre_partie_tel = $autre_partie_adresse = $categorie_facture = Null;
$entrepriseData = Yii::$app->mainCLass->getEntrepriseData($userPrimaryData['idEntreprise']);
$entreprise_id = $userPrimaryData['idEntreprise'];

if(isset($autre_partie) && is_array($autre_partie))
{
  
  foreach ($autre_partie as $key => $each_autre_partie) 
  {
    switch($categorie_autre_partie)
    {
      case 1:
        $autre_partie_denomination = $autre_partie['nom_appellation'];
        $autre_partie_tel = $autre_partie['tel1'];
        $autre_partie_adresse = $autre_partie['adresse'];
        $categorie_facture = "Client";
      break;
    }
  }
}
?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<head >
  <meta charset="UTF-8">
  <meta name="description" content="COUNTBOOK" />
  <meta name="keywords" content="" />
  <meta name="author" content="FACTORIELS" />
  <?= Html::csrfMetaTags() ?>
  <link href="<?= Yii::$app->request->baseUrl ?>/web/santeyah/assets/admin/pages/css/printme.css" rel="stylesheet">
  <link href="<?= Yii::$app->request->baseUrl ?>/web/santeyah/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<!-- BEGIN BODY -->
<body>
  <div class="row">
    <div class="col-sm-12">
      <div class="col-sm-8">
        <table>
          <thead>
            <tr><th style="font-size: 24px;"><?= $entrepriseData["nom"] ?></th></tr>
            <tr><th><span><?= $entrepriseData["addresse"] ?></span></th></tr>
            <tr><th><span><?= $entrepriseData["email"] ?></span></th></tr>
            <tr><th><span><?= $entrepriseData["tel"] ?></span></th></tr>
          </thead>
        </table>
      </div>
      <div class="col-sm-4">
        <table style="position: relative; float:  right; text-align: right;">
          <thead>
            <tr><th style="font-size: 24px;"><?= $autre_partie_denomination ?></th></tr>
            <tr><th><span><?= $autre_partie_tel ?></span></th></tr>
            <tr><th><span><?= $autre_partie_adresse ?></span></th></tr>
          </thead>
        </table>
      </div>
    </div>
  </div>

  <hr/>
  <?php 

  if(isset($bill))
  {
    foreach($bill as $each_bill)
    {

      ?>
      <div class="row">
        <table width="100%">
          <tbody style="text-align: center; font-size: 15px;">
            <tr><td style="font-weight: bold; font-size: 23px;">Devis&nbsp;<?= $categorie_facture ?></td></tr>
            <tr><td style="font-size: 15px; font-weight: bold;">No:&nbsp;<?= $each_bill['bill_number'] ?></td></tr>
          </tbody>
        </table>
      </div>


      <div class="row" style="margin-top : 18px;">
        <div class="col-md-12">
          <table border="1" width="100%">
            <thead style="font-size: 12px;">
              <tr>
                <th colspan="5">SITUATION FINANCIERE</th>
              </tr>
              <tr>
                <th>Montant global du Devis</th>
                <th>Avance (charges & divers depenses)</th>
                <th>Montant Final à payer</th>
              </tr>
            </thead>
            <tbody>
              <?php
              if(isset($bill_paid) && is_array($bill_paid))
              {
                foreach($bill_paid as $key => $each_validated_bill)
                {
                  //Preparer des variantes necessaires aux 2 cas
                  $banques = yii::$app->mainCLass->charger_banques_entreprise($entreprise_id);
                  //$fournisseur = yii::$app->fournisseurClass->get_fournisseur($each_bill['id']);
                  $historique_paiement = yii::$app->paiementClass->get_paiement_historique($each_bill['id']);
                }
              }
              echo '<tr>
                <td>'.number_format($each_bill["billAmount"]).'</td>
                <td>'.number_format($each_bill["remiseMonetaire"]).'</td>
                <td>'.number_format($each_bill["montantTotalPayer"]).'</td>
                <td>'.number_format($tt_paid).'</td>
                <td>'.number_format($dette).'</td>
              </tr>';
              ?>
            </tbody>
          </table>
        </div>
      </div>

      <div class="row" style="margin-top : 28px;">
        <div class="col-md-12">
          <table border="1" width="100%">
            <thead style="font-size: 12px;">
              <tr>
                <th colspan="3">DETAIL DU DEVIS</th>
              </tr>

              <tr>
                <th>Désignation</th>
                <th style="width: 25em;">Qte. Ajoutées</th>
                <th>Prix Unitaire</th> 
                <th>Montant</th>
              </tr>
            </thead>
            <tbody>
              <?php 
              if(isset($mvt_article) && is_array($mvt_article))
              {
                foreach($mvt_article as $each_mvt_article)
                {
                  $productid = base64_encode($each_mvt_article["idProduit"]);
                  $produitDtls = yii::$app->inventaireClass->produitDtls($productid);
                  if($produitDtls) $produitDtls = json_decode($produitDtls);
                  //die(var_dump($produitDtls));
                  echo '<tr>
                    <td>'.$produitDtls->libelle.'</td>
                    <td>'.$each_mvt_article["qteMaped"].'</td>
                    <td>'.$each_mvt_article["qteMaped"].'</td>
                    <td>'.$each_mvt_article["qteMaped"].'</td>
                  <tr>';
                }
              }
              ?>
            </tbody>
          </table>
        </div>
      </div>

      <div class="row">
        <div class="col-md-12">
          <table border="1" width="100%">
            <thead style="font-size: 12px;">
              <tr>
                <td style="text-align: right; font-weight: bold;">Montant Total&nbsp;&nbsp;</td>
                <td style="width: 25em;"><?= number_format($each_bill['billAmount'])?></td>
              </tr>

              <tr>
                <td style="text-align: right; font-weight: bold;">Remise (francs)&nbsp;&nbsp;</td>
                <td style="width: 25em;"><?= number_format($each_bill['remiseMonetaire'])?></td>
              </tr>

              <tr>
                <td style="text-align: right; font-weight: bold;">Montant Final&nbsp;&nbsp;</td>
                <td style="width: 25em;"><?= number_format($each_bill['montantTotalPayer'])?></td>
              </tr>


            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
      </div>

      <?php

    }
  }