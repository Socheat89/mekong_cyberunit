<?php
require_once __DIR__ . '/../core/classes/Database.php';
$db = Database::getInstance();

// Handle Actions
if (isset($_POST['action'])) {
    if ($_POST['action'] == 'save_modules') {
        $systemId = $_POST['system_id'];
        $selectedModules = $_POST['modules'] ?? [];
        
        // Clear existing
        $db->delete('system_modules', 'system_id = ?', [$systemId]);
        
        // Insert new
        foreach ($selectedModules as $module) {
            $db->insert('system_modules', [
                'system_id' => $systemId,
                'module_name' => $module
            ]);
        }
        header("Location: modules.php?success=1&system_id=" . $systemId);
        exit;
    }
}

$systems = $db->fetchAll("SELECT * FROM systems WHERE status = 'active'");
$availableModules = ['pos', 'inventory', 'hr']; // Based on modules directory

// Get mapping for all active systems
$existingMappings = $db->fetchAll("SELECT * FROM system_modules");
$mappings = [];
foreach ($existingMappings as $m) {
    if (!isset($mappings[$m['system_id']])) $mappings[$m['system_id']] = [];
    $mappings[$m['system_id']][] = $m['module_name'];
}

include 'header.php';
?>

<div class="header">
    <h1 class="page-title">Plan Modules Configuration</h1>
</div>

<div class="card" style="background: #eff6ff; border-left: 4px solid var(--primary);">
    <p style="font-size: 0.875rem; color: #1e40af;">
        <i class="ph-bold ph-info" style="font-size: 1.1rem; vertical-align: middle; margin-right: 0.5rem;"></i>
        Select which functional modules are unlocked for each pricing plan. This will control access for users subscribed to these plans.
    </p>
</div>

<?php if (isset($_GET['success'])): ?>
    <div class="card" style="background: #d1fae5; color: #065f46; border-color: #34d399; padding: 1rem; margin-bottom: 2rem;">
        Modules updated successfully!
    </div>
<?php endif; ?>

<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(400px, 1fr)); gap: 1.5rem;">
    <?php foreach ($systems as $system): ?>
    <div class="card">
        <h3 style="margin-bottom: 1rem; display: flex; justify-content: space-between; align-items: center;">
            <?php echo htmlspecialchars($system['name']); ?>
            <span style="font-size: 0.875rem; color: var(--primary);">$<?php echo number_format($system['price'], 2); ?>/mo</span>
        </h3>
        <p style="color: var(--text-muted); font-size: 0.875rem; margin-bottom: 1.5rem;"><?php echo htmlspecialchars($system['description']); ?></p>
        
        <form method="POST">
            <input type="hidden" name="action" value="save_modules">
            <input type="hidden" name="system_id" value="<?php echo $system['id']; ?>">
            
            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                <?php foreach ($availableModules as $mod): ?>
                <label style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: 0.5rem; cursor: pointer;">
                    <input type="checkbox" name="modules[]" value="<?php echo $mod; ?>" 
                        <?php echo (isset($mappings[$system['id']]) && in_array($mod, $mappings[$system['id']])) ? 'checked' : ''; ?>
                        style="width: 1.1rem; height: 1.1rem;">
                    <span style="font-weight: 500; text-transform: uppercase;"><?php echo $mod; ?> Module</span>
                </label>
                <?php endforeach; ?>
            </div>
            
            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1.5rem;">
                Save Configuration
            </button>
        </form>
    </div>
    <?php endforeach; ?>
</div>

<?php include 'footer.php'; ?>
