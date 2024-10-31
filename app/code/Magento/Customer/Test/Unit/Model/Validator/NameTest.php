<?php
/**
 * Copyright 2024 Adobe
 * All Rights Reserved.
 */
declare(strict_types=1);

namespace Magento\Customer\Test\Unit\Model\Validator;

use Magento\Customer\Model\Validator\Name;
use Magento\Customer\Model\Customer;
use Magento\Customer\Model\Validator\Pattern\NameValidator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Customer name validator tests
 */
class NameTest extends TestCase
{
    /**
     * @var Name
     */
    private Name $nameValidator;

    /**
     * @var NameValidator|MockObject
     */
    private MockObject $nameValidatorMock;

    /**
     * @var Customer|MockObject
     */
    private MockObject $customerMock;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->nameValidatorMock = $this->createMock(NameValidator::class);
        $this->nameValidator = new Name($this->nameValidatorMock);
        $this->customerMock = $this
            ->getMockBuilder(Customer::class)
            ->disableOriginalConstructor()
            ->addMethods(['getFirstname', 'getLastname', 'getMiddlename'])
            ->getMock();
    }

    /**
     * Test for allowed punctuation characters in customer names
     *
     * @param string $firstName
     * @param string $middleName
     * @param string $lastName
     * @param string $message
     * @return void
     * @dataProvider expectedPunctuationInNamesDataProvider
     */
    public function testValidateCorrectPunctuationInNames(
        string $firstName,
        string $middleName,
        string $lastName,
        string $message
    ) {
        $this->customerMock->expects($this->once())->method('getFirstname')->willReturn($firstName);
        $this->customerMock->expects($this->once())->method('getMiddlename')->willReturn($middleName);
        $this->customerMock->expects($this->once())->method('getLastname')->willReturn($lastName);

        $isValid = $this->nameValidator->isValid($this->customerMock);
        $this->assertTrue($isValid, $message);
    }

    /**
     * @return array
     */
    public static function expectedPunctuationInNamesDataProvider(): array
    {
        return [
            [
                'firstName' => 'John',
                'middleName' => '',
                'lastNameName' => 'O’Doe',
                'message' => 'Inclined apostrophe must be allowed in names (iOS Smart Punctuation compatibility)'
            ],
            [
                'firstName' => 'John',
                'middleName' => '',
                'lastNameName' => 'O\'Doe',
                'message' => 'Legacy straight apostrophe must be allowed in names'
            ],
            [
                'firstName' => 'John',
                'middleName' => '',
                'lastNameName' => 'O`Doe',
                'message' => 'Grave accent back quote character must be allowed in names'
            ],
            [
                'firstName' => 'John & Smith',
                'middleName' => '',
                'lastNameName' => 'O`Doe',
                'message' => 'Special character ampersand(&) must be allowed in names'
            ]
        ];
    }
}
