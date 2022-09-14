<script type="text/javascript">
  // FUNCTION QUI VERIFIE SI LES UNITES DE PRODUIT A AJUSTER SONT VRAIMENT DISPONIBBLES
  function operationAjustment(){
    var myUrl = "<?= Yii::$app->getUrlManager()->createAbsoluteUrl(md5('inventaire_produit'))?>";
    var uniteProductSaisie = 0;
    $.ajax({
      type: 'GET',
      cache: false,
      url: myUrl,
      data: {
        _csrf: "<?= Yii::$app->request->getCsrfToken() ?>",
        action_key: "<?= md5('ajustmentunitaireproduit')?>",
        thisProductId: $('#listProduct').val(),
        udmProduct: $('#listUdm').val(),
        qteAjouter: $('#unitProduct').val(),
        typeAjustation: $('#typeAjustation').val(),
      },
      success: function(rslt){
        if(rslt == false){
          $('#beforeAjuster').modal('hide');
          $('#errorMsg').modal('show');
        }else{
          $('#beforeAjuster').modal('hide');
          $('#actionMsg').modal('show');
        }
      },
      error: function(){}
    });
  }

  // FUNCTION QUI RENVOIS LA LISTE DES UDM D'UN PRODUIT
  function getUDMproduct(){
    $('#loadingModal').modal('show');
    var myurl = "<?= Yii::$app->getUrlManager()->createAbsoluteUrl(md5('inventaire_udms')) ?>";
    $.ajax({
      type: 'GET',
      cache: false,
      url: myurl,
      data: {
        _csrf: "<?= Yii::$app->request->getCsrfToken() ?>",
        thisProductIdUdms: $('#listProduct').val(),
        action_key: "<?= md5('udmBaseProductId')?>",
      },
      success: function(rslt){
        $('#loadingModal').modal('hide');
        $('#listUdm').html(rslt);
      },
      error: function(){}
    });
  }

  // FUNCTION : DO AJUSTATION
  function ajuster(){
    var requiredInput = ['listeEntrepot','pointdeVente','operation','typeAjustation','listProduct','listUdm','unitProduct'];
    var index = 1;
    var  rsltUniteVaidation = false;
    frmValidation = formValidator(index, requiredInput);
    if(frmValidation){
      operationAjustment();
    }else{
      $('#empty_msg_modal').modal('show');
      $('#beforeAjuster').modal('hide');
    	return false;
    }
  }

  // FUNCTION: CANCEL
  function cancel(){

  }
  // ACTION ON PAGE READY
  $(document).ready(function(){
    // IMPOSE LA SAISIE DES NOMBRE SEULEMENT ENTIER
    $('#unitProduct').keyup(function(){
      this.value = this.value.replace(/[^0-9]/g,``);
    });
  });

</script>
