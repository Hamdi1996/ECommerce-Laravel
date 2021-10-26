<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VendorRequest extends FormRequest
{
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
            'logo'=>'required_without:id|mimes:png,jpg,jpeg',
            'name'=>'required|string|max:100|',
            'mobile'=>'required|max:100|unique:vendors,mobile,'.$this->id,
            'email'=>'required|email|unique:vendors,email,'.$this->id,
            'category_id'=>'required|exists:main_categories,id',
            'address'=>'required|string|max:1000',
            'password'=>'required_without:id|nullable',
        ];
    }

    public function messages()
    {
            return [
                    'logo.required_without'=>'الصورة مطلوبة',
                    'required'=>'هذا الحقل مطلوب',
                    'max' => 'هذا الحقل طويل ',
                    'min' => 'هذا الحقل يجب الا يكون اقل من 8 حروف ',
                    'category_id'=>'هذا القسم غير موجود',
                    'email.email'=>'ضيغة البريد غير صحيحة',
                    'address.string'=>'العنوان لابد ان يكون حروف او حروف وارقام',
                    'name.string'=>'هذا الاسم لابد ان يكون حروف وارقام ',
                    'email.unique'=>'البريد الالكتروني مستخدم بالفعل',
                    'mobile.unique'=>'رقم الهاتف  مستخدم بالفعل',
                            ];
    }
}
