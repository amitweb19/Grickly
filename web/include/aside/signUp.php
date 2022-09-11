<div id="signUp">
	<span class="inputFull"><span style=" font-size: 30px; font-style: italic; color: #21B8C6;">-: Create an Account :-</span></span>
	<form onsubmit="return false;">
	<span class="inputFull">
		<span class="inputHalf">First Name:<br> <input id="fname" type="text" required></span>
		<span class="inputHalf">Last Name:<br> <input id="lname" type="text" required></span>
	</span>
	<span class="inputFull">
		<span class="inputHalf">Email:<br> <input id="email" type="text"  onblur="checkemail()" onfocus="emptyElement('status')" onkeyup="restrict('email')" maxlength="88" required></span>
		<span class="inputHalf">
			I am a <br>
			<select id="userlevel" style="width: 225px;" required>
				<option value=""></option>
				<option value="s">Student</option>
				<option value="t">Teacher</option>
				<option value="o">Other</option>
			</select>
		</span>
	</span>
	<span class="inputFull">
		<span class="inputHalf">New Pass:<br> <input id="pass1" type="password" required></span>
		<span class="inputHalf">Confirm Pass:<br> <input id="pass2" type="password" required></span>
	</span>
	<span class="inputFull">
		<span class="inputHalf">Gender:<br><input id="male" type="radio" name="gender" value="m" checked> <label for="male">Male</label> &emsp; <input id="female" type="radio" name="gender" value="f"> <label for="female">Female</label></span>
		<span class="inputHalf">
			Date of Birth : <br>
			<select id="date" style="width: 40px;" required>
				<option value=""></option>
				<?php
					for($d=1; $d<=31; $d++)
					{
						if($d < 10)
						{
							echo "<option value=0".$d.">0".$d."</option>";
						} else {
							echo "<option value=".$d.">".$d."</option>";
						}
					}
				?>
			</select>
			<select id="month" style="width: 90px;" required>
				<option value=""></option>
				<option value="01">January</option>
				<option value="02">February</option>
				<option value="03">March</option>
				<option value="04">April</option>
				<option value="05">May</option>
				<option value="06">June</option>
				<option value="07">July</option>
				<option value="08">August</option>
				<option value="09">September</option>
				<option value="10">October</option>
				<option value="11">November</option>
				<option value="12">December</option>
			</select>
			<select id="year" style="width: 55px;" required>
				<option value=""></option>
				<?php
					for($y=2010; $y>=1950; $y--)
					{
						echo "<option value=".$y.">".$y."</option>";
					}
				?>
			</select>
			<span style="color: #FFF; padding: 0 5px; height:; background: #21B8C6; border-radius: 30px; cursor: help; border: 3px solid #FFF;">?</span>
		</span>
	</span>
	<span class="inputFull" style="height: 50px;">By clicking Sign up, I agree to Grickly's <a href="">Terms</a> and <a href="">Privacy</a> Policy. </span>
	<span class="inputFull">
		<button  id="signupbtn"  onclick="signup()">Sign up</button><br /><br />
		<span id="status" style="color: #f00;"></span>
	</span>
</form>
</div>