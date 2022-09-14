<script type="text/javascript">
	function getdata(event) {
		console.log(event);
		if(document.getElementById(event).checked){
			document.getElementById('div_'+event).style.pointerEvents = 'auto';
			document.getElementById('div_'+event).style.opacity = '1';
		}else{
			document.getElementById('div_'+event).style.pointerEvents = 'none';
			document.getElementById('div_'+event).style.opacity = '0.4';
		}
	}
</script>