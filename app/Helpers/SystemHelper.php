<?php

use App\Models\Tenant;
use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\DNSCheckValidation;
use Egulias\EmailValidator\Validation\MultipleValidationWithAnd;
use Egulias\EmailValidator\Validation\RFCValidation;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

if (!function_exists('includeFilesInFolder')) {
    /**
     * Loops through a folder and requires all PHP files
     * Searches sub-directories as well.
     *
     * @param $folder
     */
    function includeFilesInFolder($folder)
    {
        try {
            $rdi = new RecursiveDirectoryIterator($folder);
            $it = new RecursiveIteratorIterator($rdi);

            while ($it->valid()) {
                if (!$it->isDot() && $it->isFile() && $it->isReadable() && $it->current()->getExtension() === 'php') {
                    require $it->key();
                }

                $it->next();
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}

if (!function_exists('includeRouteFiles')) {
    /**
     * @param $folder
     */
    function includeRouteFiles($folder)
    {
        includeFilesInFolder($folder);
    }
}

if (!function_exists('array_merge_recursive_distinct')) {
    function array_merge_recursive_distinct(array &$array1, array &$array2): array
    {
        $merged = $array1;
        foreach ($array2 as $key => &$value) {
            if (is_array($value) && isset($merged[$key]) && is_array($merged[$key])) {
                $merged[$key] = array_merge_recursive_distinct($merged[$key], $value);
            } else {
                $merged[$key] = $value;
            }
        }

        return $merged;
    }
}

if (!function_exists('getFullPhoneNumber')) {
    /**
     * Returns the full Russian mobile phone number (11 digits) in international format. For example:
     *  "+7 (999) 888-77-66" => "+79998887766"
     *  or "9998887766" => "+79998887766"
     *  or "89998887766" => "+79998887766"
     *  or "999888776" => "7999888776" (at least 10 digits required)
     *
     * @param string|null $number
     *
     * @return string|null
     */
    function getFullPhoneNumber(?string $number): ?string
    {
        if ($number) {
            $number = preg_replace("/\D+/", '', $number);
            if (Str::length($number) === 11 && Str::startsWith($number, "8")) {
                $number = Str::substr($number, 1);
            }
            if (Str::length($number) === 10 && !Str::startsWith($number, "7")) {
                $number = '7' . $number;
            }
            if (Str::length($number) === 11 && Str::startsWith($number, "7")) {
                $number = '+' . $number;
            }
        }

        return $number;
    }
}

if (!function_exists('formatPhoneNumber')) {
    /**
     * "+79998887766" => "+7 999 888-77-66"
     *
     * @see https://snipp.ru/php/phone-format
     *
     * @param string|null $value
     *
     * @return string|null
     */
    function formatPhoneNumber(?string $value): ?string
    {
        if (is_null($value)) {
            return null;
        }

        return preg_replace(
            [
                '/[\+]?([7|8])[-|\s]?\([-|\s]?(\d{3})[-|\s]?\)[-|\s]?(\d{3})[-|\s]?(\d{2})[-|\s]?(\d{2})/',
                '/[\+]?([7|8])[-|\s]?(\d{3})[-|\s]?(\d{3})[-|\s]?(\d{2})[-|\s]?(\d{2})/',
                '/[\+]?([7|8])[-|\s]?\([-|\s]?(\d{4})[-|\s]?\)[-|\s]?(\d{2})[-|\s]?(\d{2})[-|\s]?(\d{2})/',
                '/[\+]?([7|8])[-|\s]?(\d{4})[-|\s]?(\d{2})[-|\s]?(\d{2})[-|\s]?(\d{2})/',
                '/[\+]?([7|8])[-|\s]?\([-|\s]?(\d{4})[-|\s]?\)[-|\s]?(\d{3})[-|\s]?(\d{3})/',
                '/[\+]?([7|8])[-|\s]?(\d{4})[-|\s]?(\d{3})[-|\s]?(\d{3})/',
            ],
            [
                '+7 $2 $3-$4-$5',
                '+7 $2 $3-$4-$5',
                '+7 $2 $3-$4-$5',
                '+7 $2 $3-$4-$5',
                '+7 $2 $3-$4',
                '+7 $2 $3-$4',
            ],
            trim($value)
        );
    }
}

if (!function_exists('isEmailValid')) {
    /**
     * Basic email validator.
     *
     * @param string|null $email
     * @param bool $log
     *
     * @return bool
     */
    function isEmailValid(?string $email, bool $log = true): bool
    {
        if ($email) {
            $validator = new EmailValidator();
            $multipleValidations = new MultipleValidationWithAnd([
                new RFCValidation(),
                new DNSCheckValidation()
            ]);
            $valid = $validator->isValid($email, $multipleValidations);

            if (!$valid && $log) {
                Log::warning("Invalid email address passed: \"{$email}\".");
            }

            return $valid;
        }

        return false;
    }
}

if (!function_exists('formatFileSize')) {
    /**
     * Convert file size to human readable value.
     *
     * @param $bytes
     *
     * @return string
     */
    function formatFileSize($bytes): string
    {
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            $bytes .= ' bytes';
        } elseif ($bytes === 1) {
            $bytes .= ' byte';
        } else {
            $bytes = '0 bytes';
        }

        return $bytes;
    }
}

if (!function_exists('strReplace')) {
    /**
     * Recursive replacement in a string.
     *
     * @param string $search
     * @param string $replace
     * @param string $subject
     *
     * @return Collection
     */
    function strReplace(string $search, string $replace, string $subject): string
    {
        if (Str::contains($subject, $search)) {
            $subject = Str::replace($search, $replace, $subject);
            return strReplace($search, $replace, $subject);
        }

        return $subject;
    }
}

if (!function_exists('strGetPlainText')) {
    /**
     * Replace and strip tags and entities.
     *
     * @param string|null $subject
     *
     * @return string|null
     */
    function strGetPlainText(?string $subject): ?string
    {
        if ($subject) {
            return trim(
                strip_tags(
                    Str::replace(
                        ["<br>", "</p><p>", "&nbsp;"],
                        [" ", " ", " "],
                        $subject
                    )
                )
            );
        }

        return null;
    }
}

if (!function_exists('makeTenantUrl')) {
    /**
     * For example: "http://conf.test/login" => "http://first.conf.test/login".
     *
     * @param string $url
     * @param Tenant|null $tenant
     *
     * @return string
     */
    function makeTenantUrl(string $url, ?Tenant $tenant): string
    {
        if ($tenant) {
            $centralDomain = config('app.domain');
            $tenantDomain = $tenant->domains()->first();

            if ($tenantDomain) {
                return Str::replace("//{$centralDomain}", "//{$tenantDomain->domain}", $url);
            }
        }

        return $url;
    }
}
