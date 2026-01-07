<?php

namespace Api\Core;

class Validator
{
    private array $errors = [];
    private array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function required(string $field, string $label = null): self
    {
        $label = $label ?? $field;
        if (empty($this->data[$field]) && $this->data[$field] !== '0') {
            $this->errors[$field] = "{$label} is required";
        }
        return $this;
    }

    public function email(string $field, string $label = null): self
    {
        $label = $label ?? $field;
        if (!empty($this->data[$field]) && !filter_var($this->data[$field], FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field] = "{$label} must be a valid email";
        }
        return $this;
    }

    public function url(string $field, string $label = null): self
    {
        $label = $label ?? $field;
        if (!empty($this->data[$field]) && !filter_var($this->data[$field], FILTER_VALIDATE_URL)) {
            $this->errors[$field] = "{$label} must be a valid URL";
        }
        return $this;
    }

    public function min(string $field, int $length, string $label = null): self
    {
        $label = $label ?? $field;
        if (!empty($this->data[$field]) && strlen($this->data[$field]) < $length) {
            $this->errors[$field] = "{$label} must be at least {$length} characters";
        }
        return $this;
    }

    public function max(string $field, int $length, string $label = null): self
    {
        $label = $label ?? $field;
        if (!empty($this->data[$field]) && strlen($this->data[$field]) > $length) {
            $this->errors[$field] = "{$label} must be no more than {$length} characters";
        }
        return $this;
    }

    public function fails(): bool
    {
        return !empty($this->errors);
    }

    public function errors(): array
    {
        return $this->errors;
    }

    public function firstError(): ?string
    {
        return $this->errors ? reset($this->errors) : null;
    }

    public static function make(array $data): self
    {
        return new self($data);
    }
}