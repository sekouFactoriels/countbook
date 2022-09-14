<script type="text/javascript">
	function nfournis(){
		$("#action_key").val("<?= md5('nfournisseur') ?>");
		$("#founisseur_frm").submit();
	}
	function lfournis(){
		$("#action_key").val("<?= md5('lfournisseurs') ?>");
		$("#founisseur_frm").submit();
	}
</script>
