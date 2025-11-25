<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ConfigKey;
use App\Http\Controllers\Controller;
use App\Models\Config;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $configs = [];
        foreach (ConfigKey::cases() as $key) {
            $configs[$key->value] = [
                'label' => $key->label(),
                'value' => Config::get($key->value, $key->defaultValue()),
            ];
        }

        return view('pages.admin.setting.index', compact('configs'));
    }

    public function update(Request $request)
    {
        $rules = [];
        foreach (ConfigKey::cases() as $key) {
            $rules[$key->value] = 'nullable|string';
        }

        $validated = $request->validate($rules);

        foreach ($validated as $code => $value) {
            if ($value !== null) {
                Config::set($code, $value);
            }
        }

        return back()->with('success', 'Pengaturan berhasil disimpan.');
    }
}
