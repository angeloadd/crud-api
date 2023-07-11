<?php

declare(strict_types=1);

namespace App\Request;

use App\Exception\RequestValidationException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AbstractRequest
{
    public function __construct(protected readonly ValidatorInterface $validator, RequestStack $requestStack)
    {
        $request = $requestStack->getCurrentRequest();
        $this->populate('GET' === $request?->getMethod() ? $request?->attributes : $request?->getPayload());
        $this->validate();
    }

    public function populate(InputBag|ParameterBag $request): self
    {
        foreach ((new \ReflectionClass($this))->getProperties() as $prop) {
            $propName = $prop->getName();
            if ('validator' === $propName) {
                continue;
            }

            $snakeCaseProp = $this->snakeCasedProp($propName);

            try {
                $this->{$propName} = $request->get($snakeCaseProp);
            } catch (BadRequestException $e) {
                if (str_contains($e->getMessage(), 'contains a non-scalar value.')) {
                    $this->{$propName} = $request->all($snakeCaseProp);
                }
            }
        }

        return $this;
    }

    public function validate(): bool
    {
        $violationsList = $this->validator->validate($this);

        $errors = array_map(
            fn (ConstraintViolation $violation): array => [
                'field' => $this->snakeCasedProp($violation->getPropertyPath()),
                'message' => $violation->getMessage(),
            ],
            iterator_to_array($violationsList)
        );

        if ([] !== $errors) {
            throw RequestValidationException::fromErrors($errors);
        }

        return true;
    }

    private function snakeCasedProp(string $propName): string
    {
        return strtolower(preg_replace('/([A-Z])/', '_$1', $propName));
    }
}
