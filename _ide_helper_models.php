<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property string $company_name
 * @property string|null $nib
 * @property string|null $address
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Developer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Developer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Developer query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Developer whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Developer whereCompanyName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Developer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Developer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Developer whereNib($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Developer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Developer whereUserId($value)
 */
	class Developer extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $housing_project_id
 * @property string $name
 * @property string $status
 * @property string|null $image
 * @property string|null $floor_plan
 * @property int $price
 * @property int $land_area
 * @property int $building_area
 * @property int $total_units
 * @property string|null $specifications
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\HouseUnit> $houseUnits
 * @property-read int|null $house_units_count
 * @property-read \App\Models\HousingProject $housingProject
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HouseType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HouseType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HouseType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HouseType whereBuildingArea($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HouseType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HouseType whereFloorPlan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HouseType whereHousingProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HouseType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HouseType whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HouseType whereLandArea($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HouseType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HouseType wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HouseType whereSpecifications($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HouseType whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HouseType whereTotalUnits($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HouseType whereUpdatedAt($value)
 */
	class HouseType extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $housing_project_id
 * @property int $house_type_id
 * @property string $block
 * @property string $unit_number
 * @property string $price
 * @property string $status
 * @property string $type
 * @property string|null $unit_coordinates
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HouseUnit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HouseUnit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HouseUnit query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HouseUnit whereBlock($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HouseUnit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HouseUnit whereHouseTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HouseUnit whereHousingProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HouseUnit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HouseUnit wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HouseUnit whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HouseUnit whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HouseUnit whereUnitCoordinates($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HouseUnit whereUnitNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HouseUnit whereUpdatedAt($value)
 */
	class HouseUnit extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $address
 * @property string|null $district_code
 * @property string|null $village_code
 * @property string|null $description
 * @property string|null $latitude
 * @property string|null $longitude
 * @property string $developer_name
 * @property int|null $developer_id
 * @property string $type
 * @property string|null $image
 * @property string|null $site_plan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Laravolt\Indonesia\Models\District|null $district
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\HouseType> $houseTypes
 * @property-read int|null $house_types_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\HouseUnit> $houseUnits
 * @property-read int|null $house_units_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ProjectImage> $images
 * @property-read int|null $images_count
 * @property-read \Laravolt\Indonesia\Models\Village|null $village
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HousingProject newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HousingProject newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HousingProject query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HousingProject whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HousingProject whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HousingProject whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HousingProject whereDeveloperId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HousingProject whereDeveloperName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HousingProject whereDistrictCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HousingProject whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HousingProject whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HousingProject whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HousingProject whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HousingProject whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HousingProject whereSitePlan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HousingProject whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HousingProject whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HousingProject whereVillageCode($value)
 */
	class HousingProject extends \Eloquent {}
}

namespace App\Models\Indonesia{
/**
 * @property int $id
 * @property string $code
 * @property string $province_code
 * @property string $name
 * @property string|null $meta
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City whereMeta($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City whereProvinceCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City whereUpdatedAt($value)
 */
	class City extends \Eloquent {}
}

namespace App\Models\Indonesia{
/**
 * @property string $id
 * @property string $code
 * @property string $city_code
 * @property string $name
 * @property array<array-key, mixed>|null $meta
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property-read \Laravolt\Indonesia\Models\City $city
 * @property-read mixed $city_name
 * @property-read mixed $province_name
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravolt\Indonesia\Models\Village> $villages
 * @property-read int|null $villages_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|District newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|District newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|District query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|District search($keyword)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|District whereCityCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|District whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|District whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|District whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|District whereMeta($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|District whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|District whereUpdatedAt($value)
 */
	class District extends \Eloquent {}
}

namespace App\Models\Indonesia{
/**
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string|null $meta
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Province newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Province newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Province query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Province whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Province whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Province whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Province whereMeta($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Province whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Province whereUpdatedAt($value)
 */
	class Province extends \Eloquent {}
}

namespace App\Models\Indonesia{
/**
 * @property string $id
 * @property string $code
 * @property string $district_code
 * @property string $name
 * @property array<array-key, mixed>|null $meta
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property-read \Laravolt\Indonesia\Models\District $district
 * @property-read mixed $city_name
 * @property-read mixed $district_name
 * @property-read mixed $province_name
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Village newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Village newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Village query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Village search($keyword)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Village whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Village whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Village whereDistrictCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Village whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Village whereMeta($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Village whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Village whereUpdatedAt($value)
 */
	class Village extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $housing_project_id
 * @property string $path
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\HousingProject $housingProject
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProjectImage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProjectImage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProjectImage query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProjectImage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProjectImage whereHousingProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProjectImage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProjectImage wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProjectImage whereUpdatedAt($value)
 */
	class ProjectImage extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User role($roles, $guard = null, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutRole($roles, $guard = null)
 */
	class User extends \Eloquent {}
}

