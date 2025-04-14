<?php

class Validator
{
  protected array $data;
  protected array $rules;
  protected array $messages;
  protected array $errors = [];

  public function __construct(array $data, array $rules, array $messages = [])
  {
    $this->data = $data;
    $this->rules = $rules;
    $this->messages = $messages;
    $this->validate();
  }

  private function validate()
  {
    foreach ($this->rules as $field => $rules) {
      $rules = explode('|', $rules);

      foreach ($rules as $rule) {
        $this->applyRule($field, $rule);
      }
    }
  }

  private function applyRule(string $field, string $rule)
  {
    $value = $this->data[$field] ?? null;

    // Required
    if ($rule === 'required' && (!$value || trim($value) === '')) {
      $this->addError($field, 'required', "The $field field is required.");
    }

    // String
    if ($rule === 'string' && !is_string($value)) {
      $this->addError($field, 'string', "The $field field must be a string.");
    }

    // Number
    if ($rule === 'number' && !is_numeric($value)) {
      $this->addError($field, 'number', "The $field field must be a number.");
    }

    // Email
    if ($rule === 'email' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
      $this->addError($field, 'email', "The $field field must be a valid email address.");
    }

    // Min length
    if (strpos($rule, 'min:') === 0) {
      $min = (int) substr($rule, 4);
      if (strlen($value) < $min) {
        $this->addError($field, 'min', "The $field field must be at least $min characters long.");
      }
    }

    // Max length
    if (strpos($rule, 'max:') === 0) {
      $max = (int) substr($rule, 4);
      if (strlen($value) > $max) {
        $this->addError($field, 'max', "The $field field must not exceed $max characters.");
      }
    }

    // Unique (database check)
    if (strpos($rule, 'unique:') === 0) {
      [$table, $column] = explode(':', str_replace('unique:', '', $rule));
      if ($this->existsInDatabase($table, $column, $value)) {
        $this->addError($field, 'unique', "The $field field must be unique.");
      }
    }
  }

  private function existsInDatabase(string $table, string $column, string $value): bool
  {
    // You should replace this with actual database logic.
    global $pdo;
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM $table WHERE $column = :value");
    $stmt->execute(['value' => $value]);
    return $stmt->fetchColumn() > 0;
  }

  private function addError(string $field, string $rule, string $defaultMessage)
  {
    $this->errors[$field][] = $this->messages[$field . '.' . $rule] ?? $defaultMessage;
  }

  public function fails(): bool
  {
    return !empty($this->errors);
  }

  public function errors(): array
  {
    return $this->errors;
  }

  public function validated(): array
  {
    return $this->fails() ? [] : $this->data;
  }
}
