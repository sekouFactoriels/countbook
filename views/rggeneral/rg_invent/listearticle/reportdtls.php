<?php
  $userPrimaryData = unserialize($userPrimaryData);
  $entrepriseData = Yii::$app->mainCLass->getEntrepriseData($userPrimaryData['idEntreprise']);
  $formdataposted = unserialize($formdataposted);
  $listarticles = isset($listarticles) ? unserialize($listarticles) : Null;
  require_once(Yii::$app->basePath.'/views/rggeneral/singleHeader.php');
?>
  <div class="row" style="text-align: center;">
    <strong style="font-size: 30px; text-decoration: underline;" ><?= Yii::t('app','liste_produit')?></strong>
  </div>
  

  <div class="row">
    <div class="col-sm-6">
      <address>
        <?=
          '</br><label style="font-size: 11px;">@ le : '.date("d/m/Y").'</br>
              Filtrer par date d&#39;enregistrement </br>Du : '.Yii::$app->nonSqlClass->convert_date_to_sql_form($formdataposted['datefrom'], "M/D/Y","D/M/Y").'</br>
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
          <th width="10%">&nbsp;Type</th>
          <th width="20%">&nbsp;Libell&#233;</th>
          <th width="10%">&nbsp;Categories</th>
          <th width="17%">&nbsp;Prix d'achat unitaire</th>
          <th width="17%">&nbsp;Prix de vente Unitaire</th>
        </thead>
        <tbody>
        <?php
          if(is_array($listarticles) && sizeof($listarticles) > 0){
            foreach ($listarticles as $key => $value) {
              $key2 = $key + 1;
              echo '<tr>
              <td>'.$key2.'</td>
              <td>'.$value["productCode"].'</td>
              <td>'.Yii::$app->nonSqlClass->articleTypeLabel($value["type"]).'</td>
              <td>'.$value["libelle"].'</td>
              <td>'.$value["nom"].'</td>
              <td>'.number_format($value["prixUnitaireAchat"]).'</td>
              <td>'.number_format($value["prixUnitaireVente"]).'</td>
              </tr>';
            }
          }else{
            echo '<tr><td colspan="7">'.Yii::t('app','pasEnregistrement').'</td></tr>';
          }
        ?>
        </tbody>
      </table>
    </div>

  </div>
