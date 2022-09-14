<script type="text/javascript">
	function nVente(){
		$("#action_key").val("<?= md5('nvente')?>");
		$("#vente_simple_frm").submit();
	}

	function retreviewVente(){
		$("#action_key").val("<?= md5('retreviewVente')?>");
		$("#vente_simple_frm").submit();
	}

</script>
