<?php
  $userPrimaryData = unserialize($userPrimaryData);
  $entrepriseData = Yii::$app->mainCLass->getEntrepriseData($userPrimaryData['idEntreprise']);
  $formdataposted = unserialize($formdataposted);
  $listarticles = isset($listarticles) ? unserialize($listarticles) : Null;
  //die(print_r($listarticles));
  require_once(Yii::$app->basePath.'/views/rggeneral/singleHeader.php');
  ?>
  <div class="row" style="text-align: center;">
    <strong style="font-size: 30px; text-decoration: underline;" ><?= Yii::t('app','invent_valeurdetailleestock')?></strong>
  </div>
  

  <div class="row">
    <div class="col-sm-6">
      <address>
        <?=
          '</br><label style="font-size: 11px;">@ le : '.date("d/m/Y").'</br>
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
          <th>&nbsp;Description</th>
          <th>&nbsp;U.V.</th>
          <th>&nbsp;Qte. Disponible</th>
          <th>&nbsp;Prix d'achat Unitaire</th>
          <th>&nbsp;Prix d'achat du stock</th>
        </thead>
        <tbody>
        <?php
          if(is_array($listarticles) && sizeof($listarticles) > 0){
            $stprixVenteUni = $achattt = $grandachattt = Null;
            foreach ($listarticles as $key => $value) {
              $stprixVenteUni = $value["qteDispo"] * $value["prixUnitaireVente"];
              $key2 = $key + 1;
              $achattt = $value["prixUnitaireAchat"] * $value["qteDispo"];
              $grandachattt = $grandachattt + $achattt;
              echo '<tr>
              <td>'.$key2.'</td>
              <td>'.$value["productCode"].'&nbsp;:&nbsp;'.$value["libelle"].'</td>
              <td>'.$value["produdm"].'</td>
              <td>'.$value["qteDispo"].'</td>
              <td>'.number_format($value["prixUnitaireAchat"]).'</td>
              <td>'.number_format($achattt).'</td>
              </tr>';
            }
            echo '<tr>
              <td colspan="5"><b>TOTAL</b></td>
              <td><b>'.number_format($grandachattt).'</b></td>
              </tr>';
          }else{
            echo '<tr><td colspan="7">'.Yii::t('app','pasEnregistrement').'</td></tr>';
          }
        ?>
        </tbody>
      </table>
    </div>

    
  </div>
