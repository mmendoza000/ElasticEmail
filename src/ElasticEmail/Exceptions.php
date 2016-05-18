<?php
namespace ElasticEmail;

class ElasticEmail_Error extends \Exception {}
class ElasticEmail_HttpError extends ElasticEmail_Error {}

/**
 * The parameters passed to the API call are invalid or not provided when required
 */
class ElasticEmail_ValidationError extends ElasticEmail_Error {}

/**
 * The provided API key is not a valid Mandrill API key
 */
class ElasticEmail_Invalid_Key extends ElasticEmail_Error {}

/**
 * The requested feature requires payment.
 */
class ElasticEmail_PaymentRequired extends ElasticEmail_Error {}

/**
 * The provided subaccount id does not exist.
 */
class ElasticEmail_Unknown_Subaccount extends ElasticEmail_Error {}

/**
 * The requested template does not exist
 */
class ElasticEmail_Unknown_Template extends ElasticEmail_Error {}

/**
 * The subsystem providing this API call is down for maintenance
 */
class ElasticEmail_ServiceUnavailable extends ElasticEmail_Error {}

/**
 * The provided message id does not exist.
 */
class ElasticEmail_Unknown_Message extends ElasticEmail_Error {}

/**
 * The requested tag does not exist or contains invalid characters
 */
class ElasticEmail_Invalid_Tag_Name extends ElasticEmail_Error {}

/**
 * The requested email is not in the rejection list
 */
class ElasticEmail_Invalid_Reject extends ElasticEmail_Error {}

/**
 * The requested sender does not exist
 */
class ElasticEmail_Unknown_Sender extends ElasticEmail_Error {}

/**
 * The requested URL has not been seen in a tracked link
 */
class ElasticEmail_Unknown_Url extends ElasticEmail_Error {}

/**
 * The provided tracking domain does not exist.
 */
class ElasticEmail_Unknown_TrackingDomain extends ElasticEmail_Error {}

/**
 * The given template name already exists or contains invalid characters
 */
class ElasticEmail_Invalid_Template extends ElasticEmail_Error {}

/**
 * The requested webhook does not exist
 */
class ElasticEmail_Unknown_Webhook extends ElasticEmail_Error {}

/**
 * The requested inbound domain does not exist
 */
class ElasticEmail_Unknown_InboundDomain extends ElasticEmail_Error {}

/**
 * The provided inbound route does not exist.
 */
class ElasticEmail_Unknown_InboundRoute extends ElasticEmail_Error {}

/**
 * The requested export job does not exist
 */
class ElasticEmail_Unknown_Export extends ElasticEmail_Error {}

/**
 * A dedicated IP cannot be provisioned while another request is pending.
 */
class ElasticEmail_IP_ProvisionLimit extends ElasticEmail_Error {}

/**
 * The provided dedicated IP pool does not exist.
 */
class ElasticEmail_Unknown_Pool extends ElasticEmail_Error {}

/**
 * The user hasn't started sending yet.
 */
class ElasticEmail_NoSendingHistory extends ElasticEmail_Error {}

/**
 * The user's reputation is too low to continue.
 */
class ElasticEmail_PoorReputation extends ElasticEmail_Error {}

/**
 * The provided dedicated IP does not exist.
 */
class ElasticEmail_Unknown_IP extends ElasticEmail_Error {}

/**
 * You cannot remove the last IP from your default IP pool.
 */
class ElasticEmail_Invalid_EmptyDefaultPool extends ElasticEmail_Error {}

/**
 * The default pool cannot be deleted.
 */
class ElasticEmail_Invalid_DeleteDefaultPool extends ElasticEmail_Error {}

/**
 * Non-empty pools cannot be deleted.
 */
class ElasticEmail_Invalid_DeleteNonEmptyPool extends ElasticEmail_Error {}

/**
 * The domain name is not configured for use as the dedicated IP's custom reverse DNS.
 */
class ElasticEmail_Invalid_CustomDNS extends ElasticEmail_Error {}

/**
 * A custom DNS change for this dedicated IP is currently pending.
 */
class ElasticEmail_Invalid_CustomDNSPending extends ElasticEmail_Error {}

/**
 * Custom metadata field limit reached.
 */
class ElasticEmail_Metadata_FieldLimit extends ElasticEmail_Error {}

/**
 * The provided metadata field name does not exist.
 */
class ElasticEmail_Unknown_MetadataField extends ElasticEmail_Error {}
