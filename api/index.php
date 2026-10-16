<?php
// Marca de entrada de la app.
// conexion.php solo se ejecuta si este define existe, así nadie puede abrirlo
// directamente en el navegador (p. ej. /api/conexion.php) y aún así se puede
// incluir desde cualquier archivo.
define('APP_ENTRY', true);

// Cuando configures tu base de datos remota en Vercel, descomenta esta línea:
// require __DIR__ . '/conexion.php';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="color-scheme" content="light dark">
  <title>Vercel Php</title>
  <link rel="icon" href="/assets/logo.svg" type="image/svg+xml" />
  <style>
    :root {
      --bg: #ffffff;
      --fg: #171717;
      --muted: #666666;
      --line: #e4e4e4;
      --code: #f1f1f1;
      --btn-hover: #383838;
      --ghost-hover: #f5f5f5;
    }

    @media (prefers-color-scheme: dark) {
      :root {
        --bg: #0a0a0a;
        --fg: #ededed;
        --muted: #a1a1a1;
        --line: #2a2a2a;
        --code: #1c1c1c;
        --btn-hover: #cfcfcf;
        --ghost-hover: #1a1a1a;
      }
    }

    *, *::before, *::after { box-sizing: border-box; }

    html { -webkit-text-size-adjust: 100%; }

    body {
      margin: 0;
      min-height: 100dvh;
      display: grid;
      grid-template-rows: 1fr auto;
      background: var(--bg);
      color: var(--fg);
      font-family: ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
      font-size: 1rem;
      line-height: 1.6;
      -webkit-font-smoothing: antialiased;
    }

    main {
      display: flex;
      flex-direction: column;
      justify-content: center;
      gap: 2.5rem;
      width: 100%;
      max-width: 38rem;
      margin: 0 auto;
      padding: 4rem 1.5rem;
    }

    .brand {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      align-self: flex-start;
    }

    .logo {
      display: block;
      width: auto;
      height: 2.5rem;
    }

    .brand-name {
      font-size: 1.5rem;
      font-weight: 600;
      letter-spacing: -0.02em;
      line-height: 1;
    }

    ol {
      margin: 0;
      padding-left: 1.25rem;
      font-size: 0.95rem;
    }

    li + li { margin-top: 0.5rem; }

    code {
      font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace;
      font-size: 0.875em;
      font-weight: 600;
      background: var(--code);
      border-radius: 0.3rem;
      padding: 0.15rem 0.4rem;
    }

    .actions {
      display: flex;
      flex-wrap: wrap;
      gap: 0.75rem;
    }

    .btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      height: 2.75rem;
      padding: 0 1.4rem;
      border: 1px solid var(--line);
      border-radius: 999px;
      font-size: 0.9rem;
      font-weight: 500;
      color: var(--fg);
      text-decoration: none;
      transition: background-color 0.15s;
    }

    .btn:hover { background: var(--ghost-hover); }

    .btn-primary {
      background: var(--fg);
      border-color: var(--fg);
      color: var(--bg);
    }

    .btn-primary:hover { background: var(--btn-hover); border-color: var(--btn-hover); }

    footer {
      padding: 1.5rem;
      text-align: center;
      font-size: 0.85rem;
      color: var(--muted);
    }

    a:focus-visible {
      outline: 2px solid var(--fg);
      outline-offset: 3px;
    }

    @media (max-width: 30rem) {
      main { gap: 2rem; padding-top: 3rem; }
      .btn { flex: 1 1 100%; }
    }
  </style>
</head>
<body>

  <main>
    <div class="brand">
      <img class="logo" src="/assets/logo.svg" alt="" height="40">
      <span class="brand-name">Vercel</span>
    </div>

    <ol>
      <li>Get started by editing <code>index.php</code>.</li>
      <li>Save the file and reload this page to see your changes.</li>
    </ol>

    <div class="actions">
      <a class="btn btn-primary" href="https://vercel.com/dashboard" target="_blank">Open dashboard</a>
      <a class="btn" href="https://vercel.com/docs" target="_blank">See docs</a>
    </div>
  </main>

  <footer>
    <time id="today"></time>
  </footer>

  <script>
    // Env para el navegador: en Vercel las variables solo existen en el servidor (PHP),
    // así que las pasamos al frontend aquí. CLOUD_NAME y UPLOAD_PRESET son públicas por diseño.
    // Esto solo es recomendable con credenciales que sean explicitamente publicas por diseño.
    window.ENV = {
      CLOUD_NAME: <?= json_encode(getenv('CLOUD_NAME') ?: '') ?>,
      UPLOAD_PRESET: <?= json_encode(getenv('UPLOAD_PRESET') ?: '') ?>
    };
  </script>

  <script>
    var el = document.getElementById("today");
    var now = new Date();
    el.dateTime = now.toISOString().slice(0, 10);
    el.textContent = now.toLocaleDateString("en-US", { month: "long", day: "numeric", year: "numeric" });
  </script>

</body>
</html>