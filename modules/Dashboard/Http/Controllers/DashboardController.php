<?php

namespace Modules\Dashboard\Http\Controllers;

use App\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules;
// use Modules\Sex\Entities\Sex;
class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     testing change being done
     */
    public function index()
    {

        $total_diseases = Modules\Disease\Entities\Disease::where('is_active', 1)->count();
        $total_bodyregions = Modules\Body\Entities\BodyRegion::where('is_active', 1)->count();
        $total_bodyparts = Modules\Body\Entities\BodyPart::where('is_active', 1)->count();
        $total_symptoms = Modules\Symptom\Entities\Symptom::where('is_active', 1)->count();
        $total_vitals = Modules\Vital\Entities\Vital::where('is_active', 1)->count();
        $total_physical_examinations = Modules\PhysicalExamination\Entities\PhysicalExamination::where('is_active', 1)->count();
        $total_diagnostics = Modules\Diagnostic\Entities\Diagnostic::where('is_active', 1)->count();
        $total_food = Modules\Food\Entities\Food::where('is_active', 1)->count();
        $total_activities = Modules\Activity\Entities\Activity::where('is_active', 1)->count();
        $total_fields = Modules\Field\Entities\Field::where('is_active', 1)->count();
        $total_home_remedies = Modules\HomeRemedy\Entities\HomeRemedy::where('is_active', 1)->count();
        $total_meal_sequences = Modules\MealSequence\Entities\MealSequence::where('is_active', 1)->count();
        $total_medicines = Modules\Medicine\Entities\Medicine::where('is_active', 1)->count();
        $total_sexes = Modules\Sex\Entities\Sex::where('is_active', 1)->count();
        $total_specializations = Modules\Specialization\Entities\Specialization::where('is_active', 1)->count();
        $total_treatments = Modules\Treatment\Entities\Treatment::where('is_active', 1)->count();
        $total_timeperiods = Modules\TimePeriod\Entities\TimePeriod::where('is_active', 1)->count();
        $total_advices = Modules\Advice\Entities\Advice::where('is_active', 1)->count();
        $total_agegroups = Modules\AgeGroup\Entities\AgeGroup::where('is_active', 1)->count();
        $total_users = User::where('status', 'active')->count();
       
        return [
            'total_diseases' => $total_diseases,
            'total_bodyregions' => $total_bodyregions,
            'total_bodyparts' => $total_bodyparts,
            'total_symptoms' => $total_symptoms,
            'total_vitals' => $total_vitals,
            'total_physical_examinations' => $total_physical_examinations,
            'total_diagnostics' => $total_diagnostics,
            'total_food' => $total_food,
            'total_activities' => $total_activities,
            'total_fields' => $total_fields,
            'total_home_remedies' => $total_home_remedies,
            'total_meal_sequences' => $total_meal_sequences,
            'total_medicines' => $total_medicines,
            'total_sexes' => $total_sexes,
            'total_specializations' => $total_specializations,
            'total_treatments' => $total_treatments,
            'total_timeperiods' => $total_timeperiods,
            'total_advices' => $total_advices,
            'total_agegroups' => $total_agegroups,
            'total_users' => $total_users,
        ];
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('dashboard::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('dashboard::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('dashboard::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
