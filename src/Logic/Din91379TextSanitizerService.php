<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS plan GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\Logic;

use Psr\Log\LoggerInterface;
use Symfony\Component\String\AbstractUnicodeString;
use Symfony\Component\String\UnicodeString;

/**
 * Sanitizes text to comply with DIN SPEC 91379 datatypeC character set.
 *
 * DIN SPEC 91379 datatypeC allows:
 * - Tab (U+0009), Line Feed (U+000A), Carriage Return (U+000D)
 * - Printable ASCII (U+0020 to U+007E)
 * - Extended Latin characters (U+00A0 to U+00AC, U+00AE to U+017E)
 * - Additional Latin extended characters up to U+0233
 * - Specific combining characters with base characters
 * - Euro sign (€, U+20AC)
 * - Right single quotation mark (', U+2019)
 * - Double dagger (‡, U+2021)
 *
 * NOT allowed:
 * - Greek alphabet (α, β, γ, etc.)
 * - Cyrillic alphabet (А, Б, В, etc.)
 * - Smart quotes (" " ' ')
 * - Em/en dashes (— –)
 * - Asian characters (CJK, Arabic, etc.)
 */
class Din91379TextSanitizerService
{
    /**
     * Character replacements for common problematic characters.
     * Maps invalid characters to their closest valid equivalents.
     * Using Unicode escape sequences to avoid syntax errors with special characters.
     */
    private const CHAR_REPLACEMENTS = [
        // Smart quotes → straight quotes
        "\u{201C}" => '"',  // U+201C LEFT DOUBLE QUOTATION MARK
        "\u{201D}" => '"',  // U+201D RIGHT DOUBLE QUOTATION MARK
        "\u{201E}" => '"',  // U+201E DOUBLE LOW-9 QUOTATION MARK
        "\u{201F}" => '"',  // U+201F DOUBLE HIGH-REVERSED-9 QUOTATION MARK
        "\u{2018}" => "'",  // U+2018 LEFT SINGLE QUOTATION MARK (not in datatypeC)
        "\u{2019}" => "'",  // U+2019 RIGHT SINGLE QUOTATION MARK (already allowed in datatypeC!)
        "\u{201A}" => "'",  // U+201A SINGLE LOW-9 QUOTATION MARK
        "\u{201B}" => "'",  // U+201B SINGLE HIGH-REVERSED-9 QUOTATION MARK

        // Dashes → hyphen-minus
        "\u{2014}" => '-',  // U+2014 EM DASH
        "\u{2013}" => '-',  // U+2013 EN DASH
        "\u{2012}" => '-',  // U+2012 FIGURE DASH
        "\u{2015}" => '-',  // U+2015 HORIZONTAL BAR

        // Ellipsis → three dots
        "\u{2026}" => '...',  // U+2026 HORIZONTAL ELLIPSIS

        // Mathematical/special symbols → alternatives
        "\u{00D7}" => 'x',  // U+00D7 MULTIPLICATION SIGN
        "\u{00F7}" => '/',  // U+00F7 DIVISION SIGN
        "\u{2264}" => '<=', // U+2264 LESS-THAN OR EQUAL TO
        "\u{2265}" => '>=', // U+2265 GREATER-THAN OR EQUAL TO
        "\u{2260}" => '!=', // U+2260 NOT EQUAL TO
        "\u{221E}" => 'inf', // U+221E INFINITY

        // Bullet points → hyphen
        "\u{2022}" => '-',  // U+2022 BULLET
        "\u{00B7}" => '-',  // U+00B7 MIDDLE DOT (actually allowed in datatypeC!)
        "\u{2023}" => '-',  // U+2023 TRIANGULAR BULLET

        // Spaces (normalize to regular space)
        "\u{00A0}" => ' ',  // U+00A0 NO-BREAK SPACE (actually allowed in datatypeC!)
        "\u{2009}" => ' ',  // U+2009 THIN SPACE
        "\u{2003}" => ' ',  // U+2003 EM SPACE
        "\u{2002}" => ' ',  // U+2002 EN SPACE
    ];

    /**
     * Pattern for removing prohibited character ranges.
     * Includes Greek, Cyrillic, and other scripts not allowed in datatypeC.
     */
    private const PROHIBITED_SCRIPTS_PATTERN = '/[\p{Greek}\p{Cyrillic}]/u';

    /**
     * Final validation pattern based on DIN SPEC 91379 datatypeC XSD definition.
     * This is a simplified pattern; the full pattern from the XSD is extremely complex.
     *
     * Allowed ranges:
     * - \x09-\x0A: Tab, Line Feed
     * - \x0D: Carriage Return
     * - \x20-\x7E: Basic printable ASCII
     * - \xA0-\xFF: Latin-1 Supplement and Extended-A (simplified)
     * - Additional ranges for extended Latin characters
     */
    private const ALLOWED_CHARS_PATTERN = '/[^\x09\x0A\x0D\x20-\x7E\xA0-\xFF\x{0100}-\x{017E}\x{0187}-\x{0188}\x{018F}\x{0197}\x{01A0}-\x{01A1}\x{01AF}-\x{01B0}\x{01B7}\x{01CD}-\x{01DC}\x{01DE}-\x{01DF}\x{01E2}-\x{01F0}\x{01F4}-\x{01F5}\x{01F8}-\x{01FF}\x{0212}-\x{0213}\x{0218}-\x{021B}\x{021E}-\x{021F}\x{0227}-\x{0233}\x{0259}\x{0268}\x{0292}\x{02B9}-\x{02BA}\x{02BE}-\x{02BF}\x{02C8}\x{02CC}\x{1E02}-\x{1E03}\x{1E06}-\x{1E07}\x{1E0A}-\x{1E11}\x{1E1C}-\x{1E2B}\x{1E2F}-\x{1E37}\x{1E3A}-\x{1E3B}\x{1E40}-\x{1E49}\x{1E52}-\x{1E5B}\x{1E5E}-\x{1E63}\x{1E6A}-\x{1E6F}\x{1E80}-\x{1E87}\x{1E8C}-\x{1E97}\x{1E9E}\x{1EA0}-\x{1EF9}\x{2019}\x{2021}\x{20AC}]/u';

    public function __construct(
        private readonly LoggerInterface $logger
    ) {
    }

    /**
     * Sanitizes text to comply with DIN SPEC 91379 datatypeC.
     *
     * @param string|null $text Input text (may contain invalid characters)
     * @return string Sanitized text with only DIN 91379 datatypeC compliant characters
     */
    public function sanitize(?string $text): string
    {
        if (null === $text || '' === $text) {
            return '';
        }

        $originalText = $text;
        $text = new UnicodeString($text);

        // Step 1: Normalize Unicode (NFC = composed form, e.g., é as single character)
        $text = $text->normalize(UnicodeString::NFC);

        // Step 2: Replace known problematic characters using regex
        foreach (self::CHAR_REPLACEMENTS as $from => $to) {
            // Use replaceMatches with preg_quote to handle special regex characters
            $pattern = '/' . preg_quote($from, '/') . '/u';
            $text = $text->replaceMatches($pattern, $to);
        }

        // Step 3: Remove prohibited scripts (Greek, Cyrillic, etc.)
        $beforeRemoval = $text->toString();
        $text = $text->replaceMatches(self::PROHIBITED_SCRIPTS_PATTERN, '');

        // Step 4: Remove any remaining invalid characters
        $text = $text->replaceMatches(self::ALLOWED_CHARS_PATTERN, '');
        $afterRemoval = $text->toString();

        // Step 5: Normalize whitespace AFTER removing characters (collapse multiple spaces, tabs, newlines)
        $text = $text->collapseWhitespace();

        // Log if characters were removed
        if ($beforeRemoval !== $afterRemoval) {
            $removedChars = $this->identifyRemovedCharacters($beforeRemoval, $afterRemoval);
            $this->logger->warning(
                'DIN 91379 sanitization removed invalid characters',
                [
                    'original_length' => mb_strlen($originalText),
                    'final_length' => mb_strlen($afterRemoval),
                    'removed_count' => count($removedChars),
                    'removed_chars' => implode(', ', array_unique($removedChars)),
                ]
            );
        }

        return $text->toString();
    }

    /**
     * Validates if text complies with DIN SPEC 91379 datatypeC without modification.
     *
     * @param string|null $text Text to validate
     * @return bool True if text is compliant, false otherwise
     */
    public function isValid(?string $text): bool
    {
        if (null === $text || '' === $text) {
            return true;
        }

        // Sanitize and compare
        $sanitized = $this->sanitize($text);

        // Also check for whitespace normalization
        $normalized = (new UnicodeString($text))
            ->normalize(AbstractUnicodeString::NFC)
            ->collapseWhitespace()
            ->toString();

        return $sanitized === $normalized;
    }

    /**
     * Sanitizes an array of strings (useful for tags/keywords).
     *
     * @param array<string> $texts Array of strings to sanitize
     * @return array<string> Array of sanitized strings
     */
    public function sanitizeArray(array $texts): array
    {
        return array_map(
            fn (string $text): string => $this->sanitize($text),
            $texts
        );
    }

    /**
     * Identifies which characters were removed during sanitization.
     *
     * @param string $before Text before character removal
     * @param string $after Text after character removal
     * @return array<string> Array of removed characters with their unicode codepoints
     */
    private function identifyRemovedCharacters(string $before, string $after): array
    {
        $removedChars = [];
        $beforeChars = mb_str_split($before);
        $afterChars = mb_str_split($after);

        $beforeIndex = 0;
        $afterIndex = 0;

        while ($beforeIndex < count($beforeChars)) {
            $beforeChar = $beforeChars[$beforeIndex];

            if ($afterIndex < count($afterChars) && $beforeChar === $afterChars[$afterIndex]) {
                // Characters match, move both indices
                $beforeIndex++;
                $afterIndex++;
            } else {
                // Character was removed
                $codepoint = sprintf('U+%04X', mb_ord($beforeChar));
                $removedChars[] = sprintf('%s (%s)', $beforeChar, $codepoint);
                $beforeIndex++;
            }
        }

        return $removedChars;
    }
}
