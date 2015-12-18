<?php

namespace Tajrish\Models;

use Carbon\Carbon;

class UserToken extends BaseModel
{

    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'user_tokens';

    /**
     * Guarded attributes
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Appendable attributes
     *
     * @var array
     */
    protected $appends = ['expires_at_remaining'];

    /**
     * User of the token
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get Expires at remaining attribute
     *
     * @return int|null
     */
    public function getExpiresAtRemainingAttribute()
    {
        /** @var Carbon $attr */
        if (null === ($attr = $this->getAttribute('expires_at'))) return null;

        return $attr->diffInSeconds(Carbon::now());
    }
}