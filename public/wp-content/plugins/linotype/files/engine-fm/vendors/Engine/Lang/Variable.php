<?php

namespace Engine\Lang;

/**
 * Class Variable.
 *
 * Utility to work with variables.
 */
class Variable
{
    /**
     * Returns a parsable string representation of a variable.
     *
     * @param $var
     * @param array $opts
     *
     * @return mixed|string
     */
    public static function export($var, array $opts = [])
    {
        $opts = array_merge(['indent' => '', 'tab' => '    ', 'array-align' => false], $opts);
        switch (gettype($var)) {
            case 'array':
                $r = [];
                $indexed = array_keys($var) === range(0, count($var) - 1);
                $maxLength = $opts['array-align'] ?
                    max(array_map('strlen', array_map('trim', array_keys($var)))) + 2 : 0;
                foreach ($var as $key => $value) {
                    $key = str_replace("'' . \"\\0\" . '*' . \"\\0\" . ", '', self::export($key));
                    $r[] = $opts['indent'] . $opts['tab']
                        . ($indexed ? '' : str_pad($key, $maxLength) . ' => ')
                        . self::export($value, array_merge($opts, ['indent' => $opts['indent'] . $opts['tab']]));
                }

                return "[\n" . implode(",\n", $r) . "\n" . $opts['indent'] . ']';
            case 'boolean':
                return $var ? 'true' : 'false';
            case 'NULL':
                return 'null';
            default:
                return var_export($var, true);
        }
    }
}
