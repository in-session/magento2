<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\Framework\Validator;

class GlobalPhoneValidation
{
    /**
     * Regular expression pattern for validating phone numbers.
     */
    public const PATTERN_TELEPHONE = '/(?:[\d\s\+\-\()\/]{1,30})/u';

    /**
     * Validate a phone number string.
     *
     * @param string|null $phoneValue
     * @return bool
     */
    public function isValidPhone(mixed $phoneValue): bool
    {
        if ($phoneValue === null || $phoneValue === '' || !is_string($phoneValue)) {
            return true;
        }

        if (preg_match(self::PATTERN_TELEPHONE, trim($phoneValue), $matches)) {
            return $matches[0] === trim($phoneValue);
        }
    
        return false;
    }
}
