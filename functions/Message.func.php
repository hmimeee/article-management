<?php
function message($message,$messagetype){
		if ($messagetype =="1") {
		echo '<div class="alert alert-success text-center" role="alert">
		'.$message.'
</div>';
} else {
	echo '<div class="alert alert-danger text-center" role="alert">
		'.$message.'
</div>';
}
}
?>