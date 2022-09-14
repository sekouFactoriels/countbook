	<?php
	# RECUPERATION DES INFOS DU TABLEAU DE MESSAGE
	$msg = (!empty($msg)) ? unserialize($msg) : $msg;
	# RECUPERATION des infos precedements postees
  $dataPosted = (!empty($dataPosted)) ? unserialize($dataPosted) : $dataPosted;
	#format for the en_us Locale
	setlocale(LC_MONETARY, 'en_us');

	# Recuperation des produits a vendre et leurs details respectifs.
	$ProductdtlsForSale = isset($ProductdtlsForSale) ? unserialize($ProductdtlsForSale) : Null;
?>
<div class="panel panel-default">
  <div class="panel-heading">
    <h4 class="panel-title"><i class="glyphicon glyphicon-shopping-cart"></i>&nbsp;&nbsp;<?= Yii::t('app','vente_product')?></h4>
  </div>

  <form class="form-horizontal" action="<?= Yii::$app->request->baseUrl.'/'.md5("vente_simple")?>" id="editventesimpleForm" name="editventesimpleForm" method="post">
    <!-- DEBUT PANEL BODY -->
		<div class="panel-body">
		  <?=
		    Yii::$app->nonSqlClass->getHiddenFormTokenField();
		    $token2 = Yii::$app->getSecurity()->generateRandomString();
		    $token2 = str_replace('+','.',base64_encode($token2));
		  ?>
		  <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>"/>
		  <input type="hidden" name="token2" value="<?= $token2 ?>"/>
		  <input type="hidden" name="action_key" id="action_key" value=""/>
			<input type="hidden" name="action_on_this" id="action_on_this" value=""/>
		  <input type="hidden" name="msg" id="msg" value=""/>

			<!-- SPECIFIQUE HIDDEN INPUT -->
			<input type="hidden" name="basquetReperer" id="basquetReperer" value=""/>
			<input type="hidden" name="calculGenTotal" id="calculGenTotal" value=""/>


			<!-- DEBUT CONTENEUR DE MESSAGE  -->
      <?php $msg = (!empty($msg['type']))?$msg:null;?>
        <div class="<?= $msg['type'] ?>">
          <strong><?= $msg['strong']?></strong> <?= $msg['text']?>
        </div>
      <!-- FIN CONTENEUR DE MESSAGE -->
			<div class="form-group">
        <label class="col-sm-4 control-label">Code de vente : </label>
        <div class="col-sm-4">
          <input onKeypress="return false;" class="form-control" id="codeVente" name="codeVente" value="<?= (sizeof($dataPosted)) > 0 ? $dataPosted['codeVente'] : $ventesDtls['codeVente'] ?>">
        </div>
      </div>

			<div class="form-group">
        <label class="col-sm-4 control-label">Compte(s) Client : </label>
        <div class="col-sm-4">
          <select class="form-control chosen-select" name="acheteur" id="acheteur">
            <option value="0">Client Ordinaire</option>
						<?php
							if(sizeof($listeClient) > 0){
								foreach ($listeClient as $value) :
									$isSelected = ($value["id"] == $ventesDtls['id_client']) ? 'selected' : '';
									echo '<option value="'.$value["id"].'"  '.$isSelected.'>'.$value["nom"].' </option>';
								endforeach;
							}
						?>
          </select>
        </div>
				<div class="col-sm-1">
          <a href="javascript:;" onClick="$('#newclientForm').modal('show')" class="btn btn-circle btn-default" name="createClient" id="createClient"><i class="fa fa-plus-circle"></i></a>
        </div>
      </div>

			<div class="form-group">
        <label class="col-sm-4 control-label">Produit &#224; ajouter : </label>
        <div class="col-sm-4">
          <select class="form-control chosen-select" name="productToBeSale" id="productToBeSale" onchange="addProductToPannier()">
            <option value="0">Selectionne un</option>
						<?php
							if(sizeof($ProductdtlsForSale) > 0){
								foreach ($ProductdtlsForSale as $value) :
									echo '<option value="'.base64_encode(json_encode($value)).'">'.$value["productCode"].' : '.$value["libelle"].' </option>';
								endforeach;
							}
						?>
          </select>
        </div>
      </div>
			<fieldset>
				<legend><h4><span class="glyphicon glyphicon-shopping-cart">&nbsp;</span>Pannier de vente</h4></legend>
			</fieldset>
			<!-- TABLEAU DES PRODUITS A VENDRE -->
			<div class="form-group">
        <div class="col-sm-12">
					<div class="table-responsive">
          <table class="table table-bordered mb30" id="saleBasequet">
            <thead>
              <tr>
								<th>&#8226;</th>
                <th>Designation</th>
								<th>U.V.</th>
								<th>Qte. Dispo.</th>
								<th>Qte. En Command.</th>
								<th>S/Total du P.V</th>
								<th>Action</th>
              </tr>
            </thead>
            <tbody id="divPannierDeVente">
							<?php
								if(is_array($productVenduDtls) && sizeof($productVenduDtls) > 0){
									foreach ($productVenduDtls as $key => $productVendu) {
										$selectectedELement = Null;
										foreach ($ProductdtlsForSale as $key2 => $Productdtls) {
											if($Productdtls['idProduct'] == $productVendu['idProduit']){
												$selectectedELement = base64_encode(json_encode($Productdtls));
											}
										}
										$decryptedSelectedElement = json_decode(base64_decode($selectectedELement));
										//$qteRestaurer = $decryptedSelectedElement->qteDispo + $productVendu['qteVendu']; // Preparons la qte a restaurer
										/** Chargeons les produits **/
										echo '<tr id="row_'.$decryptedSelectedElement->slimproductid.'" class="eachrow">
														<td>&#8226;<input autocomplete="off" type="hidden" name="selectectedELement[]" id="selectectedELement" value="'.$selectectedELement.'"><input autocomplete="off" type="hidden" name="soldQteBFupdate[]" id="soldQteBFupdate" value="'.$productVendu['qteVendu'].'"><input autocomplete="off" type="hidden" name="soldProductidBFupdate[]" id="soldProductidBFupdate" value="'.$productVendu['idProduit'].'"></td>
														<td>'.$decryptedSelectedElement->productCode.' : '.$decryptedSelectedElement->libelle.'</td>
														<td>'.Yii::$app->inventaireClass->getUdmLabel($decryptedSelectedElement->udm).'</td>
														<td>'.$decryptedSelectedElement->qteDispo.'</td>
														<td>'.$productVendu['qteVendu'].'<input  type="hidden" autocomplete="off" class="form-control" onKeyup="calculSubTotal(this.id,'.$decryptedSelectedElement->qteDispo.',  '.$decryptedSelectedElement->prixUnitaireVente.', '.$decryptedSelectedElement->slimproductid.')" name="qteToBeSale[]" id="qteToBeSale_'.$decryptedSelectedElement->slimproductid.'" value="'.$productVendu['qteVendu'].'"></td>
			                      <td>'.$productVendu['spvtotal'].'<input type="hidden" autocomplete="off" class="form-control totalmustpay" onKeypress="return false;" name="sousTotalLabel[]" id="sousTotalLabel_'.$decryptedSelectedElement->slimproductid.'" value="'.$productVendu['spvtotal'].'"></td>
			                      <td><a id="'.$decryptedSelectedElement->slimproductid.'" class="btn btn-circle btn-darkblue" onclick="restore(this.id,'.$productVendu['id_dtlsvente'].','.$decryptedSelectedElement->qteDispo.','.$productVendu['qteVendu'].','.$decryptedSelectedElement->udm.','.$productVendu['spvtotal'].')"><i Class="fa fa-mail-reply-all">&nbsp;</i></a></td>

													</tr>';
									}
								}
							?>
            </tbody>
          </table>
          </div>
        </div>
      </div>

			<div class="form-group">
        <div class="col-sm-12" style="text-align: center;">
          <a class="btn btn-circle btn-primary btn-lg" href="javascript:;" onclick="caluclAddition()" id=""><i class="fa fa-money">&nbsp;</i>Calculer l'addition</a>
        </div>
      </div>

			<fieldset>
				<legend><h4><span class="glyphicon glyphicon-hand-right">&nbsp;</span>Finaliser plus bas</h4></legend>
			</fieldset>

			<div class="form-group">
        <label class="col-sm-4 control-label">Total Monetaire : <span class="asterisk">*</span></label>
        <div class="col-sm-4">
          <input autocomplete="off" class="form-control" id="totalMonetaire" name="totalMonetaire" value="<?= (sizeof($dataPosted) > 0 ) ? $productVenduDtls['prixTotalVente'] : $ventesDtls['prixTotalVente'] ?>">
        </div>
      </div>

			<div class="form-group">
        <label class="col-sm-4 control-label">Remise Monetaire : </label>
        <div class="col-sm-4">
          <input autocomplete="off" onKeyup="calculRemise()" class="form-control" id="remiseMonetaire" name="remiseMonetaire" value="<?= (sizeof($dataPosted) > 0 ) ? $dataPosted['remiseMonetaire'] : $ventesDtls['remiseMonetaire'] ?>">
        </div>
      </div>

			<div class="form-group">
        <label class="col-sm-4 control-label">Montant Final (+Remise) : </label>
        <div class="col-sm-4">
          <input autocomplete="off" class="form-control" id="montantFinal" name="montantFinal" value="<?= (sizeof($dataPosted) > 0 ) ? $dataPosted['montantFinal'] : $ventesDtls['prixVenteAccorde'] ?>">
        </div>
      </div>

			<div class="form-group">
        <label class="col-sm-4 control-label">Montant &#224; per&#231;&#233;voir : <span class="asterisk">*</span></label>
        <div class="col-sm-4">
          <input autocomplete="off" onKeyup="calculDetteVente()" class="form-control" id="montantPercu" name="montantPercu" value="<?= (sizeof($dataPosted) > 0 ) ? $dataPosted['montantPercu'] : $ventesDtls['montantpercu'] ?>">
        </div>
      </div>

			<div class="form-group">
        <label class="col-sm-4 control-label">Montant en dette : </label>
        <div class="col-sm-4">
          <input autocomplete="off" class="form-control" id="dettevente" name="dettevente" value="<?= (sizeof($dataPosted) > 0 ) ? $dataPosted['dettevente'] : $ventesDtls['detteVente'] ?>">
        </div>
      </div>


			<!-- Footer  -->
			<div class="panel-footer">
        <div class="row">
          <div class="col-sm-9 col-sm-offset-3">
            <a class="btn btn-circle btn-success" onclick="$('#mettrejourVenteSimple').modal('show')"><i class="fa fa-refresh">&nbsp;&nbsp;</i>Mettre &#224; jour</a>
            <span>&nbsp;</span>
            <a href="<?= Yii::$app->request->baseurl.'/'.md5('vente_simple')?>" class="btn btn-circle btn-warning"><i class="glyphicon glyphicon-remove-circle"></i>&nbsp;Anuler</a>
          </div>
        </div>
      </div>

			<div class="modal fade" id="mettrejourVenteSimple" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			    <div class="modal-dialog">
			        <div class="modal-content">
			            <div class="modal-header">
			                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			                <h4 class="modal-title" id="newProdCat">Mise &#224; jour de Vente</h4>
			            </div>
			            <div class="modal-body">
			              <div class="form-group">
			                <p>Etes-vous sur de bien vouloir continuer cette action ?</p>
			              </div>
			            </div>
			            <div class="modal-footer">
			                <a href="javascript:;" type="button" onclick="document.getElementById('action_key').value='<?= md5("updatesale") ?>'; document.getElementById('action_on_this').value='<?= md5("1") ?>'; document.getElementById('editventesimpleForm').submit();" class="btn btn-circle btn-success"><i class="glyphicon glyphicon-saved"></i>&nbsp;Oui</a>
			                <button type="button" onClick="cancel()" class="btn btn-circle btn-warning" data-dismiss="modal"><i class="glyphicon glyphicon-remove-circle"></i>&nbsp;Non</button>
			            </div>
			        </div>
			    </div>
			</div>

			<div class="modal fade" id="enrgVenteSimple" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			    <div class="modal-dialog">
			        <div class="modal-content">
			            <div class="modal-header">
			                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			                <h4 class="modal-title" id="newProdCat">Enregistrement de Vente</h4>
			            </div>
			            <div class="modal-body">
			              <div class="form-group">
			                <p>Etes-vous sur de bien vouloir continuer cette action ?</p>
			              </div>
			            </div>
			            <div class="modal-footer">
			                <a href="javascript:;" type="button" onclick="document.getElementById('action_key').value='<?= md5("enrgventeproduit") ?>'; document.getElementById('action_on_this').value='<?= md5("1") ?>'; document.getElementById('nventesimpleForm').submit();" class="btn btn-circle btn-success"><i class="glyphicon glyphicon-saved"></i>&nbsp;Oui</a>
			                <button type="button" onClick="cancel()" class="btn btn-circle btn-warning" data-dismiss="modal"><i class="glyphicon glyphicon-remove-circle"></i>&nbsp;<?= Yii::t('app','fermer');?></button>
			            </div>
			        </div>
			    </div>
			</div>
		<!-- Fin : Panel body -->
	</form>
</div>
<div class="modal fade" id="qteDemandSuperieur" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
				<div class="modal-content">
						<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title" id="myModalLabel"><?php echo Yii::t('app','alertRouge');?></h4>
						</div>
						<div class="modal-body">
								<p>La quantit&#233; demand&#233;e est superieur &#224; c&#39;elle disponible !</p>
						</div>

						<div class="modal-footer">
								<button type="button" class="btn btn-circle btn-warning" data-dismiss="modal"><?= Yii::t('app','ok');?></button>
						</div>
				</div>
		</div>
</div>
<?php
require_once('script/nventesimple_js.php');
?>
