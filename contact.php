<!DOCTYPE HTML>
<html>
<?php 
	session_start();
	include('link_import.php')?>
<body>
	<div class="colorlib-loader"></div>

	<div id="page">
		<?php include('includes/navigation.php')?>
		<div class="breadcrumbs">
			<div class="container">
				<div class="row">
					<div class="col">
						<p class="bread"><span><a href="index.php">Home</a></span> / <span>Contact</span></p>
					</div>
				</div>
			</div>
		</div>


		<div id="colorlib-contact">
			<div class="container">
				<div class="row">
					<div class="col-sm-12">
						<h3>Contact Information</h3>
						<div class="row contact-info-wrap">
							<div class="col-md-3">
								<p><span><i class="icon-location"></i></span> 664, Boeng Salang, Toul kork, Phnom Penh, Cambodia</p>
							</div>
							<div class="col-md-3">
								<p><span><i class="icon-phone3"></i></span> <a href="tel://1234567920">+855 6796719</a></p>
							</div>
							<div class="col-md-3">
								<p><span><i class="icon-paperplane"></i></span> <a href="mailto:info@yoursite.com">info.sneakers.com</a></p>
							</div>
							<div class="col-md-3">
								<p><span><i class="icon-globe"></i></span> <a href="#">Sneakers.com</a></p>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="contact-wrap">
							<h3>Get In Touch</h3>
							<form id="contactForm" class="contact-form">
								<div class="row">
									<div class="col-sm-12">
										<div class="form-group w-100">
											<label for="name">Your Name</label>
											<input type="text" id="name" class="form-control" placeholder="Your firstname" required>
										</div>
									</div>
									<div class="col-sm-12">
										<div class="form-group">
											<label for="email">Email</label>
											<input type="email" id="email" class="form-control" placeholder="Your email address" required>
										</div>
									</div>
									<div class="col-sm-12">
										<div class="form-group">
											<label for="subject">Subject</label>
											<input type="text" id="subject" class="form-control" placeholder="Subject" required>
										</div>
									</div>
									<div class="col-sm-12">
										<div class="form-group">
											<label for="message">Message</label>
											<textarea id="message" cols="30" rows="5" class="form-control" placeholder="Say something" required></textarea>
										</div>
									</div>
									<div class="col-sm-12">
										<div class="form-group">
											<input type="submit" value="Send Message" class="btn btn-primary">
										</div>
									</div>
								</div>
							</form>     
						</div>
					</div>
				</div>


			</div>
		</div>
		<?php include('includes/footer.php')?>
	</div>

	<div class="gototop js-top">
		<a href="#" class="js-gotop"><i class="ion-ios-arrow-up"></i></a>
	</div>
	<?php include('script_import.php')?>
	</body>
	<script src="https://cdn.jsdelivr.net/npm/emailjs-com@3/dist/email.min.js"></script>
	<script>
		(function(){
			emailjs.init("a4wxbYdyVmnoiMbYY");
		})();

		document.getElementById('contactForm').addEventListener('submit', function(event) {
			event.preventDefault();

			emailjs.send("service_mqfl5sf", "template_f1dp6uy", {
				name: document.getElementById("name").value,
				email: document.getElementById("email").value,
				subject: document.getElementById("subject").value,
				message: document.getElementById("message").value
			}).then(function(response) {
				alert("Message sent successfully!");
			}, function(error) {
				alert("Failed to send message.");
			});
		});
	</script>
</html>

