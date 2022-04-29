<?php
	//Database connection:
	$servername = "localhost";
	$username 	= "root";
	$password 	= "root";
	$dbname 	= "mustafa";
	
	//File Paths
	$dir 		= "myfiles/";
	$movedfile 	= "readed_files";
	
	
	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);

	// Check connection
	if($conn->connect_error) 
	{
		die("Connection failed: " . $conn->connect_error);
	} 
	
	$x = scandir($dir);
	$files = "AA".count($x).": ";
	$rows = 0;
	if (is_readable($dir) && count($x) > 2)
	{
		foreach($x as $file)
		{
			try{
				if(is_file($dir.$file))
				{
					$handle = fopen($dir.$file, "r");
					if($handle)
					{
						while(($line = fgets($handle)) !== false)
						{
							$data = explode(",",$line);
							$sql = "INSERT IGNORE INTO m_data (no, name, phone,file_name)
										VALUES ('".$data[0]."', 
												'".$data[1]."', 
												'".$data[2]."', 
												'".$file."')";
							if($conn->query($sql) === TRUE) 
							{
								$rows ++;
							}
						}

						fclose($handle);
						$files.= $file.",";
					} 
					rename($dir.$file,$movedfile."/".$file);
					
				}
				
			}catch(Exception $e)
			{
				die($dir.$file);
			}
		}
	}
	$ret = array("files"=>$files,"rows"=>$rows);
	echo json_encode($ret);
?>