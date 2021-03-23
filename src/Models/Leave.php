<?php

namespace Zareismail\ProjectManager\Models;

use Zareismail\ProjectManager\Helper;
use Zareismail\Task\Contracts\Taskable;
use Zareismail\Task\Concerns\InteractsWithTasks; 
use Zareismail\Markable\{Markable, HasDraft, Acceptable, HasPending, Rejectable};

class Leave extends AuthorizableModel implements Taskable
{  
	use InteractsWithTasks, Markable, HasDraft, Acceptable, HasPending, Rejectable;

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
    	'start_date' => 'date',
    	'end_date' => 'date',
    ]; 

	/**
	 * Query the related User.
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function substitute()
	{
		return $this->authenticatable('substitute_id');
	} 

	/**
	 * Determine if the "end date" was reached.
	 * 
	 * @return boolean
	 */
	public function expired(): bool
	{ 
		return $this->end_date->endOfDay()->lt(now());
	}
}
