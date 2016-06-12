<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Nqxcode\LuceneSearch\Model\SearchTrait;

class ProductTranslation extends Model
{
    /**
     * @var string
     */
    protected $table = 'product_translations';

    /**
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'locale',
        'product_id',
    ];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'product_id', 'id');
    }

    /**
     * Get id list for all searchable models.
     *
     * @return integer[]
     */
    public static function searchableIds()
    {
        return static::all()->lists('id');
    }

    /**
     * Handles search
     *
     * @param Array $search
     * @return self
     */
    public static function search($search) {
        $query = self::base(true, true)
            ->whereIn('users.id', $search);

        return $query;
    }
}
