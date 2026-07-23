<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    public function run(): void
    {
        $cities = [
            // Islamabad Capital Territory
            ['name' => 'Islamabad', 'province' => 'Islamabad Capital Territory'],

            // Punjab
            ['name' => 'Lahore', 'province' => 'Punjab'],
            ['name' => 'Faisalabad', 'province' => 'Punjab'],
            ['name' => 'Rawalpindi', 'province' => 'Punjab'],
            ['name' => 'Multan', 'province' => 'Punjab'],
            ['name' => 'Gujranwala', 'province' => 'Punjab'],
            ['name' => 'Sialkot', 'province' => 'Punjab'],
            ['name' => 'Bahawalpur', 'province' => 'Punjab'],
            ['name' => 'Sargodha', 'province' => 'Punjab'],
            ['name' => 'Sheikhupura', 'province' => 'Punjab'],
            ['name' => 'Gujrat', 'province' => 'Punjab'],
            ['name' => 'Kasur', 'province' => 'Punjab'],
            ['name' => 'Rahim Yar Khan', 'province' => 'Punjab'],
            ['name' => 'Sahiwal', 'province' => 'Punjab'],
            ['name' => 'Okara', 'province' => 'Punjab'],
            ['name' => 'Wah Cantonment', 'province' => 'Punjab'],
            ['name' => 'Dera Ghazi Khan', 'province' => 'Punjab'],
            ['name' => 'Chiniot', 'province' => 'Punjab'],
            ['name' => 'Hafizabad', 'province' => 'Punjab'],
            ['name' => 'Mandi Bahauddin', 'province' => 'Punjab'],
            ['name' => 'Jhelum', 'province' => 'Punjab'],
            ['name' => 'Khanewal', 'province' => 'Punjab'],
            ['name' => 'Mianwali', 'province' => 'Punjab'],
            ['name' => 'Layyah', 'province' => 'Punjab'],
            ['name' => 'Muzaffargarh', 'province' => 'Punjab'],
            ['name' => 'Vehari', 'province' => 'Punjab'],
            ['name' => 'Bhakkar', 'province' => 'Punjab'],
            ['name' => 'Pakpattan', 'province' => 'Punjab'],
            ['name' => 'Khushab', 'province' => 'Punjab'],
            ['name' => 'Narowal', 'province' => 'Punjab'],
            ['name' => 'Lodhran', 'province' => 'Punjab'],
            ['name' => 'Jhang', 'province' => 'Punjab'],
            ['name' => 'Chakwal', 'province' => 'Punjab'],
            ['name' => 'Attock', 'province' => 'Punjab'],
            ['name' => 'Toba Tek Singh', 'province' => 'Punjab'],
            ['name' => 'Nankana Sahib', 'province' => 'Punjab'],

            // Sindh
            ['name' => 'Karachi', 'province' => 'Sindh'],
            ['name' => 'Hyderabad', 'province' => 'Sindh'],
            ['name' => 'Sukkur', 'province' => 'Sindh'],
            ['name' => 'Larkana', 'province' => 'Sindh'],
            ['name' => 'Nawabshah', 'province' => 'Sindh'],
            ['name' => 'Mirpur Khas', 'province' => 'Sindh'],
            ['name' => 'Jacobabad', 'province' => 'Sindh'],
            ['name' => 'Shikarpur', 'province' => 'Sindh'],
            ['name' => 'Dadu', 'province' => 'Sindh'],
            ['name' => 'Khairpur', 'province' => 'Sindh'],
            ['name' => 'Badin', 'province' => 'Sindh'],
            ['name' => 'Thatta', 'province' => 'Sindh'],
            ['name' => 'Kashmore', 'province' => 'Sindh'],
            ['name' => 'Ghotki', 'province' => 'Sindh'],
            ['name' => 'Tando Allahyar', 'province' => 'Sindh'],
            ['name' => 'Umerkot', 'province' => 'Sindh'],
            ['name' => 'Sanghar', 'province' => 'Sindh'],

            // Khyber Pakhtunkhwa
            ['name' => 'Peshawar', 'province' => 'Khyber Pakhtunkhwa'],
            ['name' => 'Abbottabad', 'province' => 'Khyber Pakhtunkhwa'],
            ['name' => 'Mardan', 'province' => 'Khyber Pakhtunkhwa'],
            ['name' => 'Swat', 'province' => 'Khyber Pakhtunkhwa'],
            ['name' => 'Kohat', 'province' => 'Khyber Pakhtunkhwa'],
            ['name' => 'Bannu', 'province' => 'Khyber Pakhtunkhwa'],
            ['name' => 'Dera Ismail Khan', 'province' => 'Khyber Pakhtunkhwa'],
            ['name' => 'Charsadda', 'province' => 'Khyber Pakhtunkhwa'],
            ['name' => 'Nowshera', 'province' => 'Khyber Pakhtunkhwa'],
            ['name' => 'Manshera', 'province' => 'Khyber Pakhtunkhwa'],
            ['name' => 'Swabi', 'province' => 'Khyber Pakhtunkhwa'],
            ['name' => 'Haripur', 'province' => 'Khyber Pakhtunkhwa'],
            ['name' => 'Batkhela', 'province' => 'Khyber Pakhtunkhwa'],
            ['name' => 'Timergara', 'province' => 'Khyber Pakhtunkhwa'],
            ['name' => 'Hangu', 'province' => 'Khyber Pakhtunkhwa'],
            ['name' => 'Parachinar', 'province' => 'Khyber Pakhtunkhwa'],
            ['name' => 'Tank', 'province' => 'Khyber Pakhtunkhwa'],
            ['name' => 'Upper Dir', 'province' => 'Khyber Pakhtunkhwa'],
            ['name' => 'Lower Dir', 'province' => 'Khyber Pakhtunkhwa'],

            // Balochistan
            ['name' => 'Quetta', 'province' => 'Balochistan'],
            ['name' => 'Gwadar', 'province' => 'Balochistan'],
            ['name' => 'Turbat', 'province' => 'Balochistan'],
            ['name' => 'Khuzdar', 'province' => 'Balochistan'],
            ['name' => 'Chaman', 'province' => 'Balochistan'],
            ['name' => 'Hub', 'province' => 'Balochistan'],
            ['name' => 'Zhob', 'province' => 'Balochistan'],
            ['name' => 'Sibi', 'province' => 'Balochistan'],
            ['name' => 'Ziarat', 'province' => 'Balochistan'],
            ['name' => 'Dera Murad Jamali', 'province' => 'Balochistan'],
            ['name' => 'Loralai', 'province' => 'Balochistan'],
            ['name' => 'Panjgur', 'province' => 'Balochistan'],
            ['name' => 'Nushki', 'province' => 'Balochistan'],
            ['name' => 'Mastung', 'province' => 'Balochistan'],
            ['name' => 'Kalat', 'province' => 'Balochistan'],
            ['name' => 'Pasni', 'province' => 'Balochistan'],

            // Gilgit-Baltistan
            ['name' => 'Gilgit', 'province' => 'Gilgit-Baltistan'],
            ['name' => 'Skardu', 'province' => 'Gilgit-Baltistan'],
            ['name' => 'Hunza', 'province' => 'Gilgit-Baltistan'],
            ['name' => 'Chilas', 'province' => 'Gilgit-Baltistan'],
            ['name' => 'Gahkuch', 'province' => 'Gilgit-Baltistan'],
            ['name' => 'Khaplu', 'province' => 'Gilgit-Baltistan'],
            ['name' => 'Astore', 'province' => 'Gilgit-Baltistan'],
            ['name' => 'Diamer', 'province' => 'Gilgit-Baltistan'],

            // Azad Jammu & Kashmir
            ['name' => 'Muzaffarabad', 'province' => 'Azad Jammu & Kashmir'],
            ['name' => 'Mirpur', 'province' => 'Azad Jammu & Kashmir'],
            ['name' => 'Rawalakot', 'province' => 'Azad Jammu & Kashmir'],
            ['name' => 'Kotli', 'province' => 'Azad Jammu & Kashmir'],
            ['name' => 'Bhimber', 'province' => 'Azad Jammu & Kashmir'],
            ['name' => 'Bagh', 'province' => 'Azad Jammu & Kashmir'],
            ['name' => 'Palandri', 'province' => 'Azad Jammu & Kashmir'],
            ['name' => 'Hattian Bala', 'province' => 'Azad Jammu & Kashmir'],
        ];

        foreach ($cities as $city) {
            City::create($city);
        }
    }
}
