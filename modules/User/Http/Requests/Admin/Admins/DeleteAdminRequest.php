<?php

namespace Modules\User\Http\Requests\Admin\Admins;

use App\User;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Core\Rules\Auth\StrongPassword;

class DeleteAdminRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        /** @var User $user */
        $user = $this->user();

        return $user->can('delete_admin');
    }
}
