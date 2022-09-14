<script type="text/javascript">

/** Methode : Afficher les variantes du mode de paiement **/
function mode_paiement_selected() {
  var mode_paiement = $('#mode_paiement').val();
  switch(mode_paiement)
  {
    case '3':
      $('#div_banque').show();
      $('#div_cheque').hide();
    break;

     case '2':
      $('#div_banque').hide();
      $('#div_cheque').show();
    break;

    default:
      $('#div_banque').hide();
      $('#div_cheque').hide();
    break;
  }
}


// Function qui restaure la quantite vendu d'un article aau stok disponible
function restore(idArticle, idLigneVente, qteDispo, qteVendu, productUdm, spvtotal){
  var myurl = '<?= Yii::$app->getUrlManager()->createAbsoluteUrl(md5("vente_simple")) ?>';
  $.ajax({
        type: 'GET',
        cache: false,
        url: myurl,
        data: {
          _csrf : '<?= Yii::$app->request->getCsrfToken() ?>',
          action_key : "<?= md5('restoreqte')?>",
          idArticle: idArticle,
          idLigneVente: idLigneVente,
          qteDispo: qteDispo,
          qteVendu: qteVendu,
          productUdm: productUdm,
          spvtotal: spvtotal
              },
        success: function(data){
          $('#action_key').val("<?= md5('editsale')?>");
          $('#action_on_this').val("<?= base64_encode("+idLigneVente+")?>");
          window.location.reload();
        },
        error: function(data){ alert(JSON.stringify(data)); },
    });
}


function reset(){
  $("#action_key").val("<?= md5('nvente')?>");
  $("#nventesimpleForm").submit();
}

function calculDetteVente(){
  var lemontantfinal = $('#montantFinal').val();
  var lemontantpercevoir = $('#montantPercu').val();
  var ladettedevente = null;
  if(lemontantfinal > 0){
    ladettedevente = lemontantfinal - lemontantpercevoir;
    $('#dettevente').val(ladettedevente);
  }
  return true;
}

function calculRemise(){
  var laremise = parseInt($('#remiseMonetaire').val());
  var letotalmonetaire = parseInt($('#totalMonetaire').val());
  var balanceApresRemise = letotalmonetaire - laremise;
  $('#montantFinal').val(balanceApresRemise);
  //$('#montantPercu').val(balanceApresRemise);
  return true;
}

function caluclAddition(){
  var somation = 0;
  var achatSomation = 0;
  $('#saleBasequet').find('.eachrow').each(function(){
    var qteAvendre = parseInt($(this).find($('.totalmustpay')).val());
    somation = somation + qteAvendre;

    var qteAchete = parseInt($(this).find($('.totalBought')).val());
    achatSomation = achatSomation + qteAchete;
  });

  $('#ta').val(achatSomation);
  $('#totalMonetaire').val(somation);
  $('#remiseMonetaire').val('0');
  $('#montantFinal').val(somation);
  $('#montantPercu').val('0');
  $('#dettevente').val('0');
  return true;
}

function verifierCaracteres(event) {
	var keyCode = event.which ? event.which : event.keyCode;
	var touche = String.fromCharCode(keyCode);
	//var champ = document.getElementById('mon_input');
	var caracteres = '';
	if(caracteres.indexOf(touche) >= 0) {
		champ.value += touche;
	}
  return true;
}

function deletThisRow(id){
  var row = document.getElementById('row_'+id);
  row.parentNode.removeChild(row);
}

function calculSubTotal(id, qteDispo, pu, rowId, pua)
{
  var givingQte = (document.getElementById(id).value);
  var product_pu = pu;
  var product_pua = pua;
  var sTotal_pu = '';
  var sTotal_pua = '';

  // Bon : Rassurons nous ici que la quantie saisie est proportion
  if($.isNumeric(qteDispo) && qteDispo < givingQte){
    $('#qteDemandSuperieur').modal('show');
    removeLastCharacterTyped(id);
    return false;
  }else{
    if($.isNumeric(givingQte)){
      // NOW : Effectuer la mutiplication de la quantite par le prix unitaire de vente
      sTotal_pu = product_pu * givingQte;
      sTotal_pua = product_pua * givingQte;
      $('#calculGenTotal').val($('#calculGenTotal').val() + sTotal_pu);
      $('#totalAchat').val($('#totalAchat').val() + sTotal_pua);
    }
    $('#sousTotalLabel_'+rowId).val(sTotal_pu);
    $('#sousTotalAchat_'+rowId).val(sTotal_pua);
    return true;
  }
}

function addProductToPannier()
{
  var selectedDiv = document.getElementById('productToBeSale').value;
  // On elabore une request ajax pour ajouter cela au panier
  var myurl = '<?= Yii::$app->getUrlManager()->createAbsoluteUrl(md5("vente_simple")) ?>';
  $.ajax({
        type: 'GET',
        cache: false,
        url: myurl,
        data: {
          _csrf : '<?=Yii::$app->request->getCsrfToken()?>',
          selectedDiv : selectedDiv,
          addToSaleBaquet : "<?= md5('1')?>",
              },
        success: function(data){
          $('#divPannierDeVente').append(data);
        },
        error: function(){ alert('erreur'); },
    });
}

// function Recuperrons le prix unitqire du produit
function getProductUnitPrice()
{
  var valeurProduit = $('#produitAvendre').val();// recupere la valeur du produit selectionne
  var myurl = '<?= Yii::$app->getUrlManager()->createAbsoluteUrl(md5("vente_simple")) ?>';
  $('#loadingModal').modal('show');
  $.ajax({
        type: 'GET',
        cache: false,
        url: myurl,
        data: {
          _csrf : '<?=Yii::$app->request->getCsrfToken()?>',
          getProductUnitPrice : '1',
          productValue: valeurProduit,
              },
        success: function(data){
          $('#loadingModal').modal('hide');
          $('#puVente').val(data);
        },
        error: function(){
          alert('erreur');
        },
    });
}

/* function de recuperation de la quantite disponible*/
function getQte()
{
  var valeurProduit = $('#produitAvendre').val();// recupere la quantite du produit selectionne
   var myurl = '<?= Yii::$app->getUrlManager()->createAbsoluteUrl(md5("vente_simple")) ?>';
  $('#loadingModal').modal('show');
  $.ajax({
        type: 'GET',
        cache: false,
        url: myurl,
        data: {
          _csrf : '<?=Yii::$app->request->getCsrfToken()?>',
          getProductUnitPrice : '2',
          quantiteProduit: valeurProduit,
              },
        success: function(data){
          $('#loadingModal').modal('hide');
          $('#quantite').val(data);
        },
        error: function(){
          alert('erreur');
        },
    });

}

// Function d'insertion d'une nouvelle vente
function saveData()
{
		var requiredInput = ['codevente','Client','produit','modepayement','produitAvendre','puVente','quantite'];
	    var index = 1;
	    frmValidation = formValidator(index, requiredInput);
	    if(frmValidation){
			$('#action_key').val("<?= md5('save_nventesimple') ?>");
			$('#nventesimpleForm').submit();
		} else{
			$('#empty_msg_modal').modal('show');
    		return false;
		}
}

function getNewVenteSimple()
   {
    $('#loadingModal').modal('show');
    $('ajax_action_key').val('1');
    var myurl = '<?= Yii::$app->getUrlManager()->createAbsoluteUrl(md5("vente_simple")) ?>';
    $.ajax({
        type: 'GET',
        cache: false,
        url: myurl,
        data: {
          _csrf : '<?=Yii::$app->request->getCsrfToken()?>',
          newVenteSimple : '1'
              },
        success: function(data){
          $('#loadingModal').modal('hide');
          $('#codevente').val(data);
        },
        error: function(){
          alert('erreur');
        },
    });
  }
</script>
