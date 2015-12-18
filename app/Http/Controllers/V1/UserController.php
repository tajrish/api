<?php

namespace Tajrish\Http\Controllers\V1;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Tajrish\Models\Challenge;
use Tajrish\Models\OwnVisit;
use Tajrish\Models\Pin;
use Tajrish\Models\Plane;
use Tajrish\Models\Province;
use Tajrish\Models\Train;
use Tajrish\Models\Visit;
use Tajrish\Models\VisitPin;
use Tajrish\Services\UserTokenHandler;

class UserController extends ApiController
{
    public function __construct()
    {
    }

    public function provinces($userToken, UserTokenHandler $tokenHandler)
    {
        $user = $tokenHandler->getUserFromToken($userToken, true);

        $localChallenge = Challenge::selectRaw('challenges.*')
            ->join('pins', 'challenges.pin_id', '=', 'pins.id')
            ->join('provinces', 'provinces.id', '=', 'pins.province_id')
            ->where('provinces.id', $user['province_id'])
            ->where('challenges.starts_at', '<=', $now = Carbon::now())
            ->where('challenges.ends_at', '>=', $now)
            ->first();

        $totalOwnCount = Pin::where('province_id', $user['province_id'])->count();
        $ownDoneCount = OwnVisit::selectRaw('count(distinct pin_id) as count')->where('user_id', $user['id'])->first()->count;

        $active = Visit::where('user_id', $user['id'])->whereNull('finished_at')->first();
        $active = $active ? $active->province_id : null;

        $provinces = Province::selectRaw('provinces.*, count(distinct visit_pin.pin_id) as done_count, count(distinct pins.id) as total_count')
            ->leftJoin('visits', function ($clause) use($user){
                return $clause->on('visits.province_id', '=', 'provinces.id')
                    ->where('visits.user_id', '=', $user['id'])
                    ->whereNotNull('visits.finished_at');
            })->leftJoin('visit_pin', 'visit_pin.visit_id', '=', 'visits.id')
            ->leftJoin('pins', 'pins.province_id', '=', 'provinces.id')
            ->groupBy('provinces.id')
            ->get();

        $provinces = $provinces->toArray();

        foreach ($provinces as $key => $province) {
            if ($province['id'] == $user['province_id']) {
                $province['is_for_user'] = true;
                $province['total_count'] = $totalOwnCount;
                $province['done_count'] = $ownDoneCount;
            }else {
                $province['is_for_user'] = false;
            }
            $province['progress'] = $province['total_count'] === 0 ? 0 : $province['done_count'] / $province['total_count'];
            $provinces[$key] = $province;
        }

        return response()->json([
            'challenge' => $localChallenge->toArray(),
            'active' => $active,
            'provinces' => $provinces
        ]);
    }

    public function getPinStatus(UserTokenHandler $tokenHandler, $token, $pinId)
    {
        $user = $tokenHandler->getUserFromToken($token, true);

        $pin = Pin::findOrFail($pinId);
        $numberOfUserCheckins = VisitPin::where('visit_pin.pin_id', $pin->id)
            ->join('visits', 'visits.id', '=', 'visit_pin.visit_id')
            ->where('visits.user_id', $user['id'])
            ->count();

        $numberOfUserCheckins = $numberOfUserCheckins + OwnVisit::where('user_id', $user['id'])
            ->where('pin_id', $pinId)
            ->count();

        $comments = VisitPin::selectRaw('users.id, users.name, visit_pin.comment')
            ->whereNotNull('visit_pin.comment')
            ->where('visit_pin.pin_id', $pin->id)
            ->join('visits', 'visits.id', '=', 'visit_pin.visit_id')
            ->join('users', 'users.id', '=', 'visits.user_id')
            ->orderBy('visit_pin.id', 'desc')
            ->get();

        $count = VisitPin::selectRaw('distinct(visits.user_id) as count')
            ->where('visit_pin.pin_id', $pin->id)
            ->join('visits', 'visits.id', '=', 'visit_pin.visit_id')
            ->first();

        if ($count) {
            $count = $count->count;
        }else {
            $count = 0;
        }

        $ownCount = OwnVisit::selectRaw('distinct(user_id) as count')
            ->where('pin_id', $pinId)
            ->first();

        $ownCount = $ownCount ? $ownCount->count : 0;

        $count = $ownCount + $count;

        return response()->json([
            'comments' => $comments,
            'current_user_checkins' => (int)$numberOfUserCheckins,
            'total_users' => (int)$count
        ]);
    }

    public function postCheckin(UserTokenHandler $tokens, Request $request, $token, $pinId)
    {
        $user = $tokens->getUserFromToken($token, true);
        $pin = Pin::findOrFail($pinId);

        if ($pin['province_id'] != $user['province_id']) {
            $visit = Visit::where('user_id', $user['id'])
                          ->whereNull('finished_at')
                          ->where('started_at', '<', Carbon::now()->toDateTimeString())
                          ->firstOrFail();

            VisitPin::create([
                'visit_id' => $visit['id'],
                'pin_id' => $pin->id,
                'comment' => $request->has('comment') ? (string)$request->input('comment') : null
            ]);
        }else {
            OwnVisit::create([
                'pin_id' => $pinId,
                'user_id' => $user['id']
            ]);
        }


        return response()->json(['successful' => true]);
    }

    public function getProvinceStatus(UserTokenHandler $tokens, $token, $provinceId)
    {
        $user = $tokens->getUserFromToken($token, true);
        Province::findOrFail($provinceId);
        $trains = Train::where('destination_province_id', $provinceId)->where('start_province_id', $user['province_id'])->get();
        $planes = Plane::where('destination_province_id', $provinceId)->where('start_province_id', $user['province_id'])->get();
        $buses = Plane::where('destination_province_id', $provinceId)->where('start_province_id', $user['province_id'])->get();


        $numberOfVisitors = Visit::selectRaw('count(distinct user_id) as count')
            ->where('user_id', $user['id'])
            ->where('province_id', $provinceId)
            ->first();

        $ownNumberOfVisitors = OwnVisit::selectRaw('count(distinct own_visits.user_id) as count')
            ->join('pins', 'pins.id', '=', 'own_visits.pin_id')
            ->where('pins.province_id', $provinceId)
            ->where('own_visits.user_id', $user['id'])
            ->first();

        $ownNumberOfVisitors = $ownNumberOfVisitors ? $ownNumberOfVisitors->count : 0;

        $numberOfVisitors = $numberOfVisitors ? $numberOfVisitors->count : 0;

        $numberOfVisitors = $ownNumberOfVisitors + $numberOfVisitors;

        $numberOfCheckinsInPin = Visit::selectRaw('count(distinct visit_pin.pin_id) as count')
            ->join('visit_pin', 'visit_pin.visit_id', '=', 'visits.id')
            ->join('pins', 'pins.id', '=', 'visit_pin.pin_id')
            ->where('pins.province_id', $provinceId)
            ->first();

        $ownCheckinsInPin = OwnVisit::selectRaw('count(distinct own_visits.pin_id) as count')
            ->join('pins', 'pins.id', '=', 'own_visits.pin_id')
            ->where('pins.province_id', $provinceId)
            ->first();

        $ownCheckinsInPin = $ownCheckinsInPin ? $ownCheckinsInPin->count : 0;

        $numberOfCheckinsInPin = $numberOfCheckinsInPin ? $numberOfCheckinsInPin->count : 0;

        $numberOfCheckinsInPin = $ownCheckinsInPin + $numberOfCheckinsInPin;

        $currentUserCheckIns = Visit::selectRaw('count(distinct visit_pin.pin_id) as count')
            ->join('visit_pin', 'visit_pin.visit_id', '=', 'visits.id')
            ->where('visits.province_id', $provinceId)
            ->where('visits.user_id', $user->id)
            ->first();

        $ownCurrent = OwnVisit::selectRaw('count(distinct own_visits.pin_id) as count')
            ->join('pins', 'pins.id', '=', 'own_visits.pin_id')
            ->where('pins.province_id', '=', $provinceId)
            ->where('own_visits.user_id', $user['id'])
            ->first();

        $ownCurrent = $ownCurrent ? $ownCurrent->count : 0;

        $currentUserCheckIns = $currentUserCheckIns ? $currentUserCheckIns->count : 0;

        $currentUserCheckIns = $currentUserCheckIns + $ownCurrent;

        $numberOfPins = Pin::where('province_id', $provinceId)->count();

        return [
            'trains' => $trains,
            'planes' => $planes,
            'buses' => $buses,
            'number_of_visitors' => (int)$numberOfVisitors,
            'number_of_checkins_in_pin' => (int)$numberOfCheckinsInPin,
            'current_user_checkins' => (int)$currentUserCheckIns,
            'number_of_pins' => (int)$numberOfPins
        ];
    }

    public function postStartProvince($token, $provinceId, UserTokenHandler $tokenHandler)
    {
        $user = $tokenHandler->getUserFromToken($token, true);
        $province = Province::findOrFail($provinceId);

        Visit::create([
            'user_id' => $user['id'],
            'province_id' => $province['id'],
            'started_at' => Carbon::now(),
            'finished_at' => null
        ]);

        return response()->json(['successful' => true]);
    }

    public function postFinishProvince($token, $provinceId, UserTokenHandler $tokenHandler)
    {
        $user = $tokenHandler->getUserFromToken($token, true);
        Province::findOrFail($provinceId);

        $visit = Visit::where('user_id', $user['id'])->where('province_id', $provinceId)->whereNull('finished_at')->first();

        if ($visit) {
            $visit->update(['finished_at' => Carbon::now()]);
        }

        return response()->json(['successful' => true]);
    }
}