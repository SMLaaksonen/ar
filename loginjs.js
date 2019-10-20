// boolean variable to prevent multiple scans
var istrue = false;
var navbartext;
// titlebar element
var navbar = document.getElementById("name");
// original text (name) in titlebar
navbartext = navbar.innerHTML;
// get users name from session if not, go to landing page
var name;
if(sessionStorage.getItem('aruser'))
{
	name = sessionStorage.getItem('aruser');
} else {
	document.location.assign("index.html");
}

// get all markers on page
var markers = document.getElementsByTagName("a-marker");
// make all amrkers initially invisible for scan event to work
for (i = 0; i < markers.length; i++) {
  markers[i].object3D.visible=false;
}
// scan markers every 1 second
var scan_interval = setInterval(this.removeMarker, 1000); // 1000 means 1 second

// funtion to remove scanned marker and restore original text on navbar
function clearNavbar(m1)
{
	m1.parentNode.removeChild(m1);
	navbar.innerHTML = navbartext;
	istrue = false;
}
// funtion to restore original text on navbar (scan failed)
function clearNavbar2()
{
	navbar.innerHTML = navbartext;
	istrue = false;
}
// helper function to launch setTimeout, because it doesn't work inside anonymous function
function helper(m3)
{
	setTimeout(this.clearNavbar,2000,m3);
}
// helper function to launch setTimeout, because it doesn't work inside anonymous function, without parameter
function helper2()
{
	setTimeout(this.clearNavbar2,2000);
}
// function taht gets called every 1 second
// it iterates alla markers and checks if one of them is visible (=scanned)
function removeMarker()
{
	var ms = document.getElementsByTagName("a-marker");
	for (i = 0; i < ms.length; i++) {
		if(ms[i].object3D.visible == true) {
			console.log("Visible" + ms[i].id);
			if(!istrue){
				istrue = true;
				addScannedMarker(ms[i]);
			} 
		} 	
	}
}
// function that gets called when marker is scanned
// function sends call to PHP file that saves scan results to database
// or displays error message if saving fails
function addScannedMarker(m2) {
	var http = new XMLHttpRequest();
	var url = 'addscannedmarker.php';
	var strings = m2.id.split("_");

	var params = 'name=' + name + '&marker=' + strings[1];
	http.open('POST', url, true);

	//Send the proper header information along with the request
	http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

	http.onreadystatechange = function() {//Call a function when the state changes.
		if(http.readyState == 4 && http.status == 200) {
			//alert(http.responseText);
			if(http.responseText == "OK"){
				navbar.insertAdjacentHTML('beforeend', " " + m2.id + " scanned");
				helper(m2);
			} else {
				navbar.insertAdjacentHTML('beforeend', " " + http.responseText);
				helper2();
			}		
		} 
	}
	http.send(params);
}