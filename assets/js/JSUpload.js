import { MAX_BYTES, uploadImage } from "./uploader.js";
import { isUploader, setUplader, saveImage } from "./storage.js";

const imageInput = document.getElementById("imageInput");
const btnUpload = document.getElementById("btnUpload");
const imagenes = document.getElementById("images-preview");

function getFile(element) {
    const file = element.files[0];

    if (!file) {
        throw new Error("No se seleccionó ningún archivo");
    }

    if (file.size > MAX_BYTES) {
        throw new Error("Máximo 10 MB");
    }

    if (!file.type.startsWith("image/")) {
        throw new Error("El archivo debe ser una imagen");
    }

    return file;
}

function mostrarImagenes() {
    imagenes.innerHTML = "";
    const lista = JSON.parse(localStorage.getItem("images")) || [];

    lista.forEach(function (item) {
        const contenedor = document.createElement("div");
        const img = document.createElement("img");

        img.src = item.image_url;
        img.alt = "Imagen " + item.id;

        contenedor.appendChild(img);
        imagenes.appendChild(contenedor);
    });
}

async function onSave() {
    if (isUploader()) {
        alert("Ya has subido una imagen.");
        return;
    }

    try {
        const file = getFile(imageInput);
        const url = await uploadImage(file);

        setUplader();
        saveImage(url);
        mostrarImagenes();
    } catch (error) {
        alert(error.message);
    }
}

btnUpload.addEventListener("click", onSave);

mostrarImagenes();