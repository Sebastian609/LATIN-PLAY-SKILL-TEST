<?php

declare(strict_types=1);
require_once __DIR__ . '/../interfaces/INodeRepository.php';
require_once __DIR__ . '/../models/Node.php';

class NodeService
{
    private INodeRepository $repository;

    public function __construct(INodeRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getFamilyTree(): ?Node
    {
        return $this->repository->getRoot();
    }

    public function deleteNode(string $id): ?Node
    {
        return $this->repository->delete($id);
    }

    public function changeFather(string $id, string $fatherId): ?Node
    {
        try {
            $node = $this->repository->findById($id);
            $father = $this->repository->findById($fatherId);
            if (!$node || !$father) {
                throw new Exception("El nodo o el padre no existen");
            }
            $node->father->removeSon($node);
            $father->addSon($node);
            $this->repository->save($this->repository->getRoot() ?? $node);
            return $node;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function addNode(string $name, ?string $father = null): Node
    {
        try {
            // Resolver padre por ID primero (si es posible) y luego por nombre para compatibilidad
            $parent = null;
            if ($father === null && $father === '') {
                throw new Exception("El padre es requerido");
            }

            $parent = $this->repository->findById($father);
            if ($parent === null) {
                throw new Exception("El padre no existe, id: " . $father);
            }

            $node = new Node($name);
            $node->father = $parent;

            if ($parent) {
                $parent->addSon($node);
                // Persistir cambios del árbol en sesión
                $this->repository->save($this->repository->getRoot() ?? $node);
            } else {
                // Guardar como raíz si no hay padre
                $this->repository->save($node);
            }
            

            return $node;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
