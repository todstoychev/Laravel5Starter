<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    /**
     * @var string
     */
    protected $table = 'product_images';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = [
        'image_name',
        'product_id',
        'main_image',
    ];

    /**
     * Finds main image
     *
     * @param int $productId
     *
     * @return mixed
     */
    public static function findMainImage($productId)
    {
        return static::where(['product_id' => $productId, 'main_image' => true])
            ->get()
            ->first();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'product_id', 'id');
    }
}
