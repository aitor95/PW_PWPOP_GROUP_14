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