<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css">

<div class="container">
	<form class="form-signin" method="post" id="register-form">		
		<div class="form-group">
			<select id="SecondaryColor" name="SecondaryColor[]" multiple >				
				<option value="red">red</option>
				<option value="orange">orange</option>		
				<option value="yellow">yellow</option>
				<option value="green">green</option>
				<option value="blue">blue</option>
				<option value="purple">purple</option>
				<option value="coral">coral</option>
				<option value="pink">pink</option>
				<option value="black">black</option>
				<option value="white">white</option>
				<option value="brown">brown</option>
				<option value="ivory">ivory</option>
				<option value="tan">tan</option>
				<option value="grey">grey</option>
			</select>	
		</div>	

		<div class="form-group">
			<select id="Style" name="Style[]" multiple >				
				<option value="red">red</option>
				<option value="orange">orange</option>		
				<option value="yellow">yellow</option>
				<option value="green">green</option>
				<option value="blue">blue</option>
				<option value="purple">purple</option>
				<option value="coral">coral</option>
				<option value="pink">pink</option>
				<option value="black">black</option>
				<option value="white">white</option>
				<option value="brown">brown</option>
				<option value="ivory">ivory</option>
				<option value="tan">tan</option>
				<option value="grey">grey</option>
			</select>	
		</div>

		<div class="form-group">
			<select id="Formality" name="Formality[]" multiple >				
				<option value="red">red</option>
				<option value="orange">orange</option>		
				<option value="yellow">yellow</option>
				<option value="green">green</option>
				<option value="blue">blue</option>
				<option value="purple">purple</option>
				<option value="coral">coral</option>
				<option value="pink">pink</option>
				<option value="black">black</option>
				<option value="white">white</option>
				<option value="brown">brown</option>
				<option value="ivory">ivory</option>
				<option value="tan">tan</option>
				<option value="grey">grey</option>
			</select>	
		</div>

		<div class="form-group">		
			<button type="submit" class="btn btn-default" name="btn-save" id="btn-submit">Submit</button> 
		</div>     
	</form>	
</div>

<!-- Initialize the plugin: -->
<script type="text/javascript">
	$(document).ready(function() {       
		$('#SecondaryColor').multiselect({		
			nonSelectedText: 'Secondary Colors'				
		});
	});
	$(document).ready(function() {       
		$('#Style').multiselect({		
			nonSelectedText: 'Style'				
		});
	});
	$(document).ready(function() {       
		$('#Formality').multiselect({		
			nonSelectedText: 'Formality'				
		});
	});
</script>

<?php
print_r ($_POST); ?>
?>