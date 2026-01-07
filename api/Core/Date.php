<?php

namespace Api\Core;

class Date
{
    public static function short(string $datetime): string
    {
        return date('M j, Y', strtotime($datetime));
    }
    
    public static function long(string $datetime): string
    {
        return date('F j, Y', strtotime($datetime));
    }
    
    public static function relative(string $datetime): string
    {
        $time = strtotime($datetime);
        $diff = time() - $time;
        
        if ($diff < 60) {
            return 'just now';
        } elseif ($diff < 3600) {
            $mins = floor($diff / 60);
            return $mins . ' min' . ($mins > 1 ? 's' : '') . ' ago';
        } elseif ($diff < 86400) {
            $hours = floor($diff / 3600);
            return $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ago';
        } elseif ($diff < 604800) {
            $days = floor($diff / 86400);
            return $days . ' day' . ($days > 1 ? 's' : '') . ' ago';
        } else {
            return self::short($datetime);
        }
    }
    
    public static function monthDay(string $datetime): string
    {
        return date('M j', strtotime($datetime));
    }
}