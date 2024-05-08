<?php

namespace App\Dto;
use Symfony\Component\Validator\Constraints as Assert;

class ProductDTO
{
    public function __construct(
        #[Assert\NotNull(message: 'Name parameter is required')]
        public ?string $name = null
    )
    {

    }
}
?>