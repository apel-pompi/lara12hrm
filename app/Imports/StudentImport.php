<?php

namespace App\Imports;

use App\Models\Default\Country;
use App\Models\Student\Student;
use App\Models\Student\StudentSource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class StudentImport implements ToModel, WithHeadingRow
{
    private $importedPhones = [];
    private $processedCount = 0;
    private $successCount = 0;
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        if ($this->isEmptyRow($row)) {

            return null;
        }

        $this->processedCount++;

        try {

            // Extract and validate basic data
            $firstName = $row['first_name'] ?? null;
            $lastName = $row['last_name'] ?? null;
            $gender = $row['gender'] ?? null;
            $phone = $row['phone'] ?? null;
            $destinationCountry = $row['destination_country'] ?? null;
            $source = $row['source'] ?? null;
            $counsilorName = $row['counsilor_name'] ?? null;

            $this->validateRequiredFields($firstName, $lastName, $gender, $phone, $destinationCountry, $source, $counsilorName);

            // Phone number processing
            $cleanPhone = $this->extractPhone($row);
            $this->validatePhoneUniqueness($cleanPhone);

            // Process all data
            $genderId = $this->convertGender($gender);
            $countryId = $this->findCountryId($destinationCountry);
            $sourceId = $this->findSourceId($source);
            $counsilorId = $this->findCounsilorId($counsilorName);

            $this->successCount++;

            return new Student([
                'fname' => $firstName,
                'lname' => $lastName,
                'gender' => $genderId,
                'phone' => $cleanPhone,
                'descountry_id' => $countryId,
                'source_id' => $sourceId,
                'assain_user' => $counsilorId,
                'user_id' => Auth::id(),
            ]);
        } catch (\Exception $e) {
            throw new \Exception("Row processing failed: " . $e->getMessage());
        }
    }

    private function isEmptyRow($row)
    {
        // Check if all values are empty/null
        $hasData = false;

        foreach ($row as $key => $value) {
            // Skip numeric keys (they are usually extra columns)
            if (is_numeric($key)) {
                continue;
            }

            // If any non-empty value exists, it's not an empty row
            if (!empty(trim($value ?? ''))) {
                $hasData = true;
                break;
            }
        }

        return !$hasData;
    }

    private function validateRequiredFields($firstName, $lastName, $gender, $phone, $destinationCountry, $source, $counsilorName)
    {
        $errors = [];

        if (empty($firstName)) $errors[] = "first_name is required";
        if (empty($lastName)) $errors[] = "last_name is required";
        if (empty($gender)) $errors[] = "gender is required";
        if (empty($phone)) $errors[] = "phone is required";
        if (empty($destinationCountry)) $errors[] = "destination_country is required";
        if (empty($source)) $errors[] = "source is required";
        if (empty($counsilorName)) $errors[] = "counsilor_name is required";

        if (!empty($errors)) {
            throw new \Exception(implode(', ', $errors));
        }
    }

    // Helper methods
    private function extractPhone($row)
    {
        $phone = $row['phone'] ?? null;

        if (!$phone) {
            throw new \Exception("Phone number is required");
        }

        // Remove any non-numeric characters except +
        $phone = preg_replace('/[^\d+]/', '', (string)$phone);
        //Phone no must be exactly 11 digits starting with 0
        $cleanNumber = str_replace('+', '', $phone);
        if (strlen($cleanNumber) !== 11) {
            throw new \Exception("Phone number must be exactly 11 digits: {$phone}");
        }

        if (empty($phone)) {
            throw new \Exception("Phone number is invalid");
        }
        return $phone;
    }

    private function validatePhoneUniqueness($phone)
    {
        if (!$phone) {
            throw new \Exception("Phone number is required");
        }

        // Check duplicate in current import batch
        if (in_array($phone, $this->importedPhones)) {
            throw new \Exception("Duplicate phone number: {$phone}");
        }

        // Check duplicate in database
        if (Student::where('phone', $phone)->exists()) {
            throw new \Exception("Phone number already exists in system: {$phone}");
        }

        $this->importedPhones[] = $phone;
    }

    private function convertGender($genderInput)
    {
        if (!$genderInput) return 3; // Default to Other

        $genderMap = [
            'male' => 1,
            'female' => 2,
            'other' => 3,
        ];

        $normalizedGender = strtolower(trim($genderInput));

        return $genderMap[$normalizedGender] ?? 3; // Default to Other if not found
    }

    private function findCountryId($countryName)
    {
        if (!$countryName) {
            throw new \Exception("Destination country is required");
        }

        $country = Country::where('name', 'like', '%' . $countryName . '%')->first();

        if (!$country) {
            throw new \Exception("Country not found: {$countryName}");
        }

        return $country->id;
    }

    private function findSourceId($sourceName)
    {
        if (!$sourceName) {
            throw new \Exception("Source is required");
        }

        $source = StudentSource::where('name', 'like', '%' . $sourceName . '%')->first();

        if (!$source) {
            throw new \Exception("Student source not found: {$sourceName}");
        }

        return $source->id;
    }

    private function findCounsilorId($username)
    {
        if (!$username) {
            throw new \Exception("Counsilor name is required");
        }

        $user = User::where('username', $username)->first();

        if (!$user) {
            throw new \Exception("Counsilor not found: {$username}");
        }

        return $user->id;
    }

    public function getProcessedCount()
    {
        return $this->processedCount;
    }

    public function getSuccessCount()
    {
        return $this->successCount;
    }
}
