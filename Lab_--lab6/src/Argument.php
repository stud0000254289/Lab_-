<?php
namespace SouleymanSidick\MyProject;

class Arguments {
    private array $arguments;

    public function __construct(array $arguments) {
        $this->arguments = $arguments;
    }

    public function get(string $key): ?string {
        return $this->arguments[$key] ?? null;
    }
}
