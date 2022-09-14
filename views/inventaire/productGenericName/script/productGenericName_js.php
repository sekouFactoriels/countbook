<script type="text/javascript">

function productGeneric_np(){
  var formField = ['productGenericName'];
  var index = 1;
  var formValidation2 = formValidator(index, formField);
  if(formValidation2){
    $('#ajax_action_key').val("<?= md5('newGenericName')?>");
    var myurl = "<?= Yii::$app->getUrlManager()->createAbsoluteUrl(md5('inventaire_genericname'))?>";
    $.ajax({
      type: 'GET',
      cache: false,
      url: myurl,
      data: {
        _crsf: '<?= Yii::$app->request->getCsrfToken() ?>',
        ajax_action_key: $('#ajax_action_key').val(),
        productGenericName: $('#productGenericName').val(),
        productGenericDesc: $('#productGenericDesc').val()
      },
      success: function(rslt){
        $('#loadingModal').modal('hide');
        $('#generiqueNameId').html(rslt);
        $('#newProductGenericName').modal('hide');
      },
      error: function(error){
        $('#generiqueNameId').html(error.responseText);
        $('#newProductGenericName').modal('hide');
        //alert(error.responseText);
      }
    });
  }else{
    $('#empty_msg_modal').modal('show');
  	return false;
  }
}

function cancel(){
  $('#loadingModal').modal('close');
  $('#newProductGenericName').modal('close');
}

</script>
