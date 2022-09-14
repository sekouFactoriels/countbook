<?php
# RECUPERATION des infos precedements postees
$dataPosted = (!empty($dataPosted)) ? unserialize($dataPosted) : $dataPosted;

# Recuperation des produits a vendre et leurs details respectifs.
$produits = isset($produits) ? unserialize($produits) : Null;

?>

<!-- BEGIN FORM-->
<form class="form-horizontal" action="" name="countbook_form" id="countbook_form" action="<?= yii::$app->request->baseurl . '/' . md5('inventaire_reaprovision') ?>" method="post">
	<?=
	Yii::$app->nonSqlClass->getHiddenFormTokenField();
	$token2 = Yii::$app->getSecurity()->generateRandomString();
	$token2 = str_replace('+', '.', base64_encode($token2));
	?>
	<!-- DEBUT : BASIC HIDDEN IMPUT FOR THE FORM -->
	<input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>" />
	<input type="hidden" name="token2" value="<?= $token2 ?>" />
	<input type="hidden" name="action_key" id="action_key" value="" />
	<input type="hidden" name="action_on_this" id="action_on_this" value="" />

	<!-- SPECIFIQUE HIDDEN INPUT -->
	<input type="hidden" name="basquetReperer" id="basquetReperer" value="" />
	<input type="hidden" name="calculGenTotal" id="calculGenTotal" value="">
	<input type="hidden" name="totalAchat" id="totalAchat" value="" />
	<input type="hidden" name="ta" id="ta" value="" />

	<div class="panel panel-default">

		<!-- ENETETE DU FORMULAIRE -->
		<div class="panel-heading">
			<h4 class="panel-title"><i class="fa fa-exchange"></i>&nbsp;&nbsp;REAPROVISIONNEMENT</h4>
		</div>
		<!-- .ENETETE DU FORMULAIRE -->

		<!-- CORPS DU FORMULAIRE -->
		<div class="panel-body">

			<!-- NOTIFICATION -->
			<?= Yii::$app->session->getFlash('flashmsg');
			Yii::$app->session->removeFlash('flashmsg');  ?>

			<div class="form-group">
				<label class="col-sm-4 control-label">Date de l'opération :<span class="asterisk">*</span></label>
				<div class="col-sm-4">
					<input class="form-control datepicker" autocomplete="off" id="datefrom" name="operationdate" value="<?= date('m/d/Y') ?>">
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-4 control-label">Compte Fournisseur :<span class="asterisk">*</span> </label>
				<div class="col-sm-4 input-group mixted-input-group">
					<select class="form-control chosen-select" name="fournisseur" id="fournisseur">
						<option value="0">Non spécifié</option>
						<?php
						if (sizeof($fournisseurs) > 0) {
							foreach ($fournisseurs as $value) :
								echo '<option value="' . $value["id"] . '" >' . $value["denomination"] . ' </option>';
							endforeach;
						}
						?>
					</select>
					<a href="javascript:;" onclick="$('#fournisseur_form_modal').modal('show');" class="btn btn-default input-group-addon" name="addCategory" id="addCategory"><i class="fa fa-plus-circle"></i></a>
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-4 control-label">Produit &#224; réaprovisionner :<span class="asterisk">*</span> </label>
				<div class="col-sm-4">
					<select class="form-control chosen-select" name="produit_reaprovisionner" id="produit_reaprovisionner" onchange="addProductToPannier()">
						<option value="0">Faites le bon choix</option>
						<?php
						if (sizeof($produits) > 0) {
							foreach ($produits as $value) :
								echo '<option value="' . base64_encode(json_encode($value)) . '">' . $value["productCode"] . ' : ' . $value["libelle"] . ' </option>';
							endforeach;
						}
						?>
					</select>
				</div>
			</div>

			<fieldset>
				<legend>
					<h4><span class="glyphicon glyphicon-shopping-cart">&nbsp;</span>Pannier de réaprovisionnement</h4>
				</legend>
			</fieldset>

			<!-- TABLEAU DES PRODUITS A REAPROVISIONNER -->
			<div class="form-group">
				<div class="col-sm-12">
					<div class="table-responsive">
						<table class="table table-bordered mb30" id="lepannier">
							<thead>
								<tr>
									<th>&#8226;</th>
									<th>Designation</th>
									<th>U.V.</th>
									<th>Qte. Dispo.</th>
									<th>Qte. à réaprovisionner.</th>
									<th>S/Total du P.A</th>
									<th>Action</th>
								</tr>
							</thead>

							<tbody id="div_pannier_reaprovision">

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
				<legend>
					<h4><span class="glyphicon glyphicon-hand-right">&nbsp;</span>Finaliser plus bas</h4>
				</legend>
			</fieldset>

			<div class="form-group">
				<label class="col-sm-4 control-label">TT. Prix D'achat : <span class="asterisk">*</span></label>
				<div class="col-sm-4">
					<input autocomplete="off" class="form-control" id="totalMonetaire" name="totalMonetaire" value="<?= (sizeof($dataPosted) > 0) ? $dataPosted['totalMonetaire'] : Null ?>" readonly="readonly">
				</div>
			</div>


			<div class="form-group">
				<label class="col-sm-4 control-label">Transport + Manutention + Divers : </label>
				<div class="col-sm-4">
					<input autocomplete="off" onKeyup="calculRemise()" class="form-control" id="remiseMonetaire" name="remiseMonetaire" value="<?= (sizeof($dataPosted) > 0) ? $dataPosted['remiseMonetaire'] : Null ?>">
				</div>
			</div>


			<div class="form-group">
				<label class="col-sm-4 control-label">Montant Final à Payer : </label>
				<div class="col-sm-4">
					<input autocomplete="off" class="form-control" id="montantFinal" name="montantFinal" value="<?= (sizeof($dataPosted) > 0) ? $dataPosted['montantFinal'] : Null ?>">
				</div>
			</div>




			<div class="form-group">
				<label class="col-sm-4 control-label">Montant Payé : <span class="asterisk">*</span></label>
				<div class="col-sm-4">
					<input autocomplete="off" onKeyup="calcul_dette()" class="form-control" id="montantpaye" name="montantpaye" value="<?= (sizeof($dataPosted) > 0) ? $dataPosted['montantpaye'] : Null ?>">
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-4 control-label">Reste à payer : </label>
				<div class="col-sm-4">
					<input autocomplete="off" class="form-control" id="montantdette" name="montantdette" value="<?= (sizeof($dataPosted) > 0) ? $dataPosted['montantdette'] : Null ?>">
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
						<option value="0">Faites le bon choix</option>
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



		</div>
		<!-- CORPS DU FORMULAIRE -->

		<!-- PIEDS DE PAGE DU FORMULAIRE -->
		<div class="panel-footer">
			<div class="row">
				<div class="col-sm-9 col-sm-offset-3">
					<a class="btn btn-circle btn-success" onclick="$('#enrgVenteSimple').modal('show')"><i class="fa fa-save"></i>&nbsp;Enregistrer</a>
					<span>&nbsp;</span>
					<a href="<?= Yii::$app->request->baseurl . '/' . md5('site_index') ?>" class="btn btn-circle btn-warning"><i class="glyphicon glyphicon-remove-circle"></i>&nbsp;Anuler</a>
					<span>&nbsp;</span><span>&nbsp;</span><span>&nbsp;</span>
				</div>
			</div>
		</div>
		<!-- .PIEDS DE PAGE DU FORMULAIRE -->
		<?php require_once('newFournisseur.php'); ?>

		<div class="modal fade" id="enrgVenteSimple" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="newProdCat"><span class="fa fa-exclamation-circle">&nbsp;</span>NOTIFICATION</h4>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<p>Etes-vous sur de bien vouloir finaliser cette opération ?</p>
						</div>
					</div>
					<div class="modal-footer">
						<a href="javascript:;" type="button" onclick="document.getElementById('action_key').value='<?= md5("enregistrer_reaprovisionnement") ?>'; $('#countbook_form').attr('action','<?= md5("inventaire_reaprovision") ?>'); document.getElementById('countbook_form').submit();" class="btn btn-circle btn-success"><i class="glyphicon glyphicon-saved"></i>&nbsp;Oui</a>
						<button type="button" onClick="cancel()" class="btn btn-circle btn-warning" data-dismiss="modal"><i class="glyphicon glyphicon-remove-circle"></i>&nbsp;<?= Yii::t('app', 'fermer'); ?></button>
					</div>
				</div>
			</div>
		</div>
		<!-- Fin : Panel body -->

		<?php require_once('script/reaprovision_js.php') ?>