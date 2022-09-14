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

function reset(){
  $("#action_key").val("<?= md5('nvente')?>");
  $("#nventesimpleForm").submit();
}

function calcul_dette(){
  var lemontantfinal = $('#montantFinal').val();
  var lemontantpercevoir = $('#montantpaye').val();
  var ladettedevente = null;
  if(lemontantfinal > 0){
    ladettedevente = lemontantfinal - lemontantpercevoir;
    $('#montantdette').val(ladettedevente);
  }
  return true;
}

function calculRemise(){
  var laremise = parseInt($('#remiseMonetaire').val());
  var letotalmonetaire = parseInt($('#totalMonetaire').val());
  var balanceApresRemise = letotalmonetaire + laremise;
  $('#montantFinal').val(balanceApresRemise);
  $('#montantpaye').val('0');
  $('#montantdette').val(balanceApresRemise);
  return true;
}

function caluclAddition(){
  var somation = 0;
  var achatSomation = 0;
  $('#lepannier').find('.eachrow').each(function(){
    var qteAvendre = parseInt($(this).find($('.totalmustpay')).val());
    somation = somation + qteAvendre;

    var qteAchete = parseInt($(this).find($('.totalBought')).val());
    achatSomation = achatSomation + qteAchete;
  });

  $('#ta').val(achatSomation);
  $('#totalMonetaire').val(somation);
  $('#remiseMonetaire').val('0');
  $('#montantFinal').val(somation);
  $('#montantpaye').val('0');
  $('#montantdette').val(somation);
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

function delete_Row(id){
  var row = document.getElementById('row_'+id);
  row.parentNode.removeChild(row);
}

function calculSubTotal(id, qteDispo, pu, rowId, pua)
{
  var givingQte = (document.getElementById(id).value);
  var product_pua = pua;
  var sTotal_pua = '';

  if($.isNumeric(givingQte)){
    // NOW : Effectuer la mutiplication de la quantite par le prix unitaire d'achat
    sTotal_pua = product_pua * givingQte;
    $('#calculGenTotal').val($('#calculGenTotal').val() + sTotal_pua);
  }
  $('#sousTotalLabel_'+rowId).val(sTotal_pua);
  return true;

}

function addProductToPannier()
{
  var selectedDiv = document.getElementById('produit_reaprovisionner').value;
  // On elabore une request ajax pour ajouter cela au panier
  var myurl = '<?= Yii::$app->getUrlManager()->createAbsoluteUrl(md5("inventaire_reaprovision")) ?>';
  $.ajax({
        type: 'GET',
        cache: false,
        url: myurl,
        data: {
          _csrf : '<?=Yii::$app->request->getCsrfToken()?>',
          selectedDiv : selectedDiv,
          addToReaprovisionnementBaquet : "<?= md5('onemore')?>",
              },
        success: function(data){
          $('#div_pannier_reaprovision').append(data);
        },
        error: function(){ alert('erreur'); },
    });
}

// function Recuperrons le prix unitqire du produit
function getProductUnitPrice()
{
  var valeurProduit = $('#produitAvendre').val();// recupere la valeur du produit selectionne
  var myurl = '<?= Yii::$app->getUrlManager()->createAbsoluteUrl(md5("inventaire_reaprovision")) ?>';
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
   var myurl = '<?= Yii::$app->getUrlManager()->createAbsoluteUrl(md5("inventaire_reaprovision")) ?>';
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
    var myurl = '<?= Yii::$app->getUrlManager()->createAbsoluteUrl(md5("inventaire_reaprovision")) ?>';
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
