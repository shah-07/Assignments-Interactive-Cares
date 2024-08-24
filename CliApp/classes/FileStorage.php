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

}