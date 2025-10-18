<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Árbol Genealógico</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
        }

        h2 {
            color: #2c3e50;
            text-align: center;
        }

        ul {
            list-style-type: none;
            position: relative;
            padding-left: 40px;
        }

        ul ul::before {
            content: "";
            position: absolute;
            top: 0;
            left: 15px;
            border-left: 2px solid #ccc;
            height: 100%;
        }

        li {
            margin: 10px 0;
            padding: 5px 10px;
            position: relative;
            display: inline-block;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: #f8f9fa;
        }

        li::before {
            content: "";
            position: absolute;
            top: -10px;
            left: -20px;
            width: 20px;
            border-top: 2px solid #ccc;
        }

        li:first-child::before {
            border-top: none;
        }

        li ul {
            margin-top: 15px;
        }

        form {
            margin-bottom: 20px;
            text-align: center;
        }

        input, select, button {
            margin: 5px;
            padding: 8px;
        }

        #message {
            text-align: center;
            margin-top: 10px;
            color: green;
        }

        #message.error {
            color: red;
        }
    </style>
</head>
<body>
    <h2>🌳 Árbol Genealógico de <?php echo htmlspecialchars($fatherName); ?></h2>

    <!-- Formulario para agregar hijo dinámicamente con AJAX -->
    <form id="addChildForm" method="POST" action="">
        <label for="child_name">Nombre del Hijo:</label>
        <input type="text" id="child_name" name="child_name" required>
        
        <label for="parent_id">Seleccionar Padre:</label>
        <select id="parent_id" name="parent_id" required>
            <option value="">Elegir Padre</option>
            <?php foreach ($nodes as $node): ?>
                <option value="<?php echo $node->id; ?>"><?php echo htmlspecialchars($node->name); ?></option>
            <?php endforeach; ?>
        </select>
        
        <button type="submit">Agregar Hijo</button>
    </form>

    <div id="message"></div>

    <div id="familyTree" style="display:flex; justify-content:center;">
        <?php echo $familyTreeHtml; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(document).ready(function() {
        
            $('#addChildForm').on('submit', function(e) {
                e.preventDefault();  // Prevenir recarga de página

                var formData = $(this).serialize() + '&ajax=1';  // Agregar flag para AJAX

                $.ajax({
                    url: '',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        var data = JSON.parse(response);
                        $('#message').removeClass('error').addClass(data.success ? '' : 'error').text(data.message);

                        if (data.success) {
                            // Recargar el árbol dinámicamente
                            location.reload();  // Opcional: recargar página para simplicidad, o actualizar #familyTree con nuevo HTML
                        }
                    },
                    error: function() {
                        $('#message').addClass('error').text('Error en la solicitud');
                    }
                });
            });
        });
    </script>
</body>
</html>