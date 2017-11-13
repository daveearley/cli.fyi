<?php

namespace CliFyi\Service;

/**
 * UUID class
 *
 * The following class generates VALID RFC 4122 COMPLIANT
 * Universally Unique IDentifiers (UUID) version 3, 4 and 5.
 *
 * UUIDs generated validates using OSSP UUID Tool, and output
 * for named-based UUIDs are exactly the same. This is a pure
 * PHP implementation.
 *
 * @author Andrew Moore
 * @link http://www.php.net/manual/en/function.uniqid.php#94959
 */
class UuidGenerator
{
    const DEFAULT_NAMESPACE = '1546058f-5a25-4334-85ae-e68f2a44bbaf';

    /**
     * Generate v5 UUID
     *
     * Version 5 UUIDs are named based. They require a namespace (another
     * valid UUID) and a value (the name). Given the same namespace and
     * name, the output is always the same.
     *
     * @param string $name
     * @param string $namespace
     *
     * @return bool|string
     */
    public function v5(string $name, string $namespace = self::DEFAULT_NAMESPACE)
    {
        if (!self::is_valid($namespace)) {
            return false;
        }
        // Get hexadecimal components of namespace
        $nhex = str_replace(['-', '{', '}'], '', $namespace);
        // Binary Value
        $nstr = '';
        // Convert Namespace UUID to bits
        for ($i = 0; $i < strlen($nhex); $i += 2) {
            $nstr .= chr(hexdec($nhex[$i] . $nhex[$i + 1]));
        }
        // Calculate hash value
        $hash = sha1($nstr . $name);
        return sprintf(
            '%08s-%04s-%04x-%04x-%12s',
            // 32 bits for "time_low"
            substr($hash, 0, 8),
            // 16 bits for "time_mid"
            substr($hash, 8, 4),
            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 5
            (hexdec(substr($hash, 12, 4)) & 0x0fff) | 0x5000,
            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            (hexdec(substr($hash, 16, 4)) & 0x3fff) | 0x8000,
            // 48 bits for "node"
            substr($hash, 20, 12)
        );
    }

    public static function is_valid($uuid)
    {
        return preg_match('/^\{?[0-9a-f]{8}\-?[0-9a-f]{4}\-?[0-9a-f]{4}\-?' .
                '[0-9a-f]{4}\-?[0-9a-f]{12}\}?$/i', $uuid) === 1;
    }
}
