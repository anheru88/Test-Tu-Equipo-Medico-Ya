<?php

namespace App;


use Illuminate\Database\Eloquent\Model;


/**
 * App\File
 *
 * @property int $id
 * @property string $name
 * @property string $path_file
 * @property string $fields_file
 * @property string $fields_database
 * @property int $last_line
 * @property bool $finished
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\File whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\File whereFieldsDatabase($value)
 * @method static \Illuminate\Database\Query\Builder|\App\File whereFieldsFile($value)
 * @method static \Illuminate\Database\Query\Builder|\App\File whereFinished($value)
 * @method static \Illuminate\Database\Query\Builder|\App\File whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\File whereLastLine($value)
 * @method static \Illuminate\Database\Query\Builder|\App\File whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\File wherePathFile($value)
 * @method static \Illuminate\Database\Query\Builder|\App\File whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class File extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'path_file',
        'fields_file',
        'fields_database',
        'finished',
        'last_line'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [

    ];
}