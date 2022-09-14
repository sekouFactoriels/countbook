<?php
  $userPrimaryData = unserialize($userPrimaryData);
  $entrepriseData = Yii::$app->mainCLass->getEntrepriseData($userPrimaryData['idEntreprise']);
  $formdataposted = unserialize($formdataposted);
  $sumarydata = (isset($sumarydata)) ? unserialize($sumarydata) : Null;
  require_once(Yii::$app->basePath.'/views/rggeneral/singleHeader.php');
?>
  <div class="row" style="text-align: center;">
    <strong style="font-size: 30px; text-decoration: underline;"><?= Yii::t('app','bnef_brut')?></strong>
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
            <th>T. Prix d&#39;achat</th>
            <th>T. Prix de Vente</th>
            <th>T. Prix de Vente Accord&#233;s</th>
            <th>T. Remise</th>
            <th>T. Montant per&#231;u</th>
            <th>T. Dette</th>
            <th>T. B&#233;n&#233;fice effectu&#233;</th>
          </tr>
        </thead>
        <tbody>
        <?php
        if(is_array($sumarydata) && sizeof($sumarydata)>0){
          foreach ($sumarydata as $key => $value) {
            $marge = $value["ttpva"] - $value["ttpa"];
            echo '<tr>
            <td>'.number_format($value["ttpa"]).'</td>
              <td>'.number_format($value["ttpv"]).'</td>
              <td>'.number_format($value["ttpva"]).'</td>
              <td>'.number_format($value["ttremise"]).'</td>
              <td>'.number_format($value["ttmpercu"]).'</td>
              <td>'.number_format($value["ttdette"]).'</td>
              <td>'.number_format($marge).'</td>
            </tr>';
          }
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
        echo '<table border="1" width="100%">
          <thead style="font-size: 12px;">
            <tr>
              <th>Code de vente</th>
              <th>Date / Vente</th>
              <th>S/T. Prix d&#39;achat</th>
              <th>S/T. Prix de vente</th>
              <th>Remise</th>
              <th>S/T. Prix de vente accord&#233;</th>
              <th>S/T. Dette de vente</th>
              <th>S/T. Montant per&#231;u</th>
              <th>S/T. B&#233;n&#233;fice effectu&#233;</th>
            </tr>
          </thead>
          <tbody>
          ';
            if(is_array($coredata) && sizeof($coredata)>0){
              foreach ($coredata as $key => $value) {
                $marge = $value["prixTotalVente"] - $value["prixTotalAchat"];
                echo '<tr>
                <td>'.$value['codeVente'].'</td>
                  <td>'.$value['lastUpdate'].'</td>
                  <td>'.number_format($value['prixTotalAchat']).'</td>
                  <td>'.number_format($value['prixTotalVente']).'</td>
                  <td>'.number_format($value['remiseMonetaire']).'</td>
                  <td>'.number_format($value['prixVenteAccorde']).'</td>
                  <td>'.number_format($value['detteVente']).'</td>
                  <td>'.number_format($value['montantpercu']).'</td>
                  <td>'.number_format($marge).'</td>
                </tr>';
              }
            }
          echo '</tbody>
        </table>';
      ?>

    </div>
    
  </div>
