<?php 
	
	#Add news
	if (isset($_POST['_action_']) && $_POST['_action_'] == 'add_news') {
		$_SESSION['message'] = '';
		# htmlspecialchars — Convert special characters to HTML entities
		# http://php.net/manual/en/function.htmlspecialchars.php
		$query  = "INSERT INTO vijesti (naslov, opis, arhiva)";
		$query .= " VALUES ('" . htmlspecialchars($_POST['naslov'], ENT_QUOTES) . "', '" . htmlspecialchars($_POST['opis'], ENT_QUOTES) . "', '" . $_POST['arhiva'] . "')";
		$result = @mysqli_query($MySQL, $query);
		
		$ID = mysqli_insert_id($MySQL);
		
		# slika
        if($_FILES['slika']['error'] == UPLOAD_ERR_OK && $_FILES['slika']['name'] != "") {
                
			# strtolower - Returns string with all alphabetic characters converted to lowercase. 
			# strrchr - Find the last occurrence of a character in a string
			$ext = strtolower(strrchr($_FILES['picture']['name'], "."));
			
            $_picture = $ID . '-' . rand(1,100) . $ext;
			copy($_FILES['slika']['tmp_name'], "vijesti/".$_picture);
			
			if ($ext == '.jpg' || $ext == '.png' || $ext == '.gif') { # test if format is picture
				$_query  = "UPDATE vijesti SET slika='" . $_slika . "'";
				$_query .= " WHERE ID=" . $ID . " LIMIT 1";
				$_result = @mysqli_query($MySQL, $_query);
				$_SESSION['message'] .= '<p>Uspješno ste dodali sliku.</p>';
			}
        }
		
		
		$_SESSION['message'] .= '<p>Uspješno ste dodali vijest!</p>';
		
		# Redirect
		header("Location: index.php?menu=9&action=2");
	}
	
	# Update news
	if (isset($_POST['_action_']) && $_POST['_action_'] == 'edit_news') {
		$query  = "UPDATE vijesti SET naslov='" . htmlspecialchars($_POST['naslov'], ENT_QUOTES) . "', opis='" . htmlspecialchars($_POST['opis'], ENT_QUOTES) . "', arhiva='" . $_POST['arhiva'] . "'";
        $query .= " WHERE ID=" . (int)$_POST['edit'];
        $query .= " LIMIT 1";
        $result = @mysqli_query($MySQL, $query);
		
		# picture
        if($_FILES['slika']['error'] == UPLOAD_ERR_OK && $_FILES['slika']['name'] != "") {
                
			# strtolower - Returns string with all alphabetic characters converted to lowercase. 
			# strrchr - Find the last occurrence of a character in a string
			$ext = strtolower(strrchr($_FILES['slika']['name'], "."));
            
			$_picture = (int)$_POST['edit'] . '-' . rand(1,100) . $ext;
			copy($_FILES['slika']['tmp_name'], "vijesti/".$_picture);
			
			
			if ($ext == '.jpg' || $ext == '.png' || $ext == '.gif') { # test if format is picture
				$_query  = "UPDATE news SET slika='" . $_slika . "'";
				$_query .= " WHERE ID=" . (int)$_POST['edit'] . " LIMIT 1";
				$_result = @mysqli_query($MySQL, $_query);
				$_SESSION['message'] .= '<p>Uspješno ste dodali sliku.</p>';
			}
        }
		
		$_SESSION['message'] = '<p>Uspješno ste promijenili vijest!</p>';
		
		# Redirect
		header("Location: index.php?menu=9&action=2");
	}
	# End update news
	
	# Delete news
	if (isset($_GET['delete']) && $_GET['delete'] != '') {
		
		# Delete picture
        $query  = "SELECT slika FROM vijesti";
        $query .= " WHERE ID=".(int)$_GET['delete']." LIMIT 1";
        $result = @mysqli_query($MySQL, $query);
        $row = @mysqli_fetch_array($result);
        @unlink("vijesti/".$row['slika']); 
		
		# Delete news
		$query  = "DELETE FROM vijesti";
		$query .= " WHERE ID=".(int)$_GET['delete'];
		$query .= " LIMIT 1";
		$result = @mysqli_query($MySQL, $query);

		$_SESSION['message'] = '<p>Uspješno ste izbrisali vijest!</p>';
		
		# Redirect
		header("Location: index.php?menu=9&action=2");
	}
	# End delete news
	
	
	#Show news info
	if (isset($_GET['ID']) && $_GET['ID'] != '') {
		$query  = "SELECT * FROM vijesti";
		$query .= " WHERE ID=".$_GET['ID'];
		$query .= " ORDER BY datum DESC";
		$result = @mysqli_query($MySQL, $query);
		$row = @mysqli_fetch_array($result);
		print '
		<h2>Pregled vijesti</h2>
		<div class="news">
			<img src="vijesti/' . $row['slika'] . '" alt="' . $row['naslov'] . '" naslov="' . $row['naslov'] . '">
			<h2>' . $row['naslov'] . '</h2>
			' . $row['opis'] . '
			<time datetime="' . $row['datum'] . '">' . pickerDateToMysql($row['datum']) . '</time>
			<hr>
		</div>
		<p><a href="index.php?menu='.$menu.'&amp;action='.$action.'">Back</a></p>';
	}
	
	#Add news 
	else if (isset($_GET['add']) && $_GET['add'] != '') {
		
		print '
		<h2>Dodaj vijest</h2>
		<form action="" id="news_form" name="news_form" method="POST" enctype="multipart/form-data">
			<input type="hidden" id="_action_" name="_action_" value="add_news">
			
			<label for="naslov">Naslov *</label>
			<input type="text" id="naslov" name="naslov" placeholder="Naslov.." required>
			<label for="opis">Opis *</label>
			<textarea id="opis" name="opis" placeholder="Opis.." required></textarea>
				
			<label for="slika">Slika</label>
			<input type="file" id="slika" name="slika">
						
			<label for="arhiva">Arhiva:</label><br />
            <input type="radio" name="arhiva" value="Y"> YES &nbsp;&nbsp;
			<input type="radio" name="arhiva" value="N" checked> NO
			
			<hr>
			
			<input type="submit" value="Submit">
		</form>
		<p><a href="index.php?menu='.$menu.'&amp;action='.$action.'">Back</a></p>';
	}
	#Edit news
	else if (isset($_GET['edit']) && $_GET['edit'] != '') {
		$query  = "SELECT * FROM vijesti";
		$query .= " WHERE ID=".$_GET['edit'];
		$result = @mysqli_query($MySQL, $query);
		$row = @mysqli_fetch_array($result);
		$checked_archive = false;

		print '
		<h2>Uredi vijest</h2>
		<form action="" id="news_form_edit" name="news_form_edit" method="POST" enctype="multipart/form-data">
			<input type="hidden" id="_action_" name="_action_" value="edit_news">
			<input type="hidden" id="edit" name="edit" value="' . $row['ID'] . '">
			
			<label for="naslov">Naslov *</label>
			<input type="text" id="naslov" name="naslov" value="' . $row['naslov'] . '" placeholder="Naslov.." required>
			<label for="opis">Opis *</label>
			<textarea id="opis" name="opis" placeholder="Opis.." required>' . $row['opis'] . '</textarea>
				
			<label for="slika">Slika</label>
			<input type="file" id="slika" name="slika">
						
			<label for="arhiva">Arhiva:</label><br />
            <input type="radio" name="arhiva" value="Y"'; if($row['arhiva'] == 'Y') { echo ' checked="checked"'; $checked_archive = true; } echo ' /> YES &nbsp;&nbsp;
			<input type="radio" name="arhiva" value="N"'; if($checked_archive == false) { echo ' checked="checked"'; } echo ' /> NO
			
			<hr>
			
			<input type="submit" value="Submit">
		</form>
		<p><a href="index.php?menu='.$menu.'&amp;action='.$action.'">Back</a></p>';
	}
	else {
		print '
		<h2>Vijesti</h2>
		<div id="news">
			<table>
				<thead>
					<tr>
						<th width="16"></th>
						<th width="16"></th>
						<th width="16"></th>
						<th>Naslov</th>
						<th>Opis</th>
						<th>Datum</th>
						<th width="16"></th>
					</tr>
				</thead>
				<tbody>';
				$query  = "SELECT * FROM vijesti";
				$query .= " ORDER BY datum DESC";
				$result = @mysqli_query($MySQL, $query);
				while($row = @mysqli_fetch_array($result)) {
					print '
					<tr>
						<td><a href="index.php?menu='.$menu.'&amp;action='.$action.'&amp;id=' .$row['ID']. '"><img src="img/user.png" alt="user"></a></td>
						<td><a href="index.php?menu='.$menu.'&amp;action='.$action.'&amp;edit=' .$row['ID']. '"><img src="img/edit.png" alt="uredi"></a></td>
						<td><a href="index.php?menu='.$menu.'&amp;action='.$action.'&amp;delete=' .$row['ID']. '"><img src="img/delete.png" alt="obriši"></a></td>
						<td>' . $row['naslov'] . '</td>
						<td>';
						if(strlen($row['opis']) > 160) {
                            echo substr(strip_tags($row['opis']), 0, 160).'...';
                        } else {
                            echo strip_tags($row['opis']);
                        }
						print '
						</td>
						<td>' . pickerDateToMysql($row['datum']) . '</td>
						<td>';
							if ($row['arhiva'] == 'Y') { print '<img src="img/inactive.png" alt="" title="" />'; }
                            else if ($row['arhiva'] == 'N') { print '<img src="img/active.png" alt="" title="" />'; }
						print '
						</td>
					</tr>';
				}
			print '
				</tbody>
			</table>
			<a href="index.php?menu=' . $menu . '&amp;action=' . $action . '&amp;add=true" class="AddLink">Dodaj vijest</a>
		</div>';
	}
	
	# Close MySQL connection
	@mysqli_close($MySQL);
?>