<?php

declare(strict_types=1);

namespace App\Presentation\Controller\Request;

use App\Presentation\Controller\Request\Exception\RequestValidationException;
use Symfony\Component\Uid\Ulid as UlidId;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Required;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Ulid;

final class GetOrderRequest extends ValidatedRequest
{
    private const string ID = 'id';

    protected function assertRequestIsValid(): void
    {
        $constraints = new Collection([
            self::ID => new Required([
                new Type(type: 'string', message: 'Value must be of string type.'),
                new Ulid(message: 'Value must be a valid identifier.'),
            ]),
        ]);

        $errors = $this->validator->validate([self::ID => $this->request->get(self::ID, '')], $constraints);

        if ($errors->count() > 0) {
            throw RequestValidationException::withViolations($errors);
        }
    }

    public function getId(): UlidId
    {
        return UlidId::fromString(strval($this->request->get(self::ID, '')));
    }
}
