<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\Notifiable;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, Sluggable;

    protected $guard = 'admin';

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name',
            ],
        ];
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'first_name',
        'last_name',
        'designation',
        'password',
        'slug',
        'profile_image',
        'zoom_access_token',
        'zoom_refresh_token',
        'zoom_client_id',
        'zoom_client_secret',
        'zoom_email',
        'email_signature',
        'description_character_count',
        'about_us_image',
        'front_logo',
        'about_us_title',
        'about_us_description',
        'copyright_text',
        'business_name',
        'front_title',
        'front_description',
        'qualification_text',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function media()
    {
        return $this->belongsToMany(Media::class, 'media_organization');
    }

    public function blogs()
    {
        return $this->hasMany(Blog::class, 'author');
    }

    public function getOrganizationImages() {
        $imageList = DB::table('media')
            ->join('media_organization', 'media_organization.media_id', '=', 'media.id')
            ->where('user_id', $this->id)
            ->orderBy('media_organization.position', 'ASC')
            ->orderBy('media_organization.sort_order', 'ASC')
            ->get([
                'media.path',
                'media.name',
                'media_organization.position',
            ]);

        $organization = [];
        foreach ($imageList as $key => $_list) {
            $organization[$_list->position][] = Storage::disk('public')->url($_list->path . '/' . $_list->name);
        }

        return $organization;
    }

    public function getTestimonials() {
        $testimonials = Testimonial::where('user_id', $this->id)
        ->leftJoin('media', 'media.id', '=', 'testimonials.testimonial_image')
        ->get(['testimonials.id', 'testimonials.name', 'testimonials.designation', 'testimonials.review', 'media.name as media_name', 'media.path']);

        $result = [];
        foreach ($testimonials as $key => $value) {
            $result[$value->id] = [
                'name' => $value->name,
                'designation' => $value->designation,
                'review' => $value->review,
                'image' => Storage::disk('public')->url($value->path . '/' . $value->media_name),
            ];
        }

        return $result;
    }
}
