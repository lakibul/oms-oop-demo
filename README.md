# Mini Order Management System — OOP Case Study

A raw-PHP order management system, built naive-first and refactored phase by phase to
practice production-grade OOP: SOLID, common design patterns, and test-driven verification
of real domain invariants.

See [`OOP_Learning_Guide_Raw_PHP_Project.md`](./OOP_Learning_Guide_Raw_PHP_Project.md) for
the full phase-by-phase plan this project follows.

## Phase Log

Each phase gets an entry here as it's completed: what was wrong before, what changed, and
which principle fixed it. This becomes the final case study in Phase 13.

### Phase 0 — Project Setup
- `src/` (PSR-4 `App\`), `tests/` (PSR-4 `Tests\`), Composer + PHPUnit installed.

### Phase 1 — Naive Baseline
- One class (`Order`) doing everything: total
  calculation, if/else payment handling, stock decrement, "persistence", and notification —
  all crammed into a single class with no separation of concerns.
- What already feels wrong: `calculateTotal()`, `payment()`, `stock()`, `persistence()`, and
  `notification()` are five unrelated responsibilities living in one class. Changing how
  payments are processed risks breaking totals or stock, purely because they share a file.
- Entry point: `index.php` builds a couple of orders and runs them through the class to see
  real output (`php index.php` or via the browser).
