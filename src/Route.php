<?php

declare (strict_types=1);

class Route {

    public function __construct(private string $route, private array $params, private RequestMethod $method) {
        // konstruktor
    }

    public function getRoute(): string {
        return $this->route;
    }

    public function getParams(): array {
        return $this->params;
    }

    public function getMethod(): RequestMethod {
        return $this->method;
    }
}