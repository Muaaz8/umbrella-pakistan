<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = ['name', 'nearby'];
    
    /**
     * Get the nearby locations as an array
     *
     * @return array
     */
    public function getNearbyLocationsArray()
    {
        if (empty($this->nearby)) {
            return [];
        }
        
        return array_map('trim', explode(',', $this->nearby));
    }
    
    /**
     * Set the nearby locations from an array
     *
     * @param array $locations
     * @return void
     */
    public function setNearbyLocationsFromArray(array $locations)
    {
        $this->nearby = implode(', ', array_filter($locations));
        return $this;
    }
}