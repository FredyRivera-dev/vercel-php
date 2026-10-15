# Plantilla PHP en Vercel

Proyecto PHP mínimo (página de inicio más un helper de conexión a MySQL) configurado para desplegarse en Vercel con el runtime comunitario `vercel-php`, que usa PHP 8.5.

## Estructura del proyecto

```
.
├── api/
│   ├── index.php      # Página principal (se sirve en /)
│   └── conexion.php   # Conexión a MySQL (helper protegido)
├── assets/
│   └── logo.svg       # Archivos estáticos (los sirve el CDN de Vercel)
└── vercel.json        # Configuración de Vercel
```

## Cómo está configurado para Vercel

Todo se define en `vercel.json`:

```json
{
  "$schema": "https://openapi.vercel.sh/vercel.json",
  "functions": {
    "api/**/*.php": {
      "runtime": "vercel-php@0.9.0"
    }
  },
  "rewrites": [
    { "source": "/(.*)", "destination": "/api/index.php" }
  ]
}
```

- **functions**: registra todos los archivos `.php` dentro de `api/` (incluidas subcarpetas) con el runtime `vercel-php@0.9.0`. Esto es obligatorio, porque PHP no es un runtime oficial de Vercel y sin esta línea Vercel no ejecutaría nada. Si agregas más archivos `.php` en `api/`, quedan registrados automáticamente como funciones.
- **rewrites**: cualquier ruta que no corresponda a un archivo del proyecto se reenvía a `/api/index.php`. Así la página responde en `/` y no en `/api/index.php`. Vercel revisa primero el filesystem y después aplica los rewrites, por lo que `assets/` siempre se sirve como archivo estático y nunca pasa por PHP.
- Los archivos estáticos se sirven desde fuera de `api/`. Por eso `logo.svg` vive en `assets/` en la raíz y se referencia con ruta absoluta (`/assets/logo.svg`), tanto en el favicon como en la etiqueta `img`.

### Cómo funcionan las funciones PHP

- Cada archivo `.php` dentro de `api/` se convierte en una función independiente: se ejecuta, nunca se sirve su código fuente.
- El runtime empaqueta todos los archivos del proyecto dentro de cada función, así que un `require` entre archivos funciona sin configuración extra. Además, este runtime no soporta `includeFiles`, y si agregas `conexion.php` a `.vercelignore` dejaría de existir dentro de las funciones y el include fallaría. No lo hagas.
- Cada petición puede caer en una instancia distinta de la función, así que no hay estado compartido en memoria entre peticiones.

### Protección de conexion.php

Como `conexion.php` queda registrado como función, también podría visitarse por URL (`/api/conexion.php`). Para evitarlo, el archivo empieza con un guard:

```php
if (!defined('APP_ENTRY')) {
    http_response_code(404);
    exit;
}
```

- Si alguien abre `/api/conexion.php` directamente, recibe un 404 y el archivo no se ejecuta.
- Solo funciona cuando otro archivo lo incluye después de definir `APP_ENTRY`. `api/index.php` ya define la constante y deja lista la línea para incluirlo:

```php
define('APP_ENTRY', true);
// require __DIR__ . '/conexion.php';
```

Descomenta el `require` cuando tengas lista la base de datos remota. Antes de eso no lo hagas, porque en Vercel no existe `localhost` y la página fallaría al conectar.

## Desarrollo local

```bash
php -S localhost:8000
```

Y abre `http://localhost:8000/api/index.php`.

- El rewrite que sirve la página en `/` solo existe una vez desplegado en Vercel. Localmente se entra por la ruta completa `/api/index.php`.
- Los assets se referencian con ruta absoluta (`/assets/logo.svg`), que funciona porque el servidor local usa la raíz del proyecto como docroot.
- `vercel dev` no es compatible con este runtime. Usa siempre el servidor integrado de PHP.

## Despliegue

Con la CLI de Vercel:

```bash
npm i -g vercel
vercel login
vercel --prod
```

También puedes importar el repositorio desde el dashboard de Vercel (`https://vercel.com/dashboard`), donde se detecta `vercel.json` automáticamente.

## Cosas que tener en cuenta

**Base de datos**

- Vercel no incluye MySQL. Necesitas una base de datos remota y accesible desde internet (PlanetScale, Aiven, TiDB, Railway, Neon para Postgres, etc.).
- Guarda las credenciales en variables de entorno del proyecto (Dashboard: Project Settings, Environment Variables) y léelas en PHP con `getenv('NOMBRE')`.
- Los nombres de variables de entorno no pueden empezar con `VERCEL_`, ese prefijo está reservado.
- En PHP 8.1 o superior, `new mysqli(...)` lanza `mysqli_sql_exception` cuando la conexión falla. Conviene envolver la conexión en `try/catch` en lugar de confiar solo en `connect_error`.
- Cada instancia de la función abre sus propias conexiones. Con tráfico alto, prefiere proveedores con connection pooling o mantén las conexiones al mínimo.

**Sistema de archivos y sesiones**

- El sistema de archivos es de solo lectura, excepto `/tmp`, que es temporal y se pierde entre invocaciones. No sirve para subir archivos ni guardar datos.
- No uses sesiones basadas en archivos: no persisten de forma confiable entre instancias. Usa cookies firmadas, tokens (JWT) o un almacén externo (Redis o la propia base de datos).

**Límites y plan**

- Tamaño máximo del cuerpo de una request o response: 4.5 MB.
- Memoria por función: 2 GB en Hobby. Duración máxima: 300 segundos en Hobby.
- El plan Hobby es solo para proyectos personales no comerciales. Para uso comercial necesitas el plan Pro.
- Las funciones arrancan en frío en aproximadamente 250 ms.
- La región por defecto es `iad1` (Washington D.C.). Si tu base de datos o tus usuarios están en otra zona, cámbiala en `vercel.json` con `"regions": ["gru1"]` (São Paulo), por ejemplo.

**Runtime**

- `vercel-php` es un runtime de la comunidad, no oficial. Fija siempre la versión (`vercel-php@0.9.0`) para evitar sorpresas.
- Extensiones incluidas, entre otras: `mysqli`, `pdo_mysql`, `pdo_pgsql`, `pgsql`, `sqlite3`, `curl`, `mbstring`, `intl`, `session`, `zip`, `redis` y `mongodb`.
- Puedes sobreescribir la configuración de PHP creando un archivo `api/php.ini` (por ejemplo, para cambiar `memory_limit` o desactivar funciones).

## Enlaces útiles

- Runtime comunitario PHP: https://github.com/vercel-community/php
- Ejemplos oficiales del runtime: https://github.com/juicyfx/vercel-examples
- Documentación de Vercel: https://vercel.com/docs
