<?php

return [

    'created' => 'Treatment created successfully.',
    'updated' => 'Treatment updated successfully.',
    'deleted' => 'Treatment deleted successfully.',

    'settings' => [
        'updated' => 'User settings updated successfully.',
        'invalid-avatar' => 'The provided avatar is in an invalid format.',
    ],

    'profile' => [
        'created' => 'User profile created successfully.',
        'updated' => 'User profile updated successfully.',
        'credit-error' => 'Something went wrong, Please contact technical support.',
        'invalid-avatar' => 'The provided avatar is in an invalid format.',
    ],

    'auth' => [
        'invalid-pin' => 'The provided pin is invalid or expired.',
        'not-registered' => 'The provided phone number has not registered yet.',
        'already-registered' => 'A user with the provided phone number has already registered.',
        'phone-blacklisted' => 'The provided phone number is blacklisted and cannot be used anymore.',
        'invalid-old-phone' => 'The old phone number you have entered is invalid.',
    ],

    'access' => [
        'request' => [
            'sent' => 'Access request was successfully sent to the user.',
            'already-sent' => 'You have already submitted an access request.',
        ],

        'decline' => [
            'no-pending' => 'There is no pending request to decline.',
            'declined' => 'Access request declined successfully.',
        ],

        'accept' => [
            'no-pending' => 'There is no pending request to accept.',
            'accepted' => 'Access request accepted successfully.',
        ],
    ],

    'phone' => [
        'updated' => 'Phone number updated successfully.',

        'reset' => [
            'already-done' => 'The phone reset request has already been completedx.',
            'expired' => 'The phone reset request has expired.',
        ]
    ]
];