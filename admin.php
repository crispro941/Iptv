<?php
// Cargar los canales existentes
$jsonFile = 'canales.json';
$canales = json_decode(file_get_contents($jsonFile), true);

// Manejar el formulario de envío o edición
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['edit'])) {
        // Editar un canal existente
        foreach ($canales['canales'] as &$canal) {
            if ($canal['tvg-id'] === $_POST['id']) {
                $canal['tvg-name'] = $_POST['name'];
                $canal['tvg-logo'] = $_POST['logo'];
                $canal['description'] = $_POST['description'];
                $canal['url'] = $_POST['url'];
                $canal['redirect'] = $_POST['redirect'];
                break;
            }
        }
    } else {
        // Agregar un nuevo canal
        $newChannel = [
            'tvg-id' => uniqid(), // Generar un nuevo ID único
            'tvg-name' => $_POST['name'],
            'tvg-logo' => $_POST['logo'],
            'description' => $_POST['description'],
            'url' => $_POST['url'],
            'redirect' => $_POST['redirect']
        ];
        // Agregar el nuevo canal al array de canales
        $canales['canales'][] = $newChannel;
    }

    // Guardar los cambios en el archivo JSON
    file_put_contents($jsonFile, json_encode($canales, JSON_PRETTY_PRINT));
    echo "<script>alert('Cambios guardados exitosamente');</script>";
}

// Manejar la edición de un canal
$canalEditar = null;
if (isset($_GET['edit'])) {
    foreach ($canales['canales'] as $canal) {
        if ($canal['tvg-id'] === $_GET['edit']) {
            $canalEditar = $canal;
            break;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        form {
            display: flex;
            flex-direction: column;
            max-width: 400px;
            margin: auto;
        }
        input, textarea {
            margin-bottom: 10px;
            padding: 10px;
            font-size: 16px;
        }
        button {
            padding: 10px;
            font-size: 16px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .channel-list {
            margin-top: 20px;
        }
        .channel {
            margin-bottom: 10px;
            padding: 10px;
            background-color: #fff;
            border-radius: 5px;
        }
    </style>
</head>
<body>

    <h1>Agregar o Editar Canal</h1>
    <form method="POST" action="">
        <input type="hidden" name="id" value="<?= $canalEditar['tvg-id'] ?? '' ?>">
        <input type="text" name="name" placeholder="Nombre del Canal" value="<?= $canalEditar['tvg-name'] ?? '' ?>" required>
        <input type="text" name="logo" placeholder="URL del Logo" value="<?= $canalEditar['tvg-logo'] ?? '' ?>" required>
        <textarea name="description" placeholder="Descripción del Canal" required><?= $canalEditar['description'] ?? '' ?></textarea>
        <input type="text" name="url" placeholder="URL de Streaming" value="<?= $canalEditar['url'] ?? '' ?>" required>
        <input type="text" name="redirect" placeholder="URL de Redirección (ej: go:canal-ejemplo)" value="<?= $canalEditar['redirect'] ?? '' ?>" required>
        <button type="submit" name="<?= $canalEditar ? 'edit' : 'add' ?>"><?= $canalEditar ? 'Guardar Cambios' : 'Agregar Canal' ?></button>
    </form>

    <h2>Lista de Canales Existentes</h2>
    <div class="channel-list">
        <?php foreach ($canales['canales'] as $canal): ?>
        <div class="channel">
            <p><strong>Nombre:</strong> <?= $canal['tvg-name'] ?></p>
            <p><strong>Descripción:</strong> <?= $canal['description'] ?></p>
            <p><strong>Redirección:</strong> <?= $canal['redirect'] ?></p>
            <a href="?edit=<?= $canal['tvg-id'] ?>">Editar</a>
        </div>
        <?php endforeach; ?>
    </div>

</body>
</html>
