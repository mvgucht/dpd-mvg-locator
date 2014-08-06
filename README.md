dpd-mvg-locator
===============

DISCLAIMER
----------
The software that is described in this document is meant as an example.
DPD doesn’t provide any active support but is willing to think along and help where possible.
Because of this DPD waves any responsibility.
It is up to the user to review en test this software prior to using it on a live environment.

REQUIREMENTS
------------
- Default php soap_client active on server.
- Google maps api V3 active for your domain.

FILE OVERVIEW
-------------
- Dictionary.xml
    Simple xml document that provides the textitems used on the locator. Adding a language is as sim-ple
    as translating each node in this file.
- DPD.css
    Default and minimalistic layout for the parcelShop locator.
- Index.php
    An example on how to integrate the parcelShop locator into a site.
- Parcelshopfinder.php
    Php script that handles the soap requests to the DPD server.
    Note: REST was an option but leaves the delisID and password visible on client side.
- Parcelshoplocator.js
    The actual parcelShop locator object, encapsulated in its own namespace (DPD)

MINIMAL INTEGRATION
-------------------
For examples of the process described below please view Index.php

Add google maps api script to the header:

    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places"></script>
    
And add the parcelshoplocator.js script to the header:
(use the <pspath> if the object script isn’t nested on the same location than the page you are editing.)

    <script src="parcelshoplocator.js"></script>

Add a container in your body where you want the shopLocator to appear and give it an ‘id’
    …
 	  <div id="dpdLocatorContainer"></div>
 	  …
Create a new script object in the header that will hold the object, search action function and callback function.
    
    <script type="text/javascript">
      …
    </script>

Create your callback function. This function will get the shopID back when your user has chosen a shop.

    <script type="text/javascript">
 	    function dppChosenShop(shopID) {
 	  	  // Do something with shopID
 	    }
      …
    </script>

Create an object holding the parcelShop Locator (example below is minimal configuration example)
  
    <script type="text/javascript">
 	    …
 	    var dpdLocator = new DPD.locator({
 		    containerId: ‘dpdLocatorContainer’
 	    })
      …
    </script>

In order to reduce waiting times we already initialize the parcelShop locator, but hidden. So add an
onload function to the body of your page calling the initialize function of the object.

    <body onload="dpdLocator.initialize();">

Add a button or link to you page that will display the parcelShop locator by calling the showLocator function

    <a href="#" onclick="javascript:dpdLocator.showLocator(); return false;">Click here to choose your shop</a>
    

TODO
----
DPD.locator

  Autoresponsive setting in config: Will sense if the user is on a mobile device and use full screen rendering.
  
  Autohide setting in config:       Will cause the locator to hide when a shop is selected by the customer.
  
  Result filter on country:          Without this a German (or other) shop can be selected via a lookup starting from Belgium.                                       This would result in wrong pricing. If not this has to be caught in the callback function.
