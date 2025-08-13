<?php

namespace App\Http\Requests\Nilai;

use Illuminate\Foundation\Http\FormRequest;
use App\Services\GuruService;

class StoreBatchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return GuruService::isGuru();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'jadwal_id' => 'required|exists:jadwals,id',
            'jenis_penilaian_id' => 'required|exists:jenis_penilaians,id',
            'nilai' => 'required|array',
            'nilai.*' => 'nullable|numeric|min:0|max:100',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'jadwal_id.required' => 'Jadwal harus dipilih.',
            'jadwal_id.exists' => 'Jadwal yang dipilih tidak valid.',
            'jenis_penilaian_id.required' => 'Jenis penilaian harus dipilih.',
            'jenis_penilaian_id.exists' => 'Jenis penilaian yang dipilih tidak valid.',
            'nilai.required' => 'Data nilai harus diisi.',
            'nilai.array' => 'Data nilai harus berupa array.',
            'nilai.*.numeric' => 'Nilai harus berupa angka.',
            'nilai.*.min' => 'Nilai minimal adalah 0.',
            'nilai.*.max' => 'Nilai maksimal adalah 100.',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Verify that the schedule belongs to the authenticated teacher
            try {
                $guru = GuruService::getAuthenticatedGuru();
                $jadwal = $guru->jadwals()->where('id', $this->jadwal_id)->first();
                
                if (!$jadwal) {
                    $validator->errors()->add('jadwal_id', 'Jadwal tidak ditemukan atau tidak dapat diakses.');
                }
            } catch (\Exception $e) {
                $validator->errors()->add('auth', $e->getMessage());
            }
        });
    }
}
