<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $locations = [
            // Bangladesh - All 8 Divisions
            ['name' => 'Dhaka', 'city' => 'Dhaka', 'country' => 'Bangladesh', 'latitude' => 23.8103, 'longitude' => 90.4125, 'timezone' => 'Asia/Dhaka', 'calculation_method' => 'KARACHI'],
            ['name' => 'Chattogram (Chittagong)', 'city' => 'Chattogram', 'country' => 'Bangladesh', 'latitude' => 22.3569, 'longitude' => 91.7832, 'timezone' => 'Asia/Dhaka', 'calculation_method' => 'KARACHI'],
            ['name' => 'Rajshahi', 'city' => 'Rajshahi', 'country' => 'Bangladesh', 'latitude' => 24.3745, 'longitude' => 88.6042, 'timezone' => 'Asia/Dhaka', 'calculation_method' => 'KARACHI'],
            ['name' => 'Khulna', 'city' => 'Khulna', 'country' => 'Bangladesh', 'latitude' => 22.8456, 'longitude' => 89.5403, 'timezone' => 'Asia/Dhaka', 'calculation_method' => 'KARACHI'],
            ['name' => 'Barishal (Barisal)', 'city' => 'Barishal', 'country' => 'Bangladesh', 'latitude' => 22.7010, 'longitude' => 90.3535, 'timezone' => 'Asia/Dhaka', 'calculation_method' => 'KARACHI'],
            ['name' => 'Sylhet', 'city' => 'Sylhet', 'country' => 'Bangladesh', 'latitude' => 24.8949, 'longitude' => 91.8687, 'timezone' => 'Asia/Dhaka', 'calculation_method' => 'KARACHI'],
            ['name' => 'Rangpur', 'city' => 'Rangpur', 'country' => 'Bangladesh', 'latitude' => 25.7439, 'longitude' => 89.2752, 'timezone' => 'Asia/Dhaka', 'calculation_method' => 'KARACHI'],
            ['name' => 'Mymensingh', 'city' => 'Mymensingh', 'country' => 'Bangladesh', 'latitude' => 24.7471, 'longitude' => 90.4203, 'timezone' => 'Asia/Dhaka', 'calculation_method' => 'KARACHI'],
            
            // Bangladesh - Other Major Cities
            ['name' => 'Gazipur', 'city' => 'Gazipur', 'country' => 'Bangladesh', 'latitude' => 23.9999, 'longitude' => 90.4203, 'timezone' => 'Asia/Dhaka', 'calculation_method' => 'KARACHI'],
            ['name' => 'Narayanganj', 'city' => 'Narayanganj', 'country' => 'Bangladesh', 'latitude' => 23.6238, 'longitude' => 90.5000, 'timezone' => 'Asia/Dhaka', 'calculation_method' => 'KARACHI'],
            ['name' => 'Comilla', 'city' => 'Comilla', 'country' => 'Bangladesh', 'latitude' => 23.4607, 'longitude' => 91.1809, 'timezone' => 'Asia/Dhaka', 'calculation_method' => 'KARACHI'],
            ['name' => 'Cox\'s Bazar', 'city' => 'Cox\'s Bazar', 'country' => 'Bangladesh', 'latitude' => 21.4272, 'longitude' => 92.0058, 'timezone' => 'Asia/Dhaka', 'calculation_method' => 'KARACHI'],
            ['name' => 'Bogura', 'city' => 'Bogura', 'country' => 'Bangladesh', 'latitude' => 24.8465, 'longitude' => 89.3770, 'timezone' => 'Asia/Dhaka', 'calculation_method' => 'KARACHI'],
            ['name' => 'Jessore', 'city' => 'Jessore', 'country' => 'Bangladesh', 'latitude' => 23.1697, 'longitude' => 89.2086, 'timezone' => 'Asia/Dhaka', 'calculation_method' => 'KARACHI'],
            ['name' => 'Dinajpur', 'city' => 'Dinajpur', 'country' => 'Bangladesh', 'latitude' => 25.6217, 'longitude' => 88.6354, 'timezone' => 'Asia/Dhaka', 'calculation_method' => 'KARACHI'],
            
            // Pakistan
            ['name' => 'Karachi', 'city' => 'Karachi', 'country' => 'Pakistan', 'latitude' => 24.8607, 'longitude' => 67.0011, 'timezone' => 'Asia/Karachi', 'calculation_method' => 'KARACHI'],
            ['name' => 'Lahore', 'city' => 'Lahore', 'country' => 'Pakistan', 'latitude' => 31.5204, 'longitude' => 74.3587, 'timezone' => 'Asia/Karachi', 'calculation_method' => 'KARACHI'],
            ['name' => 'Islamabad', 'city' => 'Islamabad', 'country' => 'Pakistan', 'latitude' => 33.6844, 'longitude' => 73.0479, 'timezone' => 'Asia/Karachi', 'calculation_method' => 'KARACHI'],
            
            // Saudi Arabia
            ['name' => 'Makkah', 'city' => 'Makkah', 'country' => 'Saudi Arabia', 'latitude' => 21.3891, 'longitude' => 39.8579, 'timezone' => 'Asia/Riyadh', 'calculation_method' => 'MAKKAH'],
            ['name' => 'Madinah', 'city' => 'Madinah', 'country' => 'Saudi Arabia', 'latitude' => 24.5247, 'longitude' => 39.5692, 'timezone' => 'Asia/Riyadh', 'calculation_method' => 'MAKKAH'],
            ['name' => 'Riyadh', 'city' => 'Riyadh', 'country' => 'Saudi Arabia', 'latitude' => 24.7136, 'longitude' => 46.6753, 'timezone' => 'Asia/Riyadh', 'calculation_method' => 'MAKKAH'],
            ['name' => 'Jeddah', 'city' => 'Jeddah', 'country' => 'Saudi Arabia', 'latitude' => 21.2854, 'longitude' => 39.2376, 'timezone' => 'Asia/Riyadh', 'calculation_method' => 'MAKKAH'],
            
            // UAE
            ['name' => 'Dubai', 'city' => 'Dubai', 'country' => 'UAE', 'latitude' => 25.2048, 'longitude' => 55.2708, 'timezone' => 'Asia/Dubai', 'calculation_method' => 'DUBAI'],
            ['name' => 'Abu Dhabi', 'city' => 'Abu Dhabi', 'country' => 'UAE', 'latitude' => 24.4539, 'longitude' => 54.3773, 'timezone' => 'Asia/Dubai', 'calculation_method' => 'DUBAI'],
            
            // Egypt
            ['name' => 'Cairo', 'city' => 'Cairo', 'country' => 'Egypt', 'latitude' => 30.0444, 'longitude' => 31.2357, 'timezone' => 'Africa/Cairo', 'calculation_method' => 'EGYPT'],
            ['name' => 'Alexandria', 'city' => 'Alexandria', 'country' => 'Egypt', 'latitude' => 31.2001, 'longitude' => 29.9187, 'timezone' => 'Africa/Cairo', 'calculation_method' => 'EGYPT'],
            
            // Turkey
            ['name' => 'Istanbul', 'city' => 'Istanbul', 'country' => 'Turkey', 'latitude' => 41.0082, 'longitude' => 28.9784, 'timezone' => 'Europe/Istanbul', 'calculation_method' => 'TURKEY'],
            ['name' => 'Ankara', 'city' => 'Ankara', 'country' => 'Turkey', 'latitude' => 39.9334, 'longitude' => 32.8597, 'timezone' => 'Europe/Istanbul', 'calculation_method' => 'TURKEY'],
            
            // Indonesia
            ['name' => 'Jakarta', 'city' => 'Jakarta', 'country' => 'Indonesia', 'latitude' => -6.2088, 'longitude' => 106.8456, 'timezone' => 'Asia/Jakarta', 'calculation_method' => 'KEMENAG'],
            ['name' => 'Surabaya', 'city' => 'Surabaya', 'country' => 'Indonesia', 'latitude' => -7.2575, 'longitude' => 112.7521, 'timezone' => 'Asia/Jakarta', 'calculation_method' => 'KEMENAG'],
            
            // Malaysia
            ['name' => 'Kuala Lumpur', 'city' => 'Kuala Lumpur', 'country' => 'Malaysia', 'latitude' => 3.1390, 'longitude' => 101.6869, 'timezone' => 'Asia/Kuala_Lumpur', 'calculation_method' => 'JAKIM'],
            
            // India
            ['name' => 'Delhi', 'city' => 'Delhi', 'country' => 'India', 'latitude' => 28.7041, 'longitude' => 77.1025, 'timezone' => 'Asia/Kolkata', 'calculation_method' => 'KARACHI'],
            ['name' => 'Mumbai', 'city' => 'Mumbai', 'country' => 'India', 'latitude' => 19.0760, 'longitude' => 72.8777, 'timezone' => 'Asia/Kolkata', 'calculation_method' => 'KARACHI'],
            
            // UK
            ['name' => 'London', 'city' => 'London', 'country' => 'United Kingdom', 'latitude' => 51.5074, 'longitude' => -0.1278, 'timezone' => 'Europe/London', 'calculation_method' => 'MWL'],
            
            // USA
            ['name' => 'New York', 'city' => 'New York', 'country' => 'USA', 'latitude' => 40.7128, 'longitude' => -74.0060, 'timezone' => 'America/New_York', 'calculation_method' => 'ISNA'],
            ['name' => 'Los Angeles', 'city' => 'Los Angeles', 'country' => 'USA', 'latitude' => 34.0522, 'longitude' => -118.2437, 'timezone' => 'America/Los_Angeles', 'calculation_method' => 'ISNA'],
            
            // Canada
            ['name' => 'Toronto', 'city' => 'Toronto', 'country' => 'Canada', 'latitude' => 43.6532, 'longitude' => -79.3832, 'timezone' => 'America/Toronto', 'calculation_method' => 'ISNA'],
        ];

        foreach ($locations as $location) {
            Location::updateOrCreate(
                ['name' => $location['name'], 'country' => $location['country']],
                $location
            );
        }
    }
}
