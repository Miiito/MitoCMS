<?php
// @codingStandardsIgnoreStart
class testsetup
// @codingStandardsIgnoreEnd
{
    protected static $copyConfig = [
        'tests/codeception.yml.sample' => 'tests/codeception.yml',
        'tests/codeception/acceptance.suite.sample' => 'tests/codeception/acceptance.suite.yml',
        'config/development/common.local.example' => 'config/development/common.local.php',
        'tests/config.json.sample' => 'tests/config.json',
    ];

    public static function run($args)
    {
        if (!is_array($args) || count($args) < 3) {
            echo "Missing parameters\n";
            return;
        }

        $email = $args[1];
        $testroot = '/' . trim($args[2], '/') . '/testweb/index-test.php';
        $frontendtestroot = '/' . trim($args[2], '/') . '/tests/web/index-test.php';

        foreach (self::$copyConfig as $src => $dest) {
            if (!is_file($dest)) {
                copy($src, $dest);
            }
        }

        $contents = file_get_contents('config/development/common.local.php');

        $contents = preg_replace(
            "/'as dryrun' => \[(\s*)'email'\s*=>\s*'[^']*'/",
            "'as dryrun' => [\\1'email' => '$email'",
            $contents
        );

        file_put_contents('config/development/common.local.php', $contents);

        $contents = file_get_contents('tests/codeception.yml');

        $contents = preg_replace(
            "/c3url:.*/",
            "c3url: $testroot",
            $contents
        );

        $contents = preg_replace(
            "/test_entry_url:.*/",
            "test_entry_url: $testroot",
            $contents
        );

        file_put_contents('tests/codeception.yml', $contents);

        $contents = file_get_contents('tests/config.json');

        $contents = preg_replace(
            '/"mochaPath":.*?(,\s*$|$)/m',
            '"mochaPath": "' . $frontendtestroot . '"$1',
            $contents
        );

        $contents = preg_replace(
            '/"baseUrl":.*?(,\s*$|$)/m',
            '"baseUrl": "' . $testroot . '"$1',
            $contents
        );

        file_put_contents('tests/config.json', $contents);

    }
}

testsetup::run(isset($_SERVER['argv']) ? $_SERVER['argv'] : []);
