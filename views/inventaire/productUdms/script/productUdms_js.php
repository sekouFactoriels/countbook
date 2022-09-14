<script type="text/javascript">

function productUdm_np(){
  var formField = ['productUdmName'];
  var index = 1;
  var formValidation = formValidator(index, formField);
  if(formValidation){
    $('#ajax_action_key').val("<?= md5('newCat')?>")
    var myurl = "<?= Yii::$app->getUrlManager()->createAbsoluteUrl(md5('inventaire_udms'))?>";
    $.ajax({
      type: 'GET',
      cache: false,
      url: myurl,
      data: {
        _crsf: '<?= Yii::$app->request->getCsrfToken()?>',
        action_key: $('#action_key').val(),
        ajax_action_key: '<?= md5("newCat")?>',
        productUdmName : $('#productUdmNames').val(),
        productUdmDesc : $('#productUdmDescs').val(),
      },
      success: function(rslt){
        $('#loadingModal').modal('hide');
        $('#newProductUdm').modal('hide');
        $('#listeUdms').submit();
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
  $('#newProductUdm').modal('hide');
}


// $(document).ready(function(){
//   $(document).on('click','.edit', function(){
//     var nom=$('#nom').text();
//     var desc=$('#desc').text();

//     $('#updateProductUdm').modal('show');
//     $('#productUdmNameUpdate').val(nom);
//     $('#productUdmDescUpdate').val(desc);
//   })
// })

</script>
