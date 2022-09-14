<script type="text/javascript">

function productGroup_np(){
  var formFields = ['productGroupName'];
  var index = 1
  var formValidation3 = formValidator(index, formFields);
  if(formValidation3){
    $('#ajax_action_key').val("<?= md5('newGroup')?>");
    var myurl = "<?= Yii::$app->getUrlManager()->createAbsoluteUrl(md5('inventaire_groups'))?>";
    $.ajax({
      type: 'GET',
      cache: false,
      url: myurl,
      data: {
        _crsf: '<?= Yii::$app->request->getCsrfToken() ?>',
        action_key: $('#action_key').val(),
        ajax_action_key: '<?= md5("newGroup")?>',
        productGroupName : $('#productGroupName').val(),
        productGroupDesc : $('#productGroupDesc').val()
      },
      success: function(rslt){
        $('#loadingModal').modal('hide');
        $('#productGroup').html(rslt);
        $('#newProductGroup').modal('hide');
      },

      error: function(rslt){rslt.responseText}
    });
  }else{
    $('#empty_msg_modal').modal('show');
  	return false;
  }
}

function cancel(){
  $('#loadingModal').modal('hide');
  $('#newProductCategory').modal('hide');
}

</script>
