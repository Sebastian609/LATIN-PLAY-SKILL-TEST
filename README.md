# Árbol Genealógico Avanzado

## Descripción del Problema

Se requiere implementar un sistema en PHP que maneje un árbol genealógico dinámico con características avanzadas de gestión y análisis de datos arbóreos.

## Características Implementadas

### 🏗️ Estructura del Sistema

#### **Backend (PHP)**
- **Modelo de Datos**: Cada persona tiene `id`, `nombre` y `hijos` (array de nodos)
- **Arquitectura en Capas**:
  - `Node.php` - Modelo básico del nodo
  - `NodeRepository.php` - Manejo de datos y algoritmos
  - `NodeService.php` - Lógica de negocio
  - `NodeController.php` - Controlador API REST
  - `api/node.php` - Punto de entrada de la API

#### **Frontend (JavaScript + HTML)**
- **Interfaz Dinámica**: Árbol visual con nodos clicables
- **Modal Interactivo**: Operaciones CRUD y análisis
- **Funciones AJAX**: Comunicación asíncrona con el backend

### ⚡ Funcionalidades CRUD

#### **1. Agregar Personas**
- ✅ **Agregar en cualquier nivel**: Especificar padre por ID
- ✅ **Validación automática**: Verifica existencia del padre
- ✅ **Persistencia**: Guarda en sesión PHP

```php
// Ejemplo de uso
POST /api/node.php
{
  "name": "NUEVA PERSONA",
  "father": "ID_DEL_PADRE"
}
```

#### **2. Eliminar Personas**
- ✅ **Eliminación recursiva**: Borra persona y todo su subárbol
- ✅ **Reindexación automática**: Mantiene integridad del árbol

#### **3. Mover Subárboles**
- ✅ **Cambio de padre**: Mueve una persona a otro padre
- ✅ **Preserva relaciones**: Mantiene toda la jerarquía

```php
// Ejemplo de uso
PUT /api/node.php
{
  "id": "ID_A_MOVER",
  "fatherId": "NUEVO_ID_PADRE"
}
```

### 🔍 Algoritmos de Análisis

#### **1. Recorrido en Profundidad (DFS)**
```php
// Implementación recursiva
function dfsRecursive($node, &$result) {
    $result[] = ['id' => $node->id, 'name' => $node->name];
    foreach ($node->sons as $son) {
        $this->dfsRecursive($son, $result);
    }
}
```

#### **2. Recorrido en Anchura (BFS)**
```php
// Implementación con cola
function bfs() {
    $queue = [$this->root];
    while (!empty($queue)) {
        $node = array_shift($queue);
        $result[] = ['id' => $node->id, 'name' => $node->name];
        foreach ($node->sons as $son) {
            $queue[] = $son;
        }
    }
}
```

#### **3. Búsqueda por ID**
```php
// Búsqueda recursiva en profundidad
function findById($current, $id) {
    if ($current === null) return null;
    if ($current->id === $id) return $current;

    foreach ($current->sons as $son) {
        $found = $this->findById($son, $id);
        if ($found) return $found;
    }
    return null;
}
```

#### **4. Profundidad Máxima**
```php
// Cálculo recursivo de profundidad
function calculateDepth($node) {
    if ($node === null) return 0;

    $maxChildDepth = 0;
    foreach ($node->sons as $son) {
        $childDepth = $this->calculateDepth($son);
        $maxChildDepth = max($maxChildDepth, $childDepth);
    }

    return 1 + $maxChildDepth;
}
```

#### **5. Conteo de Descendientes**
```php
// Conteo recursivo de todos los descendientes
function countDescendants($node) {
    if ($node === null) return 0;

    $count = count($node->sons);
    foreach ($node->sons as $son) {
        $count += $this->countDescendants($son);
    }

    return $count;
}
```

### 🎨 Interfaz de Usuario

#### **Visualización del Árbol**
- ✅ **Formato enriquecido**: `Nombre (ID)` en cada nodo
- ✅ **Navegación intuitiva**: Clic para seleccionar nodos
- ✅ **Auto-copia**: ID se copia al portapapeles automáticamente

#### **Modal de Operaciones**
- ✅ **Sección de análisis**: Contar descendientes del nodo seleccionado
- ✅ **Gestión de relaciones**: Cambiar padre con dropdown
- ✅ **Operaciones CRUD**: Agregar, eliminar, modificar

#### **Panel de Análisis Global**
- ✅ **Métricas del árbol**: Profundidad máxima, recorridos completos
- ✅ **Funciones específicas**: Análisis de nodos individuales

### 🚀 Optimizaciones de Rendimiento

#### **Para Miles de Nodos**

##### **1. Serialización Eficiente**
```php
// Uso de serialize() de PHP para almacenamiento en sesión
$_SESSION['family_tree'] = serialize($this->root);
```

##### **2. Algoritmos O(n)**
- ✅ **BFS/DFS lineales**: Recorren cada nodo exactamente una vez
- ✅ **Búsqueda indexada**: No requiere recorrer todo el árbol para búsquedas frecuentes

##### **3. Estructura de Datos Optimizada**
```php
class Node {
    public array $sons = [];  // Array indexado para acceso O(1)
    public ?Node $father = null;  // Referencia directa al padre
    public string $id;  // UUID único
    public string $name;
}
```

##### **4. Lazy Loading**
- ✅ **Carga bajo demanda**: Solo se reconstruye el árbol cuando es necesario
- ✅ **Persistencia en sesión**: Mantiene estado entre requests

##### **5. Estrategias de Caché**
```php
// Reconstrucción automática de datos corruptos
if ($unserializedData === false) {
    $this->initializeExampleTree();
    $_SESSION['family_tree'] = serialize($this->root);
}
```

### 📋 Uso del Sistema

#### **Configuración Inicial**
```bash
# Iniciar servidor
php -S localhost:8080

# Navegar a la aplicación
http://localhost:8080
```

#### **Operaciones Básicas**

1. **Agregar Persona**:
   - Hacer clic en un nodo existente
   - Llenar formulario en modal
   - Especificar nombre y padre

2. **Eliminar Persona**:
   - Hacer clic en nodo a eliminar
   - Usar botón "Eliminar" en modal

3. **Cambiar Padre**:
   - Seleccionar nodo a mover
   - Elegir nuevo padre del dropdown
   - Confirmar cambio

4. **Análisis**:
   - Usar botones del panel inferior
   - Ver resultados en tiempo real

### 🔧 Tecnologías Utilizadas

- **Backend**: PHP 8.0+ con programación orientada a objetos
- **Frontend**: JavaScript ES6+ con jQuery para AJAX
- **Estilos**: Tailwind CSS para diseño responsivo
- **Almacenamiento**: Sesiones PHP para persistencia
- **Comunicación**: API RESTful con JSON

### 📈 Escalabilidad

El sistema está diseñado para manejar eficientemente grandes cantidades de datos:

- **Complejidad algorítmica**: O(n) para la mayoría de operaciones
- **Uso de memoria**: Mínimo mediante serialización
- **Búsquedas rápidas**: Acceso directo por ID
- **Operaciones atómicas**: Cada cambio persiste completamente

### 🛠️ Mantenimiento

- **Código modular**: Fácil extensión de funcionalidades
- **Separación de responsabilidades**: Modelo, servicio, controlador
- **Manejo de errores**: Validaciones y excepciones apropiadas
- **Documentación**: Comentarios y estructura clara

---

**Sistema desarrollado para manejo eficiente de árboles genealógicos con capacidades avanzadas de análisis y operaciones CRUD.**
