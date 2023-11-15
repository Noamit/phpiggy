<?php

namespace Framework\Rules;

use Framework\Contracts\RuleInterface;

class DateFormatRule implements RuleInterface
{
  public function validate(array $data, string $field, array $params): bool
  {
    //$params[0] is the format
    $parsedDate = date_parse_from_format($params[0], $data[$field]);
    return $parsedDate['error_count'] === 0 && $parsedDate['warning_count'] === 0;
  }

  public function getMessage(array $data, string $field, array $params): string
  {
    return "Invalid date";
  }
}