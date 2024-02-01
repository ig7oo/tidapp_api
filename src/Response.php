<?php

declare (strict_types=1);

class Response {

    public function __construct(private string|array|stdClass $content, private int $status = 200) {
        
    }

    public function getContent(): string|array|stdClass {
        return $this->content;
    }

    public function getStatus(): int {
        return $this->status;
    }

    public function setContent(string|array|stdClass $content): void {
        $this->content = $content;
    }

    public function setStatus(int $status): void {
        $this->status = $status;
    }

    /**
     * Skickar response-svaret som en JSON 
     * @param Response $response
     * @return never
     */
    public function skickaJSON(): never {
        http_response_code($this->status);
        header("Content-Type: application/json; charset=UTF-8");
        $json = json_encode($this->content, JSON_PRETTY_PRINT + JSON_UNESCAPED_UNICODE);
        echo $json;
        exit;
    }
}