<?php

namespace App\Enums\Types;

use App\Http\Traits\HandleEnum;

enum BusinessType: string
{
    use HandleEnum;

    case PUBLIC = 'Public';
    case PRIVATE_COMPANY = 'Private Company';
    case STORE = 'Store';
    case RESIDENCE = 'Residence';
    case COMMERCIAL_BUILDING = 'Commercial Building';
    case GAS_STATION = 'Gas Station';
    case RESORT = 'Resort';
    case WAREHOUSE = 'Warehouse';
    case MALL = 'Mall';
    case OFFICE_BUILDING = 'Office Building';
    case RESTAURANT = 'Restaurant';
    case HOTEL = 'Hotel';
    case SUPERMARKET = 'Supermarket';
    case CONVENIENCE_STORE = 'Convenience Store';
    case PARKING_LOT = 'Parking Lot';
    case FACTORY = 'Factory';
    case PLANT = 'Plant';
    case CONSTRUCTION_SITE = 'Construction Site';
    case SCHOOL = 'School';
    case UNIVERSITY = 'University';
    case HOSPITAL = 'Hospital';
    case CLINIC = 'Clinic';
    case CHURCH = 'Church';
    case GOVERNMENT_OFFICE = 'Government Office';
    case BARANGAY_HALL = 'Barangay Hall';
    case POLICE_STATION = 'Police Station';
    case FIRE_STATION = 'Fire Station';
    case APARTMENT = 'Apartment';
    case SUBDIVISION = 'Subdivision';
    case GATED_COMMUNITY = 'Gated Community';
    case BANK = 'Bank';
    case PAWNSHOP = 'Pawnshop';
    case TELECOM_TOWER_SITE = 'Telecom Tower Site';
    case TRANSPORTATION_TERMINAL = 'Transportation Terminal';
    case FARM = 'Farm';
    case WATER_STATION = 'Water Station';

    /**
     * Meta properties.
     *
     * @return array
     */
    public static function metaProperties(): array
    {
        return array_map(fn($case) => [
            'value' => $case,
            'title' => $case->value,
        ], self::cases());
    }
}
