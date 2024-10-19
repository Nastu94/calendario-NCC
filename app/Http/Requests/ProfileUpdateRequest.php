<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules()
    {
        return [
            'indirizzo' => 'required|string|max:255',
            'cap' => 'required|string|max:10',
            'citta' => 'required|string|max:255',
            'provincia' => 'required|string|max:255',
            'partita_iva' => 'required|string|max:20',
            'codice_sdi' => 'required|string|max:20',
            'codice_fiscale' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'cellulare' => 'required|string|max:20',
        ];
    }
    
}
