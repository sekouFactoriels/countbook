<script type="text/javascript">

  function alertSaveProduct(){
    $('#submitFormModalText').html('<p><?= yii::t('app','clickOkValid')?></p>');
    $('#submitFormModal').modal('show');
  }

  function submitFilter(){
    $('#listeProduits').submit();
  }

</script>
