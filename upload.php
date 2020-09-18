<?php
if (isset($_FILES['file'])) {
    if ( 0 < $_FILES['file']['error'] ) {
        echo 'Error: ' . $_FILES['file']['error'] . '<br>';
    }
    else {
    	$directory = "uploads/";
    	$path = $directory.$_FILES['file']['name'];
    	if (file_exists($path))
	{
		$i= 1;
		
		while (file_exists($path))
		{
			// get file extension
			$extension = pathinfo($path, PATHINFO_EXTENSION);
			
			// get file's name
			$filename = pathinfo($path, PATHINFO_FILENAME);
			
			// add and combine the filename, iterator, extension
			$new_filename = $filename . '-' . $i . '.' . $extension;
			
			// add file name to the end of the path to place it in the new directory; the while loop will check it again
			$path = $directory . $new_filename;
			$i++;
			
		}
    move_uploaded_file($_FILES['file']['tmp_name'], $path);
    echo $new_filename;
    } else {

    	$file = $_FILES['file']['name'];
        move_uploaded_file($_FILES['file']['tmp_name'], 'uploads/' . $file);
        echo $file; 
    }
    }
  }

?>
