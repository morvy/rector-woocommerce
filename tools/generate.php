<?php

/*
 * Generates config/sets, level sets, set list classes and set tests from tools/deprecations.json.
 *
 * usage: php tools/generate.php
 */

const MIN        = [3, 0];
const MAX        = [11, 3];
const LAST_MINOR = [3 => 9, 4 => 9, 5 => 9, 6 => 9, 7 => 9, 8 => 9, 9 => 9, 10 => 9, 11 => 3];

$root    = dirname(__DIR__);
$entries = json_decode((string) file_get_contents(__DIR__ . '/deprecations.json'), true, flags: JSON_THROW_ON_ERROR);

$versions = [];

foreach (LAST_MINOR as $major => $lastMinor) {
    for ($minor = 0; $minor <= $lastMinor; $minor++) {
        $versions[] = "{$major}.{$minor}";
    }
}

$bySet = array_fill_keys($versions, []);

foreach ($entries as $entry) {
    if (!isset($bySet[$entry['set']])) {
        fwrite(STDERR, "Unknown set {$entry['set']}\n");

        exit(1);
    }

    $bySet[$entry['set']][] = $entry;
}

/*
 * Keyed configs merge across sets (string keys: the last set wins) and Rector re-runs rules until nothing changes,
 * so a duplicate key silently drops a rule and a rename cycle would never terminate.
 */
$keys  = [];
$edges = [];

foreach ($entries as $entry) {
    $key = match ($entry['rule']) {
        'RenameFunction', 'RenameFunctionAsStatement', 'RenameFunctionWithArguments', 'RemoveFuncCall', 'ReturnFirstArgFunc' => 'function:' . mb_strtolower($entry['old'] ?? $entry['function']),
        'RemoveMethodCall', 'ReturnFirstArgMethod'                                                                           => $entry['rule'] . ':' . mb_strtolower($entry['method']),
        'RenameClass'                                                                                                        => 'class:' . mb_strtolower($entry['old']),
        'RenameHook'                                                                                                         => 'hook:' . $entry['old'],
        'RenameConstant'                                                                                                     => 'constant:' . $entry['old'],
        default                                                                                                              => null,
    };

    if ($key !== null) {
        if (isset($keys[$key])) {
            fwrite(STDERR, "Duplicate rule key {$key} (sets {$keys[$key]} and {$entry['set']})\n");

            exit(1);
        }

        $keys[$key] = $entry['set'];
    }

    if (in_array($entry['rule'], ['RenameFunction', 'RenameFunctionAsStatement', 'RenameClass'], true)) {
        $edges[mb_strtolower($entry['old'])] = mb_strtolower($entry['new']);
    }

    if ($entry['rule'] === 'RenameHook') {
        $edges['hook:' . $entry['old']] = 'hook:' . $entry['new'];
    }

    if ($entry['rule'] === 'RenameMethod') {
        $edges[mb_strtolower($entry['class'] . '::' . $entry['old'])] = mb_strtolower($entry['class'] . '::' . $entry['new']);
    }
}

foreach (array_keys($edges) as $start) {
    $seen = [];

    for ($node = $start; isset($edges[$node]); $node = $edges[$node]) {
        if (isset($seen[$node])) {
            fwrite(STDERR, "Rename cycle through {$start}\n");

            exit(1);
        }

        $seen[$node] = true;
    }
}

function q(string $value): string
{
    return var_export($value, true);
}

function lit(mixed $value): string
{
    return match (true) {
        is_array($value) && $value === []         => '[]',
        is_array($value) && array_is_list($value) => '[' . implode(', ', array_map(lit(...), $value)) . ']',
        is_array($value)                          => '[' . implode(', ', array_map(static fn ($k, $v): string => lit($k) . ' => ' . lit($v), array_keys($value), $value)) . ']',
        $value === null                           => 'null',
        default                                   => var_export($value, true),
    };
}

/**
 * Fully-qualified name as Rector prints it in fixtures.
 */
function fq(string $name): string
{
    return '\\' . $name;
}

/**
 * @param array<array<string, mixed>> $entries
 *
 * @return array{0: string, 1: string, 2: string}
 */
function render(array $entries): array
{
    $uses    = ['Rector\Config\RectorConfig' => true];
    $blocks  = [];
    $todo    = [];
    $before  = [];
    $after   = [];
    $grouped = [];

    foreach ($entries as $entry) {
        $grouped[$entry['rule']][] = $entry;
    }

    ksort($grouped);

    $i = 0;

    foreach ($grouped as $rule => $items) {
        $lines = [];

        foreach ($items as $e) {
            $var = '$wc' . $i++;

            switch ($rule) {
                case 'RenameFunction':
                    $uses['Rector\Renaming\Rector\FuncCall\RenameFunctionRector'] = true;
                    $lines[]                                                      = q($e['old']) . ' => ' . q($e['new']) . ',';
                    $before[]                                                     = "{$var} = {$e['old']}('foo');";
                    $after[]                                                      = "{$var} = {$e['new']}('foo');";

                    break;

                case 'RenameFunctionAsStatement':
                    $uses['Morvy\RectorWooCommerce\Rules\FuncCall\RenameFunctionAsStatementRector'] = true;
                    $lines[]                                                                        = q($e['old']) . ' => ' . q($e['new']) . ',';
                    $before[]                                                                       = "{$e['old']}('foo');\nif ({$e['old']}('foo')) {\n    echo 'kept';\n}";
                    $after[]                                                                        = fq($e['new']) . "('foo');\nif ({$e['old']}('foo')) {\n    echo 'kept';\n}";

                    break;

                case 'CallMapping':
                    $uses['Morvy\RectorWooCommerce\Rules\Call\CallMappingRector']  = true;
                    $uses['Morvy\RectorWooCommerce\ValueObject\CallMapping']       = true;
                    $lines[]                                                       = 'new CallMapping(' . lit($e['class']) . ', ' . q($e['name']) . ', ' . lit($e['newClass']) . ', ' . q($e['newName']) . ', ' . lit($e['arguments']) . '),';
                    $oldArgs                                                       = array_map(static fn (int $n): string => "'a{$n}'", range(0, $e['params'] - 1));
                    $newArgs                                                       = array_map(static fn ($a): string => mappedArg($a, $var), $e['arguments']);
                    $call                                                          = match (true) {
                        $e['class'] === null             => $e['name'],
                        ($e['static'] ?? false) === true => fq($e['class']) . '::' . $e['name'],
                        default                          => "{$var}->{$e['name']}",
                    };
                    $setup    = $e['class'] !== null && ($e['static'] ?? false) !== true ? "{$var} = new " . fq($e['class']) . "();\n" : '';
                    $target   = ($e['newClass'] === null ? fq($e['newName']) : fq($e['newClass']) . '::' . $e['newName']);
                    $before[] = $setup . "{$var}r = {$call}(" . implode(', ', $oldArgs) . ');';
                    $after[]  = $setup . "{$var}r = {$target}(" . implode(', ', $newArgs) . ');';

                    break;

                case 'RenameHook':
                    $uses['Morvy\RectorWooCommerce\Rules\FuncCall\RenameHookRector'] = true;
                    $lines[]                                                         = q($e['old']) . ' => ' . q($e['new']) . ',';
                    $before[]                                                        = 'add_filter(' . q($e['old']) . ", 'my_callback');";
                    $after[]                                                         = 'add_filter(' . q($e['new']) . ", 'my_callback');";

                    break;

                case 'RenameFunctionWithArguments':
                    $uses['Morvy\RectorWooCommerce\Rules\FuncCall\RenameFunctionWithArgumentsRector'] = true;
                    $uses['Morvy\RectorWooCommerce\ValueObject\FunctionRenameWithArguments']          = true;
                    $lines[]                                                                          = 'new FunctionRenameWithArguments(' . q($e['old']) . ', ' . q($e['new']) . ', ' . lit($e['arguments']) . '),';
                    $before[]                                                                         = "{$var} = {$e['old']}('foo');";
                    $after[]                                                                          = "{$var} = {$e['new']}('foo'" . implode('', array_map(static fn ($a) => ', ' . lit($a), $e['arguments'])) . ');';

                    break;

                case 'RenameMethod':
                    $uses['Morvy\RectorWooCommerce\Rules\MethodCall\RenameMethodRector'] = true;
                    $uses['Rector\Renaming\ValueObject\MethodCallRename']                = true;
                    $lines[]                                                             = 'new MethodCallRename(' . q($e['class']) . ', ' . q($e['old']) . ', ' . q($e['new']) . '),';
                    $before[]                                                            = "{$var} = new " . fq($e['class']) . "();\n{$var}->{$e['old']}('foo');";
                    $after[]                                                             = "{$var} = new " . fq($e['class']) . "();\n{$var}->{$e['new']}('foo');";

                    break;

                case 'RenameStaticMethod':
                    $uses['Rector\Renaming\Rector\StaticCall\RenameStaticMethodRector'] = true;
                    $uses['Rector\Renaming\ValueObject\RenameStaticMethod']             = true;
                    $lines[]                                                            = 'new RenameStaticMethod(' . q($e['class']) . ', ' . q($e['old']) . ', ' . q($e['newClass']) . ', ' . q($e['new']) . '),';
                    $before[]                                                           = "{$var} = " . fq($e['class']) . "::{$e['old']}('foo');";
                    $after[]                                                            = "{$var} = " . fq($e['newClass']) . "::{$e['new']}('foo');";

                    break;

                case 'FuncCallToStaticCall':
                    $uses['Rector\Transform\Rector\FuncCall\FuncCallToStaticCallRector'] = true;
                    $uses['Rector\Transform\ValueObject\FuncCallToStaticCall']           = true;
                    $lines[]                                                             = 'new FuncCallToStaticCall(' . q($e['old']) . ', ' . q($e['class']) . ', ' . q($e['method']) . '),';
                    $before[]                                                            = "{$var} = {$e['old']}('foo');";
                    $after[]                                                             = "{$var} = " . fq($e['class']) . "::{$e['method']}('foo');";

                    break;

                case 'StaticCallToFuncCall':
                    $uses['Rector\Transform\Rector\StaticCall\StaticCallToFuncCallRector'] = true;
                    $uses['Rector\Transform\ValueObject\StaticCallToFuncCall']             = true;
                    $lines[]                                                               = 'new StaticCallToFuncCall(' . q($e['class']) . ', ' . q($e['method']) . ', ' . q($e['function']) . '),';
                    $before[]                                                              = "{$var} = " . fq($e['class']) . "::{$e['method']}('foo');";
                    $after[]                                                               = "{$var} = " . fq($e['function']) . "('foo');";

                    break;

                case 'MethodCallToFuncCall':
                    $uses['Morvy\RectorWooCommerce\Rules\MethodCall\MethodCallToFuncCallRector'] = true;
                    $uses['Rector\Transform\ValueObject\MethodCallToFuncCall']                   = true;
                    $lines[]                                                                     = 'new MethodCallToFuncCall(' . q($e['class']) . ', ' . q($e['method']) . ', ' . q($e['function']) . '),';
                    $before[]                                                                    = "{$var} = new " . fq($e['class']) . "();\n{$var}->{$e['method']}('foo');";
                    $after[]                                                                     = "{$var} = new " . fq($e['class']) . "();\n" . fq($e['function']) . "('foo');";

                    break;

                case 'RemoveFuncCall':
                    $uses['Rector\Removing\Rector\FuncCall\RemoveFuncCallRector'] = true;
                    $lines[]                                                      = q($e['function']) . ',';
                    $before[]                                                     = "{$e['function']}();";

                    break;

                case 'RemoveMethodCall':
                    $uses['Morvy\RectorWooCommerce\Rules\MethodCall\RemoveMethodCallRector'] = true;
                    // RemoveMethodCallRector is keyed by method name, so one class per method name per set.
                    $lines[]  = q($e['method']) . ' => ' . q($e['class']) . ',';
                    $before[] = "{$var} = new " . fq($e['class']) . "();\n{$var}->{$e['method']}();";
                    $after[]  = "{$var} = new " . fq($e['class']) . '();';

                    break;

                case 'ReturnFirstArgFunc':
                    $uses['Morvy\RectorWooCommerce\Rules\FuncCall\ReturnFirstArgumentRector'] = true;
                    $lines[]                                                                  = q($e['function']) . ',';
                    $before[]                                                                 = "{$var} = {$e['function']}('foo');";
                    $after[]                                                                  = "{$var} = 'foo';";

                    break;

                case 'ReturnFirstArgMethod':
                    $uses['Morvy\RectorWooCommerce\Rules\MethodCall\ReturnFirstArgumentRector as MethodReturnFirstArgumentRector'] = true;
                    $lines[]                                                                                                       = q($e['method']) . ' => ' . q($e['class']) . ',';
                    $before[]                                                                                                      = "{$var} = new " . fq($e['class']) . "();\n{$var} = {$var}->{$e['method']}('foo');";
                    $after[]                                                                                                       = "{$var} = new " . fq($e['class']) . "();\n{$var} = 'foo';";

                    break;

                case 'PropertyFetchToMethodCall':
                    $uses['Morvy\RectorWooCommerce\Rules\PropertyFetch\PropertyFetchToMethodCallRector'] = true;
                    $uses['Morvy\RectorWooCommerce\ValueObject\PropertyFetchToMethodCall']               = true;
                    $lines[]                                                                             = 'new PropertyFetchToMethodCall(' . q($e['class']) . ', ' . q($e['property']) . ', ' . q($e['getMethod']) . '),';
                    $before[]                                                                            = "{$var} = new " . fq($e['class']) . "();\necho {$var}->{$e['property']};";
                    $after[]                                                                             = "{$var} = new " . fq($e['class']) . "();\necho {$var}->{$e['getMethod']}();";

                    break;

                case 'RenameClass':
                    $uses['Rector\Renaming\Rector\Name\RenameClassRector'] = true;
                    $lines[]                                               = q($e['old']) . ' => ' . q($e['new']) . ',';
                    $before[]                                              = "{$var} = new " . fq($e['old']) . '();';
                    $after[]                                               = "{$var} = new " . fq($e['new']) . '();';

                    break;

                case 'RenameClassConstFetch':
                    $uses['Rector\Renaming\Rector\ClassConstFetch\RenameClassConstFetchRector'] = true;
                    $uses['Rector\Renaming\ValueObject\RenameClassAndConstFetch']               = true;
                    $lines[]                                                                    = 'new RenameClassAndConstFetch(' . q($e['class']) . ', ' . q($e['old']) . ', ' . q($e['newClass']) . ', ' . q($e['new']) . '),';
                    $before[]                                                                   = "{$var} = " . fq($e['class']) . "::{$e['old']};";
                    $after[]                                                                    = "{$var} = " . fq($e['newClass']) . "::{$e['new']};";

                    break;

                case 'RenameConstant':
                    $uses['Rector\Renaming\Rector\ConstFetch\RenameConstantRector'] = true;
                    $lines[]                                                        = q($e['old']) . ' => ' . q($e['new']) . ',';
                    $before[]                                                       = "{$var} = {$e['old']};";
                    $after[]                                                        = "{$var} = {$e['new']};";

                    break;

                case 'todo':
                    $todo[$e['category']][] = $e['name'];

                    continue 2;

                case 'skip':
                    continue 2;

                default:
                    fwrite(STDERR, "Unknown rule {$rule}\n");

                    exit(1);
            }
        }

        if ($lines === []) {
            continue;
        }

        $ruleClass = [
            'RenameFunction'              => 'RenameFunctionRector',
            'RenameFunctionAsStatement'   => 'RenameFunctionAsStatementRector',
            'RenameFunctionWithArguments' => 'RenameFunctionWithArgumentsRector',
            'RenameHook'                  => 'RenameHookRector',
            'RenameMethod'                => 'RenameMethodRector',
            'RenameStaticMethod'          => 'RenameStaticMethodRector',
            'CallMapping'                 => 'CallMappingRector',
            'FuncCallToStaticCall'        => 'FuncCallToStaticCallRector',
            'StaticCallToFuncCall'        => 'StaticCallToFuncCallRector',
            'MethodCallToFuncCall'        => 'MethodCallToFuncCallRector',
            'RemoveFuncCall'              => 'RemoveFuncCallRector',
            'RemoveMethodCall'            => 'RemoveMethodCallRector',
            'ReturnFirstArgFunc'          => 'ReturnFirstArgumentRector',
            'ReturnFirstArgMethod'        => 'MethodReturnFirstArgumentRector',
            'PropertyFetchToMethodCall'   => 'PropertyFetchToMethodCallRector',
            'RenameClass'                 => 'RenameClassRector',
            'RenameClassConstFetch'       => 'RenameClassConstFetchRector',
            'RenameConstant'              => 'RenameConstantRector',
        ][$rule];

        sort($lines);
        $blocks[] = "    \$rectorConfig->ruleWithConfiguration({$ruleClass}::class, [\n        " . implode("\n        ", array_unique($lines)) . "\n    ]);";
    }

    if ($todo !== []) {
        ksort($todo);
        $comment = "    /*\n     * TODO: these are not handled currently\n";

        foreach ($todo as $category => $names) {
            $names = array_unique($names);
            sort($names);
            $comment .= "     *\n     * {$category}\n" . implode('', array_map(static fn ($n) => "     * - {$n}\n", $names));
        }

        $blocks[] = $comment . '     */';
    }

    $uses = array_keys($uses);
    sort($uses);

    $config = "<?php\n\n" . implode('', array_map(static fn ($u) => "use {$u};\n", $uses))
        . "\nreturn static function (RectorConfig \$rectorConfig): void {\n    \$rectorConfig->import(__DIR__ . '/../config.php');\n"
        . ($blocks === [] ? '' : "\n" . implode("\n\n", $blocks) . "\n")
        . "};\n";

    $fixture = $before === [] ? '' : "<?php\n\n" . implode("\n", $before) . "\n\n?>\n-----\n<?php\n\n" . implode("\n", $after) . ($after === [] ? '' : "\n") . "\n?>\n";

    return [$config, $fixture, ''];
}

/**
 * Expected fixture text for one CallMapping argument item, given an old call with arguments 'a0', 'a1', ...
 */
function mappedArg(mixed $item, string $receiver): string
{
    return match (true) {
        is_int($item)                      => "'a{$item}'",
        isset($item['param'])              => "'a{$item['param']}'",
        array_key_exists('literal', $item) => lit($item['literal']),
        isset($item['receiverCall'])       => "{$receiver}->{$item['receiverCall']}()",
        isset($item['receiver'])           => $receiver,
        default                            => '[' . implode(', ', array_map(static fn ($k, $v): string => lit($k) . ' => ' . mappedArg($v, $receiver), array_keys($item['array']), $item['array'])) . ']',
    };
}

function constName(string $version): string
{
    return str_replace('.', '_', $version);
}

function className(string $version): string
{
    return str_replace('.', '', $version);
}

function testClass(string $namespace, string $class, string $config, string $depth): string
{
    return <<<PHP
        <?php

        declare(strict_types=1);

        namespace Morvy\\RectorWooCommerce\\Tests\\Rector\\Sets\\{$namespace};

        use PHPUnit\\Framework\\Attributes\\DataProvider;
        use Rector\\Testing\\PHPUnit\\AbstractRectorTestCase;

        /**
         * @internal
         */
        final class {$class} extends AbstractRectorTestCase
        {
            #[DataProvider('provideCases')]
            public function test(string \$filePath): void
            {
                \$this->doTestFile(\$filePath);
            }

            public static function provideCases(): iterable
            {
                return self::yieldFilesFromDirectory(__DIR__);
            }

            public function provideConfigFilePath(): string
            {
                return __DIR__ . '{$depth}config/sets/{$config}';
            }
        }

        PHP;
}

$setConsts   = [];
$allBefore   = [];
$allAfter    = [];
$levelConsts = [];
$previous    = null;

foreach ($versions as $version) {
    [$config, $fixture] = render($bySet[$version]);
    file_put_contents("{$root}/config/sets/wc-{$version}.php", $config);

    $sets = $previous === null
        ? 'WooCommerceSetList::WC_' . constName($version)
        : 'WooCommerceSetList::WC_' . constName($version) . ', WooCommerceLevelSetList::UP_TO_WC_' . constName($previous);
    $uses = $previous === null
        ? "use Morvy\\RectorWooCommerce\\Set\\WooCommerceSetList;\n"
        : "use Morvy\\RectorWooCommerce\\Set\\WooCommerceLevelSetList;\nuse Morvy\\RectorWooCommerce\\Set\\WooCommerceSetList;\n";
    file_put_contents(
        "{$root}/config/sets/level/up-to-wc-{$version}.php",
        "<?php\n\n{$uses}use Rector\\Config\\RectorConfig;\n\nreturn static function (RectorConfig \$rectorConfig): void {\n    \$rectorConfig->sets([{$sets}]);\n};\n"
    );

    $dir = "{$root}/tests/Rector/Sets/Wc" . className($version);

    if ($fixture !== '') {
        [$fixtureBefore, $fixtureAfter] = explode("\n?>\n-----\n<?php\n", $fixture);
        $allBefore[]                    = mb_trim(mb_substr($fixtureBefore, 5));
        $allAfter[]                     = mb_trim(mb_substr($fixtureAfter, 0, -4));
        @mkdir($dir, 0o777, true);
        file_put_contents("{$dir}/test_fixture.php.inc", $fixture);
        file_put_contents("{$dir}/Wc" . className($version) . 'Test.php', testClass('Wc' . className($version), 'Wc' . className($version) . 'Test', "wc-{$version}.php", '/../../../../'));
    } elseif (is_dir($dir)) {
        array_map(unlink(...), glob("{$dir}/*") ?: []);
        rmdir($dir);
    }

    $setConsts[]   = '    public const WC_' . constName($version) . " = __DIR__ . '/../../config/sets/wc-{$version}.php';";
    $levelConsts[] = '    public const UP_TO_WC_' . constName($version) . " = __DIR__ . '/../../config/sets/level/up-to-wc-{$version}.php';";
    $previous      = $version;
}

$levelDir = "{$root}/tests/Rector/Sets/Level/UpToWc" . className($previous);
@mkdir($levelDir, 0o777, true);
file_put_contents("{$levelDir}/test_fixture.php.inc", "<?php\n\n" . implode("\n", $allBefore) . "\n\n?>\n-----\n<?php\n\n" . implode("\n", array_filter($allAfter)) . "\n\n?>\n");
file_put_contents("{$levelDir}/UpToWc" . className($previous) . 'Test.php', testClass('Level\UpToWc' . className($previous), 'UpToWc' . className($previous) . 'Test', "level/up-to-wc-{$previous}.php", '/../../../../../'));

file_put_contents("{$root}/src/Set/WooCommerceSetList.php", "<?php\n\ndeclare(strict_types=1);\n\nnamespace Morvy\\RectorWooCommerce\\Set;\n\nfinal class WooCommerceSetList\n{\n" . implode("\n", $setConsts) . "\n}\n");
file_put_contents("{$root}/src/Set/WooCommerceLevelSetList.php", "<?php\n\ndeclare(strict_types=1);\n\nnamespace Morvy\\RectorWooCommerce\\Set;\n\nfinal class WooCommerceLevelSetList\n{\n" . implode("\n", $levelConsts) . "\n}\n");

echo 'Generated ' . count($versions) . " sets\n";
