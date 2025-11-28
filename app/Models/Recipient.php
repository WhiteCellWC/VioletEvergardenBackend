<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recipient extends Model
{
    const table = 'recipients';

    const id = 'id';

    const userId = 'user_id';

    const letterId = 'letter_id';

    const name = 'name';

    const email = 'email';

    const phone = 'phone';

    const addressLine1 = 'address_line_1';

    const addressLine2 = 'address_line_2';

    const variables = 'variables';

    const countryId = 'country_id';

    const stateId = 'state_id';

    const postalCode = 'postal_code';

    const version = 'version';

    const createdBy = 'created_by';

    const updatedBy = 'updated_by';

    // Relation Start
    public const letterDeliveries = 'letterDeliveries';

    public const country = 'country';

    public const state = 'state';
    // Relation End

    protected $fillable = [
        'user_id',
        'letter_id',
        'name',
        'email',
        'phone',
        'address_line_1',
        'address_line_2',
        'variables',
        'country_id',
        'state_id',
        'postal_code',
        'version',
        'created_by',
        'updated_by'
    ];

    #region Relations
    public function user()
    {
        return $this->belongsTo(User::class, Recipient::userId);
    }

    public function letter()
    {
        return $this->belongsTo(Letter::class, Recipient::letterId);
    }

    public function letterDeliveries()
    {
        return $this->hasMany(LetterDelivery::class, LetterDelivery::recipientId);
    }

    public function state()
    {
        return $this->belongsTo(State::class, Recipient::stateId);
    }

    public function country()
    {
        return $this->belongsTo(Country::class, Recipient::countryId);
    }
    #endregion
}
