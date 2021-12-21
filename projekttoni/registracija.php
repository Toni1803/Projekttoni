<?php 
	print '
	<h1>Forma za registraciju</h1>
	<div id="register">';
	
	if ($_POST['_action_'] == FALSE) {
		print '
		<form action="" id="registration_form" name="registration_form" method="POST">
			<input type="hidden" id="_action_" name="_action_" value="TRUE">
			
			<label for="ime">Ime *</label>
			<input type="text" id="ime" name="ime" placeholder="Vaše ime.." required>
			<label for="prezime">Prezime *</label>
			<input type="text" id="prezime" name="prezime" placeholder="Vaše prezime.." required>
				
			<label for="email">Vaš E-mail *</label>
			<input type="email" id="email" name="email" placeholder="Vaš email.." required>
			
			<label for="korime">Korisničko ime:* <small>(Korisničko ime mora imati između 5 i 10 znakova)</small></label>
			<input type="text" id="korime" name="korime" pattern=".{5,10}" placeholder="Korime.." required><br>
			
			<label for="grad">Grad *</label>
			<input type="text" id="grad" name="grad" placeholder="Vaš grad.." required>
			
			<label for="ulica">Ulica *</label>
			<input type="text" id="ulica" name="ulica" placeholder="Vaš email.." required>
			
			<label for="datum">Datum_rođenja *</label>
			<input type="date" id="datum_rođenja" name="datum_rođenja" placeholder="Datum_rođenja.." required>
									
			<label for="lozinka">Lozinka:* <small>(Lozinka mora imati najmanje 4 znaka)</small></label>
			<input type="password" id="lozinka" name="lozinka" placeholder="Lozinka.." pattern=".{4,}" required>
			<label for="drzava">Drzava:</label>
			<select name="drzava" id="drzava">
				<option value="">molimo odaberite</option>';
				#Select all countries from database projekttoni, table drzave
				$query  = "SELECT * FROM drzave";
				$result = @mysqli_query($MySQL, $query);
				while($row = @mysqli_fetch_array($result)) {
					print '<option value="' . $row['kod_drzave'] . '">' . $row['ime_drzave'] . '</option>';
				}
			print '
			</select>
			<input type="submit" value="Submit">
		</form>';
	}
	else if ($_POST['_action_'] == TRUE) {
		
		    $query  = "SELECT * FROM korisnici";
		    $query .= " WHERE email='" .  $_POST['email'] . "'";
		    $query .= " OR korime='" .  $_POST['korime'] . "'";
		    $result = @mysqli_query($MySQL, $query);
		    $row = @mysqli_fetch_array($result, MYSQLI_ASSOC);
		
		if ($row['email'] == '' || $row['korime'] == '') {
			# password_hash https://secure.php.net/manual/en/function.password-hash.php
			# password_hash() creates a new password hash using a strong one-way hashing algorithm
			$pass_hash = password_hash($_POST['lozinka'], PASSWORD_DEFAULT, ['cost' => 12]);
			
			$query  = "INSERT INTO korisnici (ime, prezime, email, korime, lozinka, drzava)";
			$query .= " VALUES ('" . $_POST['ime'] . "', '" . $_POST['prezime'] . "', '" . $_POST['email'] . "', '" . $_POST['korime'] . "', '" . $pass_hash . "', '" . $_POST['drzava'] . "')";
			$result = @mysqli_query($MySQL, $query);
			
			echo '<p>' . ucfirst(strtolower($_POST['ime'])) . ' ' .  ucfirst(strtolower($_POST['prezime'])) . ', hvala na registraciji </p>
			<hr>';
		}
		else {
			echo '<p>User with this email or username already exist!</p>';
		}
	}
	print '
	</div>';
?>