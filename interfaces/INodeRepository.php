<?php
declare(strict_types=1);

interface INodeRepository
{
    public function getRoot(): ?Node;
    public function save(Node $node): ?Node;
    public function findByName(string $name): ?Node;
    public function findById(string $id): ?Node;
    public function delete(string $id): ?Node;
    public function dfs(): array;
    public function bfs(): array;
    public function getMaxDepth(): int;
    public function getDescendantCount(string $id): int;
}
