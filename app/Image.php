<?php
namespace App;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Image
 *
 * @property int $id
 * @property string $name
 * @property string $external_url
 * @property string $internal_url
 * @property bool $downloaded
 * @property int $product_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Product $product
 * @method static \Illuminate\Database\Query\Builder|\App\Image whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Image whereDownloaded($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Image whereExternalUrl($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Image whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Image whereInternalUrl($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Image whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Image whereProductId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Image whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Image extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'external_url',
        'downloaded',
        'internal_url'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'downloaded',
        'external_url'
    ];

    /**
     * Regresa el producto al cual esta asociado la imagen.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo('App\Product');
    }

}