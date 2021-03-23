<?php

namespace Zareismail\ProjectManager;

use Illuminate\Support\Stringable;

class Helper 
{   
    /**
     * Prefix the table name with unique string.
     * 
     * @param  string $table 
     * @return string        
     */
    public static function prefixTable(string $table): string
    {
        return with(new Stringable($table), function($string) {
            return strval(
                $string->startsWith(static::prefix(), $string) ? $string : $string->prepend(static::prefix())
            );
        }); 
    }

    /**
     * The prefix string of table.
     * 
     * @return string
     */
    public static function prefix(): string
    {
        return 'pm_';
    }
}
