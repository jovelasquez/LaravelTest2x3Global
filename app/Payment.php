<?php

namespace App;

use App\Filters\Filterable;
use Illuminate\Support\Str;
use App\Jobs\DolarExchangeJob;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\DispatchesJobs;

class Payment extends Model
{
    use Filterable;
    use DispatchesJobs;
    
    /**
     * Primary key
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table ="payments";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
        'payment_date',
        'expires_at',
        'status',
        'user_id',
        'clp_usd',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * Relationship with client
     *
     * @return void
     */
    public function client()
    {
        return $this->belongsTo('App\Client');
    }
    
    /**
     * Boot function
     *
     * Add saving observable function
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }
}
