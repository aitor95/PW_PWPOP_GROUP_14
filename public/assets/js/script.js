var Calendar = document.querySelectorAll('.datepicker');
M.Datepicker.init(Calendar, {
    format: 'dd-mmmm-yyyy'
});

var sideNav = document.querySelector('.sidenav');
M.Sidenav.init(sideNav, {});

var Slider = document.querySelector('.slider');
M.Slider.init(Slider, {
    indicators: false,
    height: 300,
    transition: 500,
    interval: 6000
});

//Autocomplete
var Autocomplete = document.querySelector('.autocomplete');
M.Autocomplete.init(Autocomplete, {
    data: {
        "Product1":null,
        "Product2":null,
        "Product3":null,
        "Product4":null,
        "Product5":null,
        "Product6":null,
        "Product7":null,
    }
});


function validaFormulario(event){

    var devolver = true;

    //event.preventDefault();
    var name = document.getElementById("name").value;
    var message_name = document.getElementById("message-name");
    if (!/^[a-z0-9]+$/i.test(name)){
        message_name.innerHTML ="The name can only consist of alphanumeric characters";
        message_name.classList.add("error");
        devolver = false;
    } else {
        message_name.innerHTML = "";
        message_name.classList.remove("error");
    }

    var username = document.getElementById("username").value;
    var message_username = document.getElementById("message-username");
    if (!/^[a-z0-9]+$/i.test(username) || username.length > 20){
        message_username.innerHTML = "The name can only consist of alphanumeric and 20 characters max";
        message_username.classList.add("error");
        devolver = false;
    }  else {
        message_username.innerHTML = "";
    }

    var email = document.getElementById("email").value;
    var message_email = document.getElementById("message-email");
    //COMENTARIO REALIZADO PORQUE NO DETECTA BIEN EL CORREO
    if (!(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,9})+$/.test(email))){
        message_email.innerHTML = "Please enter a valid email address.";
        message_email.classList.add("error");
        devolver = false;
    } else {
        message_email.innerHTML = "";
        message_email.classList.remove("error");
    }

    var phone = document.getElementById("phone").value;
    var message_phone = document.getElementById("message-phone");
    if (phone.length > 9){
        message_phone.innerHTML = "Format: xxx xxx xxx";
        message_phone.classList.add("error");
        devolver = false;
    } else {
        message_phone.classList.remove("error");
    }

    var password = document.getElementById("password").value;
    var message_password = document.getElementById("message-password");
    if (password.length <6){
        message_password.innerHTML = "Minimum 6 characters";
        message_password.classList.add("error");
        devolver = false;
    } else {
        message_password.innerHTML = "";
    }

    var confirmpassword = document.getElementById("confirm-password").value;
    var message_confirm = document.getElementById("message-confirm-password");
    if (confirmpassword.length < 6 ) {
        message_confirm.innerText = "Minimum 6 characters";
        message_confirm.classList.add("error");
        devolver = false;
    } else {
        if (confirmpassword !== password){
            message_confirm.innerHTML = "Passwords must match";
            message_confirm.classList.add("error");
            devolver = false;
        } else {
            message_confirm.innerHTML = "";
        }
    }

    if (!devolver){
        event.preventDefault();
    }

}

document.getElementById("formulario").addEventListener('submit', validaFormulario);