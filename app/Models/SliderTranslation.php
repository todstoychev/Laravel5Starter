<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Nqxcode\LuceneSearch\Model\SearchableInterface;
use Nqxcode\LuceneSearch\Model\SearchTrait;

/**
 * Class SliderTranslation
 *
 * @property $id
 * @property $title
 * @property $description
 * @property $locale
 * @property $product_id
 *
 * @package App\Models
 */
class SliderTranslation extends Model implements SearchableInterface
{
    use SearchTrait;

    /**
     * @var string
     */
    protected $table = 'slider_translations';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['title', 'text', 'locale', 'slider_id'];

    /**
     * Saves translation entries
     *
     * @param array $data
     * @param int $sliderId
     *
     * @return bool
     */
    public static function saveTranslations(array $data, $sliderId)
    {
        // Create translations data if any
        if (
            (array_key_exists('title', $data) && !empty($data['title'])) ||
            (array_key_exists('text', $data) && !empty($data['text']))
        ) {
            $locales = Settings::getLocales();

            foreach ($locales as $locale) {
                $translationData = [
                    'title' => $data['title'][$locale],
                    'text' => $data['text'][$locale],
                    'slider_id' => $sliderId,
                    'locale' => $locale,
                ];

                SliderTranslation::create($translationData);
            }

            return true;
        }

        return false;
    }

    /**
     * Updates translation entry
     *
     * @param int $sliderId
     * @param array $data
     *
     * @return bool
     * @throws \RuntimeException
     */
    public static function updateTranslations($sliderId, array $data)
    {
        // Find translations
        /** @var Collection $translations */
        $translations = static::where(['slider_id' => $sliderId])
            ->get();

        // Update them
        if ($translations->isEmpty()) {
            throw new \RuntimeException('Empty result set for productTranslations model found!');
        }

        foreach ($translations as $translation) {
            $dataSet = [
                'title' => $data['title'][$translation->locale],
                'text' => $data['text'][$translation->locale]
            ];

            $translation->update($dataSet);
        }

        return true;
    }

    /**
     * Get id list for all searchable models.
     *
     * @return integer[]
     */
    public static function searchableIds()
    {
        return self::all()->lists('id');
    }
}
