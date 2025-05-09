<?php

declare(strict_types=1);

namespace App\Presentation\Controller\Request;

use App\Presentation\Controller\Request\Exception\RequestValidationException;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Required;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;

final class CreateOrderRequest extends ValidatedRequest
{
    private const string PRODUCT_NAME = 'productName';
    private const string PRODUCT_ID = 'productId';
    private const string PRODUCT_PRICE = 'price';
    private const string PRODUCT_QUANTITY = 'quantity';
    private const string ITEMS = 'items';

    protected function assertRequestIsValid(): void
    {
        $constraints = new Collection([
            self::ITEMS => new All([new Collection([
                self::PRODUCT_ID => new Required([
                    new NotNull(message: 'Value cannot be null.'),
                    new Type(type: 'string', message: 'Value must be of string type.'),
                    new Length(
                        min: 1,
                        max: 255,
                        minMessage: 'Value must be at least {{ limit }} character long.',
                        maxMessage: 'Value must be at most {{ limit }} characters long.'
                    ),
                ]),
                self::PRODUCT_NAME => new Required([
                    new NotNull(message: 'Value cannot be null.'),
                    new Type(type: 'string', message: 'Value must be of string type.'),
                    new Length(
                        min: 1,
                        max: 255,
                        minMessage: 'Value must be at least {{ limit }} character long.',
                        maxMessage: 'Value must be at most {{ limit }} characters long.'
                    ),
                ]),
                self::PRODUCT_PRICE => new Required([
                    new NotNull(message: 'Value cannot be null.'),
                    new Type(type: 'int', message: 'Value must be of int type.'),
                    new GreaterThan(value: 0, message: 'Value must be greater than 0.'),
                ]),
                self::PRODUCT_QUANTITY => new Required([
                    new NotNull(message: 'Value cannot be null.'),
                    new Type(type: 'int', message: 'Value must be of int type.'),
                    new GreaterThan(value: 0, message: 'Value must be greater than 0.'),
                ]),
            ])]),
        ]);

        $errors = $this->validator->validate($this->getRequestData(), $constraints);

        if ($errors->count() > 0) {
            throw RequestValidationException::withViolations($errors);
        }

        $this->validateProductIdsUniqeness();
    }

    private function validateProductIdsUniqeness(): void
    {
        $productIds = array_map(static fn (array $item) => $item[self::PRODUCT_ID], $this->getRequestData()['items']);

        if (array_unique($productIds) !== $productIds) {
            $violation = new ConstraintViolation(
                message: 'All `productId` values in `items` list must be unique.',
                messageTemplate: 'All `productId` values in `items` list must be unique.',
                parameters: [],
                root: null,
                propertyPath: null,
                invalidValue: null,
                plural: null,
                code: null
            );

            throw RequestValidationException::withViolations(new ConstraintViolationList([$violation]));
        }
    }

    public function asArray(): array
    {
        return $this->getRequestData();
    }
}
