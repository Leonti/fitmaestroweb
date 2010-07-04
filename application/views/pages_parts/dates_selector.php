Start: <input type = "text" id = "start-date" value = "<?php echo date("m/d/Y", strtotime('-1 month')); ?>" /> 
End: <input type = "text" id = "end-date" value = "<?php echo date("m/d/Y"); ?>" />
<input type = "submit" value = "Refresh" id = "get-stats" /> 