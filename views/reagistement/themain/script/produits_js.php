<script type="text/javascript">
  function submitFilter(){
    $('#listeProduits').submit();
  }

  function reagistement(id) {
    var qteActu='';
    var row = document.getElementById('row_' + id);
    qteActu = $('#qteActuelle'+id).val();

    qteActu=parseInt(qteActu);
    $('#qteExistante_'+id).val(qteActu);
  }
</script>
