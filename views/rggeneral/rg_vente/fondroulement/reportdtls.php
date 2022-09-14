<?php
  $userPrimaryData = unserialize($userPrimaryData);
  $entrepriseData = Yii::$app->mainCLass->getEntrepriseData($userPrimaryData['idEntreprise']);
  $formdataposted = unserialize($formdataposted);
  $sumarydata = (isset($sumarydata)) ? unserialize($sumarydata) : Null;
  require_once(Yii::$app->basePath.'/views/rggeneral/singleHeader.php');
?>
  <div class="row" style="text-align: center;">
    <strong style="font-size: 30px; text-decoration: underline;">Fond de roulement</strong>
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
    <div class="col-sm-12">
      <table border="1" width="100%">
        <tbody>
          <tr>
            <td>Espece en caisse</td>
            <td><?= number_format($montant_caisse)?></td>
          </tr>
          
          <tr>
            <td>Cout du stock</td>
            <td><?= number_format($cout_stock)?></td>
          </tr>

          <tr style="background-color: #f7f7f7; color: #000;">
            <td><b>FOND DE ROULEMENT</b></td>
            <td></b><?= number_format($montant_caisse + $cout_stock) ?></td>
          </tr>

        </tbody>
      </table>
    </div>
  </div>
