<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Dokumen - BMKG Mamuju</title>
    <style>html, body { height: 100%; margin: 0; padding: 0; overflow: hidden; }</style>
    <script src="https://teknisi.blinklab.com/office/web-apps/apps/api/documents/api.js"></script>
</head>
<body>
    <div id="placeholder"></div>

    <script>
        // Terima config dari Controller
        var config = @json($config);

        // Inisialisasi Editor
        var docEditor = new DocsAPI.DocEditor("placeholder", config);
    </script>
</body>
</html>