<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'service/file-manager/add',
        'service/file-manager/upload/add',
        '/system/plans/add',
        'service-users/check-serviceuser-username-exists',
        '/login',
        '/admin/login',
        '/bug-report',
        '/bug-report/add',

        'api/service/contact-us',
        'api/service/login',
        'api/service/note/add',
        'api/service/notes',
        'api/service/personal-detail',
        'api/service/targets',
        'api/service/daily-tasks',
        'api/service/living-skill',
        'api/service/education-records',
        'api/service/earning/daily-tasks',
        'api/service/earning/living-skill',
        'api/service/earning/education-records',
        'api/service/earning-scheme-categories',
        'api/service/earning-scheme-details',
        'api/service/moods',
        'api/service/mood/add',
        'api/service/mood/user',
        'api/service/money',
        'api/service/money/request/add',
        'api/service/earning/incentive/add',
        'api/service/appointment/add',
        'api/service/care-center/in-danger',
        'api/change-password',
        'api/forgot-password',
        'api/service/message/add',
        'api/service/need-assistance/message/add',
        'api/service/care-center/request-callback',
        'api/service/care-center/need-assistance/send-message',
        'api/service/care-center/message-office/send-message',
        'api/service/care-center/complaint/add',
        'api/service/appointment/save',
        'api/service/location/add',
        'api/service/logout/location',

        'api/service/device/add',
        'api/staff/device/add',
        'api/staff/money-request/update',
        'api/staff/mood/add-suggestion',
        'api/staff/message-office/add-message',

        'api/service/location/add',
        'api/service/location/add-missing',
        
        'api/service/note/edit',
        'api/remove-device',

        '/admin/company-charge/validate-home-range',
        '/admin/company-charge/validate-range-gap',
        '/admin/manager/change-status',
        '/admin/manager/check-email-exists',
        '/admin/manager/check-contact-no-exists',

        //Sick Leave
        'admin/user/sick-leave/user-list/*'
    ];
}
