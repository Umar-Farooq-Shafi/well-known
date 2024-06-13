<?php

namespace App\Enums;

enum Setting: string
{
    case CURRENCY = 'currency_ccy';

    case CURRENCY_SYMBOL = 'currency_symbol';

    case CURRENCY_POSITION = 'currency_position';

    case FEED_NAME = 'feed_name';

    case FEED_DESCRIPTION = 'feed_description';

    case FEED_EVENTS_LIMIT = 'feed_events_limit';

    case IMG_LOADER = 'img_loader';

    case BLOG_COMMENTS_ENABLED = 'blog_comments_enabled';

    case FACEBOOK_APP_ID = 'facebook_app_id';

    case DISQUS_SUBDOMAIN = 'disqus_subdomain';

    case NEWSLETTER_ENABLED = 'newsletter_enabled';

    case MAILCHIMP_API_KEY = 'mailchimp_api_key';

    case MAILCHIMP_LIST_ID = 'mailchimp_list_id';

    case HOMEPAGE_SHOW_SEARCH = 'home_page_show_search';

    case HOMEPAGE_EVENTS_NUMBER = 'home_page_events_number';


    case HOMEPAGE_CATEGORIES_NUMBER = 'home_page_categories_number';
    case HOMEPAGE_BLOGPOSTS_NUMBER = 'homepage_blogposts_number';
    case HOMEPAGE_SHOW_CALL_TO_ACTION = 'homepage_show_call_to_action';
    case SHOW_TERMS_OF_SERVICE_PAGE = 'show_terms_of_service_page';
    case TERMS_OF_SERVICE_PAGE_CONTENT = 'terms_of_service_page_content';
    case SHOW_PRIVACY_POLICY_PAGE = 'show_privacy_policy_page';
    case PRIVACY_POLICY_PAGE_CONTENT = 'privacy_policy_page_content';
    case SHOW_COOKIE_POLICY_PAGE = 'show_cookie_policy_page';
    case COOKIE_POLICY_PAGE_CONTENT = 'cookie_policy_page_content';
    case SHOW_GDPR_COMPLIANCE_PAGE = 'show_gdpr_compliance_page';
    case GDPR_COMPLIANCE_PAGE_CONTENT = 'gdpr_compliance_page_content';
    case TERMS_OF_SERVICE_PAGE_SLUG = 'terms_of_service_page_slug';
    case PRIVACY_POLICY_PAGE_SLUG = 'privacy_policy_page_slug';
    case COOKIE_POLICY_PAGE_SLUG = 'cookie_policy_page_slug';
    case GDPR_COMPLIANCE_PAGE_SLUG = 'gdpr_compliance_page_slug';
    case TICKET_FEE_ONLINE = 'ticket_fee_online';
    case TICKET_FEE_POS = 'ticket_fee_pos';
    case CHECKOUT_TIMELEFT = 'checkout_timeleft';
    case ORGANIZER_PAYOUT_PAYPAL_ENABLED = 'organizer_payout_paypal_enabled';
    case ORGANIZER_PAYOUT_STRIPE_ENABLED = 'organizer_payout_stripe_enabled';
    case ONLINE_TICKET_PRICE_PERCENTAGE_CUT = 'online_ticket_price_percentage_cut';
    case POS_TICKET_PRICE_PERCENTAGE_CUT = 'pos_ticket_price_percentage_cut';
    case BLOG_POSTS_PER_PAGE = 'blog_posts_per_page';
    case EVENTS_PER_PAGE = 'events_per_page';
    case SHOW_MAP_BUTTON = 'show_map_button';
    case SHOW_CALENDAR_BUTTON = 'show_calendar_button';
    case SHOW_RSS_FEED_BUTTON = 'show_rss_feed_button';
    case SHOW_CATEGORY_FILTER = 'show_category_filter';
    case SHOW_LOCATION_FILTER = 'show_location_filter';
    case SHOW_DATE_FILTER = 'show_date_filter';
    case SHOW_TICKET_PRICE_FILTER = 'show_ticket_price_filter';
    case SHOW_AUDIENCE_FILTER = 'show_audience_filter';
    case VENUE_COMMENTS_ENABLED = 'venue_comments_enabled';
    case SHOW_TICKETS_LEFT_ON_CART_MODAL = 'show_tickets_left_on_cart_modal';
    case WEBSITE_NAME = 'website_name';
    case WEBSITE_SLUG = 'website_slug';
    case WEBSITE_ROOT_URL = 'website_root_url';
    case WEBSITE_DESCRIPTION_EN = 'website_description_en';
    case WEBSITE_KEYWORDS_EN = 'website_keywords_en';
    case CONTACT_EMAIL = 'contact_email';
    case CONTACT_PHONE = 'contact_phone';
    case CONTACT_FAX = 'contact_fax';
    case CONTACT_ADDRESS = 'contact_address';
    case FACEBOOK_URL = 'facebook_url';
    case INSTAGRAM_URL = 'instagram_url';
    case YOUTUBE_URL = 'youtube_url';
    case TWITTER_URL = 'twitter_url';
    case PRIMARY_COLOR = 'primary_color';
    case NO_REPLY_EMAIL = 'no_reply_email';
    case SHOW_BACK_TO_TOP_BUTTON = 'show_back_to_top_button';
    case SHOW_COOKIE_POLICY_BAR = 'show_cookie_policy_bar';
    case CUSTOM_CSS = 'custom_css';
    case GOOGLE_ANALYTICS_CODE = 'google_analytics_code';
    case WEBSITE_DESCRIPTION_FR = 'website_description_fr';
    case WEBSITE_DESCRIPTION_AR = 'website_description_ar';
    case WEBSITE_KEYWORDS_FR = 'website_keywords_fr';
    case WEBSITE_KEYWORDS_AR = 'website_keywords_ar';
    case MAIL_SERVER_TRANSPORT = 'mail_server_transport';
    case MAIL_SERVER_HOST = 'mail_server_host';
    case MAIL_SERVER_PORT = 'mail_server_port';
    case MAIL_SERVER_ENCRYPTION = 'mail_server_encryption';
    case MAIL_SERVER_AUTH_MODE = 'mail_server_auth_mode';
    case MAIL_SERVER_USERNAME = 'mail_server_username';
    case MAIL_SERVER_PASSWORD = 'mail_server_password';
    case GOOGLE_RECAPTCHA_ENABLED = 'google_recaptcha_enabled';
    case GOOGLE_RECAPTCHA_SITE_KEY = 'google_recaptcha_site_key';
    case GOOGLE_RECAPTCHA_SECRET_KEY = 'google_recaptcha_secret_key';
    case SOCIAL_LOGIN_FACEBOOK_ENABLED = 'social_login_facebook_enabled';
    case SOCIAL_LOGIN_FACEBOOK_ID = 'social_login_facebook_id';
    case SOCIAL_LOGIN_FACEBOOK_SECRET = 'social_login_facebook_secret';
    case SOCIAL_LOGIN_GOOGLE_ID = 'social_login_google_id';
    case SOCIAL_LOGIN_GOOGLE_SECRET = 'social_login_google_secret';
    case SOCIAL_LOGIN_GOOGLE_ENABLED = 'social_login_google_enabled';
    case APP_ENVIRONMENT = 'app_environment';
    case MAINTENANCE_MODE = 'maintenance_mode';
    case MAINTENANCE_MODE_CUSTOM_MESSAGE = 'maintenance_mode_custom_message';
    case APP_THEME = 'app_theme';
    case APP_LAYOUT = 'app_layout';
    case WEBSITE_URL = 'website_url';
    case WEBSITE_DESCRIPTION_ES = 'website_description_es';
    case WEBSITE_KEYWORDS_ES = 'website_keywords_es';
    case TICKET_FEE_ADD = 'ticket_fee_add';
    case HOMEPAGE_FEATURED_EVENTS_NB = 'homepage_featured_events_nb';

}
