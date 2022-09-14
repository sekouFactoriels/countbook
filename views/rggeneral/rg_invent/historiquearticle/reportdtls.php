<?php
  $userPrimaryData = unserialize($userPrimaryData);
  $entrepriseData = Yii::$app->mainCLass->getEntrepriseData($userPrimaryData['idEntreprise']);
  $formdataposted = unserialize($formdataposted);
  $data = isset($data) ? unserialize($data) : Null;
  require_once(Yii::$app->basePath.'/views/rggeneral/singleHeader.php');
?>
  <div class="row" style="text-align: center;">
    <strong style="font-size: 30px; text-decoration: underline;" ><?= Yii::t('app','historic_produit');?></strong>
  </div>

  <div class="row">
    <div class="col-sm-6">
      <address>
        <?php
          if(!empty($formdataposted['code'])){
            $code = $formdataposted['code'];
          }else {
            $code = 'N/A';
          }
          
          $from = Yii::$app->nonSqlClass->convert_date_to_sql_form($formdataposted['datefrom'], "M/D/Y");
          $to = Yii::$app->nonSqlClass->convert_date_to_sql_form($formdataposted['dateto'], "M/D/Y");

        echo '</br><label style="font-size: 11px;">@ le : '.date("d/m/Y").'</br>
              Filtrer par date de transaction </br>Du : '.Yii::$app->nonSqlClass->convert_date_to_sql_form($formdataposted['datefrom'], "M/D/Y","D/M/Y").'</br>
              Au : '.Yii::$app->nonSqlClass->convert_date_to_sql_form($formdataposted['dateto'], "M/D/Y","D/M/Y").'</br>
              Filtrer par Code </br>
              Code specifi&#233; : '.$code.'
          </label>';
        ?>
      </address>
    </div>
  </div>
  <?php
    foreach ($data as $key => $value) :


    echo '<table border="0" width="100%">
            <thead style="font-size: 12px;">
              <tr>
                <th width="70%">&nbsp;</th>
                <th>Code : '.$value['productCode'].'</th>
              </tr>
              <tr>
                <th>&nbsp;</th>
                <th>Libelle : '.$value['libelle'].'</th>
              </tr>
              <tr>
                <th>&nbsp;</th>
                <th>UDM : '.$value['produdm'].'</th>
              </tr>
            </thead>
          </table>';

?>
      <table border="1" width="100%">
        <thead style="font-size: 14px;">
          <th width="4%">&nbsp;N&#176;</th>
          <th>&nbsp;Type de Mouvement</th>
          <th>&nbsp;Quantit&#233;</th>
          <th>&nbsp;Date de Mouvement</th>
          <th>&nbsp;Acteur</th>
        </thead>
        <tbody>
        <?php
          $artcleHistoricalData = Yii::$app->inventaireClass->showArticleHistoriqueDetail($value['productid'], $from, $to);
          if(is_array($artcleHistoricalData) && sizeof($artcleHistoricalData) > 0){
            foreach ($artcleHistoricalData as $detailkey => $detailValue) {
              $key2 = ++$detailkey;
              echo '<tr><td>'.$key2.'</td>
                        <td>'.Yii::$app->inventaireClass->mouvTypeName($detailValue["idMaptype"]).'</td>
                        <td>'.$detailValue["qteMaped"].'</td>
                        <td>'.$detailValue["mapDte"].'</td>
                        <td>'.Yii::$app->mainCLass->getUserFullnameBaseId($detailValue["idActionneur"]).'</td>
                </tr>';
            }
          }
        ?>
        </tbody>
      </table>
      <address>  </address>
    </div>
  </div>

<?php
  endforeach;
?>
</div>
