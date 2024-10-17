<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\HtmlString;
use Modules\Access\Entities\AccessRequest;
use Modules\Contract\Entities\Contract;
use Modules\Core\Entities\BaseUser;
use Modules\Core\Entities\Role;
use Modules\Core\Entities\Traits\ResetsPassword;
use Modules\Core\Entities\Traits\ResetsPhone;
use Modules\Core\Entities\Traits\VerifiesEmail;
use Modules\Core\Entities\User\LoginPin;
use Modules\Core\Libraries\Filterable;
use Modules\Rating\Entities\Rating;
use Modules\User\Entities\LoginHistory;
use Modules\User\Entities\PersonalAccessToken;
use Modules\User\Entities\Traits\CachesUser;
use Modules\User\Entities\Traits\HasAvatar;
use Modules\User\Entities\Traits\HasGivenRatingsCount;
use Modules\User\Entities\Traits\HasRatedQualities;
use Modules\User\Entities\Traits\HasRateLock;
use Modules\User\Entities\Traits\HasReceivedRatingsCount;
use Modules\User\Entities\Traits\HasSubscriptions;
use Laravel\Cashier\Billable;
use Laravel\Cashier\Subscription;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Spatie\MediaLibrary\InteractsWithMedia;
use Staudenmeir\EloquentEagerLimit\HasEagerLimit;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * Class User
 *
 * @package App
 */
class User extends BaseUser implements MustVerifyEmail
{
    use Filterable, InteractsWithMedia, HasEagerLimit, VerifiesEmail, ResetsPhone, ResetsPassword,
        Billable, HasAvatar, HasSubscriptions, HasRatedQualities, SoftDeletes,
        HasReceivedRatingsCount, HasGivenRatingsCount, CachesUser, HasRateLock, HasRoles;

    /**
     * @var string
     */
    protected $guard_name = "api";

    /**
     * Project avatar path in the public folder
     */
    const AVATAR_IMAGE_PATH = 'images/avatars';

    /**
     * User languages
     */
    const USER_LANGUAGE_ENGLISH = 'en';

    /**
     * Different currencies
     */
    const CURRENCY_USD = 'usd';
    const CURRENCY_CAD = 'cad';
    const CURRENCY_EURO = 'eur';

    /**
     * Sorting orders
     */
    const ORDER_BY_ID_DESC = 'id_desc';
    const ORDER_BY_ID_ASC = 'id_asc';
    const ORDER_BY_NAME_DESC = 'full_name_desc';
    const ORDER_BY_NAME_ASC = 'full_name_asc';
    const ORDER_BY_SUSPENDED_DESC = 'suspended_desc';
    const ORDER_BY_SUSPENDED_ASC = 'suspended_asc';

    /**
     * User statuses in terms of suspension
     */
    const STATUS_SUSPENDED = 'suspended';
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    /**
     * Locking time before the same user is rated by
     * somebody else
     */
    const USER_RATING_LOCK_IN_SECONDS = 3;

    /**
     * Default trust score to start with for the
     * newly registered users
     */
    // const DEFAULT_TRUST_SCORE = 5;

    /**
     * On-boot model hooks
     */
    protected static function boot()
    {
        parent::boot();
    }

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the contracts created by user
     *
     * @return HasMany
     */
    public function contractsCreated()
    {
        return $this->hasMany(Contract::class,
            'creator_id');
    }

    /**
     * Get the contracts created by user
     *
     * @return HasMany
     */
    public function contractsReceived()
    {
        return $this->hasMany(Contract::class,
            'target_user_id');
    }

    /**
     * Create a new user
     *
     * @param string $phoneNumber
     * @param string $email
     * @param string|null $firstName
     * @param string|null $lastName
     * @return User
     */
     public static function createUser(string $phoneNumber, string $email, string $password, 
                                        $firstName = null, $lastName = null)
    {  
        return self::create([
            'phone_number' => $phoneNumber,
            'email' => strtolower(trim($email)),
            'password' => Hash::make($password),
            'first_name' => $firstName,
            'last_name' => $lastName,
        ]);
    }

    /**
     * Create a new admin user
     *
     * @param string $email
     * @param string $password
     * @param string|null $firstName
     * @param string|null $lastName
     * @return User
     */
    public static function createAdmin(string $phoneNumber, string $email, string $password,
                                              $firstName = null, $lastName = null)
    {
        return self::create([
            'phone_number' => $phoneNumber,
            'email' => strtolower(trim($email)),
            'password' => Hash::make($password),
            'first_name' => $firstName,
            'last_name' => $lastName,
        ]);
    }

    /**
     * Get the users that current user has given rating to
     *
     * @return BelongsToMany
     */
    public function ratedUsers()
    {
        return $this->belongsToMany(User::class, 'ratings',
            'creator_id', 'target_user_id');
    }

    /**
     * Get the login pin records for user
     *
     * @return HasMany
     */
    public function loginPins()
    {
        return $this->hasMany(LoginPin::class,
            'user_id');
    }

    /**
     * Get user's subscriptions
     *
     * @return HasMany
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, $this->getForeignKey())
            ->orderBy('created_at', 'desc');
    }

    /**
     * Generate a new login token for user
     *
     * @param Request $request
     * @return string
     */
    public function generateLoginToken($request)
    {
        $token = $this->createToken(
            $request->server('HTTP_USER_AGENT')
        );

        return $token->plainTextToken;
    }

    /**
     * Fetch single user by id from database
     *
     * @param int $id
     * @param bool $orFail
     * @return User|Builder|Model|object|null
     */
    public static function fetchUserById($id, $orFail = false)
    {
        return self::query()
            ->where('id', $id)
            ->{$orFail ? 'firstOrFail' : 'first'}();
    }

    /**
     * Fetch single user by email from database
     *
     * @param string $email
     * @param bool $orFail
     * @return User|Builder|Model|object|null
     */
    public static function fetchUserByEmail($email, $orFail = false)
    {
        return self::query()
            ->where('email', $email)
            ->{$orFail ? 'firstOrFail' : 'first'}();
    }

    /**
     * Get get login histories of user
     *
     * @return HasMany
     */
    public function loginHistories()
    {
        return $this->hasMany(LoginHistory::class);
    }

    /**
     * Get the last access of the user to the application
     *
     * @return MorphOne
     */
    public function lastAccess()
    {
        return $this->morphOne(PersonalAccessToken::class, 'tokenable')
            ->orderByDesc('last_used_at')
            ->orderByDesc('created_at');
    }

    /**
     * Get all of the currencies
     *
     * @return array
     */
    public static function allCurrencies()
    {
        return [
            self::CURRENCY_USD,
            self::CURRENCY_CAD,
            self::CURRENCY_EURO
        ];
    }

    /**
     * Check if user is an admin
     *
     * @return bool
     */

    public function isNormalAdmin()
    {
        return $this->hasRole(
            Role::ROLE_NORMAL_ADMIN
        );
    }

    public function isAdmin()
    {
        return $this->hasRole(
            Role::ROLE_ADMIN
        );
    }

    /**
     * Get the ratings that has been created by user
     *
     * @return HasMany
     */
    public function ratingsGiven()
    {
        return $this->hasMany(Rating::class,
            'creator_id');
    }

    /**
     * Get the ratings that has been received by user
     *
     * @return HasMany
     */
    public function ratingsReceived()
    {
        return $this->hasMany(Rating::class,
            'target_user_id');
    }

    /**
     * Get the received access requests
     *
     * @return HasMany
     */
    public function accessRequestsReceived()
    {
        return $this->hasMany(AccessRequest::class,
            'target_user_id');
    }

    /**
     * Get the given access requests
     *
     * @return HasMany
     */
    public function accessRequestsGiven()
    {
        return $this->hasMany(AccessRequest::class,
            'creator_id');
    }

    /**
     * Get the QR code of the user
     *
     * @return bool|HtmlString
     */
    public function getQrCodeAttribute()
    {
        $cached = self::getQrCodeFromCache($this);

        if ($cached) return $cached;

        self::storeQrCodeIntoCache(
            $this,
            $qr = $this->regenerateQrCode()
        );

        return $qr;
    }

    /**
     * Regenerate the QR code for the user
     *
     * @return HtmlString
     */
    protected function regenerateQrCode()
    {
        return QrCode::size(512)
            ->generate($this->id);
    }

    /**
     * Get the QR code for the given user from the cache
     *
     * @param User|int $user
     * @return bool
     */
    public static function getQrCodeFromCache($user)
    {
        return Cache::get(self::qrCodeCacheKey($user));
    }

    /**
     * Store a QR code for the given user into the cache
     *
     * @param User|int $user
     * @param HtmlString $qr
     * @return bool
     */
    public static function storeQrCodeIntoCache($user, $qr)
    {
        return Cache::put(self::qrCodeCacheKey($user), $qr);
    }

    /**
     * Reset the QR code cache for the given user
     *
     * @param User|int $user
     * @return bool
     */
    public static function resetQrCodeCache($user)
    {
        return Cache::forget(self::qrCodeCacheKey($user));
    }

    /**
     * Get the location attribute for user
     *
     * @return string
     */
    public function getLocationAttribute()
    {
        $elements = array_filter([
            $this->city,
            $this->state,
            $this->country,
        ]);

        return implode(', ', $elements);
    }

    /**
     * Get the cache key for the QR code of the given user
     *
     * @param User|int
     * @return string
     */
    public static function qrCodeCacheKey($user)
    {
        if ($user instanceof User) $user = $user->id;

        return "user_qr_code:$user";
    }
}
