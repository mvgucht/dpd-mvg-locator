<?php
	session_name("DPDLocator");
	
	$serverIP = $_SERVER['SERVER_ADDR'];
	$clientIP = $_SERVER['REMOTE_ADDR'];
	$sugar = "b7amcxHT7ECBxyhkgV5uMj7byWqsgU";
	$pagePath = "http://".$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'];
	
	$sessionID = md5(md5($serverIP.'.'.$clientIP).$sugar.md5($pagePath));
	
	if(session_start()){
		$_SESSION['identifier'] = $sessionID;
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta name="viewport" content="device-width, user-scalable=no">
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="DPD.css">
		<style>
			html, body {
				height: 100%;
				margin: 0px;
				padding: 0px
			}
			
			body {
				text-align: center;
			}
			
			#centerContainer {
				display: inline-block;
				width: 990px;
				text-align: left;
			}
			
			.inputBlock {
				display: block;
				float: left;
				width: 450px;
			}
			
			.inputBlock input {
				width: 300px;
			}
			
			.inputBlock.address {
				width: 900px;
			}
			.inputBlock.address input {
				width: 400px;
			}
			
			.clearFloat {
				clear: both ;
			}
			.inputBlock.radio input{
				width: auto;
			}
			
		</style>
		<title>DPD ParcelShop Locator</title>
		<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places"></script>
		<script src="parcelshoplocator.js"></script>
		<script type="text/javascript">
			function dpdChosenShop(shopID) {
				var shop = dpdLocator.getShopInfo(shopID);
				
				//dpdLocator.hideLocator();
				
				var objContainer = document.getElementById('chosenShop');
				objContainer.innerHTML = '<p>You have chosen: <strong>' + shop.name + '</strong> <br>Located at: ' + shop.street + ' ' + shop.houseNo + ', ' + shop.zipCode + ' ' + shop.city + ' '  ;
			}
			
			var dpdLocator = new DPD.locator({
				containerId: 'dpdLocatorContainer',
				fullscreen: false,
				//width: '800px',
				//height: '600px',
				filter: 'pick-up',
				callback: 'dpdChosenShop',
				language: '<?php echo (isset($_GET['lang']) ? $_GET['lang'] : 'en'); ?>'
			});
			
			function selectParcelshop(){
				var address1 = document.getElementById('txtShipAddress1').value;
				var address2 = document.getElementById('txtShipAddress2').value;
				var zipCode = document.getElementById('txtShipZipCode').value;
				var city = document.getElementById('txtShipCity').value;
				
				var query;
				
				if( address1 != "" || zipCode != "") {
					query =  address1 + ' ' + address2 + ', ' + zipCode + ' ' + city;
				}
				dpdLocator.showLocator(query);
			}			
	</script>
	</head>
	<body onload="dpdLocator.initialize();">
		<div id="centerContainer">
			<div id="contentContainer">
				<div id="mainContainer">
					<div id="checkoutContainer">
						<div id="shippingData" class="clearFloat">
							<h2>Shipping Information</h2>
							<span class="inputBlock">
								<label>First Name</label><br><input type="text" id="txtShipFirstName"><br>
							</span>
							<span class="inputBlock">
								<label>Last Name</label><br><input type="text" id="txtShipLastName"><br>
							</span>
							<span class="inputBlock">
								<label>Company</label><br><input type="text" id="txtShipCompany"><br>
							</span>
							<span class="inputBlock">
								<label>Email Address</label><br><input type="text" id="txtShipEmail"><br>
							</span>
							<span class="inputBlock address">
								<label>Address</label><br><input type="text" id="txtShipAddress1"><br>
								<input type="text" id="txtShipAddress2"><br>
							</span>
							<span class="inputBlock">
								<label>City</label><br><input type="text" id="txtShipCity"><br>
							</span>
							<span class="inputBlock">
								<label>Zip Code</label><br><input type="text" id="txtShipZipCode"><br>
							</span>
							<span class="inputBlock">
								<label>Telephone</label><br><input type="text" id="txtShipTelephone"><br>
							</span>
							<span class="inputBlock">
								<label>Fax</label><br><input type="text" id="txtShipFax"><br>
							</span>
							<span class="inputBlock radio">
								<input type="checkbox" id="useBillingAdd" onclick=""> Use billing address.
							</span>
						</div>
						<div id="shippingMethod" class="clearFloat">
							<h2>Shipping Information</h2>
							<h3><input type="radio" name="group2" id="blDPDParcelshop" checked>DPD ParcelShop</h3>
							<a href="#" onclick="javascript:selectParcelshop(); return false;">Click here to choose your shop</a> <input type="checkbox" id="toggleFullscreen" onclick="javascript:dpdLocator.toggleFullscreen();"> Fullscreen
							<div id="chosenShop"></div>
							<div id="dpdLocatorContainer">
							</div>
							<h3><input type="radio" name="group2" id="blDPDHome"> DPD Home</h3>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
