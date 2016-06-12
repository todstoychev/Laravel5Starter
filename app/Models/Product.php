<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Nqxcode\LuceneSearch\Model\SearchableInterface;
use Nqxcode\LuceneSearch\Model\SearchTrait;
use Todstoychev\Icr\Icr;

/**
 * Class Product
 *
 * @property integer id
 *
 * @package App\Models
 */
class Product extends Model implements SearchableInterface
{
    use SoftDeletes,
        SearchTrait;

    /**
     * @var string
     */
    protected $table = 'products';

    /**
     * @var array
     */
    protected $fillable = [
        'price',
        'qty',
        'currency',
    ];

    /**
     * Handles product add
     *
     * @param array $data
     *
     * @return bool|string
     */
    public static function createRecord(array $data)
    {
        $imageNames = [];

        try {
            DB::beginTransaction();

            $product = Product::create($data);

            // Create translations
            $locales = Settings::getLocales();

            foreach ($locales as $locale) {
                ProductTranslation::create(
                    [
                        'title' => $data['title'][$locale],
                        'description' => $data['description'][$locale],
                        'locale' => $locale,
                        'product_id' => $product->id,
                    ]
                );
            }

            // Add main image
            $imageName = Icr::uploadImage($data['files']['main_image'], 'product', 'images');
            $imageNames[] = $imageName;
            ProductImage::create(
                [
                    'image_name' => $imageName,
                    'product_id' => $product->id,
                    'main_image' => true,
                ]
            );

            // Create other images
            foreach ($data['files']['product_images'] as $image) {
                if (null !== $image) {
                    $imageName = Icr::uploadImage($image, 'product', 'images');
                    $imageNames[] = $imageName;
                    ProductImage::create(
                        [
                            'image_name' => $imageName,
                            'product_id' => $product->id,
                        ]
                    );
                }
            }

            DB::commit();

            return true;

        } catch (\Exception $e) {
            DB::rollback();

            foreach ($imageNames as $imageName) {
                Icr::deleteImage($imageName, 'product', 'images');
            }

            return $e->getMessage();
        }
    }

    /**
     * Update record
     *
     * @param integer $id
     * @param array $data
     *
     * @return bool|string
     */
    public static function updateRecord($id, array $data)
    {
        $imageNames = [];

        try {
            DB::beginTransaction();

            $product = Product::find($id);
            $product->update($data);

            // Create translations
            $locales = Settings::getLocales();

            foreach ($locales as $locale) {
                ProductTranslation::where(['product_id' => $product->id, 'locale' => $locale])
                    ->get()
                    ->first()
                    ->update(
                        [
                            'title' => $data['title'][$locale],
                            'description' => $data['description'][$locale],
                        ]
                    );
            }

            // Add main image
            if (array_key_exists('files', $data)) {
                if (array_key_exists('main_image', $data['files'])) {
                    $imageName = Icr::uploadImage($data['files']['main_image'], 'product', 'images');
                    $imageNames[] = $imageName;
                    ProductImage::create(
                        [
                            'image_name' => $imageName,
                            'product_id' => $product->id,
                            'main_image' => true,
                        ]
                    );
                }

                // Create other images
                if (array_key_exists('product_images', $data['files'])) {
                    foreach ($data['files']['product_images'] as $image) {
                        if ($image === null) {
                            continue;
                        }
                        
                        $imageName = Icr::uploadImage($image, 'product', 'images');
                        $imageNames[] = $imageName;
                        ProductImage::create(
                            [
                                'image_name' => $imageName,
                                'product_id' => $product->id,
                            ]
                        );
                    }
                }
            }

            DB::commit();

            return true;

        } catch (\Exception $e) {
            DB::rollback();

            foreach ($imageNames as $imageName) {
                Icr::deleteImage($imageName, 'product', 'images');
            }

            return $e->getMessage();
        }
    }

    /**
     * Base User query
     *
     * @param boolean $withTrashed Get with deleted
     * @param boolean $withJoin Use defined joins
     * @return Model
     */
    private static function base($withTrashed = false, $withJoin = false) {
        $query = self::select('products.*')
            ->with(['productImages', 'productTranslations']);

//        $withTrashed ? $query->withTrashed() : null;

        if ($withJoin) {
            $query->leftJoin('product_translations as pt', 'pt.product_id', '=', 'products.id')
                ->leftJoin('product_images as pi', 'products.id', '=', 'pi.product_id');
        }

        $query->distinct();

        return $query;
    }

    /**
     * Creates all elements query
     *
     * @param Boolean $withTrashed With soft deleted entries
     * @param Boolean $withJoin With join clauses
     * @return Model
     */
    public static function getAll($withTrashed, $withJoin) {
        $query = self::base($withTrashed, $withJoin)
            ->where('pt.locale', app()->getLocale())
            ->where('pi.main_image', true);

        return $query;
    }

    /**
     * Handles search
     *
     * @param array $search
     * @param string $order
     * @param string $param
     *
     * @return static
     */
    public static function search($search, $order, $param) {
        $ids = '(';

        foreach ($search as $id) {
            if ($id !== end($search)) {
                $ids .= $id . ', ';

                continue;
            }

            $ids .= $id;
        }

        $ids .= ')';
        $locale = app()->getLocale();

        $sql = "SELECT DISTINCT p.*, pt.*, pi.image_name FROM products AS p 
                  LEFT JOIN product_translations AS pt ON p.id = pt.product_id
                  LEFT JOIN product_images AS pi ON p.id = pi.product_id
                  WHERE p.id IN {$ids} OR pt.id IN {$ids} AND pt.locale = '{$locale}' AND pi.main_image IS TRUE";

        if (null !== $order && null !== $param) {
            $sql .= " ORDER BY {$param} {$order}";
        }

        return DB::select(DB::raw($sql));
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productImages()
    {
        return $this->hasMany('App\Models\ProductImage', 'product_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productTranslations()
    {
        return $this->hasMany('App\Models\ProductTranslation', 'product_id', 'id');
    }

    /**
     * @inheritdoc
     */
    public static function searchableIds()
    {
        return self::all()->lists('id');
    }
}
