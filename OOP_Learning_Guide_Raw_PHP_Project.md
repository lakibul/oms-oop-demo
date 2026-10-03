# OOP Deep Learning Guide — Mini Order Management System (Raw PHP)

A single evolving project, built naive-first then refactored phase by phase, touching all 31 OOP topics from your reference guide — with test-driven verification and real domain invariants, so this reads as production engineering practice, not a toy exercise.

## How to Use This With Claude Code — Read This First

**The one rule: Claude Code reviews, you write.**

At each phase:
1. Write the code yourself first, even if it feels clumsy.
2. Prompt Claude Code to critique, not generate — e.g. *"Review OrderProcessor.php for SRP violations. List what you find and why. Do NOT rewrite the file for me."*
3. Fix it yourself based on the critique.
4. Only if genuinely stuck, ask for a small hint, not a full solution.

If you skip straight to "write me a Factory pattern for payment gateways," you'll have working code and shallow understanding. That defeats the point.

---

## Part 1 — OOP Topics Covered (Quick Reference)

| # | Topic | Built in Phase |
|---|---|---|
| 1 | Why OOP | 1 |
| 2 | Class & Object | 2 |
| 3 | Four Pillars | 2, 3 |
| 4 | Interface vs Abstract Class | 4 |
| 5 | Overriding vs Overloading | 6 |
| 6 | Multiple Inheritance (Traits) | 4 |
| 7 | Static vs Instance | 5 |
| 8 | Constructor/Destructor | 2, 7 |
| 9 | Access Modifiers | 6 |
| 10 | Single Responsibility (SRP) | 4 |
| 11 | Open/Closed (OCP) | 5 |
| 12 | Liskov Substitution (LSP) | 6 |
| 13 | Interface Segregation (ISP) | 7 |
| 14 | Dependency Inversion (DIP) | 7 |
| 15 | DRY | 8 |
| 16 | Law of Demeter | 9 |
| 17 | Tell, Don't Ask | 9 |
| 18 | Cohesion / Coupling | 9 |
| 19 | Composition over Inheritance | 10 |
| 20 | Program to an Interface | 12 |
| 21 | Encapsulate What Varies | 8 |
| 22 | YAGNI | 10 |
| 23 | Immutability / Value Objects | 11 |
| 24 | Repository Pattern | 7 |
| 25 | Singleton | 12 |
| 26 | Factory Pattern | 5 |
| 27 | Observer Pattern | 12 |
| 28 | Strategy Pattern | 8 |
| 29 | Dependency Injection | 7 |
| 30 | Clean Code Architecture | 12 |
| 31 | Raw PHP vs Laravel | 13 |

---

## Part 2 — System Requirements & Feature Plan

### Purpose
A simplified order management system demonstrating production-grade OOP, tested and guarded against invalid state transitions — a portfolio piece showing engineering discipline, not just syntax knowledge.

### Core Entities
- **Customer** (Regular, VIP — differ by discount strategy)
- **Product**
- **Order** (owns its own valid state transitions)
- **Payment** (multiple gateway implementations)

### Core Features
1. Customer registration with a type (Regular / VIP)
2. Product catalog (in-memory, no DB needed for this project)
3. Order creation — add products, calculate totals
4. Discount calculation, varying by customer type (via Strategy, not if/else)
5. Payment processing across multiple gateways (Stripe-style, PayPal-style, both dummy/simulated)
6. **Order state machine**: `Pending → Paid → Shipped → Cancelled`, with guarded, invalid transitions rejected
7. Order persistence via Repository pattern (in-memory or flat-file — no real DB required), with proper resource lifecycle (constructor opens, destructor closes)
8. Notifications on status change (Observer pattern — logger + notifier as separate listeners)
9. Shared logging behavior across unrelated classes via a `Loggable` trait
10. A single shared application logger instance (Singleton — deliberately included, then critiqued)
11. Automated test coverage proving business rules hold after every refactor

### Domain Invariants (business rules that must never break, tested explicitly)
- An order cannot transition from `Cancelled` to `Paid`.
- An order cannot be paid twice.
- Discounts are only ever applied through the assigned strategy, never overridden manually.
- Every state transition must be logged (ties into the Observer pattern in Phase 12).

### Non-Functional Requirements
- Raw PHP, no framework — Composer PSR-4 autoload only.
- PHPUnit test coverage for all business logic from Phase 4 onward.
- One Git commit per phase, with a clear message — the commit history itself becomes part of the portfolio story.

### High-Level Plan / Todo
- [x] Phase 0 — Project setup
- [x] Phase 1 — Naive baseline (deliberately bad)
- [x] Phase 2 — Classes, Objects, Encapsulation **+ validating constructors**
- [x] Phase 3 — Inheritance & Polymorphism
- [ ] Phase 4 — Interfaces, SRP split, **Loggable trait (multiple inheritance)** **+ first PHPUnit suite**
- [ ] Phase 5 — OCP + Factory Pattern **+ static vs instance (static factory method, static counter) + tests**
- [ ] Phase 6 — LSP + Access Modifiers **+ overriding vs overloading + substitutability tests**
- [ ] Phase 7 — ISP (bloated interface → split), DIP + Repository + DI **+ constructor/destructor resource handling + tests using a fake, focused repository**
- [ ] Phase 8 — DRY (create duplication → feel the pain → extract it) + Strategy Pattern **+ tests + regression test**
- [ ] Phase 9 — Law of Demeter + Tell Don't Ask **+ state guardrails + DomainException tests**
- [ ] Phase 10 — Composition over Inheritance + YAGNI audit **+ regression test run**
- [ ] Phase 11 — Immutability / Money Value Object **+ immutability tests**
- [ ] Phase 12 — Observer Pattern + **Singleton (with honest trade-off critique)** + Clean Architecture pass **+ guarded-transition event tests**
- [ ] Phase 13 — Reflection, case-study README, final test coverage review

---

## Part 3 — Phase-by-Phase Execution Guide

### Phase 0 — Setup (30 min)
- [x] Create `src/`, `tests/`, `README.md`, `composer.json` (PSR-4 autoload).
- [x] Add PHPUnit via Composer (`composer require --dev phpunit/phpunit`).
- [x] Empty `README.md` — one entry per phase becomes your case study.

---

### Phase 1 — The Naive Version (deliberately bad)
**Topics:** none yet — this is the baseline you'll spend the guide fixing.

**Task:** One class, `OrderProcessor.php`, doing everything — total calculation with hardcoded if/else discounts, if/else payment handling, saving to a flat array, echoing confirmation directly. Don't clean it up.

**Claude Code prompt:** *"List every distinct responsibility this class currently handles."*

- [x] Phase 1 done. README entry: what feels wrong already.

---

### Phase 2 — Classes, Objects, Encapsulation, Constructors
**Topics:** 1, 2, 3 (part), 8 (part — constructors)

**Task:** Extract `Order`, `Product`, `Customer` as real classes with private properties, getters, and **explicit constructors** that validate input on creation (e.g., reject a negative price in `Product`'s constructor). This is the deliberate part — don't just let PHP auto-populate properties, write the constructor logic yourself so you feel why it matters.

**Claude Code prompt:** *"Any properties still exposed that should be private? Does every constructor actually validate its inputs, or just assign them blindly?"*

- [x] Phase 2 done.

---

### Phase 3 — Inheritance & Polymorphism
**Topics:** 3 (full)

**Task:** `RegularCustomer` and `VipCustomer` extending `Customer`, each with different `getDiscountRate()`.

**Claude Code prompt:** *"Ask me three questions testing whether I understand why this is polymorphism."*

- [x] Phase 3 done.

---

### Phase 4 — Interfaces, SRP Split, Traits — First Test Suite
**Topics:** 4, 6, 10

**Task:**
- `PaymentMethod` interface.
- Split `OrderProcessor` into `Order`, `OrderRepository`, `OrderNotifier`.
- **Create a `Loggable` trait** (e.g. a `log(string $message)` method) and `use` it in both `OrderRepository` and `OrderNotifier`. This is genuine PHP multiple-inheritance-style code reuse — two unrelated classes sharing real behavior without a shared parent class.
- **Write your first PHPUnit tests**: verify order total calculation and notification content are unchanged after the split. This is your first proof that refactoring preserved behavior.

**Claude Code prompt:** *"Does each new class still have exactly one responsibility? Is the Loggable trait actually being reused, or would a shared parent class have worked just as well here, and why is the trait the better choice? Do my new tests actually verify behavior, or just that code runs?"*

- [ ] Phase 4 done. Tests passing.

---

### Phase 5 — Open/Closed Principle + Factory Pattern + Static vs Instance
**Topics:** 7, 11, 26

**Task:** Replace if/else payment handling with `StripeGateway`/`PaypalGateway` implementing `PaymentMethod`, built via `PaymentGatewayFactory`. Make the factory's `create()` method **static** (`PaymentGatewayFactory::create('stripe')`), and add a small **instance** counter property on `Order` (e.g., `private static int $totalOrdersCreated = 0;`, incremented in the constructor) so you have both a static method and a static property to compare against your instance methods/properties in the same phase.

**Test task:** Write a test proving you can add a third gateway (e.g. `SSLCommerzGateway`) by adding a new class only — zero existing test should need modification. This *is* the OCP proof. Also test that `$totalOrdersCreated` actually increments across separate `Order` instances, proving it's shared, not per-object.

**Claude Code prompt:** *"If I add a new payment method, which files change? Is that list too long? Separately, explain why the factory method is static but an individual gateway's pay() method isn't — what would break if I made pay() static too?"*

- [ ] Phase 5 done. Tests passing.

---

### Phase 6 — Liskov Substitution + Access Modifiers + Overriding vs Overloading
**Topics:** 5, 9, 12

**Task:** Deliberately break LSP first (make `VipCustomer` throw on a base method). Notice it's wrong, fix it — this fix *is* proper method **overriding** (`VipCustomer` legitimately redefining `Customer`'s method with compatible behavior, not a broken one). While you're in the `Customer` hierarchy, also try to add a second `getDiscountRate()` method with different parameters — PHP will error, proving **overloading doesn't exist natively** here; refactor that attempt into a single method with a default parameter instead. Separately, do a full pass over every class so far and correct any `public` that should be `protected` or `private`.

**Test task:** Write ONE shared test suite that runs against both `RegularCustomer` and `VipCustomer` through the `Customer` type — proving substitutability directly, not just by inspection.

**Claude Code prompt:** *"Can VipCustomer safely replace Customer anywhere, tested against my actual code? Separately, review every class's access modifiers — anything public that shouldn't be?"*

- [ ] Phase 6 done. Shared substitution test passing for both subclasses.

---

### Phase 7 — ISP + DIP + Repository Pattern + Dependency Injection + Destructor
**Topics:** 8 (part — destructor), 13, 14, 24, 29

**Task (ISP — naive first):** Before splitting anything, write ONE bloated `OrderRepositoryInterface` that forces in unrelated methods no flat-file repository actually needs cleanly — e.g. `save()`, `find()`, AND `generateMonthlyReport()`, `emailAdminOnFailure()`. Implement it, notice how awkward and irrelevant some methods feel in a simple repository. Then split it into focused interfaces (`OrderRepositoryInterface` for save/find only; move reporting/notification concerns elsewhere). This is ISP's actual lesson — you have to feel the bloat before splitting it means anything.

**Task (DIP + Repository):** `Order` depends on your now-focused `OrderRepositoryInterface`, manually wired (no framework container).

**Task (Destructor):** Make your concrete flat-file `OrderRepository` open a file handle in its **constructor** and add a `__destruct()` method that closes/flushes it — a genuine, honest use for a destructor (resource cleanup), not a contrived example.

**Test task:** Write a **fake/in-memory implementation** of the now-focused `OrderRepositoryInterface` used only in tests. This is the real payoff of DIP — your tests never touch real storage. (The fake repository also conveniently has no file handle to manage, which is worth noticing.)

**Claude Code prompt:** *"Before I split it — is my bloated interface actually a realistic ISP violation, or am I forcing an example? After splitting, does anything still depend on the fat interface instead of the focused one? Separately, is my destructor actually necessary here, or would explicit cleanup be safer than relying on PHP's garbage collector?"*

- [ ] Phase 7 done. Tests run against the fake, focused repository only.

---

### Phase 8 — DRY + Encapsulate What Varies + Strategy Pattern
**Topics:** 15, 21, 28

**Task (DRY — create the problem first):** Before fixing anything, deliberately duplicate a calculation — write a "loyalty bonus" or "bulk-order bonus" formula copy-pasted separately inside both `RegularCustomer` and `VipCustomer` (slightly different values is fine, the duplication is the point). Run it, confirm it works. Now change the formula in one copy and notice the other silently stays wrong — that's the actual cost of duplication, not just an abstract warning.

**Task (fix — Strategy + Encapsulate What Varies):** Extract that logic, plus your existing discount-rate logic, into a `DiscountStrategy` interface with concrete strategy classes, injected into `Customer`. One formula, one place.

**Test task:** One isolated test per strategy — proving each discount calculates correctly independent of the rest of the system. Add a specific regression test that would have caught the Phase 8 "changed one copy, forgot the other" bug if it had shipped.

**Claude Code prompt:** *"Before I refactor — is my duplicated loyalty bonus a realistic example of DRY violation, or too contrived? After extracting it into a Strategy, is discount logic duplicated anywhere else I haven't noticed?"*

- [ ] Phase 8 done. Tests passing per strategy, including the regression test.

---

### Phase 9 — Law of Demeter + Tell Don't Ask + State Guardrails
**Topics:** 16, 17, 18

**Task (Law of Demeter/Tell Don't Ask):** Find and fix any `->`-chain reaching through objects. Move any external decision-making into the object itself.

**Task (state guardrails — new addition):** Give `Order` an explicit status (`Pending`, `Paid`, `Shipped`, `Cancelled`) and enforce valid transitions **inside** the `Order` class itself:

```php
final class Order
{
    private OrderStatus $status;

    public function markAsPaid(): void
    {
        if ($this->status === OrderStatus::Cancelled) {
            throw new DomainException('Cannot pay a cancelled order.');
        }
        if ($this->status === OrderStatus::Paid) {
            throw new DomainException('Order has already been paid.');
        }
        $this->status = OrderStatus::Paid;
    }
}
```

This is Tell Don't Ask and Encapsulation working together to protect a real business invariant — structurally the same problem as a concurrency-safe booking system, just without threads.

**Test task:** Write tests explicitly proving `Cancelled → Paid` throws `DomainException`, and that paying twice throws too.

**Claude Code prompt:** *"Find every `->` chain longer than one call. For each, does it violate Law of Demeter? Separately, can you find any way to force Order into an invalid state despite my guards?"*

- [ ] Phase 9 done. Invalid-transition tests passing.

---

### Phase 10 — Composition over Inheritance + YAGNI Audit
**Topics:** 19, 22

**Task:** Reconsider `Customer` → `VipCustomer` inheritance now that discounts are a Strategy — does composition make more sense? Refactor if so. Audit for unused "just in case" interfaces and delete them.

**Test task:** Re-run your **entire existing test suite** after this refactor. If everything still passes with no test changes needed, that's proof the refactor was behavior-preserving — the actual point of having tests at all.

**Claude Code prompt:** *"Argue both sides: keep VipCustomer as a subclass, or replace with composition. Don't pick for me."*

- [ ] Phase 10 done. Full suite still green.

---

### Phase 11 — Immutability / Value Objects
**Topics:** 23

**Task:** Immutable `Money` value object (`add()`/`subtract()` return new instances) replacing raw floats.

**Test task:** Explicitly test that calling `$money->add($other)` does **not** mutate the original `$money` instance — this is the actual behavior immutability promises, so test it directly, don't assume it.

**Claude Code prompt:** *"Find every place still using a raw float for money instead of Money."*

- [ ] Phase 11 done. Immutability test passing.

---

### Phase 12 — Observer Pattern + Singleton + Guarded Events + Clean Architecture Pass
**Topics:** 20, 25, 27, 30

**Task:** `OrderStatusChanged` event, at least two listeners (logger + notifier). Wire it so the event **only fires on a successful, guarded transition** from Phase 9 — an invalid transition attempt should never reach a listener. Refactor your `Logger` (from the `Loggable` trait's destination, if you gave it one, or a new simple logger) into a **Singleton** — one shared instance across the whole app, accessed via `Logger::getInstance()`.

**Then critique it honestly:** Singleton is a real, documented pattern, but it's also widely considered a modern anti-pattern because it introduces global state and makes testing harder (your fake repository trick from Phase 7 gets awkward with a Singleton in the mix). Write two lines in your README: when Singleton is actually justified here, and what you'd do instead in a framework context (Laravel's service container gives you a shared instance without the global-state problem — this is worth connecting back to Phase 13).

**Test task:** Test that listeners fire exactly once on a valid transition, never on a rejected one. Separately, try to test the Singleton Logger in isolation — notice if it's harder to test than your other classes, and write down why.

**Claude Code prompt:** *"Full Clean Code Architecture review — are business logic, data access, and notifications still properly separated after everything? Separately, push back on my Singleton Logger — is it actually earning its place, or would simple dependency injection have been better here too?"*

- [ ] Phase 12 done. Event tests passing, including the "no event on rejected transition" case, and the Singleton trade-off is written down honestly.

---

### Phase 13 — Raw PHP vs Laravel Reflection + Final Deliverable
**Topics:** 31, full consolidation

**Task:**
- Write a reflection: which parts would Laravel give you for free, and which did you now understand deeply *because* you built them by hand?
- Finalize `README.md` as a real case study: naive version → final version → which principle fixed which specific problem → final test coverage summary.
- Record yourself explaining the whole journey out loud, 3-5 minutes, no notes.

**Claude Code prompt:** *"Read my full case study README. Ask me the three hardest questions an interviewer might ask about this project."*

- [ ] Phase 13 done. This README is your portfolio artifact.

---

## Pacing

Roughly one phase per 1-2 sessions (90-minute structure: read → find real target → implement → test → explain out loud → commit). At that pace, 4-6 weeks total — now producing a repo with a real, tested, guarded transactional core, not just a set of design-pattern demos.
