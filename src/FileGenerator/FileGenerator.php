<?php

namespace Railken\LaraOre\FileGenerator;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Config;
use Railken\LaraOre\DataBuilder\DataBuilder;
use Railken\Laravel\Manager\Contracts\EntityContract;

/**
 * @property string      $name
 * @property DataBuilder $data_builder
 * @property string      $body
 * @property string      $filename
 */
class FileGenerator extends Model implements EntityContract
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'filetype',
        'filename',
        'body',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Creates a new instance of the model.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = Config::get('ore.file-generator.table');
        $this->fillable = array_merge($this->fillable, array_keys(Config::get('ore.file-generator.attributes')));
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function data_builder()
    {
        return $this->belongsTo(DataBuilder::class);
    }
}
