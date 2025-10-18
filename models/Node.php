<?php

require_once __DIR__ . "/../utils/Uiid.php";

class Node {
    /** @var Node[] */
    public array $sons = [];
    public ?Node $father;
    public string $id;
    public string $name;

    public function __construct(string $name) {
        $this->id = Uiid::generateUuid();
        $this->name = $name;
        $this->father = null;
    }

    // 🔹 Agregar un hijo
    public function addSon(Node $son): void {
        $this->sons[] = $son; // siempre crea un índice limpio
        $son->father = $this;
    }

    // 🔹 Eliminar un hijo (rompe vínculo y reindexa)
    public function removeSon(Node $son): void {
        foreach ($this->sons as $key => $s) {
            if ($s->id === $son->id) {
                $s->father = null;
                unset($this->sons[$key]);
                $this->sons = array_values($this->sons); // reindexar
                break;
            }
        }
    }
    

    // 🔹 Convertir el nodo a un array limpio (para JSON)
    public function toArray(): array {
        return [
            'name' => $this->name,
            'id' => $this->id,
            'children' => array_map(
                fn(Node $child) => $child->toArray(),
                array_values($this->sons) // 👈 evita que se convierta en objeto con índices
            ),
        ];
    }
}
