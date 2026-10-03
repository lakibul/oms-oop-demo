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

### Phase 2 — Classes, Objects, Encapsulation
- Extracted real classes: `Customer`, `Product`, and `Order` all now have private
  properties instead of public ones — nothing outside the class can reach in and
  overwrite state directly.
- Constructors now validate input instead of blindly assigning it: `Product` rejects a
  negative price, `Customer` rejects an empty name/type, `Order` rejects an empty
  product list, missing product fields, negative prices, and zero/negative quantities —
  all via `InvalidArgumentException`.
- `RegularCustomer`/`VipCustomer` inherited the encapsulation fix for free, since they
  only call `parent::__construct()`.
- Verified with a smoke test (invalid inputs correctly throw, valid input passes
  through unchanged) and a full re-run of `index.php` to confirm existing behavior was
  preserved.

### Phase 3 — Inheritance & Polymorphism
- `RegularCustomer` and `VipCustomer` both `extends Customer`, each overriding
  `getDiscount()` with a different rate (`0.0` vs `0.10`).
- `Customer` is now `abstract` with `getDiscount()` declared as an `abstract` method —
  previously it only existed on the two subclasses, so code written against the plain
  `Customer` type couldn't call it. Making `Customer` abstract also blocks `new
  Customer(...)` directly, since a bare customer with no discount rule doesn't make
  sense in this domain.
- Verified real polymorphism: a function type-hinted against `Customer` (not the
  subclasses) correctly calls the right subclass's `getDiscount()` depending on which
  concrete object it's handed, and direct `Customer` instantiation now throws an
  `Error` as expected.
