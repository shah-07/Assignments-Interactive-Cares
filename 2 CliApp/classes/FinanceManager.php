<?php

declare(strict_types=1);

class FinanceManager
{
  private StorageInterface $storage;

  public function __construct(StorageInterface $storage)
  {
    $this->storage = $storage;
  }

  public function addIncome(float $amount, string $category): void
  {
    $this->storage->saveIncome($amount, $category);
    echo "income added" . PHP_EOL;
  }
  public function addExpense(float $amount, string $category): void
  {
    $this->storage->saveExpense($amount, $category);
    echo "Expense added" . PHP_EOL;
  }
  public function showIncomes(): void
  {
    $incomes = $this->storage->getIncomes();
    if (!empty($incomes)) {
      foreach ($incomes as $income) {
        printf("Amount: %f, Category: %s\n", $income['amount'], $income['category']);
      }
    } else {
      printf("Income Data Not Found!\n");
    }

  }

  public function showExpenses(): void
  {
    $expenses = $this->storage->getExpenses();
    if (!empty($expenses)) {
      foreach ($expenses as $expense) {
        printf("Amount: %f, Category: %s\n", $expense['amount'], $expense['category']);
      }
    } else {
      printf("Expense Data Not Found!\n");
    }
  }

  public function showSavings(): void
  {
    $incomes = $this->storage->getIncomes();
    $expenses = $this->storage->getExpenses();
    $totalIncomes = array_sum(array_column($incomes, 'amount'));
    $totalExpenses = array_sum(array_column($expenses, 'amount'));
    $savings = $totalIncomes - $totalExpenses;
    printf("Savings: %f\n", $savings);
  }
  public function showCategories(): void
  {
    $categories = $this->storage->getCategories();
    if (!empty($categories)) {
      foreach ($categories as $categoryName => $categoryType) {
        printf("Name: %s, Type: %s\n", $categoryName, $categoryType);
      }
    } else {
      printf("Categories Data Not Found!\n");
    }

  }

}