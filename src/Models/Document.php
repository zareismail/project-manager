<?php

namespace Zareismail\ProjectManager\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia\{HasMedia, HasMediaTrait}; 
use Zareismail\Task\Concerns\InteractsWithTasks;
use Zareismail\Contracts\Concerns\Uniqueness;
use Zareismail\Task\Contracts\Taskable;

class Document extends AuthorizableModel implements Taskable, HasMedia
{
    use InteractsWithTasks, HasMediaTrait, Uniqueness;

    public function registerMediaCollections()
    {
    	$this->addMediaCollection('attachments');
    }
}
