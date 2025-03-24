<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DoctorRegistrationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Set to true to allow the request
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:doctors,email',
            'password' => 'required|min:8',
            'phone' => 'required|regex:/^01[0-2]{1}[0-9]{8}$/|unique:doctors,phone',
            'price' => 'required|numeric|min:0',
            'description' => 'required|string|max:150',
            'usr_img' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'specialty_id' => 'required|exists:specialties,id',
        ];
    }

    /**
     * Get custom error messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'first_name.required' => 'الاسم الأول مطلوب',
            'first_name.string' => 'الاسم الأول يجب أن يكون نصاً',
            'first_name.max' => 'الاسم الأول يجب ألا يتجاوز 255 حرفاً',

            'last_name.required' => 'الاسم الأخير مطلوب',
            'last_name.string' => 'الاسم الأخير يجب أن يكون نصاً',
            'last_name.max' => 'الاسم الأخير يجب ألا يتجاوز 255 حرفاً',

            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'يرجى إدخال بريد إلكتروني صحيح',
            'email.unique' => 'البريد الإلكتروني مستخدم بالفعل',

            'password.required' => 'كلمة المرور مطلوبة',
            'password.min' => 'كلمة المرور يجب أن تكون 8 أحرف على الأقل',

            'phone.required' => 'رقم الهاتف مطلوب',
            'phone.regex' => 'يرجى إدخال رقم هاتف مصري صحيح',
            'phone.unique' => 'رقم الهاتف مستخدم بالفعل',

            'price.required' => 'سعر الكشف مطلوب',
            'price.numeric' => 'سعر الكشف يجب أن يكون رقماً',
            'price.min' => 'سعر الكشف يجب أن يكون 0 على الأقل',

            'description.required' => 'الوصف مطلوب',
            'description.string' => 'الوصف يجب أن يكون نصاً',
            'description.max' => 'الوصف يجب ألا يتجاوز 150 حرفاً',

            'usr_img.required' => 'الصورة الشخصية مطلوبة',
            'usr_img.image' => 'يجب أن يكون الملف صورة',
            'usr_img.mimes' => 'يجب أن تكون الصورة من نوع: jpeg, png, jpg',
            'usr_img.max' => 'حجم الصورة لا يجب أن يتجاوز 2 ميجابايت',

            'latitude.required' => 'يرجى تحديد موقع العيادة',
            'latitude.numeric' => 'إحداثيات الموقع يجب أن تكون أرقاماً',

            'longitude.required' => 'يرجى تحديد موقع العيادة',
            'longitude.numeric' => 'إحداثيات الموقع يجب أن تكون أرقاماً',

            'specialty_id.required' => 'التخصص مطلوب',
            'specialty_id.exists' => 'التخصص غير موجود',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'first_name' => 'الاسم الأول',
            'last_name' => 'الاسم الأخير',
            'email' => 'البريد الإلكتروني',
            'password' => 'كلمة المرور',
            'phone' => 'رقم الهاتف',
            'price' => 'سعر الكشف',
            'description' => 'الوصف',
            'usr_img' => 'الصورة الشخصية',
            'latitude' => 'خط العرض',
            'longitude' => 'خط الطول',
            'specialty_id' => 'التخصص',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // You can modify the data before validation if needed
        $this->merge([
            'phone' => str_replace([' ', '-'], '', $this->phone),
        ]);
    }
}
