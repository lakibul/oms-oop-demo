<?php

require __DIR__ . '/vendor/autoload.php';

use App\Order;
use App\Customer;
use App\RegularCustomer;
use App\VipCustomer;

/**
 * Captures a block's echo output as plain text, then renders it as a
 * styled terminal window with simple line-based highlighting.
 */
function terminal(callable $block): void
{
    ob_start();
    $block();
    $raw = ob_get_clean();

    $lines = explode("\n", rtrim($raw, "\n"));
    $html  = [];

    foreach ($lines as $line) {
        $escaped = htmlspecialchars($line, ENT_QUOTES);
        $class   = 'line';

        if (preg_match('/rejected|blocked/i', $line)) {
            $class = 'line line--bad';
        } elseif (preg_match('/created order ok|discount rate|^total:/i', $line)) {
            $class = 'line line--good';
        }

        $html[] = "<span class=\"{$class}\">{$escaped}</span>";
    }

    echo '<div class="terminal">';
    echo '<div class="terminal__bar"><span></span><span></span><span></span></div>';
    echo '<pre class="terminal__body">' . implode("\n", $html) . '</pre>';
    echo '</div>';
}

$phases = [
    ['n' => 0,  'label' => 'Setup',               'done' => true],
    ['n' => 1,  'label' => 'Naive Baseline',      'done' => true],
    ['n' => 2,  'label' => 'Encapsulation',       'done' => true],
    ['n' => 3,  'label' => 'Inheritance',         'done' => true],
    ['n' => 4,  'label' => 'SRP + Traits',        'done' => false],
    ['n' => 5,  'label' => 'OCP + Factory',       'done' => false],
    ['n' => 6,  'label' => 'LSP',                 'done' => false],
    ['n' => 7,  'label' => 'DIP + Repository',    'done' => false],
    ['n' => 8,  'label' => 'DRY + Strategy',      'done' => false],
    ['n' => 9,  'label' => 'Demeter',             'done' => false],
    ['n' => 10, 'label' => 'Composition',         'done' => false],
    ['n' => 11, 'label' => 'Value Objects',       'done' => false],
    ['n' => 12, 'label' => 'Observer',            'done' => false],
    ['n' => 13, 'label' => 'Reflection',          'done' => false],
];

?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Mini OMS — OOP Case Study</title>
<style>
    :root {
        --bg: #0f1115;
        --panel: #15181f;
        --panel-border: #262b35;
        --ink: #e4e7ee;
        --ink-dim: #9aa3b2;
        --accent: #6ee7b7;
        --accent-strong: #34d399;
        --bad: #f87171;
        --good: #6ee7b7;
        --card: #1a1e27;
    }
    * { box-sizing: border-box; }
    body {
        margin: 0;
        background: radial-gradient(1200px 600px at 50% -10%, #1b2030, var(--bg) 60%);
        color: var(--ink);
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Inter, sans-serif;
        line-height: 1.6;
    }
    main { max-width: 880px; margin: 0 auto; padding: 3rem 1.25rem 5rem; }

    .hero { text-align: center; margin-bottom: 2.5rem; }
    .hero__kicker {
        display: inline-block; font-size: 0.75rem; letter-spacing: 0.12em; text-transform: uppercase;
        color: var(--accent); background: rgba(110, 231, 183, 0.1); border: 1px solid rgba(110,231,183,0.3);
        padding: 0.25rem 0.75rem; border-radius: 999px; margin-bottom: 1rem;
    }
    .hero h1 { font-size: 2rem; margin: 0 0 0.5rem; }
    .hero p { color: var(--ink-dim); max-width: 560px; margin: 0 auto; }

    .tracker {
        display: flex; flex-wrap: wrap; gap: 0.5rem; justify-content: center;
        margin: 2rem 0 3rem; padding: 1rem; background: var(--panel);
        border: 1px solid var(--panel-border); border-radius: 12px;
    }
    .step {
        display: flex; align-items: center; gap: 0.4rem; font-size: 0.78rem;
        color: var(--ink-dim); padding: 0.3rem 0.6rem; border-radius: 999px;
    }
    .step--done { color: var(--bg); background: var(--accent); font-weight: 600; }
    .step__dot { width: 6px; height: 6px; border-radius: 50%; background: currentColor; }

    .phase {
        background: var(--card); border: 1px solid var(--panel-border);
        border-radius: 14px; padding: 1.75rem 1.75rem 1.5rem; margin-bottom: 2rem;
        position: relative;
    }
    .phase__badge {
        position: absolute; top: -14px; left: 1.75rem;
        background: var(--accent-strong); color: #04281c; font-weight: 700;
        font-size: 0.8rem; padding: 0.25rem 0.7rem; border-radius: 999px;
        box-shadow: 0 2px 8px rgba(52, 211, 153, 0.35);
    }
    .phase h2 { margin: 0.75rem 0 0.25rem; font-size: 1.3rem; }
    .phase__tag { color: var(--ink-dim); font-size: 0.85rem; margin-bottom: 1rem; }
    .phase__tag strong { color: var(--accent); font-weight: 600; }

    .note {
        display: flex; gap: 0.75rem; background: rgba(255,255,255,0.03);
        border: 1px solid var(--panel-border); border-radius: 10px;
        padding: 0.9rem 1.1rem; margin-bottom: 1.25rem; font-size: 0.92rem; color: #c7cdda;
    }
    .note__icon { flex-shrink: 0; font-size: 1.1rem; }

    h3.sub { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.08em; color: var(--ink-dim); margin: 1.25rem 0 0.5rem; }

    .terminal { border-radius: 10px; overflow: hidden; border: 1px solid #000; margin-bottom: 0.25rem; }
    .terminal__bar { background: #21242c; padding: 0.55rem 0.75rem; display: flex; gap: 6px; }
    .terminal__bar span { width: 10px; height: 10px; border-radius: 50%; background: #3a3f4b; }
    .terminal__bar span:nth-child(1) { background: #ff5f57; }
    .terminal__bar span:nth-child(2) { background: #febc2e; }
    .terminal__bar span:nth-child(3) { background: #28c840; }
    .terminal__body {
        background: #0b0d12; margin: 0; padding: 1rem 1.1rem; overflow-x: auto;
        font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace;
        font-size: 0.85rem;
    }
    .line { display: block; color: #d7dce5; }
    .line--good { color: var(--good); }
    .line--bad { color: var(--bad); }

    code { background: rgba(255,255,255,0.08); padding: 0.1rem 0.4rem; border-radius: 4px; font-size: 0.88em; }

    footer { text-align: center; color: var(--ink-dim); font-size: 0.85rem; margin-top: 3rem; }
    footer a { color: var(--accent); text-decoration: none; }
</style>
</head>
<body>
<main>

    <div class="hero">
        <span class="hero__kicker">Raw PHP · No Framework</span>
        <h1>Mini Order Management System</h1>
        <p>A phase-by-phase OOP case study — every section below runs real code from this project
        and shows exactly which principle it's proving.</p>
    </div>

    <div class="tracker">
        <?php foreach ($phases as $p): ?>
            <span class="step <?= $p['done'] ? 'step--done' : '' ?>">
                <span class="step__dot"></span>
                <?= $p['n'] ?>. <?= htmlspecialchars($p['label']) ?>
            </span>
        <?php endforeach; ?>
    </div>

    <article class="phase">
        <span class="phase__badge">Phase 1</span>
        <h2>The Naive Baseline</h2>
        <div class="phase__tag">Concept: <strong>why OOP matters</strong> — one class, five jobs</div>

        <div class="note">
            <span class="note__icon">💬</span>
            <span><code>Order</code> still calculates totals, handles payment, decrements stock,
            "persists" data, and sends a notification — all inside one class. It works, but changing
            payment logic risks breaking totals, purely because they share a file.</span>
        </div>

        <?php terminal(function () {
            $regularOrder = new Order(
                "John Smith",
                "Regular",
                [
                    ['name' => 'Widget', 'price' => 20.0, 'quantity' => 3],
                    ['name' => 'Gadget', 'price' => 50.0, 'quantity' => 1],
                ],
                "PayPal"
            );

            echo "Total: " . $regularOrder->calculateTotal() . "\n";
            echo $regularOrder->payment() . "\n";
            $regularOrder->stock();
            $regularOrder->persistence();
            $regularOrder->notification();
            echo $regularOrder->status() . "\n";
        }); ?>
    </article>

    <article class="phase">
        <span class="phase__badge">Phase 2</span>
        <h2>Encapsulation &amp; Validating Constructors</h2>
        <div class="phase__tag">Concept: <strong>private state + guarded creation</strong></div>

        <div class="note">
            <span class="note__icon">🔒</span>
            <span><code>Order</code> and <code>Customer</code> keep every property <code>private</code> —
            nothing outside the class can reach in and overwrite state directly. Their constructors
            also reject bad input instead of silently accepting it.</span>
        </div>

        <h3 class="sub">Valid input</h3>
        <?php terminal(function () {
            $validOrder = new Order("Jane Doe", "VIP", [['name' => 'Gizmo', 'price' => 15.0, 'quantity' => 2]], "Credit Card");
            echo "Created order OK. Total: " . $validOrder->calculateTotal() . "\n";
        }); ?>

        <h3 class="sub">Invalid input, rejected at construction</h3>
        <?php terminal(function () {
            $badInputs = [
                'Empty customer name'   => fn() => new Order('', 'Regular', [['name' => 'X', 'price' => 5, 'quantity' => 1]], 'PayPal'),
                'Empty product list'    => fn() => new Order('John', 'Regular', [], 'PayPal'),
                'Negative price'        => fn() => new Order('John', 'Regular', [['name' => 'X', 'price' => -5, 'quantity' => 1]], 'PayPal'),
                'Zero quantity'         => fn() => new Order('John', 'Regular', [['name' => 'X', 'price' => 5, 'quantity' => 0]], 'PayPal'),
                'Empty name (Customer)' => fn() => new RegularCustomer(''),
            ];

            foreach ($badInputs as $label => $attempt) {
                try {
                    $attempt();
                    echo "{$label}: no exception (unexpected)\n";
                } catch (\InvalidArgumentException $e) {
                    echo "{$label}: rejected — {$e->getMessage()}\n";
                }
            }
        }); ?>
    </article>

    <article class="phase">
        <span class="phase__badge">Phase 3</span>
        <h2>Inheritance &amp; Polymorphism</h2>
        <div class="phase__tag">Concept: <strong>one call, different behavior per subclass</strong></div>

        <div class="note">
            <span class="note__icon">🧬</span>
            <span><code>Customer</code> is <code>abstract</code> and declares <code>getDiscount()</code>
            with no body. <code>RegularCustomer</code> and <code>VipCustomer</code> each supply their own
            version. Code written only against the <code>Customer</code> type still gets the correct,
            different answer depending on the real object handed to it.</span>
        </div>

        <h3 class="sub">Same function, resolved per subclass</h3>
        <?php terminal(function () {
            function describeDiscount(Customer $customer): string
            {
                return get_class($customer) . " → discount rate: " . $customer->getDiscount();
            }

            $customers = [
                new RegularCustomer("John Smith"),
                new VipCustomer("Alice Johnson"),
            ];

            foreach ($customers as $customer) {
                echo describeDiscount($customer) . "\n";
            }
        }); ?>

        <h3 class="sub">The abstract base class refuses direct instantiation</h3>
        <?php terminal(function () {
            try {
                new Customer("Bob", "Something");
                echo "Instantiated Customer directly (unexpected)\n";
            } catch (\Error $e) {
                echo "blocked — " . $e->getMessage() . "\n";
            }
        }); ?>
    </article>

    <footer>
        Built phase by phase — see <a href="README.md">README.md</a> for the case-study log and
        <a href="OOP_Learning_Guide_Raw_PHP_Project.md">the full guide</a>.
    </footer>

</main>
</body>
</html>
