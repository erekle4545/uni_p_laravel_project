<?php

namespace App\Http\Requests;

use Illuminate\Validation\Factory;
use Illuminate\Foundation\Http\FormRequest;
use Spatie\Permission\Models\Permission;
class StorePermissions extends FormRequest
{

    public function __construct(Factory $factory)
    {
        $factory->extend(
            'permission_is_unique',
            function () {

                return Permission::where('name',"{$this->name} {$this->endpoint}")->get()->count() === 0;
            },
            'Provided permission already exists, choose different one'
        );

    }
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'=>['required','string','permission_is_unique'],
            'endpoint'=>'required'
        ];

    }
}
