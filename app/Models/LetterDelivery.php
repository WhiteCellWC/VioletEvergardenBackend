<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LetterDelivery extends Model
{
    const id = 'id';

    const recipientId = 'recipient_id';

    const deliveryOptionId = 'delivery_option_id';

    const deliveryTierId = 'delivery_tier_id';

    const deliveryCost = 'delivery_cost';

    const trackingNumber = 'tracking_number';

    const deliveryStatus = 'delivery_status';

    const scheduledAt = 'scheduled_at';

    const shippedAt = 'shipped_at';

    const deliveredAt = 'delivered_at';

    const version = 'version';

    const createdBy = 'created_by';

    const updatedBy = 'updated_by';

    protected $fillable = [
        'recipient_id',
        'delivery_option_id',
        'delivery_tier_id',
        'delivery_cost',
        'tracking_number',
        'delivery_status',
        'scheduled_at',
        'shipped_at',
        'delivered_at',
        'version',
        'created_by',
        'updated_by'
    ];

    #region Relations
    public function deliveryOption()
    {
        return $this->belongsTo(DeliveryOption::class, LetterDelivery::deliveryOptionId);
    }

    public function deliveryTier()
    {
        $this->belongsTo(DeliveryTier::class, LetterDelivery::deliveryTierId);
    }

    public function recipient()
    {
        $this->belongsTo(Recipient::class, LetterDelivery::recipientId);
    }
    #endregion
}
