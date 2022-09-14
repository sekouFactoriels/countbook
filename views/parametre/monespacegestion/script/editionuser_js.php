<script type="text/javascript">
  function questionmotpassaction(){
    var data = $('#questionmotpass').val();
    switch (data) {
      case '1': $('#divnouveaumotpasse').show(); break;
      case '2': $('#divnouveaumotpasse').hide(); break;
    }
    return true;
  }
</script>
