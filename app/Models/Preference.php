<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;

    class Preference extends Model
    {
        protected $table = 'user_preferences';

        protected $fillable = [
            'user_id',
            'currency',
            'rate',
            'unit',
            'availability',
            'hours_per_week',
            'remote_work',
            'open_to_work',
            'long_term',
        ];

        protected $casts = [
            'rate'        => 'decimal:2',
            'remote_work' => 'boolean',
            'open_to_work'=> 'boolean',
            'long_term'   => 'boolean',
        ];

        public function user(): BelongsTo
        {
            return $this->belongsTo(User::class);
        }
    }
