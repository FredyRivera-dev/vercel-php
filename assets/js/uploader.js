// Los env llegan desde PHP (api/index.php) como window.ENV.
const CLOUD_NAME = window.ENV?.CLOUD_NAME || '';
const UPLOAD_PRESET = window.ENV?.UPLOAD_PRESET || '';

// Limite de 10MB para subir
const MAX_BYTES = 10 * 1024 * 1024;

// Por cierto estoy delegando la carga de imagenes al navegador en vez del servidor con php
// Se que estoy usando spanglish, pero ya estoy acostumbrado a esto jaja.
export async function uploadImage(file) {
  if (!CLOUD_NAME || !UPLOAD_PRESET) {
    throw new Error("Faltan CLOUD_NAME o UPLOAD_PRESET: revisa las variables de entorno");
  }

  if (!(file instanceof File) || !file.type.startsWith("image/")) {
    throw new Error("El archivo no es una imagen");
  }

  if (file.size > MAX_BYTES) {
    throw new Error(`La imagen pesa más de 10 MB`);
  }

  const formData = new FormData();
  formData.append("file", file);
  formData.append("upload_preset", UPLOAD_PRESET);

  const res = await fetch(
    `https://api.cloudinary.com/v1_1/${CLOUD_NAME}/image/upload`,
    { method: "POST", body: formData }
  );

  // Cloudinary no siempre responde JSON (p. ej. 413 del CDN o errores 5xx);
  // si el parseo falla, el header X-Cld-Error trae el detalle.
  let data = null;
  try {
    data = await res.json();
  } catch {
    // Respuesta sin cuerpo JSON.
  }

  if (!res.ok) {
    const mensaje =
      data?.error?.message ??
      res.headers.get("X-Cld-Error") ??
      `${res.status} ${res.statusText}`;
    throw new Error(`Upload falló: ${mensaje}`);
  }

  return data.secure_url;
}