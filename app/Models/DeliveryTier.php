<?php

namespace App\Models;

class DeliveryTier extends BaseModel
{
    // Table Column Start
    const table = 'delivery_tiers';

    const id = 'id';

    const deliveryOptionId = 'delivery_option_id';

    const maxWeight = 'max_weight';

    const price = 'price';

    const status = 'status';

    const version = 'version';

    const createdBy = 'created_by';

    const updatedBy = 'updated_by';
    // Table Column End

    // Relation Start
    public const deliveryOption = 'deliveryOption';
    // Relation End

    protected $fillable = [
        'delivery_option_id',
        'max_weight',
        'price',
        'status',
        'version',
        'created_by',
        'updated_by'
    ];

    #region Relations
    public function deliveryOption()
    {
        return $this->belongsTo(DeliveryOption::class, DeliveryTier::deliveryOptionId);
    }

    public function letterDeliveries()
    {
        return $this->hasMany(LetterDelivery::class, LetterDelivery::deliveryTierId);
    }
    #endregion
}
