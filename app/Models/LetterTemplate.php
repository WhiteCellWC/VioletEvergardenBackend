<?php

namespace App\Models;

class LetterTemplate extends BaseModel
{
    // Table Column Start
    public const table = 'letter_templates';

    public const id = 'id';

    public const name = 'name';

    public const description = 'description';

    public const sendType = 'send_type';

    public const paperTypeId = 'paper_type_id';

    public const fragranceTypeId = 'fragrance_type_id';

    public const envelopeTypeId = 'envelope_type_id';

    public const waxSealTypeId = 'wax_seal_type_id';

    public const status = 'status';

    public const version = 'version';

    public const createdBy = 'created_by';

    public const updatedBy = 'updated_by';
    // Table Column End

    // Relation Start
    public const letterTypes = 'letterTypes';

    public const paperType = 'paperType';

    public const waxSealType = 'waxSealType';

    public const fragranceType = 'fragranceType';

    public const envelopeType = 'envelopeType';
    // Relation End

    protected $fillable = [
        'name',
        'description',
        'send_type',
        'paper_type_id',
        'fragrance_type_id',
        'envelope_type_id',
        'wax_seal_type_id',
        'status',
        'version',
        'created_by',
        'updated_by'
    ];

    #region Relations
    public function paperType()
    {
        return $this->belongsTo(PaperType::class, LetterTemplate::paperTypeId);
    }

    public function letterTypes()
    {
        return $this->belongsToMany(LetterType::class);
    }

    public function waxSealType()
    {
        return $this->belongsTo(WaxSealType::class, LetterTemplate::waxSealTypeId);
    }

    public function fragranceType()
    {
        return $this->belongsTo(FragranceType::class, LetterTemplate::fragranceTypeId);
    }

    public function envelopeType()
    {
        return $this->belongsTo(EnvelopeType::class, LetterTemplate::envelopeTypeId);
    }
    #endregion
}
