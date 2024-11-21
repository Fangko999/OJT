<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function edit()
    {
        $attendanceSetting = Setting::first();
        $checkInTime = $attendanceSetting->check_in_time;
        $checkOutTime = $attendanceSetting->check_out_time;

        return view('fe_attendances/setting', compact('checkInTime', 'checkOutTime'));
    }

    public function update(Request $request)
    {
        $attendanceSetting = Setting::first();
        $attendanceSetting->update([
            'check_in_time' => $request->input('check_in_time'),
            'check_out_time' => $request->input('check_out_time')
        ]);

        return redirect()->back()->with('message', 'Cập nhật thời gian Check In/Check Out thành công!');
    }
}
