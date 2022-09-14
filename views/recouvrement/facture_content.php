<?php

use yii\helpers\Html;
use app\assets\ReportAsset;

$autre_partie_denomination = $autre_partie_tel = $autre_partie_adresse = $categorie_facture = Null;
$entrepriseData = Yii::$app->mainCLass->getEntrepriseData($userPrimaryData['idEntreprise']);
$entreprise_id = $userPrimaryData['idEntreprise'];

if (isset($autre_partie) && is_array($autre_partie)) {

  foreach ($autre_partie as $key => $each_autre_partie) {
    switch ($categorie_autre_partie) {
      case 1:
        $autre_partie_denomination = $autre_partie['nom_appellation'];
        $autre_partie_tel = $autre_partie['tel1'];
        $autre_partie_adresse = $autre_partie['adresse'];
        $categorie_facture = "Client";
        break;

      case 2:
        $autre_partie_denomination = $each_autre_partie['denomination'];
        $autre_partie_tel = $each_autre_partie['telephone'];
        $autre_partie_adresse = $each_autre_partie['adresse_physique'];
        $categorie_facture = "Fournisseur";
        break;
    }
  }
}
?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<head>
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
            <tr>
              <th style="font-size: 24px;"><?= $entrepriseData["nom"] ?></th>
            </tr>
            <tr>
              <th><span><?= $entrepriseData["addresse"] ?></span></th>
            </tr>
            <tr>
              <th><span><?= $entrepriseData["email"] ?></span></th>
            </tr>
            <tr>
              <th><span><?= $entrepriseData["tel"] ?></span></th>
            </tr>
          </thead>
        </table>
      </div>
      <div class="col-sm-4">
        <table style="position: relative; float:  right; text-align: right;">
          <thead>
            <tr>
              <th style="font-size: 24px;"><?= $autre_partie_denomination ?></th>
            </tr>
            <tr>
              <th><span><?= $autre_partie_tel ?></span></th>
            </tr>
            <tr>
              <th><span><?= $autre_partie_adresse ?></span></th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>

  <hr />
  <?php

  if (isset($bill)) {
    foreach ($bill as $each_bill) {

  ?>
      <div class="row">
        <table width="100%">
          <tbody style="text-align: center; font-size: 15px;">
            <tr>
              <td style="font-weight: bold; font-size: 23px;">Facture&nbsp;<?= $categorie_facture ?></td>
            </tr>
            <tr>
              <td style="font-size: 15px; font-weight: bold;">No:&nbsp;<?= $each_bill['bill_number'] ?></td>
            </tr>
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
                <th>Montant global de la Facture</th>
                <th>Avance (charges & divers depenses)</th>
                <th>Montant Final à payer</th>
                <th>Total payé</th>
                <th>Montant restant</th>
              </tr>
            </thead>
            <tbody>
              <?php
              if (isset($bill_paid) && is_array($bill_paid)) {
                foreach ($bill_paid as $key => $each_validated_bill) {
                  //Preparer des variantes necessaires aux 2 cas
                  $banques = yii::$app->mainCLass->charger_banques_entreprise($entreprise_id);
                  //$fournisseur = yii::$app->fournisseurClass->get_fournisseur($each_bill['id']);
                  $historique_paiement = yii::$app->paiementClass->get_paiement_historique($each_bill['id']);
                  $tt_paid = $bill_paid[$key]['tt_paid'];
                  $dette = $each_bill['montantTotalPayer'] - $tt_paid;
                }
              }
              echo '<tr>
                <td>' . number_format($each_bill["billAmount"]) . '</td>
                <td>' . number_format($each_bill["remiseMonetaire"]) . '</td>
                <td>' . number_format($each_bill["montantTotalPayer"]) . '</td>
                <td>' . number_format($tt_paid) . '</td>
                <td>' . number_format($dette) . '</td>
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
                <th colspan="4">FACTURE</th>
              </tr>
              <?php
              if ($typeOperation == 1) {
              ?>
                <tr>
                  <th>Désignation</th>
                  <th style="width: 25em;">Qte. Ajoutées</th>
                  <th style="width: 25em;">Prix Unitaire</th>
                  <th style="width: 25em;">Montant</th>
                </tr>
              <?php
              } else if ($typeOperation == 2) {
              ?>
                <tr>
                  <th>Désignation</th>
                  <th style="width: 25em;">Qte. Ajoutées</th>
                </tr>
              <?php
              }
              ?>
            </thead>
            <tbody>
              <?php
              if ($typeOperation == 1) {
                if (isset($mvt_article) && is_array($mvt_article)) {
                  foreach ($mvt_article as $key => $each_mvt_article) {

                    $productid = base64_encode($each_mvt_article["idProduit"]);
                    $produitDtls = yii::$app->inventaireClass->produitDtls($productid);
                    $autreProduitDtls = yii::$app->inventaireClass->getPriceProductVendu($each_mvt_article['bill_bill_number']);

                    if ($produitDtls) $produitDtls = json_decode($produitDtls);
                    //die(var_dump($produitDtls));
                    echo '<tr>
                      <td>' . $produitDtls->libelle . '</td>
                      <td>' . $each_mvt_article["qteMaped"] . '</td>
                      <td>' . number_format($autreProduitDtls[$key]["spvtotal"] / $each_mvt_article["qteMaped"]) . '</td>
                      <td>' . number_format($autreProduitDtls[$key]["spvtotal"]) . '</td>
                    <tr>';
                  }
                }
              } else if ($typeOperation == 2) {
                if (isset($mvt_article) && is_array($mvt_article)) {
                  foreach ($mvt_article as $key => $each_mvt_article) {

                    $productid = base64_encode($each_mvt_article["idProduit"]);
                    $produitDtls = yii::$app->inventaireClass->produitDtls($productid);;

                    if ($produitDtls) $produitDtls = json_decode($produitDtls);
                    //die(var_dump($produitDtls));
                    echo '<tr>
                      <td>' . $produitDtls->libelle . '</td>
                      <td>' . $each_mvt_article["qteMaped"] . '</td>
                    <tr>';
                  }
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
                <td style="width: 25em;"><?= number_format($each_bill['billAmount']) ?></td>
              </tr>

              <tr>
                <td style="text-align: right; font-weight: bold;">Remise (francs)&nbsp;&nbsp;</td>
                <td style="width: 25em;"><?= number_format($each_bill['remiseMonetaire']) ?></td>
              </tr>

              <tr>
                <td style="text-align: right; font-weight: bold;">Montant Final&nbsp;&nbsp;</td>
                <td style="width: 25em;"><?= number_format($each_bill['montantTotalPayer']) ?></td>
              </tr>


            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
      </div>


      <div class="row" style="margin-top : 18px;">
        <div class="col-md-12">
          <table border="1" width="100%">
            <thead>
              <tr>
                <th colspan="6">HISTORIQUE DE PAIEMENT</th>
              </tr>
              <tr>
                <th>#</th>
                <th>Date</th>
                <th>Montant versé</th>
                <th>Montant restant</th>
                <th>Mode de paiement</th>
                <th>Identifiant</th>
              </tr>
            </thead>
            <tbody>
              <?php
              if (is_array($paiement_historique) && sizeof($paiement_historique) > 0) {
                foreach ($paiement_historique as $key => $each_historique_paiement) {
                  $key2 = $key + 1;
                  $mode_paiement = yii::$app->nonSqlClass->libeller_mode_paiement($each_historique_paiement["mode_paiement"]);
                  echo '
                  <tr>
                  <td>' . $key2 . '</td>
                  <td>' . $each_historique_paiement["date_maj"] . '</td>
                  <td>' . number_format($each_historique_paiement["montantpaye"]) . '</td>
                  <td>' . number_format($each_historique_paiement["montantrestant"]) . '</td>
                  <td>' . $mode_paiement . '</td>
                  <td>' . $each_historique_paiement["pj_mode_paiement"] . '</td>
                  ';
                }
              }
              ?>
            </tbody>
          </table>
        </div>
      </div>

  <?php

    }
  }
