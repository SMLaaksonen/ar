function submitForm()
{
	var textfield = document.getElementById("arname");
	if(textfield.value=="")
	{
		Swal.fire({
			title: 'Control, control, you must learn control!',
			text: 'Happens to every guy sometimes this does! Enter name you must!',
			type: 'warning',
			confirmButtonText: 'Cool'
		})
		return;
	}
	if(sessionStorage.getItem('aruser'))
	{
		if(sessionStorage.getItem('aruser')==document.getElementById("arname").value)
		{
			document.getElementById("arname").value = sessionStorage.getItem('aruser');
		} else {
			sessionStorage.setItem('aruser', document.getElementById("arname").value);
		}
	} else {
		sessionStorage.setItem('aruser', document.getElementById("arname").value);
	}
	document.getElementById("startform").submit();
}
function submitForm2()
{
	document.getElementById("bestform").submit();
}
function checkUser()
{
	if(sessionStorage.getItem('aruser'))
	{
		document.getElementById("arname").value = sessionStorage.getItem('aruser');
	}	
}