<script type="text/javascript">
  // empecher la saisie des montants negatifs
  function positifMontantParcu() {
    var lemontantpercevoir = parseInt($('#mont_payer').val());
    const element = document.getElementById("contenuModalAlert");

    if (lemontantpercevoir < 0) {

      $('#mont_payer').val(0);
      $('#mont_payer').focus();
      $('#nouveau_mont_rest').val(0);
      $('#nextpaiement_div').hide();
      element.innerHTML = "Le montant à payer ne doit pas être négatif !";
      $('#modalAlert').modal('show');
    }
  }

  /** Methode : preparer la soumission du formulaire **/
  function submit_form(argument) {
    var formField = ['ancien_mont_rest', 'mont_payer', 'nouveau_mont_rest', 'mode_paiement'];
    var index = 1;
    var formValidation = formValidator(index, formField);
    if (formValidation) {
      $('#countbook_form_modal').modal('show');
    } else {
      $('#empty_msg_modal').modal('show');
      return false;
    }
  }

  /** Methode : Calculer le montant restant du paiement **/
  function calculDetteVente() {
    var lemontantfinal = $('#ancien_mont_rest').val();
    var lemontantpercevoir = $('#mont_payer').val();
    var ladettedevente = null;
    var categorie_autre_partie = $('#categorie_autre_partie').val();
    if (lemontantfinal > 0) {
      ladettedevente = lemontantfinal - lemontantpercevoir;
      $('#nouveau_mont_rest').val(ladettedevente);

      switch (categorie_autre_partie) {
        case '1':
          if (ladettedevente > 0) {
            $('#nextpaiement_div').show();
          } else {
            $('#nextpaiement_div').hide();
          }
          break;

      }
    }
    return true;
  }

  /** Methode : Afficher les variantes du mode de paiement **/
  function mode_paiement_selected() {
    var mode_paiement = $('#mode_paiement').val();
    switch (mode_paiement) {
      case '3':
        $('#div_banque').show();
        $('#div_cheque').hide();
        break;

      case '2':
        $('#div_banque').hide();
        $('#div_cheque').show();
        break;

      default:
        $('#div_banque').hide();
        $('#div_cheque').hide();
        break;
    }
  }
</script>