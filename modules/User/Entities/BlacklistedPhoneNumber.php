<?php

namespace Modules\User\Entities;

use App\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BlacklistedPhoneNumber extends Model
{
    use HasFactory;

    /**
     * Mass-assignable attributes
     *
     * @var string[]
     */
    protected $fillable = [
        'user_id', 'phone_number',
    ];

    /**
     * Get the related user
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Create a new record for the given phone number
     *
     * @param User $user
     * @param string $phoneNumber
     * @return self|Model
     */
    public static function createRecord(User $user, string $phoneNumber)
    {
        return self::create([
            'user_id' => $user->id,
            'phone_number' => $phoneNumber
        ]);
    }
}
