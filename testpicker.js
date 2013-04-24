var labelURL;

	
function loadFrame(URLstring){
	window.parent.document.getElementById("problem").src =URLstring;
}
	
function showURL() {
labelURL=document.getElementById("labelURL");
	if (!document.getElementById){
		return;
	}
	if (labelURL.style.visibility=="visible"){
		labelURL.style.visibility="hidden";
	}
	else{
		labelURL.style.visibility="visible";
	}
}