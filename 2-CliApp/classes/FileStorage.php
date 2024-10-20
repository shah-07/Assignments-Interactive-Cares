<?php

declare(strict_types=1);

interface StorageInterface
{
  public function saveIncome(float $amount, string $category): void;
  public function saveExpense(float $amount, string $category): void;
  public function getIncomes(): array;
  public function getExpenses(): array;
  public function getCategories(): array;
}

class FileStorage implements StorageInterface
{
  private string $filePath;

  public function __construct(string $filePath)
  {
    $this->filePath = $filePath;
    if (!file_exists($this->filePath)) {
      file_put_contents($this->filePath, ''); // Create an empty file
    }
  }

  public function saveIncome(float $amount, string $category): void
  {
    $this->saveData(['type' => 'income', 'amount' => $amount, 'category' => $category]);
  }

  public function saveExpense(float $amount, string $category): void
  {
    $this->saveData(['type' => 'expense', 'amount' => $amount, 'category' => $category]);
  }

  private function saveData(array $data): void
  {
    file_put_contents($this->filePath, json_encode($data) . "\n", FILE_APPEND);
  }

  public function getIncomes(): array
  {
    return $this->getData('income');
  }

  public function getExpenses(): array
  {
    return $this->getData('expense');
  }

  public function getCategories(): array
  {
    $categories = [];
    $data = $this->getAllData();
    foreach ($data as $item) {
      $categories[$item['category']] = $item['type'];
    }
    return $categories;
  }

  private function getAllData(): array
  {
    $data = file($this->filePath, FILE_IGNORE_NEW_LINES);
    $result = [];
    foreach ($data as $line) {
      $result[] = json_decode($line, true);
    }
    return $result;
  }

  private function getData(string $type): array
  {
    $data = $this->getAllData();
    $result = [];
    foreach ($data as $item) {
      if (!empty($item['type'])) {
        if ($item['type'] === $type) {
          $result[] = $item;
        }
      }
    }
    return $result;
  }
}