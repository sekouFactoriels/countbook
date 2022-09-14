	<?php
	# RECUPERATION DES INFOS DU TABLEAU DE MESSAGE
	$msg = (!empty($msg)) ? unserialize($msg) : $msg;
	# RECUPERATION des infos precedements postees
	$dataPosted = (!empty($dataPosted)) ? unserialize($dataPosted) : $dataPosted;

	# Recuperation des produits a vendre et leurs details respectifs.
	$ProductdtlsForSale = isset($ProductdtlsForSale) ? unserialize($ProductdtlsForSale) : Null;
	?>

	<div class="panel panel-default">
		<div class="panel-heading">
			<h4 class="panel-title"><i class="glyphicon glyphicon-shopping-cart"></i>&nbsp;&nbsp;<?= Yii::t('app', 'vente_product') ?></h4>
		</div>
		<form class="form-horizontal" action="<?= Yii::$app->request->baseUrl . '/' . md5("vente_simple") ?>" id="nventesimpleForm" name="nventesimpleForm" method="post">
			<!-- DEBUT PANEL BODY -->
			<div class="panel-body">
				<?=
				Yii::$app->nonSqlClass->getHiddenFormTokenField();
				$token2 = Yii::$app->getSecurity()->generateRandomString();
				$token2 = str_replace('+', '.', base64_encode($token2));
				?>
				<input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>" />
				<input type="hidden" name="token2" value="<?= $token2 ?>" />
				<input type="hidden" name="action_key" id="action_key" value="<?= md5('newProduct') ?>" />
				<input type="hidden" name="action_on_this" id="action_on_this" value="<?= md5('1') ?>" />
				<input type="hidden" name="msg" id="msg" value="" />

				<!-- SPECIFIQUE HIDDEN INPUT -->
				<input type="hidden" name="basquetReperer" id="basquetReperer" value="" />
				<input type="hidden" name="calculGenTotal" id="calculGenTotal" value="" />
				<input type="hidden" name="totalAchat" id="totalAchat" value="" />
				<input type="hidden" name="ta" id="ta" value="" />



				<!-- NOTIFICATION -->
				<?= Yii::$app->session->getFlash('flashmsg');
				Yii::$app->session->removeFlash('flashmsg');  ?>

				<div class="form-group">
					<label class="col-sm-4 control-label">Date de l'opération :<span class="asterisk">*</span></label>
					<div class="col-sm-4">
						<input class="form-control datepicker" autocomplete="off" id="datefrom" name="ventedate" value="<?= date('m/d/Y') ?>">
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-4 control-label">Code de vente : </label>
					<div class="col-sm-4">
						<input onKeypress="return false;" class="form-control" id="codeVente" name="codeVente" value="<?= (sizeof($dataPosted)) > 0 ? $dataPosted['codeVente'] : Yii::$app->nonSqlClass->genNouveauCode() ?>">
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-4 control-label">Compte(s) Client : </label>
					<div class="col-sm-4 input-group mixted-input-group">
						<select class="form-control chosen-select" name="acheteur" id="acheteur">
							<option value="0">Client Ordinaire</option>
							<?php
							if (sizeof($listeClient) > 0) {
								foreach ($listeClient as $value) :
									echo '<option value="' . $value["id"] . '" >' . $value["nom_appellation"] . ' </option>';
								endforeach;
							}
							?>
						</select>
						<a href="javascript:;" onclick="$('#client_form_modal').modal('show');" class="btn btn-default input-group-addon" name="addCategory" id="addCategory"><i class="fa fa-plus-circle"></i></a>
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-4 control-label">Produit &#224; ajouter : </label>
					<div class="col-sm-4">
						<select class="form-control chosen-select" name="productToBeSale" id="productToBeSale" onchange="addProductToPannier()">
							<option value="0">Selectionne un</option>
							<?php
							if (sizeof($ProductdtlsForSale) > 0) {
								foreach ($ProductdtlsForSale as $value) :
									echo '<option value="' . base64_encode(json_encode($value)) . '">' . $value["productCode"] . ' : ' . $value["libelle"] . ' </option>';
								endforeach;
							}
							?>
						</select>
					</div>
				</div>
				<fieldset>
					<legend>
						<h4><span class="glyphicon glyphicon-shopping-cart">&nbsp;</span>Pannier de vente</h4>
					</legend>
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
										<th>U.V.A.</th>
										<th>Qte. Dispo.</th>
										<th style="width: 20%;">Qte. En Command.</th>
										<th>S/Total du P.V</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody id="divPannierDeVente">
									<?php
									if (sizeof($dataPosted) > 0) {
										foreach ($dataPosted['selectectedELement'] as $key => $value) {
											$sub_selectectedELement = json_decode(base64_decode($dataPosted['selectectedELement'][$key]));
											echo '<tr id="row_' . $selectectedELement->slimproductid . '" class="eachrow">
								                      <td><input type="hidden" Class="totalBought" name="sousTotalAchat[]" id="sousTotalAchat_' . $selectectedELement->slimproductid . '" value="' . $selectectedELement->prixUnitaireAchat . '">&#8226;<input autocomplete="off" type="hidden" name="selectectedELement[]" id="selectectedELement' . $selectectedELement->slimproductid . '" class="eachHidden" value="' . $_GET['selectedDiv'] . '"></td>
								                      <td>' . $selectectedELement->productCode . ' : ' . $selectectedELement->libelle . '</td>
								                      <td>' . Yii::$app->inventaireClass->getUdmLabel($selectectedELement->udm) . '</td>
								                      <td>' . $selectectedELement->qteDispo . '</td>
								                      <td class="text-center">
								                      <div class="form-group text-center" style="margin-left: 3%;">
																			<div class="input-group">
								                     
								                      <input autocomplete="off" class="form-control qtes text-center" style="width: 50%;" onKeyup="calculSubTotal(this.id,' . $selectectedELement->qteDispo . ',  ' . $selectectedELement->prixUnitaireVente . ', ' . $selectectedELement->slimproductid . ',' . $selectectedELement->prixUnitaireAchat . ')" name="qteToBeSale[]" id="qteToBeSale_' . $selectectedELement->slimproductid . '" value="1">
								                      
								                      <a href="#" onclick="decQte(this.id,' . $selectectedELement->qteDispo . ',  ' . $selectectedELement->prixUnitaireVente . ', ' . $selectectedELement->slimproductid . ',' . $selectectedELement->prixUnitaireAchat . ',' . $selectectedELement->slimproductid . ')" name="qteToBeSale[]" id="qteToBeSale_' . $selectectedELement->slimproductid . '" class="input-group-text btn btn-warning qtebtn dec" id="btn1">-</a>
								                      &nbsp;
																			<a href="#" onclick="incQte(this.id,' . $selectectedELement->qteDispo . ',  ' . $selectectedELement->prixUnitaireVente . ', ' . $selectectedELement->slimproductid . ',' . $selectectedELement->prixUnitaireAchat . ',' . $selectectedELement->slimproductid . ')" name="qteToBeSale[]" id="qteToBeSale_' . $selectectedELement->slimproductid . '" class="input-group-text btn btn-success qtebtn inc" id="btn2">+</a>
								                      </div>
								                      </div>
								                      </td>
								                      <td><input autocomplete="off" onchange="positifSubMontant(' . $selectectedELement->slimproductid . ')" class="form-control totalmustpay" type="number"  name="sousTotalLabel[]" id="sousTotalLabel_' . $selectectedELement->slimproductid . '" value="' . $selectectedELement->prixUnitaireVente . '"></td>
								                      <td><a id="' . $selectectedELement->slimproductid . '" class="btn btn-danger" onclick="deletThisRow(this.id)"><i Class="fa fa-times">&nbsp;</i></a></td>
								                    </tr>
					                  ';
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
						<a class="btn btn-circle btn-danger btn-lg" href="javascript:;" onclick="caluclAddition()" id=""><i class="fa fa-money">&nbsp;</i>Crédit</a>&nbsp;&nbsp;
						<a class="btn btn-circle btn-primary btn-lg" href="javascript:;" onclick="caluclSolde()" id=""><i class="fa fa-money">&nbsp;</i>Soldé</a>
					</div>
				</div>

				<fieldset>
					<legend>
						<h4><span class="glyphicon glyphicon-hand-right">&nbsp;</span>Finaliser plus bas</h4>
					</legend>
				</fieldset>

				<div class="form-group">
					<label class="col-sm-4 control-label">Total Monetaire : <span class="asterisk">*</span></label>
					<div class="col-sm-4">
						<input autocomplete="off" class="form-control" id="totalMonetaire" name="totalMonetaire" value="<?= (sizeof($dataPosted) > 0) ? $dataPosted['totalMonetaire'] : Null ?>" readonly="readonly">
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-4 control-label">Remise Monetaire : </label>
					<div class="col-sm-4">
						<input autocomplete="off" type="number" onKeyup="calculRemise()" onchange="positifMontantRemise()" class="form-control" id="remiseMonetaire" name="remiseMonetaire" value="<?= (sizeof($dataPosted) > 0) ? $dataPosted['remiseMonetaire'] : Null ?>">
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-4 control-label">Montant Final (+Remise) : </label>
					<div class="col-sm-4">
						<input autocomplete="off" class="form-control" id="montantFinal" name="montantFinal" value="<?= (sizeof($dataPosted) > 0) ? $dataPosted['montantFinal'] : Null ?>" readonly="readonly">
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-4 control-label">Montant &#224; per&#231;&#233;voir : <span class="asterisk">*</span></label>
					<div class="col-sm-4">
						<input autocomplete="off" onKeyup="calculDetteVente()" type="number" onchange="positifMontantParcu()" class="form-control" id="montantPercu" name="montantPercu" value="<?= (sizeof($dataPosted) > 0) ? $dataPosted['montantPercu'] : Null ?>">
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-4 control-label">Montant en dette : </label>
					<div class="col-sm-4">
						<input autocomplete="off" class="form-control" id="dettevente" name="dettevente" value="<?= (sizeof($dataPosted) > 0) ? $dataPosted['dettevente'] : Null ?>" readonly="readonly">
					</div>
				</div>

				<div class="form-group" id="dateEcheance" style="display: none;">
					<label class="col-sm-4 control-label">Date d'échéance :<span class="asterisk">*</span></label>
					<div class="col-sm-4">
						<input class="form-control datepicker" autocomplete="off" name="dateEcheance" value="<?= date('m/d/Y') ?>">
					</div>
				</div>

				<fieldset>
					<legend>
						<h4><span class="glyphicon glyphicon-hand-right">&nbsp;</span>Moyen de Paiement</h4>
					</legend>
				</fieldset>

				<div class="form-group">
					<label class="col-sm-4 control-label">Mode de paiement :<span class="asterisk">*</span> </label>
					<div class="col-sm-4">
						<select class="form-control chosen-select" name="mode_paiement" id="mode_paiement" onchange="mode_paiement_selected()">
							<option value="1">Cash</option>
							<option value="2">Chèque</option>
							<option value="3">Virement Bancaire</option>
						</select>
					</div>
				</div>

				<div class="form-group" id="div_banque" style="display: none;">
					<label class="col-sm-4 control-label">Banque :<span class="asterisk">*</span> </label>
					<div class="col-sm-4">
						<select class="form-control chosen-select" name="banque_denomination" id="banque_denomination">
							<option value="0">Faites le bon choix</option>
							<?php

							if (sizeof($banques) > 0) {
								foreach ($banques as $each_banque) :
									echo '<option value="' . $each_banque['id'] . '">' . $each_banque["numero_compte"] . ' : ' . $each_banque["banque"] . ' </option>';
								endforeach;
							}
							?>
						</select>
					</div>
				</div>

				<div class="form-group" id="div_cheque" style="display: none;">
					<label class="col-sm-4 control-label">Numéro du chèque : </label>
					<div class="col-sm-4">
						<input autocomplete="off" class="form-control" id="numero_cheque" name="numero_cheque" value="<?= (sizeof($dataPosted) > 0) ? $dataPosted['numero_cheque'] : Null ?>">
					</div>
				</div>


				<!-- Footer  -->
				<div class="panel-footer">
					<div class="row">
						<div class="col-sm-9 col-sm-offset-3">
							<a class="btn btn-circle btn-success" onclick="$('#enrgVenteSimple').modal('show')"><i class="fa fa-save"></i>&nbsp;Enregistrer</a>
							<span>&nbsp;</span>
							<a href="<?= Yii::$app->request->baseurl . '/' . md5('vente_simple') ?>" class="btn btn-circle btn-warning"><i class="glyphicon glyphicon-remove-circle"></i>&nbsp;Anuler</a>
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
								<button type="button" onClick="cancel()" class="btn btn-circle btn-warning" data-dismiss="modal"><i class="glyphicon glyphicon-remove-circle"></i>&nbsp;<?= Yii::t('app', 'fermer'); ?></button>
							</div>
						</div>
					</div>
				</div>
				<!-- Fin : Panel body -->

				<!-- modal new client -->
				<?php require_once('nclient.php'); ?>
				<?php require_once('modalAlerte.php'); ?>
				<!--/ modal new client -->
		</form>
	</div>
	<div class="modal fade" id="qteDemandSuperieur" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel"><?php echo Yii::t('app', 'alertRouge'); ?></h4>
				</div>
				<div class="modal-body">
					<p>La quantit&#233; demand&#233;e est superieur &#224; c&#39;elle disponible !</p>
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-circle btn-warning" data-dismiss="modal"><?= Yii::t('app', 'ok'); ?></button>
				</div>
			</div>
		</div>
	</div>


	<script>
		$('#btn1').click(function() {
			alert('test');
		});
	</script>

	<?php require_once(Yii::$app->basePath . '/views/parametre/motifenrgclient/newmotif.php'); ?>
	<?php require_once('script/nventesimple_js.php'); ?>