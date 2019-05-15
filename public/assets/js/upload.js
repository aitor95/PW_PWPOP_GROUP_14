var Selector = document.querySelector('.selector');
M.FormSelect.init(Selector, {});

let funcionaimage = true;
var file = document.getElementById("file");
$("#file_chooser").change(function(event) {

    var val = $(this).val();

    switch(val.substring(val.lastIndexOf('.') + 1).toLowerCase()){
        case 'gif': case 'jpg': case 'png':
            if (this.files[0].size/1024/1024 > 1 ){
                alert("an image menor que 1mb");
                funcionaimage = false;
                $(this).val('');
            }
            break;

        default:
            $(this).val('');
            alert("not an image");
            break;
    }
});


function validaFormularioUpload(event){
    var devolver = true;
    var description = document.getElementById("title").value;
    var description_message = document.getElementById("message-description");
     if (description.length < 20){
         description_message.value.innerHTML = "Minimum 20 characters.";
         description_message.classList.add("error");
         devolver = false;
     } else {
         $('#message-title').innerText = "";
         $('#message-title').classList.remove("error");
         devolver = true;
     }

    if (!devolver || !funcionaimage){
        event.preventDefault();
    }
}

document.getElementById("formulario").addEventListener('submit', validaFormularioUpload);

