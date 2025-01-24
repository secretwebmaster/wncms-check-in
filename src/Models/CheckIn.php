<?php

namespace Wncms\CheckIn\Models;

use Illuminate\Database\Eloquent\Model;

class CheckIn extends Model
{
    protected $guarded = [];

    /**
     * Get the owning translatable model.
     */
    public function check_ins()
    {
        $userModel = config('wncms.default_user_model', \Wncms\Models\User::class);
        return $this->belongsTo($userModel);
    }
}
