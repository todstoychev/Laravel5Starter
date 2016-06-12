<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Nqxcode\LuceneSearch\Model\SearchableInterface;
use Nqxcode\LuceneSearch\Model\SearchTrait;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Todstoychev\Icr\Icr;

/**
 * Class Slider
 * @package App\Models
 */
class Slider extends Model implements SearchableInterface
{
    use SearchTrait;

    /**
     * @var string
     */
    protected $table = 'sliders';

    /**
     * @var array
     */
    protected $fillable = ['link', 'image_name'];

    /**
     * @var array
     */
    protected $dates = ['created_at', 'updated_at'];

    /**
     * Creates slider record
     *
     * @param array $data
     *
     * @return bool|string
     */
    public static function createRecord(array $data)
    {
        try {
            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $data['image'];
            array_forget($data, 'image');
            $fileName = $data['image_name'] . '.' . $uploadedFile->guessExtension();
            $data['image_name'] = $fileName;
            Icr::uploadImage($uploadedFile, 'slider', 'images', $data['image_name']);
        } catch (\Exception $e) {
            return $e->getMessage();
        }

        try {
            DB::beginTransaction();

            // Save slider data
            $slider = static::create($data);

            // Save translations data
            SliderTranslation::saveTranslations($data, $slider->id);

            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollback();
            Icr::deleteImage($fileName, 'slider', 'images');

            return $e->getMessage();
        }
    }

    /**
     * Update record
     *
     * @param int $id
     * @param array $data
     *
     * @return bool|string
     */
    public static function updateRecord($id, array $data)
    {
        $oldImageName = null;
        $newImageName = null;

        try {
            DB::beginTransaction();

            // Find record
            $slider = Slider::getOneBy(['id' => $id]);

            // Check for image name changes
            preg_match('/\.[a-z]{3,4}$/', $slider->image_name, $matches);
            $extension = array_shift($matches);
            $oldImageName = $slider->image_name;
            $newImageName = $data['image_name'] . $extension;
            static::updateImageName($oldImageName, $newImageName);

            // Update it
            $data['image_name'] = $newImageName;
            $slider->update($data);

            // Update translations
            SliderTranslation::updateTranslations($slider->id, $data);

            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollback();
            Icr::renameImage($newImageName, $oldImageName, 'slider', 'images');

            return $e->getMessage();
        }
    }

    /**
     * Gets one record by certain parameters
     *
     * @param array $params
     *
     * @return mixed
     */
    public static function getOneBy(array $params)
    {
        return static::with(['translations'])
            ->where($params)
            ->get()
            ->first();
    }

    /**
     * Gets all items
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public static function getAll()
    {
        return self::base();
    }

    /**
     * Handles search
     *
     * TODO Think about language filtering
     *
     * @param string $search
     * @param string $order
     * @param string $param
     *
     * @return mixed
     */
    public static function search($search, $order, $param)
    {
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
        $sql = "SELECT s.*, st.title, st.text, st.locale FROM sliders AS s 
                  LEFT JOIN slider_translations AS st ON st.slider_id = s.id 
                  WHERE st.locale = '{$locale}' AND 
                  (s.id IN {$ids} OR st.id IN {$ids})";

        if (null !== $order && null !== $param) {
            $sql .= " ORDER BY {$param} {$order}";
        }

        return DB::select(DB::raw($sql));
    }

    /**
     * Handles image renaming
     *
     * @param string $oldImageName
     * @param string $newImageName
     *
     * @return bool
     */
    private static function updateImageName($oldImageName, $newImageName)
    {
        if ($oldImageName == $newImageName) {
            return true;
        }

        if (Storage::disk('images')->has('slider/' . $oldImageName)) {
            return Icr::renameImage($oldImageName, $newImageName, 'slider', 'images');
        }

        return true;
    }

    /**
     * Creates base query
     *
     * @return mixed
     */
    private static function base()
    {
        return static::select(
            ['s.*', 'st.title', 'st.text', 'st.locale']
        )
            ->from('sliders as s')
            ->leftJoin('slider_translations as st', 'st.slider_id', '=', 's.id')
            ->where(['st.locale' => app()->getLocale()]);
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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function translations()
    {
        return $this->hasMany('App\Models\SliderTranslation', 'slider_id', 'id');
    }
}
