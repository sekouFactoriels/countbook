<script type="text/javascript">
	// Fonction d'affissage de l'ensemble des fournisseurs  
	function saveData(){
	    var index = 1;
	    frmValidation = formValidator(index, requiredInput);
	    if(frmValidation){
	    	$('#action_key').val("<?= md5('save_lfournisseur') ?>");
			$('#lfournisseurForm').submit();
		}else{
			$('#empty_msg_modal').modal('show');
    		return false;
		}
	}
</script>