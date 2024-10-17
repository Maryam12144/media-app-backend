<?php

namespace Modules\User\Entities\Traits;

use Exception;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Image;
use Throwable;

trait HasAvatar
{
    /**
     * Update avatar
     *
     * @param UploadedFile|File|\Illuminate\Support\Facades\File|Image $avatar
     * @return bool
     * @throws Exception
     */
    public function updateAvatar($avatar)
    {
        $this->removeAvatar(false);

        if ($avatar instanceof Image) {
            /*
             * If an image object has been passed
             * as avatar
             */
            $name = Str::random(40) . '.jpg';

            self::avatarDisk()
                ->put($this->id . '/' . $name, $avatar->getEncoded());
        } else {
            /*
             * If an uploaded file object has been passed
             * as avatar
             */
            $name = $avatar->hashName();

            $avatar->store('public/' . self::AVATAR_IMAGE_PATH . '/'
                . $this->id);
        }

        $this->update([
            'avatar' => $name
        ]);

        return $name;
    }

    /**
     * Remove avatar
     *
     * @return bool
     */
    public function removeAvatar($save = true)
    {
        try {
            if ($avatar = $this->avatar) {
                unlink(self::avatarPath($this->id,
                    $avatar));
            }
        } catch (Throwable $exception) {
        }

        if ($save) {
            return $this->update([
                'avatar' => null
            ]);
        }
    }

    /**
     * Get the path of the avatar image
     *
     * @param int $userId
     * @param string|null $avatarName
     * @return string
     */
    public static function avatarPath($userId, $avatarName = null)
    {
        $relativePath = $avatarName ? self::AVATAR_IMAGE_PATH . "/" .
            $userId . '/' . $avatarName : self::AVATAR_IMAGE_PATH . "/" . $userId;

        return public_path($relativePath);
    }

    /**
     * Get the url of avatar image
     *
     * @param int $userId
     * @param string|null $logo
     * @return string
     */
    public static function avatarUrl($userId, $logo = null)
    {
        $relativePath = $logo ? self::AVATAR_IMAGE_PATH . "/" .
            $userId . '/' . $logo : self::AVATAR_IMAGE_PATH . "/" . $userId;

        return url($relativePath);
    }

    /**
     * Get the avatar url attribute
     *
     * @return string
     */
    public function getAvatarUrlAttribute()
    {
        return $this->avatar
            ? self::avatarUrl($this->id,
                $this->avatar)
            : self::defaultAvatarUrl();
    }

    /**
     * Get the default avatar url if there no url
     *
     * @return Application|UrlGenerator|string
     */
    public static function defaultAvatarUrl()
    {
        return url('images/avatar.png');
    }

    /**
     * Get the storage disk for user avatars
     *
     * @return Filesystem|FilesystemAdapter
     */
    public static function avatarDisk()
    {
        return Storage::disk('avatars');
    }
}