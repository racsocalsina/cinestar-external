<?php


namespace App\Enums;


class GlobalEnum
{
    public const SETTINGS_CK_BILLBOARD_DATES = 'billboard_dates';
    public const SETTINGS_CK_COMMUNITY = 'community_system_vars';
    public const SETTINGS_CK_PGR_TO_FREE = 'payment_gateway_response_to_free';
    public const SETTINGS_CK_SYSTEM_CONFIGURATION = 'system_configuration';

    public const TYPES_DOCUMENTS = ['01', '04', '06', '07', '11', '00'];

    // Bucket folders
    public const BANNERS_FOLDER = 'banners';
    public const CUSTOMERS_FOLDER = 'customers';
    public const MOVIES_FOLDER = 'movies';
    public const HEADQUARTERS_FOLDER = 'headquarters';
    public const JOB_APPLICATIONS_FOLDER = "job_applications";
    public const PRODUCTS_FOLDER = 'products';
    public const TICKET_PROMOTION_FOLDER = 'ticket-promotions';
    public const CHOCO_PROMOTION_FOLDER = 'choco-promotions';
    public const TICKET_AWARDS_FOLDER = 'ticket-awards';
    public const CHOCO_AWARDS_FOLDER = 'choco-awards';
    public const CONTENT_MANAGEMENT_FOLDER = 'content-management';

    // Others Enums
    public const ROLE_NAME_SUPER_ADMIN = "super-admin";

    // Sync Logs Status
    public const SYNC_LOG_STATUS_SYNCING = 'syncing';
    public const SYNC_LOG_STATUS_SUCCESS = 'success';
    public const SYNC_LOG_STATUS_ERROR = 'error';

    public const ACTION_SYNC_INSERT = 'INSERT';
    public const ACTION_SYNC_UPDATE = 'UPDATE';
    public const ACTION_SYNC_DELETE = 'DELETE';
    public const ACTION_SYNC_IMPORT = 'IMPORT';

    // Others
    public const COMPLETED_STATUS = 'completed';
}
