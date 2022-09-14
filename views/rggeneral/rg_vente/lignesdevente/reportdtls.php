<?php
  $userPrimaryData = unserialize($userPrimaryData);
  $entrepriseData = Yii::$app->mainCLass->getEntrepriseData($userPrimaryData['idEntreprise']);
  $formdataposted = unserialize($formdataposted);
  $sumarydata = (isset($sumarydata)) ? unserialize($sumarydata) : Null;
  require_once(Yii::$app->basePath.'/views/rggeneral/singleHeader.php');
?>
  <div class="row" style="text-align: center;">
    <strong style="font-size: 30px; text-decoration: underline;"><?= Yii::t('app','historiq_ca')?></strong>
  </div>

  <div class="row">
    <div class="col-sm-6">
      <address>
        <?=
          '</br><label style="font-size: 11px;">@ le : '.date("d/m/Y").'</br>
              Filtrer / date de vente </br>Du : '.Yii::$app->nonSqlClass->convert_date_to_sql_form($formdataposted['datefrom'], "M/D/Y","D/M/Y").'</br>
              Au : '.Yii::$app->nonSqlClass->convert_date_to_sql_form($formdataposted['dateto'], "M/D/Y","D/M/Y").'
          </label>';
        ?>
      </address>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <table border="1" width="100%">
        <thead style="font-size: 12px;">
          <tr>
            <th>Total Prix de Vente</th>
            <th>Total Remise</th>
            <th>Total Prix de Vente Accord&#233;s</th>
            <th>Total Montant per&#231;u</th>
            <th>Total Dette</th>
          </tr>
        </thead>
        <tbody>
        <?php
        foreach ($sumarydata as $key => $value) {
          echo '<tr>
            <td>'.number_format($value["ttpv"]).'</td>
            <td>'.number_format($value["ttremise"]).'</td>
            <td>'.number_format($value["ttpva"]).'</td>
            <td>'.number_format($value["ttmpercu"]).'</td>
            <td>'.number_format($value["ttdette"]).'</td>
          </tr>';
        }
        ?>
        </tbody>
      </table>
    </div>

  </div>

  <div class="row">
    <div class="col-md-12">
      <?php
        echo '<table border="0">&nbsp;</table><table border="0">&nbsp;</table>';
        if(is_array($coredata) && sizeof($coredata)>0){
        foreach ($coredata as $key => $value) {
            echo '<table border="0">&nbsp;</table><table border="0">&nbsp;</table>';
          echo '<table border="0" width="100%">
                  <thead style="font-size: 12px;">
                    <tr>
                      <th width="70%">&nbsp;</th>
                      <th>Code de vente: '.$value['codeVente'].'</th>
                    </tr>
                    <tr>
                      <th>&nbsp;</th>
                      <th>Date/vente : '.$value['lastUpdate'].'</th>
                    </tr>
                    <tr>
                      <th>&nbsp;</th>
                      <th>S/Total Prix de vente : '.number_format($value['prixTotalVente']).'</th>
                    </tr>
                    <tr>
                    <th>&nbsp;</th>
                    <th>Remise : '.number_format($value['remiseMonetaire']).'</th>
                    </tr>

                    <tr>
                      <th>&nbsp;</th>
                      <th>S/Total Prix de vente accord&#233;: '.number_format($value['prixVenteAccorde']).'</th>
                    </tr>

                    <tr>
                    <th>&nbsp;</th>
                    <th>S/Total Dette de vente: '.number_format($value['detteVente']).'</th>
                    </tr>

                    <tr>
                      <th>&nbsp;</th>
                      <th>S/Total Montant per&#231;u: '.number_format($value['montantpercu']).'</th>
                    </tr>

                  </thead>
                </table>';
                echo '<table border="0">&nbsp;</table>';
                // LOOPONS LA LISTE DES ARTICLES OBTENU EN DETAIL
                if(sizeof($dtlsdata) > 0){
                  echo '<table border="1" width="100%">
                    <thead style="font-size: 12px;">
                      <tr>
                        <th>Code du produit</th>
                        <th>Libell&#233;</th>
                        <th>Quantit&#233;</th>
                        <th>Prix Unitaire</th>
                        <th>S/T Prix Vente</th>
                      </tr>
                    </thead>
                    <tbody>';
                  foreach ($dtlsdata as $key => $value2) {
                    if($value2['id_dtlsvente'] == $value['id']){
                      echo '<tr>
                            <td>'.$value2["productCode"].'</td>
                            <td>'.$value2["libelle"].'</td>
                            <td>'.$value2["qteVendu"].'</td>
                            <td>'.number_format($value2["pvunitaire"]).'</td>
                            <td>'.number_format($value2["spvtotal"]).'</td>
                            </tr>';
                    }
                   }
                  echo '</thead>
                </table>';
                }
        }
      }
      ?>

    </div>




  </div>
