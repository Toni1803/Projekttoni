<?php 
	
	# Update user profile
	if (isset($_POST['edit']) && $_POST['_action_'] == 'TRUE') {
		$query  = "UPDATE korisnici SET ime='" . $_POST['ime'] . "', prezime='" . $_POST['prezime'] . "', email='" . $_POST['email'] . "', korime='" . $_POST['korime'] . "', drzava='" . $_POST['drzava'] . "', arhiva='" . $_POST['arhiva'] . "'";
        $query .= " WHERE ID=" . (int)$_POST['edit'];
        $query .= " LIMIT 1";
        $result = @mysqli_query($MySQL, $query);
		# Close MySQL connection
		@mysqli_close($MySQL);
		
		$_SESSION['message'] = '<p>Uspješno ste promijenili profil!</p>';
		
		# Redirect
		header("Location: index.php?menu=9&action=1");
	}
	# End update user profile
	
	# Delete user profile
	if (isset($_GET['delete']) && $_GET['delete'] != '') {
	
		$query  = "DELETE FROM korisnici";
		$query .= " WHERE ID=".(int)$_GET['delete'];
		$query .= " LIMIT 1";
		$result = @mysqli_query($MySQL, $query);

		$_SESSION['message'] = '<p>Uspješno ste izbrisali profil!</p>';
		
		# Redirect
		header("Location: index.php?menu=9&action=1");
	}
	# End delete user profile
	
	
	#Show user info
	if (isset($_GET['ID']) && $_GET['ID'] != '') {
		$query  = "SELECT * FROM korisnici";
		$query .= " WHERE ID=".$_GET['ID'];
		$result = @mysqli_query($MySQL, $query);
		$row = @mysqli_fetch_array($result);
		print '
		<h2>Profil korisnika</h2>
		<p><b>Ime:</b> ' . $row['ime'] . '</p>
		<p><b>Prezime:</b> ' . $row['prezime'] . '</p>
		<p><b>Korime:</b> ' . $row['korime'] . '</p>';
		$_query  = "SELECT * FROM drzave";
		$_query .= " WHERE kod_drzave='" . $row['drzava'] . "'";
		$_result = @mysqli_query($MySQL, $_query);
		$_row = @mysqli_fetch_array($_result);
		print '
		<p><b>Drzava:</b> ' .$_row['ime_drzave'] . '</p>
		<p><b>Date:</b> ' . pickerDateToMysql($row['date']) . '</p>
		<p><a href="index.php?menu='.$menu.'&amp;action='.$action.'">Back</a></p>';
	}
	#Edit user profile
	else if (isset($_GET['edit']) && $_GET['edit'] != '') {
		$query  = "SELECT * FROM korisnici";
		$query .= " WHERE ID=".$_GET['edit'];
		$result = @mysqli_query($MySQL, $query);
		$row = @mysqli_fetch_array($result);
		$checked_archive = false;
		
		print '
		<h2>Uredi profil</h2>
		<form action="" id="registration_form" name="registration_form" method="POST">
			<input type="hidden" id="_action_" name="_action_" value="TRUE">
			<input type="hidden" id="edit" name="edit" value="' . $_GET['edit'] . '">
			
			<label for="ime">Ime *</label>
			<input type="text" id="ime" name="ime" value="' . $row['ime'] . '" placeholder="Vaše ime.." required>
			<label for="prezime">Prezime *</label>
			<input type="text" id="prezime" name="prezime" value="' . $row['prezime'] . '" placeholder="Vaše prezime.." required>
				
			<label for="email">Vaš E-mail *</label>
			<input type="email" id="email" name="email"  value="' . $row['email'] . '" placeholder="Vaš e-mail.." required>
			
			<label for="korime">Korime * <small>(Korisničko ime mora imati između 5 i 10 znakova)</small></label>
			<input type="text" id="korime" name="korime" value="' . $row['korime'] . '" pattern=".{5,10}" placeholder="Korime.." required><br>
			
			<label for="drzava">Drzava</label>
			<select name="drzava" id="drzava">
				<option value="">molimo odaberite</option>';
				#Select all countries from database projekttoni, table drzave
				$_query  = "SELECT * FROM drzave";
				$_result = @mysqli_query($MySQL, $_query);
				while($_row = @mysqli_fetch_array($_result)) {
					print '<option value="' . $_row['kod_drzave'] . '"';
					if ($row['drzava'] == $_row['kod_drzave']) { print ' selected'; }
					print '>' . $_row['ime_drzave'] . '</option>';
				}
			print '
			</select>
			
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
		<h2>Lista korisnika</h2>
		<div id="users">
			<table>
				<thead>
					<tr>
						<th width="16"></th>
						<th width="16"></th>
						<th width="16"></th>
						<th>Ime</th>
						<th>Prezime</th>
						<th>E mail</th>
						<th>Država</th>
						<th width="16"></th>
					</tr>
				</thead>
				<tbody>';
				$query  = "SELECT * FROM korisnici";
				$result = @mysqli_query($MySQL, $query);
				while($row = @mysqli_fetch_array($result)) {
					print '
					<tr>
						<td><a href="index.php?menu='.$menu.'&amp;action='.$action.'&amp;id=' .$row['ID']. '"><img src="img/user.png" alt="user"></a></td>
						<td><a href="index.php?menu='.$menu.'&amp;action='.$action.'&amp;edit=' .$row['ID']. '"><img src="img/edit.png" alt="uredi"></a></td>
						<td><a href="index.php?menu='.$menu.'&amp;action='.$action.'&amp;delete=' .$row['ID']. '"><img src="img/delete.png" alt="obriši"></a></td>
						<td><strong>' . $row['ime'] . '</strong></td>
						<td><strong>' . $row['prezime'] . '</strong></td>
						<td>' . $row['email'] . '</td>
						<td>';
							$_query  = "SELECT * FROM drzave";
							$_query .= " WHERE kod_drzave='" . $row['drzava'] . "'";
							$_result = @mysqli_query($MySQL, $_query);
							$_row = @mysqli_fetch_array($_result, MYSQLI_ASSOC);
							print $_row['ime_drzave'] . '
						</td>
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
		</div>';
	}
?>