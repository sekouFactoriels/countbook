<script type="text/javascript">

// Function d'insertion d'un nouveau Fournisseur 
	function saveData(){
		var requiredInput = ['typeFourn','nom', 'societe','adresse','tel',];
	    var index = 1;
	    frmValidation = formValidator(index, requiredInput);
	    if(frmValidation){
	    	$('#action_key').val("<?= md5('save_nfournisseur') ?>");
			$('#nfournisseurForm').submit();
		}else{
			$('#empty_msg_modal').modal('show');
    		return false;
		}
	}
</script>