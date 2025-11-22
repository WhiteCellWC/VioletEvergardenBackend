<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryOption extends Model
{
    public const table = 'delivery_options';

    public const id = 'id';

    public const name = 'name';

    public const isWeightBased = 'is_weight_based';

    public const baseCost = 'base_cost';

    public const deliveryType = 'delivery_type';

    public const estimatedDays = 'estimated_days';

    public const hasTracking = 'has_tracking';

    public const status = 'status';

    public const version = 'version';

    public const createdBy = 'created_by';

    public const updatedBy = 'udpated_by';

    protected $fillable = [
        'name',
        'is_weight_based',
        'base_cost',
        'delivery_type',
        'estimated_days',
        'has_tracking',
        'status',
        'version',
        'created_by',
        'updated_by'
    ];

    #region Relations
    public function letterDeliveries()
    {
        return $this->hasMany(LetterDelivery::class, LetterDelivery::deliveryOptionId);
    }

    public function deliveryTiers()
    {
        return $this->hasMany(DeliveryTier::class, DeliveryTier::deliveryOptionId);
    }
    #endregion
}
