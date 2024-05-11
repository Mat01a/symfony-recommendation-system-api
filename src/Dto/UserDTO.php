<?php

namespace App\Dto;
use Symfony\Component\Validator\Constraints as Assert;

class UserDTO
{
    public function __construct(
        #[Assert\Email]
        #[Assert\NotBlank]
        public ?string $email = null,
        #[Assert\NotNull(message: 'Password is necessary')]
        public ?string $password = null,
    ) 
    {

    }
}
?>