<script type="text/javascript">

  function saveData(){
    var formField = ['motif','montant'];
    var index = 1;
    var formValidation = formValidator(index, formField);
    if(formValidation){
      $('#ncharge').submit(); // SUBMIT THE FORM TO BE SAVED
    }else{
      $('#empty_msg_modal').modal('show');
    	return false;
    }
  }

  function addNewMotif(){
    var formField = ['productCatName'];
    var index = 1;
    var formValidation = formValidator(index, formField);
    if(formValidation){
      $('#ajax_action_key').val("<?= md5('newCat')?>")
      var myurl = "<?= Yii::$app->getUrlManager()->createAbsoluteUrl(md5('diver_charge'))?>";
      $.ajax({
        type: 'GET',
        cache: false,
        url: myurl,
        data: {
          _crsf: '<?= Yii::$app->request->getCsrfToken()?>',
          action_key: $('#action_key').val(),
          ajax_action_key: '<?= md5("newMotif")?>',
          motifLabel : $('#motifLabel').val(),
        },
        success: function(rslt){
          //$('#loadingModal').modal('hide');
          $('#newMotif').modal('hide');
          $('#motif').html(rslt);
        },

        error: function(){}
      });
    }else{
      $('#empty_msg_modal').modal('show');
    	return false;
    }
  }
</script>
