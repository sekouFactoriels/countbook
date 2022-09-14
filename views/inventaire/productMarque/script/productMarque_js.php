<script type="text/javascript">

var fabriquantId = '';

// ACTION SAVE NEW MARQUE
function enrgMarque(idNewFabricant){
  var myurl = "<?= Yii::$app->getUrlManager()->createAbsoluteUrl(md5('inventaire_marques'))?>";
  $.ajax({
    type: 'GET',
    cache: false,
    url: myurl,
    data: {
      _crsf: '<?= Yii::$app->request->getCsrfToken() ?>',
      idNewFabricant: idNewFabricant,
      ajax_action_key: "<?= md5('inventaire_marques') ?>",
      nom : $('#productMarqueName').val(), // NOM DE LA NOUVELLE MARQUE
      description : $('#productMarqueDesc').val(),
    },
    success: function(marqueRslt){
      return marqueRslt;
    },
    error: function(){}
  });
}

// ACTION WHEN FRABRICANT IS SELECTED
function fabriquantIdSelected(){
  var fabriquantId = $('#fabriquantId').val();
  if(fabriquantId > 0){
    $('#nouveauFabriquant').hide();
  }else{
    $('#nouveauFabriquant').show();
  }
}

// ACTION : SAVE NEW MARQUE
function productMarque_np(){
  var enrgProcess = false;
  // ANALYSER LA NATURE DE LA SECTION DU FABRIQUANT
  fabriquantId = $('#fabriquantId').val();
  if(fabriquantId > 0){
    $('#fabriquantKeyInfo').val(fabriquantId);
  }else{
    $('#fabriquantKeyInfo').val($('#nomNouveauFabriquant').val());
  }

  var formFields = ['fabriquantKeyInfo','productMarqueName'];
  var index = '1';
  var formValidation = formValidator(index, formFields);
  if(formValidation){
    //$('#loadingModal').modal('show');
    $('#ajax_action_key').val("<?= md5('newFabricant')?>");
    var myurl = "<?= Yii::$app->getUrlManager()->createAbsoluteUrl(md5('inventaire_marques'))?>";
    if(fabriquantId==0){ // DEBUT CONDITION SI UN NOUVEAU FABRICANT EST A CREER
      $.ajax({ // ENREGISTRER NOUVEAU FABRIQUANT
        type: 'GET',
        cache: false,
        url: myurl,
        data: {
          _crsf: '<?= Yii::$app->request->getCsrfToken() ?>',
          ajax_action_key: $('#ajax_action_key').val(),
          nomNouveauFabriquant : $('#nomNouveauFabriquant').val()
        },
        success: function(idNewFabricant){ // RECUPERONS L'IDE DU FABRICANT RECEMENT ENREGISTRE
          enrgProcess = enrgMarque(idNewFabricant);
          $('#productMarque').html(enrgProcess);
          $('#loadingModal').modal('hide');
          $('#newProductMarque').modal('hide');
        },
        error: function(){}
      });
    }else{// FIN CONDITION SI UN NOUVEAU FABRICANT EST A CREER | USAGE DES FABRICANTS DISPONIBLES
      enrgProcess = enrgMarque(fabriquantId);
      $('#productMarque').html(enrgProcess);
      $('#loadingModal').modal('hide');
      $('#newProductMarque').modal('hide');
    }
  }else{
    $('#empty_msg_modal').modal('show');
  	return false;
  }
}

// ACTION CANCEL
function cancel(){
  $('#loadingModal').modal('close');
  $('#newProductCategory').modal('close');
}

</script>
