<section class="login align_footer">
	<div class="container">
		<div class="login_wrap">
			<input id="sign_in" type="radio" name="login" class="login_sign_in" checked><label for="sign_in">Sign In</label>
			<input id="sign_up" type="radio" name="login" class="login_sign_up"><label for="sign_up">Sign Up</label>
			<div name="login-form" class="login_form">
				<form name="sign-in" method="post">
					<div class="login_form_in">
						<div class="login_group">
							<input type="text" name="username" placeholder="Username">
							<input type="password" name="pass" placeholder="Password">
							<input type="submit" name="submit" value="Sign In">
						</div>
						<a href="remind.php">Forgot username or password?</a>
					</div>
				</form>
				<form name="sign-up" method="post">
					<div class="login_form_up">
						<div class="login_group">
							<input type="text" name="username_up" placeholder="Username">
							<input type="email" name="email_up" placeholder="Email">
							<input type="password" name="pass_up" placeholder="Password">
							<input type="password" name="repass_up" placeholder="Repeat Password">
							<input type="submit" name="submit" value="Sign Up">
						</div>
					</div>
				</form>
			</div>

		</div>
	</div>
</section>