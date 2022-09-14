<?php
  $userPrimaryData = unserialize($userPrimaryData);
  $entrepriseData = Yii::$app->mainCLass->getEntrepriseData($userPrimaryData['idEntreprise']);
  $formdataposted = unserialize($formdataposted);
  $sumarydata = (isset($sumarydata)) ? unserialize($sumarydata) : Null;
  require_once(Yii::$app->basePath.'/views/rggeneral/singleHeader.php');
?>
  <div class="row" style="text-align: center;">
    <strong style="font-size: 30px; text-decoration: underline;"><?= Yii::t('app','bnef_net')?></strong>
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
        <tbody>
        <?php
        $chargeAndDette = Null;
        foreach ($sumarydata as $key => $value) {
          $marge = $value["ttpva"] - $value["ttpa"];
          $chargeAndDette = $charges + $value["ttdette"];
          $margeNet = $marge - $chargeAndDette ;
          echo '
          <tr>
            <td><b>T. Prix d&#39;achat</b></td>
            <td>'.number_format($value["ttpa"]).'</td>
          </tr>
          <tr>
            <td><b>T. Prix de Vente</b></td>
            <td>'.number_format($value["ttpv"]).'</td>
          </tr>
          <tr>
            <td><b>T. Prix de Vente Accord&#233;s</b></td>
            <td>'.number_format($value["ttpva"]).'</td>
          </tr>

          <tr>
            <td><b>T. Remise</b></td>
            <td>'.number_format($value["ttremise"]).'</td>
          </tr>

          <tr>
            <td><b>T. Montant per&#231;u</b></td>
            <td>'.number_format($value["ttmpercu"]).'</td>
          </tr>

          <tr style="background-color: #f7f7f7; color: #000;">
            <td style="text-align: center;"><b>B&#233;n&#233;fice Brut effectu&#233;</b></td>
            <td>'.number_format($marge).'</td>
          </tr>

          <tr>
            <td><b>T. Dette</b></td>
            <td>'.number_format($value["ttdette"]).'</td>
          </tr>

          <tr>
            <td><b>T. Charges</b></td>
            <td>'.number_format($charges).'</td>
          </tr>

          <tr  style="background-color: #f7f7f7; color: #000;">
            <td style="text-align: center;"><b>B&#233;n&#233;fice Net effectu&#233;</b></td>
            <td>'.number_format($margeNet).'</td>
          </tr>
          ';
        }
        ?>
        </tbody>
      </table>
    </div>
    <!-- Fin de la vue mobile -->
  </div>
