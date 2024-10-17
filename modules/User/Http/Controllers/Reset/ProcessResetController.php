<?php

namespace Modules\User\Http\Controllers\Reset;

use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Modules\Core\Entities\User\PhoneReset;
use Modules\Core\Http\Controllers\Controller;
use Modules\User\Http\Requests\Reset\ProcessResetRequest;
use Throwable;

class ProcessResetController extends Controller
{
    /**
     * Request for phone number reset
     *
     * @param ProcessResetRequest $request
     * @param PhoneReset $reset
     * @return ResponseFactory|Response
     * @throws Throwable
     */
    public function process(ProcessResetRequest $request, PhoneReset $reset)
    {
        if ($this->hasExpired($reset)) {
            return $this->returnHasExpiredResponse();
        }

        if ($this->alreadyUsed($reset)) {
            return $this->returnAlreadyUsedResponse();
        }

        $this->finalizeChanges($reset);

        return $this->successResponse();
    }

    /**
     * Process the reset request for the user
     * and send email to him to complete the process
     *
     * @param PhoneReset $reset
     * @throws Throwable
     */
    protected function finalizeChanges(PhoneReset $reset)
    {
        DB::transaction(function () use ($reset) {
            $reset->apply();
        });
    }

    /**
     * Check if the phone reset record has expired
     *
     * @param PhoneReset $reset
     * @return bool
     */
    public function hasExpired($reset)
    {
        return $reset->hasExpired();
    }

    /**
     * Return an error response to notify user that
     * the the phone reset record has expired
     *
     * @return ResponseFactory|Response
     */
    protected function returnHasExpiredResponse()
    {
        return $this->errorResponse(
            __('user.phone.reset.expired')
        );
    }

    /**
     * Check if the phone reset record has been used before
     *
     * @param PhoneReset $reset
     * @return bool
     */
    public function alreadyUsed($reset)
    {
        return $reset->isUsed();
    }

    /**
     * Return an error response to notify user that
     * the the phone reset record has been used before
     *
     * @return ResponseFactory|Response
     */
    protected function returnAlreadyUsedResponse()
    {
        return $this->errorResponse(
            __('user.phone.reset.already-done')
        );
    }
}
