<?php
//echo print_r($coredata);
  $userPrimaryData = unserialize($userPrimaryData);
  $entrepriseData = Yii::$app->mainCLass->getEntrepriseData($userPrimaryData['idEntreprise']);
  $formdataposted = unserialize($formdataposted);
  $data = (isset($data)) ? unserialize($data) : Null;
  require_once(Yii::$app->basePath.'/views/rggeneral/singleHeader.php');
?>
 <div class="row hidden-xs" style="text-align: center;">
    <strong style="font-size: 30px; text-decoration: underline;" >Rapport des depenses</strong>
  </div>
  <div class="row visible-xs" style="text-align: center;">
    <strong style="font-size: 20px; text-decoration: underline;" >Rapport des depenses</strong>
  </div>

  <div class="row">
    <div class="col-sm-6">
      <address>
        <?=
          '</br><label style="font-size: 11px;">@ le : '.date("d/m/Y").'</br>
              Filtrer / date d&#39;evenement </br>Du : '.Yii::$app->nonSqlClass->convert_date_to_sql_form($formdataposted['datefrom'], "M/D/Y","D/M/Y").'</br>
              Au : '.Yii::$app->nonSqlClass->convert_date_to_sql_form($formdataposted['dateto'], "M/D/Y","D/M/Y").'
          </label>';
        ?>
      </address>
    </div>
  </div>

  <div class="row">
    <div class="col-sm-12 hidden-xs">
      <table border="1" width="100%">
        <thead style="font-size: 14px;">
          <th width="4%">&nbsp;N&#176;</th>
          <th width="20%">&nbsp;Motif</th>
          <th width="20%">&nbsp;Utulisateur</th>
          <th width="20%">&nbsp;Montant</th>
          <th width="17%">&nbsp;Description</th>
          <th width="17%">&nbsp;Date / Temps</th>
        </thead>
        <tbody>
	        <?php
           $montacttotal = 0;
	         if (is_array($coredata) && sizeof($coredata) >0) {
	           	foreach ($coredata as $key1 => $value) {
               $montacttotal = $montacttotal + $value["montant"];
      	           		$key1 = $key1 + 1;
      	           		echo '<tr>
                  	              <td>'.$key1.'</td>
                  	              <td>'.Yii::$app->diverClass->nommotifcharge($value["idMotif"]).'</td>
                                   <td>'.Yii::$app->mainCLass->getUserFullnameBaseId($value["idActor"]).'</td>
                                   <td>'.number_format($value["montant"]).'</td>
            	                    <td>'.$value["description"].'</td>
                                   <td>'.$value["LastUpdatedOn"].'</td>
      	                        </tr>';
      	           	}
                  echo '<tr><td colspan="5">Total</td><td>'.number_format($montacttotal).'</td></tr>';
	           }else{
	            echo '<tr><td colspan="7">'.Yii::t('app','pasEnregistrement').'</td></tr>';
	          }
	        ?>
        </tbody>
      </table>
    </div>
    <div class="col-sm-12 visible-xs">
      <table border="1" width="100%">
      <thead style="font-size: 14px;">
          <th width="4%">&nbsp;N&#176;</th>
          <th width="20%">&nbsp;Motif</th>
          <th width="20%">&nbsp;Utulisateur</th>
          <th width="20%">&nbsp;Montant</th>
          <th width="17%">&nbsp;Description</th>
          <th width="17%">&nbsp;Date / Temps</th>
        </thead>
        <tbody>
        <?php
           $montacttotal = 0;
	         if (is_array($coredata) && sizeof($coredata) >0) {
	           	foreach ($coredata as $key1 => $value) {
               $montacttotal = $montacttotal + $value["montant"];
      	           		$key1 = $key1 + 1;
      	           		echo '<tr>
                  	              <td>'.$key1.'</td>
                  	              <td>'.Yii::$app->diverClass->nommotifcharge($value["idMotif"]).'</td>
                                   <td>'.Yii::$app->mainCLass->getUserFullnameBaseId($value["idActor"]).'</td>
                                   <td>'.number_format($value["montant"]).'</td>
            	                    <td>'.$value["description"].'</td>
                                   <td>'.$value["LastUpdatedOn"].'</td>
      	                        </tr>';
      	           	}
                  echo '<tr><td colspan="5">Total</td><td>'.number_format($montacttotal).'</td></tr>';
	           }else{
	            echo '<tr><td colspan="7">'.Yii::t('app','pasEnregistrement').'</td></tr>';
	          }
	        ?>
        </tbody>
      </table>
    </div>





  </div>
