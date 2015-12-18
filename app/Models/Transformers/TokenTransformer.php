<?php

namespace Tajrish\Models\Transformers;

use Tajrish\Models\Token;
use League\Fractal\TransformerAbstract;

class TokenTransformer extends TransformerAbstract
{
    public function transform(Token $token)
    {
        return [
            'token' => $token->token,
        ];
    }
}