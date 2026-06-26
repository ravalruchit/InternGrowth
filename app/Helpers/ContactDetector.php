<?php

namespace App\Helpers;

use Illuminate\Validation\ValidationException;

class ContactDetector
{
    /**
     * Scan text for any potential contact details (emails, phone numbers, social media handles/links).
     *
     * @param string|null $text
     * @return array|null Returns details of findings, or null if clean.
     */
    public static function scan(?string $text): ?array
    {
        if (empty($text)) {
            return null;
        }

        // 1. E-mail detection (including obfuscations like [at], (at), at, [dot], (dot), dot)
        $emailRegex = '/[a-z0-9._%+-]+\s*(?:@|\[at\]|\(at\)|at)\s*[a-z0-9.-]+\s*(?:\.|\[dot\]|\(dot\)|dot)\s*[a-z]{2,8}/i';
        if (preg_match($emailRegex, $text, $matches)) {
            return [
                'type' => 'email',
                'matched' => $matches[0],
                'message' => 'Email address detected.'
            ];
        }

        // 2. Normalize text to check for obfuscated numbers (e.g. "nine eight seven")
        $normalizedText = strtolower($text);
        
        // Normalize separators for phone/number detection to prevent bypasses like "nine_eight_seven"
        $cleanedTextForNumbers = str_replace(['_', '-', '.', ','], ' ', $normalizedText);
        $cleanedTextForNumbers = preg_replace('/\s+/', ' ', $cleanedTextForNumbers);
        
        // Map word numbers to digits
        $wordMap = [
            'zero' => '0', 'one' => '1', 'two' => '2', 'three' => '3', 'four' => '4',
            'five' => '5', 'six' => '6', 'seven' => '7', 'eight' => '8', 'nine' => '9',
            'o' => '0', // people sometimes use 'o' for zero
        ];
        
        // Replace words with digits in a temporary string
        $digitizedText = $cleanedTextForNumbers;
        foreach ($wordMap as $word => $digit) {
            // Use word boundary to avoid partial replacements like "stone" -> "st1"
            $digitizedText = preg_replace('/\b' . preg_quote($word, '/') . '\b/', $digit, $digitizedText);
        }

        // Remove all whitespace and common separators in normalized and digitized versions
        $cleanOnlyDigits = preg_replace('/[^0-9]/', '', $digitizedText);
        
        // 3. Phone number detection (8+ consecutive digits)
        if (strlen($cleanOnlyDigits) >= 8) {
            return [
                'type' => 'phone',
                'message' => 'Phone number or numeric sequence detected.'
            ];
        }

        // Check for general patterns like "call me on +91..." or "message me on..."
        $callMeRegex = '/(?:call|whatsapp|contact|message|reach|msg|ping)\s+(?:me\s+)?(?:at|on|via)?\s*[\d\s\-\+\(\)]{8,}/i';
        if (preg_match($callMeRegex, $text, $matches) || preg_match($callMeRegex, $digitizedText, $matches)) {
            return [
                'type' => 'phone_phrase',
                'matched' => $matches[0],
                'message' => 'Contact request phrase detected.'
            ];
        }

        // 4. Social media profile detection (handles and links)
        $socialKeywords = [
            't.me/',
            'telegram',
            'whatsapp.com',
            'wa.me',
            'discord',
            'instagram.com',
            'linkedin.com',
            'github.com',
            'facebook.com',
            'fb.me',
            'x.com',
            'twitter.com',
        ];

        foreach ($socialKeywords as $keyword) {
            if (str_contains($normalizedText, $keyword)) {
                return [
                    'type' => 'social_link',
                    'matched' => $keyword,
                    'message' => 'Social media link or username handle reference detected.'
                ];
            }
        }

        // Obfuscation patterns
        $obfuscations = [
            '[at]', '(at)', '[dot]', '(dot)', 'at gmail', 'at outlook', 'at hotmail', 'at yahoo'
        ];
        foreach ($obfuscations as $obf) {
            if (str_contains($normalizedText, $obf)) {
                return [
                    'type' => 'obfuscation',
                    'matched' => $obf,
                    'message' => 'Obfuscated contact signature detected.'
                ];
            }
        }

        return null;
    }

    /**
     * Validate a field and throw a ValidationException if contact details are found.
     *
     * @param string|null $text
     * @param string $fieldName
     * @throws ValidationException
     */
    public static function validate(?string $text, string $fieldName = 'content'): void
    {
        $scanResult = self::scan($text);
        if ($scanResult !== null) {
            throw ValidationException::withMessages([
                $fieldName => [
                    '⚠ Sharing contact details (email, phone, social links) is not allowed before an offer is officially accepted on InternGrowth.'
                ]
            ]);
        }
    }
}
