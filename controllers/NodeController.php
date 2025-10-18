<?php

declare(strict_types=1);

require_once __DIR__ . '/../services/NodeService.php';

class NodeController
{
    private NodeService $service;

    public function __construct(NodeService $service)
    {
        $this->service = $service;
    }

    public function index(): void
    {
        $tree = $this->service->getFamilyTree();
        $data = $tree ? $tree->toArray() : [];
        $this->respond(['data' => $data]);
    }


    public function store(array $request): void
    {
        try {

            $name   = trim($request['name'] ?? '');
            $father = $request['father'] ?? null;

            if ($name === '') {
                throw new Exception("El nombre es requerido");
            }

            if ($father === null) {
                throw new Exception("El padre es requerido");
            }

            $node = $this->service->addNode($name, $father);
            $this->respond(['message' => 'Nodo agregado correctamente', 'data' => $node, 'success' => true]);
        } catch (Exception $e) {
            $this->respond(['message' => $e->getMessage(), 'success' => false], 400);
        }
    }

    private function respond(array $data, int $status = 200): void
    {
        http_response_code($status);
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }

    public function delete(array $request): void
    {
        try {
            $id = $request['id'] ?? null;
            if ($id === null) {
                throw new Exception("El id es requerido");
            }
            $node = $this->service->delete($id);
            $this->respond(['message' => 'Nodo eliminado correctamente', 'data' => $node, 'success' => true], 200);
        } catch (Exception $e) {
            $this->respond(['message' => $e->getMessage(), 'success' => false], 400);
        }
    }

    public function changeFather(array $request): void
    {
        try {
            $id = $request['id'] ?? null;
            $fatherId = $request['father_id'] ?? null;
            if ($id === null || $fatherId === null) {
                throw new Exception("El id y el id del padre son requeridos");
            }
            $node = $this->service->changeFather($id, $fatherId);
            $this->respond(['message' => 'Nodo modificado correctamente', 'data' => $node, 'success' => true], 200);
        } catch (Exception $e) {
            $this->respond(['message' => $e->getMessage(), 'success' => false], 400);
        }
    }

    public function getMaxDepth(): void
    {
        $depth = $this->service->getMaxDepth();
        $this->respond(['max_depth' => $depth]);
    }

    public function getDescendantCount(): void
    {
        $id = $_GET['id'] ?? null;
        if ($id === null) {
            $this->respond(['error' => 'ID parameter is required'], 400);
            return;
        }

        $count = $this->service->getDescendantCount($id);
        $this->respond(['descendant_count' => $count]);
    }

    public function getDfsTraversal(): void
    {
        $dfsResult = $this->service->dfs();
        $this->respond(['dfs' => $dfsResult]);
    }

    public function getBfsTraversal(): void
    {
        $bfsResult = $this->service->bfs();
        $this->respond(['bfs' => $bfsResult]);
    }
}
