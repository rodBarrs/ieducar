<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/swagger-ui/3.19.5/swagger-ui.css" integrity="sha512-+EPE8aR1DXy53X3XoNDfnIqwayrk1ftACKcZu/eCNcoiaugvrtrve6SKl1zMstzXwyVy8BTlt4ltFj0EgpW5vw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        .topbar {
            display: none;
        }
    </style>
    <title>API</title>
</head>

<body>
    <div id="swagger-ui"></div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/swagger-ui/3.19.5/swagger-ui-bundle.js" integrity="sha512-m8LAqAUUciH5gXu/COwRz9d7MkFwK1OTC5LSeVQ7oYPmU8o7XXBkRKQf9fBvTw8Ws9RUw9c2ngKQwGcOiTSJWA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/swagger-ui/3.19.5/swagger-ui-standalone-preset.js" integrity="sha512-wKJoDGS6d4z2v5gYkM+lgpqfY81nAvtoeZhvv3QTTjCOsiSIls7UhH1DFci8UF20uDaPYAyyugUpHNH0QQbKoA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        window.onload = function () {
            const ui = SwaggerUIBundle({
                url: '{{ url('openapi.json') }}',
                dom_id: '#swagger-ui',
                deepLinking: true,
                presets: [
                    SwaggerUIBundle.presets.apis,
                    SwaggerUIStandalonePreset
                ],
                plugins: [
                    SwaggerUIBundle.plugins.DownloadUrl
                ],
                layout: "StandaloneLayout"
            })

            window.ui = ui
        }
    </script>
    </body>
</html>
