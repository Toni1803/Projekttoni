<?php
        print'
		<h1>Forma za kontakt</h1>
		<div id="contact">
			<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2780.766797286913!2d16.000859515085015!3d45.81592861792642!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4765d7b186afcbed%3A0xeefc736386492694!2sCLINIC%20DRINKOVI%C4%86!5e0!3m2!1sen!2shr!4v1635942947100!5m2!1sen!2shr" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
			<form action="http://work2.eburza.hr/pwa/responzive-page/send-contact.php" id="contact_form" name="contact_form" method="POST">
				<label for="fname">First Name *</label>
				<input type="text" id="fname" name="firstname" placeholder="Your name.." required>

				<label for="lname">Last Name *</label>
				<input type="text" id="lname" name="lastname" placeholder="Your last name.." required>
				
				<label for="lname">Your E-mail *</label>
				<input type="email" id="email" name="email" placeholder="Your e-mail.." required>

				<label for="country">Country</label>
				<select id="country" name="country">
				  <option value="">Please select</option>
				  <option value="BE">Belgium</option>
				  <option value="HR" selected>Croatia</option>
				  <option value="LU">Luxembourg</option>
				  <option value="HU">Hungary</option>
				</select>

				<label for="subject">Subject</label>
				<textarea id="subject" name="subject" placeholder="Write something.." style="height:200px"></textarea>

				<input type="submit" value="Submit">
			</form>
		</div>';
?>