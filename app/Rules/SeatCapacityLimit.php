<?php

namespace App\Rules;

use App\Models\Package;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class SeatCapacityLimit implements ValidationRule
{
    protected $packageId;
    protected $seatCount;

    public function __construct($packageId, $seatCount){
        $this->packageId = $packageId;
        $this->seatCount = $seatCount;
    }



    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $package = Package::find($this->packageId);

        if(!$package){
            $fail('Invalid package ID.');
            return;
        }

        if($this->seatCount > $package->capacity){
            $fail("The number of seats exceeds the package capacity of {$package->capacity}.");
        }
    }
}
