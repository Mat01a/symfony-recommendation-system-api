<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class OrderDTO
{
    public function __construct(
        #[Assert\NotBlank(message: "Order can't be empty")]
        public ?array $products = null
    )
    {
        
    }
}
?>