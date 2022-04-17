<?php

namespace App;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Hash;
use function explode;
use function str_random;
use function strpos;

/**
 * @property int $id
 * @property string $net_id
 * @property Role $role
 * @property \Illuminate\Database\Eloquent\Collection|Food[] $foods
 * @property \Illuminate\Database\Eloquent\Collection|Menu[] $menus
 * @property string $api_token
 * @property \Carbon\Carbon $api_token_expiration
 * @property string $password
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 * @property array $attributes
 * @method static Builder|User[]  whereApiToken($token)
 * @method Builder|Station findOrFail($id, $columns = array())
 * @mixin \Illuminate\Database\Eloquent\Model
 */
class User extends Model implements Authenticatable
{
    use SoftDeletes;

    /** @var array */
    protected $guarded = [];

    /** @var array */
    protected $dates = [
        'api_token_expiration',
        'created_at',
        'updated_at',
    ];

    public static function generateToken()
    {
        do {
            $token = str_random(32);

            $user = User::whereApiToken($token)->first();
        } while ($user);

        return $token;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function foods()
    {
        return $this->belongsToMany(Food::class, 'food_logs')
            ->withPivot([
                'menu_id',
                'meal_block',
                'servings',
                'created_at',
            ]);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'food_logs')
            ->withPivot([
                'food_id',
                'meal_block',
                'servings',
                'created_at',
            ]);
    }

    /**
     * Fetch the email for the associated user based off of net id
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->net_id . '@iastate.edu';
    }

    /**
     * @return string
     */
    public function getApiTokenAttribute()
    {
        return '';
    }

    /**
     * @return string
     */
    public function getPasswordAttribute()
    {
        return '';
    }

    /**
     * @param $password string
     */
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = Hash::make($password);
    }

    /**
     * @param $netId string
     */
    public function setNetIdAttribute($netId)
    {
        // Convert netId to just netId, no email address
        if (strpos($netId, '@')) {
            $netId = explode('@', $netId)[0];
        }

        $this->attributes['net_id'] = $netId;
    }

    public function getAuthIdentifierName()
    {
        // TODO: Implement getAuthIdentifierName() method.
    }

    public function getAuthIdentifier()
    {
        // TODO: Implement getAuthIdentifier() method.
    }

    public function getAuthPassword()
    {
        return $this->getOriginal('password');
    }

    public function getRememberToken()
    {
        return '';
    }

    public function setRememberToken($value)
    {
        return '';
    }

    public function getRememberTokenName()
    {
        return '';
    }


}