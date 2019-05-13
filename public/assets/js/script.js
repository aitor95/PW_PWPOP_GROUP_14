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

var validaFormulario = function(){
    var devolver = false;
    var name = document.getElementById("name").value;
    
    if (!/^[a-zA-Zs]+$/.test(name)){
        $("#message-name").attr("data-error", "The name can only consist of alphabetical and space");
        devolver = false;
    } else {
        $("#message-name").removeAttr("data-error");
        devolver = true;
    }
    
    var username = document.getElementById("username").value;
    if (!/^[a-zA-Zs]+$/.test(email) && email.length >20){
        $("#message-username").attr("data-error", "The name can only consist of alphabetical and 20 characters max");
        devolver = false;
    } else {
        $("#message-username").removeAttr("data-error");
        devolver = true;
    }

    var phone = document.getElementById("phone").value;
    if (!"^\\d{3} \\d{3} \\d{3}$"+$.test(phone)){
        $("#message-phone").attr("data-error", "Format: nxx xxx xxx");
        devolver = false;
    } else {
        $("#message-phone").removeAttr("data-error");
        devolver = false;
    }
    
    var password = document.getElementById("password").value;
    if (password.length <6){
        $("#message-password").attr("data-error", "Minimum 6 characters");
        devolver = false;
    } else {
        $("#message-password").removeAttr("data-error");
        devolver = true
    }

    var confirmpassword = document.getElementById("confirm-password").value;
    if (confirmpassword !== password){
        $("#message-confirm-password").attr("data-error", "Passwords must match");
        devolver = false;
    } else {
        $("#message-confirm-password").removeAttr("data-error");
        devolver = true
    }

    return devolver;
}