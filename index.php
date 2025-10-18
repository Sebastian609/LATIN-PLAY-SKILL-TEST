<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Árbol Genealógico</title>
    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 p-6 font-sans relative">

    <h1 class="text-3xl font-bold mb-8 text-center text-gray-800">Árbol Genealógico</h1>

   <!-- Modal del formulario -->
    <div id="modal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden">
        <div class="bg-white p-6 rounded shadow-lg w-96">
            <button id="delete" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition">Eliminar esta persona e hijos</button>

            <div class="mt-4 p-3 bg-gray-50 rounded">
                <h3 class="text-lg font-semibold mb-2">Análisis del Nodo</h3>
                <div class="space-y-2">
                    <button id="countDescendantsBtn" class="w-full bg-indigo-500 text-white px-3 py-2 rounded hover:bg-indigo-600 transition text-sm">
                        Contar Descendientes
                    </button>
                    <div id="descendantResult" class="text-sm text-gray-600 hidden">
                        <!-- Resultado se mostrará aquí -->
                    </div>
                </div>
            </div>

            <h2 class="text-xl font-semibold mb-4">Cambiar padre</h2>
            <div class="mb-4">
                <label for="parentSelect" class="block text-sm font-medium mb-2">Seleccionar nuevo padre:</label>
                <select id="parentSelect" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <option value="">Seleccionar nuevo padre</option>
                </select>
            </div>
            <button id="changeParentBtn" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition mb-4">Cambiar Padre</button>

            <h2 class="text-xl font-semibold mb-4">Agregar miembro</h2>
            <form id="addMemberForm" class="flex flex-col gap-3">
                <input type="text" name="name" id="name" placeholder="Nombre" 
                       class="border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
               <button type="submit" 
                        class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">
                    Agregar
                </button>
            </form>
            <button id="closeModal" class="mt-4 text-gray-500 hover:text-gray-800">Cerrar</button>
        </div>
    </div>

    <!-- Árbol genealógico -->
    <div id="familyTree" class="flex justify-center"></div>

    <!-- Tree Analysis Panel -->
    <div class="mt-8 max-w-4xl mx-auto">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <button id="maxDepthBtn" class="bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded transition">
                Profundidad Máxima
            </button>
            
            <button id="dfsBtn" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded transition">
                Recorrido DFS
            </button>
            <button id="bfsBtn" class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded transition">
                Recorrido BFS
            </button>
        </div>
        <h2 class="text-2xl font-bold mb-4 text-center text-gray-800">Análisis del Árbol</h2>
        
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="script.js"></script>
</body>
</html>
