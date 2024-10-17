<?php

namespace Modules\User\Http\Controllers\Admin\Members;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Core\Entities\Role;
use Modules\Core\Events\UserRegistered;
use Modules\Core\Http\Controllers\Controller;
use Modules\User\Http\Requests\Admin\Members\AddMemberRequest;
use Illuminate\Support\Facades\Hash;
use Modules\User\Http\Resource\Admin\UserResource;
use Modules\Transection\Entities\Transection;
use Modules\Transection\Entities\TransectionLog;
use Modules\User\Entities\UserProfile;
use Throwable;
use Auth;

class AddMemberController extends Controller
{
    /**
     * Add a new admin to the database
     *
     * @param AddMemberRequest $request
     * @return ResponseFactory|Response
     * @throws Throwable
     */
    public function add(AddMemberRequest $request)
    {
        
    
        $member = $this->createMember($request);

        return $this->successResponse(
            __('member.members.added'), [
                'member' => new UserResource($member)
            ]
        );
    }

    /**
     * Create a new admin based on the given details
     *
     * @param AddMemberRequest $request
     * @return User
     * @throws Throwable
     */
    protected function createMember($request)
    {
        // $admin = Auth::user();
        // $admin_credits = UserProfile::where('user_id', $admin->id)->pluck('credits')->first();
        // if($admin_credits < $request->get('credits')){
        //     return $this->errorResponse(
        //         __('user.profile.credit-error'),
        //     );
        // }

        $user = User::create([ 
            'first_name' => $request->get('first_name'),
            'last_name' => $request->get('last_name'),
            'email' => strtolower(trim($request->get('email'))),
            'phone_number' => $request->get('phone_number'),
            'birthday' => $request->get('dob'),
            'rating' => $request->get('rating'),
            'status' => $request->get('status'),
            'password' => Hash::make($request->get('password')),
        ]);

        event(new UserRegistered($user));

        $user->assignRole(Role::ROLE_USER);

        $firstname = strtolower($request->get('first_name'));
        $lastname = strtolower($request->get('last_name'));

        $username =  $this->generate_unique_username($firstname, $lastname, $user->id);

        $credits = $this->memberRegisterCreditsTransection($user->id, $request->get('credits'));

        UserProfile::create([
            'user_id' => $user->id,
            'username' => $username,
            'location' => $request->get('location'),
            'credits' => $credits,
        ]);

        return $user->fresh();
    }


    public function memberRegisterCreditsTransection($user_id, $given_credits)
    {
        $user = Auth::user();
        $sender = UserProfile::where('user_id', $user->id)->first();
        if($sender == null){
            return $credits = 0;
        }
        
        $receiver = UserProfile::where('user_id', $user_id)->first();

        $transectionLog = new TransectionLog;
        $transectionLog->sender_opening_balance = $sender->credits;
        $transectionLog->receiver_opening_balance = 0;
        $transectionLog->save();

        $transection = new Transection;
        $transection->sent_by = $user->id;
        $transection->received_by = $user_id;
        $transection->credits = $given_credits;
        $transection->transection_type = 'signup';
        $transection->status = 'Completed'; 
        $transection->save();

        $sender->update([
            'credits' => $sender->credits - $given_credits
        ]);
        

        $transectionLog->update([
            'transection_id' => $transection->id,
            'sender_closing_balance' => $sender->credits,
            'receiver_closing_balance' => $given_credits
        ]);

        return $credits = $given_credits;
    }

    //function that will be used to figure out if the user name is available or not
    function isAvailable($userName){
     
        $result = UserProfile::where('username', $userName)->count();

        // We know username exists if the rows returned are more than 0
        if ( $result > 0 ) {
             //echo 'User with this username already exists!';
             return false;
        }else{
            return true;
        }
    }

    function generate_unique_username($firstname, $lastname, $id){    
        $userNamesList = array();
        $firstChar = str_split($firstname, 1)[0];
        $firstTwoChar = str_split($firstname, 2)[0];
        /**
         * an array of numbers that may be used as suffix for the user names index 0 would be the year
         * and index 1, 2 and 3 would be month, day and hour respectively.
         */
        $numSufix = explode('-', date('Y-m-d-H')); 

        // create an array of nice possible user names from the first name and last name
        array_push($userNamesList, 
            $firstname,                 //james
            $lastname,                 // oduro
            $firstname.$lastname,       //jamesoduro
            $firstname.'.'.$lastname,   //james.oduro
            $firstname.'-'.$lastname,   //james-oduro
            $firstChar.$lastname,       //joduro
            $firstTwoChar.$lastname,    //jaoduro,
            $firstname.$numSufix[0],    //james2019
            $firstname.$numSufix[1],    //james12 i.e the month of reg
            $firstname.$numSufix[2],    //james28 i.e the day of reg
            $firstname.$numSufix[3]     //james13 i.e the hour of day of reg
        );


        $isAvailable = false; //initialize available with false
        $index = 0;
        $maxIndex = count($userNamesList) - 1;

        // loop through all the userNameList and find the one that is available
        do {
            $availableUserName = $userNamesList[$index];
            $isAvailable = $this->isAvailable($availableUserName);
            $limit =  $index >= $maxIndex;
            $index += 1;
            if($limit){
            break;
            }
        } while (!$isAvailable );

        // if all of them is not available concatenate the first name with the user unique id from the database
        // Since no two rows can have the same id. this will sure give a unique username
        if(!$isAvailable){
            return $firstname.$id;
        }
        return $availableUserName;
    }


}
