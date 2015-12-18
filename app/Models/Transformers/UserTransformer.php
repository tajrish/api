<?php

namespace Tajrish\Models\Transformers;

use Tajrish\Models\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{

    public function transform(User $user)
    {
        return [
            'id' => (int)$user->id,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'username' => $user->username,
            'email' => $user->email,
            'mobile' => $user->mobile,
            'bank' => $user->bank,
            'birth_date' => $user->birth_date,
            'status' => $user->status,
            'avatar' => $user->avatar,
            'updated_at' => $user->updated_at,
            'created_at' => $user->created_at,
            '_links' => [
                'self' =>[
                    'href' => '/users/' . $user->id,
                ]
            ],
        ];
    }

}