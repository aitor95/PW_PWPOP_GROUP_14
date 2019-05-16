var Selector = document.querySelector('.selector');
M.FormSelect.init(Selector, {});

var file = document.getElementById("file");


function validaFormularioUpload(event){
    var devolver = true;
    var imatge = document.getElementById("file_chooser").value;
    var message_image = document.getElementById("message-chooser");
   /* switch(imatge.substring(imatge.lastIndexOf('.') + 1).toLowerCase()){
        case 'gif': case 'jpg': case 'png':
            if (this.files[0].size/1024/1024 > 1 ){
                alert(this.files[0].size);
                message_image.innerText = "Invalid image format";
                message_image.classList.add("error");
                devolver = false;
                $('#file_chooser').val('');
            }
            break;

        default:
            $('#file_chooser').val('');
            alert("not an image");
            message_image.innerHTML = "Invalid image format";
            message_image.classList.add("error");
            devolver = false;
            break;
    }*/

    var description = document.getElementById("description").value;
    var description_message = document.getElementById("message-description");
     if (description.length < 20){

         description_message.innerHTML = "Minimum 20 characters.";
         description_message.classList.add("error");
         devolver = false;
     } else {
         description_message.innerText = "";
         description_message.classList.remove("error");
         devolver = true;
     }

     var price = document.getElementById("price").value;
     var price_message = document.getElementById("message-price");
     if (isNaN(price_message)){
         price_message.innerHTML =" Introduce a number";
         price_message.classList.add("error");
         devolver = false;
     } else {
         price_message.innerHTML ="";
         price_message.classList.remove("error");
         devolver = true;
     }
    if (!devolver){
        event.preventDefault();
    }
}

document.getElementById("formulario").addEventListener('submit', validaFormularioUpload);

