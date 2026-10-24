<?php

// Extract WooCommerce deprecations from source trees into JSON.
// usage: php tools/extract.php <woocommerce-dir>... > raw.json
// Lists every wc_deprecated_function/_deprecated_function call with its enclosing function and a delegation guess,
// as raw input for classifying new entries in tools/deprecations.json.
require __DIR__ . '/../vendor/autoload.php';

use PhpParser\Node;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;
use PhpParser\NodeVisitor\NameResolver;
use PhpParser\ParserFactory;
use PhpParser\PrettyPrinter\Standard;

const DEPRECATORS = ['wc_deprecated_function', '_deprecated_function'];

final class V extends NodeVisitorAbstract
{
    public array $out         = [];
    public string $file       = '';
    private array $classStack = [];
    private array $fnStack    = [];
    private Standard $pp;

    public function __construct()
    {
        $this->pp = new Standard;
    }

    public function enterNode(Node $n): void
    {
        if ($n instanceof Node\Stmt\ClassLike) {
            $this->classStack[] = $n->namespacedName ? $n->namespacedName->toString() : null;
        }

        if ($n instanceof Node\Stmt\Function_ || $n instanceof Node\Stmt\ClassMethod) {
            $this->fnStack[] = $n;
            $this->analyse($n);
        }

        if ($n instanceof Node\Expr\Closure || $n instanceof Node\Expr\ArrowFunction) {
            $this->fnStack[] = null;
        }
    }

    public function leaveNode(Node $n): void
    {
        if ($n instanceof Node\Stmt\ClassLike) {
            array_pop($this->classStack);
        }

        if ($n instanceof Node\Stmt\Function_ || $n instanceof Node\Stmt\ClassMethod ||
            $n instanceof Node\Expr\Closure || $n instanceof Node\Expr\ArrowFunction) {
            array_pop($this->fnStack);
        }
    }

    private function str(?Node $e): ?string
    {
        if ($e === null) {
            return null;
        }

        if ($e instanceof Node\Scalar\String_) {
            return $e->value;
        }

        if ($e instanceof Node\Scalar\LNumber || $e instanceof Node\Scalar\DNumber) {
            return (string) $e->value;
        }

        return '#' . $this->pp->prettyPrintExpr($e);
    }

    private function analyse(Node $fn): void
    {
        $stmts = $fn->stmts ?? [];
        $dep   = null;
        $rest  = [];

        foreach ($stmts as $s) {
            if ($dep === null && $s instanceof Node\Stmt\Expression && $s->expr instanceof Node\Expr\FuncCall &&
                $s->expr->name instanceof Node\Name && in_array(mb_strtolower($s->expr->name->getLast()), DEPRECATORS, true)) {
                $dep = $s->expr;

                continue;
            }
            $rest[] = $s;
        }

        if ($dep === null) {
            return;
        }
        $args   = $dep->args;
        $class  = $fn instanceof Node\Stmt\ClassMethod ? end($this->classStack) : null;
        $params = array_map(fn ($p) => [
            'name'     => $p->var->name,
            'default'  => $p->default ? $this->pp->prettyPrintExpr($p->default) : null,
            'byRef'    => $p->byRef,
            'variadic' => $p->variadic,
        ], $fn->params);
        $rec = [
            'file'        => $this->file,
            'kind'        => $class === null ? 'function' : ($fn->isStatic() ? 'static' : 'method'),
            'class'       => $class ?: null,
            'name'        => $fn instanceof Node\Stmt\ClassMethod ? $fn->name->toString() : $fn->namespacedName->toString(),
            'public'      => $fn instanceof Node\Stmt\ClassMethod ? $fn->isPublic() : true,
            'label'       => $this->str($args[0]->value ?? null),
            'version'     => $this->str($args[1]->value ?? null),
            'replacement' => $this->str($args[2]->value ?? null),
            'params'      => $params,
            'body'        => array_map(fn ($s) => $this->pp->prettyPrint([$s]), $rest),
            'delegate'    => $this->delegate($rest, $fn->params),
        ];
        $this->out[] = $rec;
    }

    // Detect `return X(...$params);` / `X(...$params);` pure delegation or trivial constant return.
    private function delegate(array $rest, array $params): ?array
    {
        if (count($rest) !== 1) {
            return count($rest) === 0 ? ['type' => 'noop'] : null;
        }
        $s        = $rest[0];
        $isReturn = $s instanceof Node\Stmt\Return_;
        $e        = $isReturn ? $s->expr : ($s instanceof Node\Stmt\Expression ? $s->expr : null);

        if ($e === null) {
            return $isReturn ? ['type' => 'noop'] : null;
        }

        if ($e instanceof Node\Expr\ConstFetch) {
            return ['type' => 'const', 'value' => mb_strtolower($e->name->toString()), 'return' => $isReturn];
        }

        if ($e instanceof Node\Scalar\String_ && $e->value === '') {
            return ['type' => 'const', 'value' => "''", 'return' => $isReturn];
        }

        if ($e instanceof Node\Expr\Array_ && $e->items === []) {
            return ['type' => 'const', 'value' => '[]', 'return' => $isReturn];
        }

        if ($e instanceof Node\Expr\Variable && isset($params[0]) && $e->name === $params[0]->var->name) {
            return ['type' => 'first_arg', 'return' => $isReturn];
        }

        $target = null;

        if ($e instanceof Node\Expr\FuncCall && $e->name instanceof Node\Name) {
            $target = ['call' => 'function', 'name' => $e->name->toString()];
        } elseif ($e instanceof Node\Expr\MethodCall && $e->var instanceof Node\Expr\Variable && $e->var->name === 'this' && $e->name instanceof Node\Identifier) {
            $target = ['call' => 'this', 'name' => $e->name->toString()];
        } elseif ($e instanceof Node\Expr\StaticCall && $e->class instanceof Node\Name && $e->name instanceof Node\Identifier) {
            $target = ['call' => 'static', 'class' => $e->class->toString(), 'name' => $e->name->toString()];
        } else {
            return null;
        }
        // Args: classify each as param passthrough (index) or literal.
        $argSpec    = [];
        $paramNames = array_map(static fn ($p) => $p->var->name, $params);

        foreach ($e->args as $a) {
            if (!$a instanceof Node\Arg || $a->unpack || $a->name) {
                return null;
            }
            $v = $a->value;

            if ($v instanceof Node\Expr\Variable && is_string($v->name) && ($i = array_search($v->name, $paramNames, true)) !== false) {
                $argSpec[] = ['param' => $i];
            } elseif ($v instanceof Node\Scalar || $v instanceof Node\Expr\ConstFetch || ($v instanceof Node\Expr\Array_ && $v->items === [])) {
                $argSpec[] = ['literal' => $this->pp->prettyPrintExpr($v)];
            } else {
                return null;
            }
        }

        return ['type' => 'call', 'target' => $target, 'args' => $argSpec, 'return' => $isReturn];
    }
}

$parser = (new ParserFactory)->createForNewestSupportedVersion();
$v      = new V;
$t      = new NodeTraverser;
$t->addVisitor(new NameResolver);
$t->addVisitor($v);
$all = [];

foreach (array_slice($argv, 1) as $root) {
    $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS));

    foreach ($it as $f) {
        $p = $f->getPathname();

        if (!str_ends_with($p, '.php') || preg_match('#/(vendor|node_modules|tests?|packages|lib|assets)/#', $p)) {
            continue;
        }

        try {
            $ast = $parser->parse(file_get_contents($p));
        } catch (Throwable) {
            continue;
        }
        $v->out  = [];
        $v->file = mb_substr($p, mb_strlen($root));
        $t->traverse($ast);

        foreach ($v->out as $r) {
            $r['source'] = basename(dirname($root)) . '/' . basename($root);
            $all[]       = $r;
        }
    }
}
echo json_encode($all, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
