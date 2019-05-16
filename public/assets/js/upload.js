var Selector = document.querySelector('.selector');
M.FormSelect.init(Selector, {});

var file = document.getElementById("file");


function validaFormularioUpload(event){
    var devolver = true;

    var description = document.getElementById("description").value;
    var description_message = document.getElementById("message-description");
     if (description.length < 20){
         description_message.innerHTML = "Minimum 20 characters.";
         description_message.classList.add("error");
         devolver = false;
     } else {
         description_message.innerText = "";
         description_message.classList.remove("error");

     }

    var price = document.getElementById("price").value;
    var price_message = document.getElementById("message-price");
    if (isNaN(price)){
        price_message.innerHTML =" Introduce a number";
        price_message.classList.add("error");
        devolver = false;
    } else {
        price_message.innerHTML = "";
        price_message.classList.remove("error");
    }

    var imatge = document.getElementById("file_chooser");
    var message_chooser = document.getElementById("message-chooser");
    var imagePath = imatge.value;
    var allowedExtensions = /(\.jpg|\.jpg|\.png|\.gif)$/i;
    if (!allowedExtensions.test(imagePath)){
        message_chooser.innerHTML = "Please, choose an image file";
        message_chooser.classList.add("error");
        devolver = false;
    } else {
        if (imatge.files[0].size/1024/1024 > 1){
            message_chooser.innerHTML = "Choose an image maximum 1MB size";
            message_chooser.classList.add("error");
            devolver = false;
        } else {
            message_chooser.innerHTML = "";
            message_chooser.classList.remove("error");
        }
    }

    if (!devolver){
        event.preventDefault();
    }
}

document.getElementById("formulario").addEventListener('submit', validaFormularioUpload);

