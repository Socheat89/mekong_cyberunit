<?php
// core/classes/Tenant.php
class Tenant {
    private static $currentTenant = null;

    public static function detect($tenantSlug = null) {
        if (self::$currentTenant !== null) {
            return self::$currentTenant;
        }

        $host = $_SERVER['HTTP_HOST'];
        $requestUri = $_SERVER['REQUEST_URI'];

        // Check for subdomain (e.g., shop1.mysaas.com)
        $parts = explode('.', $host);
        if (count($parts) > 2) {
            $subdomain = $parts[0];
            $tenant = self::findBySubdomain($subdomain);
            if ($tenant) {
                self::$currentTenant = $tenant;
                return $tenant;
            }
        }

        // Check for URL path (e.g., /shop1/...) - use provided slug or parse
        if ($tenantSlug) {
            $tenant = self::findBySubdomain($tenantSlug);
            if ($tenant) {
                self::$currentTenant = $tenant;
                return $tenant;
            }
        } else {
            // Fallback parsing (for backward compatibility)
            if (preg_match('/^\/([^\/]+)\//', $requestUri, $matches)) {
                $pathTenant = $matches[1];
                $tenant = self::findBySubdomain($pathTenant);
                if ($tenant) {
                    self::$currentTenant = $tenant;
                    return $tenant;
                }
            }
        }

        // Default tenant or error
        throw new Exception('Tenant not found');
    }

    private static function findBySubdomain($subdomain) {
        $db = Database::getInstance();
        return $db->fetchOne("SELECT * FROM tenants WHERE subdomain = ? AND status = 'active'", [$subdomain]);
    }

    public static function getCurrent() {
        return self::$currentTenant;
    }

    public static function getId() {
        return self::$currentTenant ? self::$currentTenant['id'] : null;
    }

    public static function hasSystem($systemName) {
        if (!self::$currentTenant) return false;
        $db = Database::getInstance();
        $count = $db->fetchOne(
            "SELECT COUNT(*) as count FROM tenant_systems ts 
             JOIN systems s ON ts.system_id = s.id 
             WHERE ts.tenant_id = ? AND s.name = ? AND ts.status = 'active'",
            [self::getId(), $systemName]
        );
        return $count['count'] > 0;
    }

    public static function setCurrent($tenant) {
        self::$currentTenant = $tenant;
    }
}
?>