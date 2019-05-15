var Selector = document.querySelector('.selector');
M.FormSelect.init(Selector, {});

/*
var file = document.getElementById("file");

$("#file_chooser").change(function() {

    var val = $(this).val();

    switch(val.substring(val.lastIndexOf('.') + 1).toLowerCase()){
        case 'gif': case 'jpg': case 'png':
            alert("an image menor que 1mb");
            break;

        default:
            $(this).val('');
            // error message here
            alert("not an image");
            break;
    }
});*/

var uploadField = document.getElementById("file");

uploadField.onchange = function() {
    if(this.files[0].size/1024/1024 > 1){
        alert(this.files[0].size/1024/1024);
        alert("File is too big!");
        this.value = "";
    };
};