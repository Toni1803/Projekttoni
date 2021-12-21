<?php 
	print '
	<link rel="stylesheet" href="stil.css">
	<h1>Forma za prijavu</h1>
	<div id="signin">';
	
	if ($_POST['_action_'] == FALSE) {
		print '
		<form action="" name="myForm" id="myForm" method="POST">
			<input type="hidden" id="_action_" name="_action_" value="TRUE">
			<label for="korime">Korisničko ime:*</label>
			<input type="text" id="korime" name="korime" value="" pattern=".{5,10}" required>
									
			<label for="lozinka">Lozinka:*</label>
			<input type="password" id="lozinka" name="lozinka" value="" pattern=".{4,}" required>
									
			<input type="submit" value="Submit">
		</form>';
	}
	else if ($_POST['_action_'] == TRUE) {
		
		$query  = "SELECT * FROM korisnici";
		$query .= " WHERE korime='" .  $_POST['korime'] . "'";
		$result = @mysqli_query($MySQL, $query);
		$row = @mysqli_fetch_array($result, MYSQLI_ASSOC);
		
		if (password_verify($_POST['lozinka'], $row['lozinka'])) {
			#password_verify https://secure.php.net/manual/en/function.password-verify.php
			$_SESSION['user']['valid'] = 'true';
			$_SESSION['user']['id'] = $row['id'];
			$_SESSION['user']['ime'] = $row['ime'];
			$_SESSION['user']['prezime'] = $row['prezime'];
			$_SESSION['message'] = '<p>Dobrodošli, ' . $_SESSION['user']['ime'] . ' ' . $_SESSION['user']['prezime'] . '</p>';
			# Redirect to admin website
			header("Location: index.php?menu=9");
		}
		
		# Bad username or password
		else {
			unset($_SESSION['user']);
			$_SESSION['message'] = '<p>Unijeli ste krivo korisničko ime ili lozinku!</p>';
			header("Location: index.php?menu=8");
		}
	}
	print '
	</div>';
?>