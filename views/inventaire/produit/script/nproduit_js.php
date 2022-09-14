<script type="text/javascript">
  function source_code_produit() {
    var generation_source = $('#generation_code_produit').val();
    if (generation_source == 1) {
      $('#div_code_article').html('<input type="text" value="" class="form-control" name="productCode" id="productCode" autocomplete="off"/>');
    } else {
      $('#div_code_article').html('<input onKeypress="return false;" type="text" value="<?= (isset($_POST[Yii::$app->params['productCode']])) ? $_POST['productCode'] : Null ?>" class="form-control" name="productCode" id="productCode" autocomplete="off" onkeyup="verifierCaracteres()"/><a href="javascript:;" onClick="getNewProductCode()" class="btn btn-default input-group-addon" name="productCode" id="productCode"><i class="fa fa-random"></i></a>');
    }
  }


  function verifierCaracteres(event) {
    var keyCode = event.which ? event.which : event.keyCode;
    var touche = String.fromCharCode(keyCode);
    //var champ = document.getElementById('mon_input');
    var caracteres = '';
    if (caracteres.indexOf(touche) >= 0) {
      champ.value += touche;
    }
    return false;
  }


  function alertSaveProduct() {
    $('#submitFormModalText').html('<p><?= yii::t('app', 'clickOkValid') ?></p>');
    $('#submitFormModal').modal('show');
  }

  function submitFilter() {
    $('#listeProduits').submit();
  }

  function getNewProductCode() {
    $('#loadingModal').modal('show');
    $('ajax_action_key').val('1');
    var myurl = '<?= Yii::$app->getUrlManager()->createAbsoluteUrl(md5("inventaire_nproduit")); ?>';
    $.ajax({
      type: 'GET',
      cache: false,
      url: myurl,
      data: {
        _csrf: '<?= Yii::$app->request->getCsrfToken() ?>',
        newCodeProduit: '1',
      },
      success: function(data) {
        $('#loadingModal').modal('hide');
        $('#productCode').val(data);
      },
      error: function(data) {
        //alert(JSON.stringify(data));
        alert('erreur');
      },
    });
  }

  function saveProduct() {
    var formField = ['productCode', 'productType', 'productName', 'productCategory'];
    var index = 1;
    var formValidation = formValidator(index, formField);
    if (formValidation) {
      $('#nProduitForm').submit(); // SUBMIT THE FORM TO BE SAVED
    } else {
      $('#empty_msg_modal').modal('show');
      return false;
    }
  }

  
</script>