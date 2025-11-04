<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS plan GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\Tests\Unit\Logic;

use DemosEurope\DemosplanAddon\XBeteiligung\Logic\Din91379TextSanitizerService;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class Din91379TextSanitizerServiceTest extends TestCase
{
    private Din91379TextSanitizerService $sut;
    private LoggerInterface $logger;

    protected function setUp(): void
    {
        parent::setUp();

        $this->logger = $this->createMock(LoggerInterface::class);
        $this->sut = new Din91379TextSanitizerService($this->logger);
    }

    public function testSanitizeNullReturnsEmptyString(): void
    {
        // Act
        $result = $this->sut->sanitize(null);

        // Assert
        self::assertSame('', $result);
    }

    public function testSanitizeEmptyStringReturnsEmptyString(): void
    {
        // Act
        $result = $this->sut->sanitize('');

        // Assert
        self::assertSame('', $result);
    }

    public function testSanitizeBasicAsciiRemainsUnchanged(): void
    {
        // Arrange
        $text = 'Hello World 123 !@#$%^&*()';

        // Act
        $result = $this->sut->sanitize($text);

        // Assert
        self::assertSame($text, $result);
    }

    public function testSanitizeGermanUmlautsArePreserved(): void
    {
        // Arrange
        $text = 'Müller äöü ÄÖÜ ß';

        // Act
        $result = $this->sut->sanitize($text);

        // Assert
        self::assertSame($text, $result);
        self::assertStringContainsString('Müller', $result);
        self::assertStringContainsString('äöü', $result);
        self::assertStringContainsString('ÄÖÜ', $result);
        self::assertStringContainsString('ß', $result);
    }

    public function testSanitizeFrenchAccentsArePreserved(): void
    {
        // Arrange
        $text = 'café résumé naïve Élysée';

        // Act
        $result = $this->sut->sanitize($text);

        // Assert
        self::assertSame($text, $result);
        self::assertStringContainsString('café', $result);
        self::assertStringContainsString('résumé', $result);
        self::assertStringContainsString('Élysée', $result);
    }

    public function testSanitizeSpanishCharactersArePreserved(): void
    {
        // Arrange
        $text = 'España niño ñ';

        // Act
        $result = $this->sut->sanitize($text);

        // Assert
        self::assertSame($text, $result);
        self::assertStringContainsString('España', $result);
        self::assertStringContainsString('ñ', $result);
    }

    public function testSanitizeSmartQuotesAreConverted(): void
    {
        // Arrange - using Unicode escape sequences for smart quotes
        $text = "He said \u{201C}hello\u{201D} and \u{2018}goodbye\u{2019}";

        // Act
        $result = $this->sut->sanitize($text);

        // Assert
        self::assertStringNotContainsString("\u{201C}", $result); // Left double quote
        self::assertStringNotContainsString("\u{201D}", $result); // Right double quote
        self::assertStringNotContainsString("\u{2018}", $result); // Left single quote
        self::assertStringContainsString('"hello"', $result);
        self::assertStringContainsString("'goodbye'", $result);
    }

    public function testSanitizeEmDashIsConvertedToHyphen(): void
    {
        // Arrange
        $text = 'This is — an em dash';

        // Act
        $result = $this->sut->sanitize($text);

        // Assert
        self::assertStringNotContainsString('—', $result);
        self::assertStringContainsString('-', $result);
        self::assertSame('This is - an em dash', $result);
    }

    public function testSanitizeEnDashIsConvertedToHyphen(): void
    {
        // Arrange
        $text = 'Range 1–10';

        // Act
        $result = $this->sut->sanitize($text);

        // Assert
        self::assertStringNotContainsString('–', $result);
        self::assertStringContainsString('-', $result);
        self::assertSame('Range 1-10', $result);
    }

    public function testSanitizeEllipsisIsConvertedToThreeDots(): void
    {
        // Arrange
        $text = 'Wait…';

        // Act
        $result = $this->sut->sanitize($text);

        // Assert
        self::assertStringNotContainsString('…', $result);
        self::assertSame('Wait...', $result);
    }

    public function testSanitizeGreekCharactersAreRemoved(): void
    {
        // Arrange
        $text = 'Greek: αβγδε test';

        // Act
        $result = $this->sut->sanitize($text);

        // Assert
        self::assertStringNotContainsString('α', $result);
        self::assertStringNotContainsString('β', $result);
        self::assertStringNotContainsString('γ', $result);
        self::assertSame('Greek: test', $result);
    }

    public function testSanitizeCyrillicCharactersAreRemoved(): void
    {
        // Arrange
        $text = 'Cyrillic: АБВ спасибо test';

        // Act
        $result = $this->sut->sanitize($text);

        // Assert
        self::assertStringNotContainsString('А', $result);
        self::assertStringNotContainsString('Б', $result);
        self::assertStringNotContainsString('спасибо', $result);
        self::assertSame('Cyrillic: test', $result);
    }

    public function testSanitizeEuroSignIsPreserved(): void
    {
        // Arrange
        $text = 'Price: 100€';

        // Act
        $result = $this->sut->sanitize($text);

        // Assert
        self::assertStringContainsString('€', $result);
        self::assertSame('Price: 100€', $result);
    }

    public function testSanitizeMultipleSpacesAreCollapsed(): void
    {
        // Arrange
        $text = 'Multiple    spaces     here';

        // Act
        $result = $this->sut->sanitize($text);

        // Assert
        self::assertSame('Multiple spaces here', $result);
    }

    public function testSanitizeTabsAndNewlinesAreCollapsed(): void
    {
        // Arrange
        $text = "Line1\n\nLine2\t\tTab";

        // Act
        $result = $this->sut->sanitize($text);

        // Assert
        self::assertSame('Line1 Line2 Tab', $result);
    }

    public function testSanitizeUnicodeNormalization(): void
    {
        // Arrange - using decomposed form (é as e + combining acute)
        $text = "cafe\u{0301}"; // café with decomposed é

        // Act
        $result = $this->sut->sanitize($text);

        // Assert - should be normalized to composed form
        self::assertSame('café', $result);
    }

    public function testSanitizeMathematicalSymbolsAreReplaced(): void
    {
        // Arrange
        $text = '5 × 3 ÷ 2 ≤ 10 ≥ 5 ≠ 0';

        // Act
        $result = $this->sut->sanitize($text);

        // Assert
        self::assertStringNotContainsString('×', $result);
        self::assertStringNotContainsString('÷', $result);
        self::assertStringNotContainsString('≤', $result);
        self::assertStringNotContainsString('≥', $result);
        self::assertStringNotContainsString('≠', $result);
        self::assertStringContainsString('x', $result);
        self::assertStringContainsString('/', $result);
        self::assertStringContainsString('<=', $result);
        self::assertStringContainsString('>=', $result);
        self::assertStringContainsString('!=', $result);
    }

    public function testSanitizeComplexRealWorldExample(): void
    {
        // Arrange - realistic statement text with various problematic characters
        $text = "Stellungnahme zur \u{201C}Planung\u{201D}\u{2014}Das Projekt in München\u{2013}Schwabing kostet ca. 100€\u{2026}"
            . ' Wir fordern Änderungen αβγ (griechische Buchstaben)';

        // Act
        $result = $this->sut->sanitize($text);

        // Assert
        self::assertStringNotContainsString("\u{201C}", $result); // LEFT DOUBLE QUOTATION MARK removed
        self::assertStringNotContainsString("\u{201D}", $result); // RIGHT DOUBLE QUOTATION MARK removed
        self::assertStringNotContainsString("\u{2014}", $result); // EM DASH removed
        self::assertStringNotContainsString("\u{2013}", $result); // EN DASH removed
        self::assertStringNotContainsString("\u{2026}", $result); // ELLIPSIS removed
        self::assertStringNotContainsString('α', $result); // Greek removed
        self::assertStringNotContainsString('β', $result);
        self::assertStringNotContainsString('γ', $result);

        // Valid characters preserved
        self::assertStringContainsString('München', $result);
        self::assertStringContainsString('Änderungen', $result);
        self::assertStringContainsString('€', $result);
        self::assertStringContainsString('"Planung"', $result); // Converted to straight quotes
    }

    public function testSanitizeLogsWarningWhenCharactersRemoved(): void
    {
        // Arrange
        $text = 'Greek αβγ Cyrillic АБВ';

        // Expect
        $this->logger->expects(self::once())
            ->method('warning')
            ->with(
                'DIN 91379 sanitization removed invalid characters',
                self::callback(function (array $context): bool {
                    return isset($context['removed_count'])
                        && $context['removed_count'] > 0
                        && isset($context['removed_chars']);
                })
            );

        // Act
        $this->sut->sanitize($text);
    }

    public function testSanitizeDoesNotLogWhenNoCharactersRemoved(): void
    {
        // Arrange
        $text = 'Simple ASCII text';

        // Expect
        $this->logger->expects(self::never())->method('warning');

        // Act
        $this->sut->sanitize($text);
    }

    public function testIsValidReturnsTrueForValidText(): void
    {
        // Arrange
        $text = 'Valid text with Umlauts äöü and special chars €';

        // Act
        $result = $this->sut->isValid($text);

        // Assert
        self::assertTrue($result);
    }

    public function testIsValidReturnsFalseForTextWithSmartQuotes(): void
    {
        // Arrange
        $text = "Text with \u{201C}smart quotes\u{201D}";

        // Act
        $result = $this->sut->isValid($text);

        // Assert
        self::assertFalse($result);
    }

    public function testIsValidReturnsFalseForTextWithGreek(): void
    {
        // Arrange
        $text = 'Text with Greek α β γ';

        // Act
        $result = $this->sut->isValid($text);

        // Assert
        self::assertFalse($result);
    }

    public function testIsValidReturnsTrueForNull(): void
    {
        // Act
        $result = $this->sut->isValid(null);

        // Assert
        self::assertTrue($result);
    }

    public function testIsValidReturnsTrueForEmptyString(): void
    {
        // Act
        $result = $this->sut->isValid('');

        // Assert
        self::assertTrue($result);
    }

    public function testSanitizeArraySanitizesAllElements(): void
    {
        // Arrange
        $texts = [
            "Text with \u{201C}quotes\u{201D}",
            'Greek αβγ',
            'Valid text',
            'Umlaut ä',
        ];

        // Act
        $result = $this->sut->sanitizeArray($texts);

        // Assert
        self::assertCount(4, $result);
        self::assertStringNotContainsString("\u{201C}", $result[0]); // Unicode smart quote removed
        self::assertStringNotContainsString("\u{201D}", $result[0]); // Unicode smart quote removed
        self::assertStringContainsString('"', $result[0]); // Converted to regular quotes
        self::assertStringNotContainsString('α', $result[1]);
        self::assertSame('Valid text', $result[2]);
        self::assertStringContainsString('ä', $result[3]);
    }

    public function testSanitizePreservesAllowedSpecialCharacters(): void
    {
        // Arrange - characters that SHOULD be preserved according to datatypeC
        $text = '§ © ® ° ± ² ³ µ ¼ ½ ¾ À Á Â Ã Ä Å Æ';

        // Act
        $result = $this->sut->sanitize($text);

        // Assert
        self::assertSame($text, $result);
    }

    public function testSanitizeHandlesEmojis(): void
    {
        // Arrange
        $text = 'Text with emoji 😀 and more 🎉';

        // Act
        $result = $this->sut->sanitize($text);

        // Assert - emojis should be removed
        self::assertStringNotContainsString('😀', $result);
        self::assertStringNotContainsString('🎉', $result);
        self::assertSame('Text with emoji and more', $result);
    }

    public function testSanitizeHandlesCJKCharacters(): void
    {
        // Arrange
        $text = 'Chinese: 你好 Japanese: こんにちは';

        // Act
        $result = $this->sut->sanitize($text);

        // Assert - CJK characters should be removed
        self::assertStringNotContainsString('你好', $result);
        self::assertStringNotContainsString('こんにちは', $result);
        self::assertSame('Chinese: Japanese:', $result);
    }

    public function testSanitizeHandlesArabicCharacters(): void
    {
        // Arrange
        $text = 'Arabic: مرحبا test';

        // Act
        $result = $this->sut->sanitize($text);

        // Assert - Arabic should be removed
        self::assertStringNotContainsString('مرحبا', $result);
        self::assertSame('Arabic: test', $result);
    }

    public function testSanitizeHandlesMixedValidAndInvalidCharacters(): void
    {
        // Arrange
        $text = 'Valid: Müller, Invalid: αβγ, Valid: café, Invalid: 你好';

        // Act
        $result = $this->sut->sanitize($text);

        // Assert
        self::assertStringContainsString('Müller', $result);
        self::assertStringContainsString('café', $result);
        self::assertStringNotContainsString('αβγ', $result);
        self::assertStringNotContainsString('你好', $result);
    }

    public function testSanitizePreservesLineBreaksAndTabs(): void
    {
        // Arrange - tabs and line breaks ARE allowed in datatypeC
        $text = "Line1\nLine2\tTabbed";

        // Act
        $result = $this->sut->sanitize($text);

        // Assert - whitespace should be collapsed
        self::assertSame('Line1 Line2 Tabbed', $result);
    }

    public function testSanitizeHandlesVeryLongText(): void
    {
        // Arrange - test performance with large text
        $text = str_repeat('Valid text with Umlauts äöü. ', 1000);

        // Act
        $result = $this->sut->sanitize($text);

        // Assert
        self::assertStringContainsString('Umlauts', $result);
        self::assertStringContainsString('äöü', $result);
    }

    public function testSanitizeHandlesOnlyInvalidCharacters(): void
    {
        // Arrange
        $text = 'αβγδε'; // Only Greek

        // Act
        $result = $this->sut->sanitize($text);

        // Assert - should return empty string after whitespace collapse
        self::assertSame('', $result);
    }

    public function testSanitizeHandlesBulletPoints(): void
    {
        // Arrange
        $text = '• Point 1\n· Point 2\n‣ Point 3';

        // Act
        $result = $this->sut->sanitize($text);

        // Assert
        self::assertStringNotContainsString('•', $result);
        self::assertStringContainsString('Point 1', $result);
        self::assertStringContainsString('Point 2', $result);
        self::assertStringContainsString('Point 3', $result);
    }
}
