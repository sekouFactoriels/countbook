<?php
  $userPrimaryData = unserialize($userPrimaryData);
  $entrepriseData = Yii::$app->mainCLass->getEntrepriseData($userPrimaryData['idEntreprise']);
  $formdataposted = unserialize($formdataposted);
  $data = (isset($data)) ? unserialize($data) : Null;
  require_once(Yii::$app->basePath.'/views/rggeneral/singleHeader.php');
?>
 <div class="row hidden-xs" style="text-align: center;">
    <strong style="font-size: 30px; text-decoration: underline;" >Rapport des utulisateurs</strong>
  </div>
  <div class="row visible-xs" style="text-align: center;">
    <strong style="font-size: 20px; text-decoration: underline;" >Rapport des utulisateurs</strong>
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
    <div class="col-sm-12 hidden-xs">
      <table border="1" width="100%">
        <thead style="font-size: 14px;">
          <th width="4%">&nbsp;N&#176;</th>
          <th width="20%">&nbsp;<?= Yii::t('app','nom')?></th>
          <th width="20%">&nbsp;<?= Yii::t('app','pNom')?></th>
          <th width="20%">&nbsp;<?= Yii::t('app','addresse')?></th>
        </thead>
        <tbody>
	        <?php
	         if (is_array($coredata) && sizeof($coredata) >0) {
	           	foreach ($coredata as $key1 => $value) {
	           		$key1 = $key1 + 1;
	           		echo '<tr>
	              <td>'.$key1.'</td>
	              <td>'.$value['nom'].'</td>
                <td>'.$value['prenom'].'</td>
                <td>'.$value["adresse"].'</td>
	              </tr>';
	           	}
	           }else{
	            echo '<tr><td colspan="7">'.Yii::t('app','pasEnregistrement').'</td></tr>';
	          }
	         ?>
        </tbody>
      </table>
    </div>
    <div class="col-sm-12 visible-xs">
      <table border="1" width="100%">
        <tbody>
        <?php
          if(is_array($data) && sizeof($data) > 0){
            foreach ($data as $key => $value) {
              $key2 = $key + 1;
              echo '<tr>
              <td> <b>&nbsp;N&#176; : </b>'.$key2.'
              </br><b>&nbsp;Evenement : </b>'.$value["eventDesc"].'
              </br><b>&nbsp;Evenement : </b>'.$value["eventDesc"].'
              </br><b>&nbsp;Evenement : </b>'.$value["eventDesc"].'
              </br><b>&nbsp;Evenement : </b>'.$value["eventDesc"].'
              </br><b>&nbsp;Evenement : </b>'.$value["eventDesc"].'
              </br> <b>&nbsp;Date : </b>'.$value["eventDte"].'
              </br> </td>
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
