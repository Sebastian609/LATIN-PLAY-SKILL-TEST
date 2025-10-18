<?php
declare(strict_types=1);
require_once __DIR__ . '/../models/Node.php';
require_once __DIR__ . '/../interfaces/INodeRepository.php';

class NodeRepository implements INodeRepository
{
    private ?Node $root = null;
    private bool $initialized = false;

    public function __construct()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $stored = $_SESSION['family_tree'] ?? null;
        if ($stored instanceof Node) {
            // Sesiones previas pudieron guardar el objeto directamente
            $this->root = $stored;
            $this->initialized = true;
            // Normalizar a formato serializado para futuras lecturas
            $_SESSION['family_tree'] = serialize($this->root);
        } elseif (is_string($stored)) {
            $un = @unserialize($stored);
            if ($un instanceof Node) {
                $this->root = $un;
                $this->initialized = true;
            } else {
                // Dato corrupto o incompatible: reinicializar
                $this->initializeExampleTree();
                $_SESSION['family_tree'] = serialize($this->root);
                $this->initialized = true;
            }
        } else {
            // No hay dato en sesión: inicializar árbol de ejemplo
            $this->initializeExampleTree();
            $_SESSION['family_tree'] = serialize($this->root);
            $this->initialized = true;
        }
    }

    public function getRoot(): ?Node
    {
        return $this->root;
    }

    public function save(Node $node): ?Node
    {
        if ($this->root === null) {
            $this->root = $node;
            $this->initialized = true;
        }

        $_SESSION['family_tree'] = serialize($this->root);
        return $node;
    }

    public function findByName(string $name): ?Node
    {
        return $this->searchNode($this->root, $name);
    }

    private function searchNode(?Node $current, string $name): ?Node
    {
        if ($current === null) return null;
        if ($current->name === $name) return $current;

        foreach ($current->sons as $son) {
            $found = $this->searchNode($son, $name);
            if ($found) return $found;
        }

        return null;
    }

    private function searchNodeById(?Node $current, string $id): ?Node
    {
        if ($current === null) return null;
        if ($current->id === $id) return $current;

        foreach ($current->sons as $son) {
            $found = $this->searchNodeById($son, $id);
            if ($found) return $found;
        }

        return null;
    }

    public function findById(string $id): ?Node
    {
        return $this->searchNodeById($this->root, $id);
    }

    public function delete(string $id): ?Node
    {
        $node = $this->searchNodeById($this->root, $id);
        if ($node) {
            $node->father->removeSon($node);
            $this->save($this->root);
        }
        return $node;
    }

    private function initializeExampleTree(): void
    {
        // Crear raíz y familia de ejemplo
        $this->root = new Node("LUIS");
        $son1 = new Node("SEBASTIAN");
        $son2 = new Node("CARLA");
        $son3 = new Node("MARIA");

        // Hijos de LUIS
        $this->root->addSon($son1);
        $this->root->addSon($son2);
        $this->root->addSon($son3);

        // Hijos de SEBASTIAN
        $son1->addSon(new Node("JUAN"));
        $son1->addSon(new Node("VALENTINA"));

        // Hijos de CARLA
        $son2->addSon(new Node("MATEO"));
        $son2->addSon(new Node("SOFIA"));

        // Hijos de MARIA
        $son3->addSon(new Node("ANTONIA"));

        // Nietos de JUAN
        $son1->sons[0]->addSon(new Node("PEDRO"));
        $son1->sons[0]->addSon(new Node("LUCIA"));

        // Nietos de VALENTINA
        $son1->sons[1]->addSon(new Node("DIEGO"));
    }

    public function dfs(): array
    {
        $result = [];
        $this->dfsRecursive($this->root, $result);
        return $result;
    }

    public function bfs(): array
    {
        $result = [];
        if ($this->root === null) {
            return $result;
        }

        $queue = [$this->root];

        while (!empty($queue)) {
            $node = array_shift($queue);
            $result[] = [
                'id' => $node->id,
                'name' => $node->name
            ];

            foreach ($node->sons as $son) {
                $queue[] = $son;
            }
        }

        return $result;
    }

    public function getMaxDepth(): int
    {
        return $this->calculateDepth($this->root);
    }

    public function getDescendantCount(string $id): int
    {
        $node = $this->findById($id);
        if ($node === null) {
            return 0;
        }

        return $this->countDescendants($node);
    }

    private function dfsRecursive(?Node $node, array &$result): void
    {
        if ($node === null) {
            return;
        }

        $result[] = [
            'id' => $node->id,
            'name' => $node->name
        ];

        foreach ($node->sons as $son) {
            $this->dfsRecursive($son, $result);
        }
    }

    private function calculateDepth(?Node $node): int
    {
        if ($node === null) {
            return 0;
        }

        $maxChildDepth = 0;
        foreach ($node->sons as $son) {
            $childDepth = $this->calculateDepth($son);
            if ($childDepth > $maxChildDepth) {
                $maxChildDepth = $childDepth;
            }
        }

        return 1 + $maxChildDepth;
    }

    private function countDescendants(?Node $node): int
    {
        if ($node === null) {
            return 0;
        }

        $count = count($node->sons);

        foreach ($node->sons as $son) {
            $count += $this->countDescendants($son);
        }

        return $count;
    }
}