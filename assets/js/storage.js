let ss = JSON.parse(localStorage.getItem("is-uploader")) || null;
let image = JSON.parse(localStorage.getItem("images")) || [];

// Verificación tonta
// Ojo: sin async. Una función async devuelve una Promise, y una Promise
// siempre es truthy — el if que la llama daría true siempre.
export function isUploader() {
    return ss !== null;
}

// Set tonto, sirve para validar
export async function setUplader() {
    ss = true;
    localStorage.setItem("is-uploader", JSON.stringify(true));
}

export async function saveImage(url) {
    const imagesS = {
        id: Date.now(),
        image_url: url
    }

    image.push(imagesS);
    localStorage.setItem("images", JSON.stringify(image));
}
