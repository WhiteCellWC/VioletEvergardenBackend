<?php

namespace App\Models;

class Letter extends BaseModel
{
    // Table Column Start
    public const table = 'letters';

    public const id = 'id';

    public const userId = 'user_id';

    public const title = 'title';

    public const body = 'body';

    public const sendType = 'send_type';

    public const paperTypeId = 'paper_type_id';

    public const fragranceTypeId = 'fragrance_type_id';

    public const envelopeTypeId = 'envelope_type_id';

    public const waxSealTypeId = 'wax_seal_type_id';

    public const isDraft = 'is_draft';

    public const isSent = 'is_sent';

    public const isSealed = 'is_sealed';

    public const isPrinted = 'is_printed';

    public const version = 'version';

    public const createdBy = 'created_by';

    public const updatedBy = 'updated_by';
    // Table Column End

    // Relation Start
    public const user = 'user';

    public const recipients = 'recipients';

    public const letterTypes = 'letterTypes';
    // Relation End

    protected $fillable = [
        'user_id',
        'title',
        'body',
        'send_type',
        'paper_type_id',
        'fragrance_type_id',
        'envelope_type_id',
        'wax_seal_type_id',
        'is_draft',
        'is_sent',
        'is_sealed',
        'is_printed',
        'version',
        'created_by',
        'updated_by'
    ];

    #region Relations
    public function waxSealType()
    {
        return $this->belongsTo(WaxSealType::class, Letter::waxSealTypeId);
    }

    public function letterTypes()
    {
        return $this->belongsToMany(LetterType::class);
    }

    public function paperType()
    {
        return $this->belongsTo(PaperType::class, Letter::paperTypeId);
    }

    public function fragranceType()
    {
        return $this->belongsTo(FragranceType::class, Letter::fragranceTypeId);
    }

    public function envelopeType()
    {
        return $this->belongsTo(EnvelopeType::class, Letter::envelopeTypeId);
    }

    public function letterPayments()
    {
        return $this->hasMany(LetterPayment::class, LetterPayment::letterId);
    }

    public function recipients()
    {
        return $this->hasMany(Recipient::class, Recipient::letterId);
    }

    public function user()
    {
        return $this->belongsTo(User::class, Letter::userId);
    }
    #endregion
}
