<script type="text/javascript">
	function nEntite(){
		$("#action_key").val("<?= md5('new_entite')?>");
		$("#entit_frm").submit();
	}
	function lEntite(){
		$("#action_key").val("<?= md5('liste_entite')?>");
		$("#entit_frm").submit();
	}


</script>