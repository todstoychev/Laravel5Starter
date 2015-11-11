<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Nqxcode\LuceneSearch\Model\SearchableInterface;
use Nqxcode\LuceneSearch\Model\SearchTrait;

/**
 * Permissions model.
 *
 * @property mixed permissions
 * @author Todor Todorov <todstoychev@gmail.com>
 * @package App\Models
 */
class Action extends Model implements SearchableInterface
{
    use SearchTrait;

    /**
     * Database table name
     *
     * @var string
     */
    protected $table = 'actions';

    /**
     * Timestamps
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Fillable properties
     *
     * @var array
     */
    protected $fillable = [
        'uri',
        'action'
    ];

    /**
     * Delete all data in the table
     */
    public static function deleteAllData()
    {
        self::all()->each(function($action) {
            try {
                DB::beginTransaction();
                $action->delete();
                DB::commit();

                return;
            } catch (Exception $e) {
                DB::rollBack();
                return $e->getMessage();
            }
        });
    }

    /**
     * Handle search
     *
     * @param $search
     * @return mixed
     */
    public static function search($search)
    {
        $query = self::with(['permissions', 'permissions.role'])
            ->whereIn('id', $search);

        return $query;
    }

    /**
     * Permission relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function permissions()
    {
        return $this->hasMany('App\Models\Permission');
    }

    /**
     * Is the model available for search indexing?
     *
     * @return boolean
     */
    public function isSearchable()
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public static function searchableIds()
    {
        return self::all()->lists('id');
    }

}
