<?php
  $userPrimaryData = unserialize($userPrimaryData);
  $entrepriseData = Yii::$app->mainCLass->getEntrepriseData($userPrimaryData['idEntreprise']);
  $formdataposted = unserialize($formdataposted);
  $data = (isset($data)) ? unserialize($data) : Null;
  require_once(Yii::$app->basePath.'/views/rggeneral/singleHeader.php');
?>
  <div class="row hidden-xs" style="text-align: center;">
    <strong style="font-size: 30px; text-decoration: underline;" ><?= Yii::t('app','car_produit')?></strong>
  </div>
  <div class="row visible-xs" style="text-align: center;">
    <strong style="font-size: 20px; text-decoration: underline;" ><?= Yii::t('app','car_produit')?></strong>
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
        <thead style="font-size: 14px;">
          <th width="4%">&nbsp;N&#176;</th>
          <th width="10%">&nbsp;Code</th>
          <th width="20%">&nbsp;Libell&#233;</th>
          <th width="17%">&nbsp;Montant re&#231;u</th>
        </thead>
        <tbody>
        <?php
          $ttamount = 0;
          if(is_array($data) && sizeof($data) > 0){
            foreach ($data as $key => $value) {
              $ttamount = $ttamount + $value["sousprixvente"];
              $key2 = $key + 1;
              echo '<tr>
              <td>'.$key2.'</td>
              <td>'.$value["productCode"].'</td>
              <td>'.$value["libelle"].'</td>
              <td>'.number_format($value["sousprixvente"]).'</td>
              </tr>';
            }
            echo '<tr>
              <td colspan="3"><b>TOTAL</b></td>
              <td><b>'.number_format($ttamount).'</b></td>
              </tr>';
          }else{
            echo '<tr><td colspan="7">'.Yii::t('app','pasEnregistrement').'</td></tr>';
          }
        ?>
        </tbody>
      </table>
    </div>



  </div>
