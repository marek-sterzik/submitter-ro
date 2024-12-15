<?php

namespace App\Utility;

class Action
{
    public static function get(string $uri, ?string $label = null, ?string $cssClass = null): self
    {
        return new self($uri, $label, $cssClass);
    }

    private function __construct(private string $uri, private ?string $label = null, private ?string $cssClass)
    {
    }

    public function render(): string
    {
        return sprintf(
            "<a href=\"%s\" class=\"btn btn-sm%s\" role=\"button\">%s</a>",
            htmlspecialchars($this->uri),
            isset($this->cssClass) ? (" " . $this->cssClass) : "",
            htmlspecialchars($this->label ?? "!")
        );
    }
}
