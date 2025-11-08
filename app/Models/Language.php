<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

/**
 * Language Model
 *
 * Represents a language in the system.
 *
 * @property int $id
 * @property string $language
 * @property string $name
 * @property bool $isDefault
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Translation> $translations
 */
class Language extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'languages';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'language',
        'name',
        'is_default',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_default' => 'boolean',
        ];
    }

    /**
     * Get the translations for this language.
     *
     * @return HasMany<Translation>
     */
    public function translations(): HasMany
    {
        return $this->hasMany(Translation::class, 'locale', 'language');
    }

    /**
     * Get the default language (cached).
     *
     * @return Language|null
     */
    public static function getDefaultLanguage(): ?self
    {
        return Cache::rememberForever('default_language', function () {
            return self::where('is_default', true)->first();
        });
    }

    /**
     * Forget cached language data.
     *
     * @return void
     */
    public static function forgetCachedLanguage(): void
    {
        Cache::forget('default_language');
        Cache::forget('languages_list');
    }

    /**
     * Set the default language and update cache.
     *
     * @param int $id
     * @return void
     */
    public static function setDefaultLanguage(int $id): void
    {
        self::query()->update(['is_default' => false]);

        $language = self::findOrFail($id);
        $language->is_default = true;
        $language->save();

        setcookie('language', $language->language, time() + (86400 * 365), '/');
        Cache::forever('default_language', $language);

        session(['locale' => $language->language]);
        app()->setLocale($language->language);
        config(['app.locale' => $language->language]);

        Artisan::call('cache:clear');
        Artisan::call('config:clear');
    }
}

