<script type="text/javascript">

function productCategorie_np(){
  var formField = ['productCatName'];
  var index = 1;
  var formValidation = formValidator(index, formField);
  if(formValidation){
    $('#ajax_action_key').val("<?= md5('newCat')?>")
    var myurl = "<?= Yii::$app->getUrlManager()->createAbsoluteUrl(md5('inventaire_cats'))?>";
    $.ajax({
      type: 'GET',
      cache: false,
      url: myurl,
      data: {
        _crsf: '<?= Yii::$app->request->getCsrfToken()?>',
        action_key: $('#action_key').val(),
        ajax_action_key: '<?= md5("newCat")?>',
        productCatName : $('#productCatNames').val(),
        productCatDesc : $('#productCatDescs').val(),
      },
      success: function(rslt){
        // if(rslt == 'refreshPage'){
        //   $('#listeCategories').submit();
        // }
        $('#loadingModal').modal('hide');
        $('#newProductCategory').modal('hide');
        // $('#productCategory').html(rslt);
        $('#listeCategories').submit();
      },

      error: function(){}
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
