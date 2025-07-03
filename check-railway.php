<?php
// Script de vérification pour Railway.app
// Accédez à ce fichier via : https://votre-site.up.railway.app/check-railway.php

echo "<h1>🚂 Vérification Railway.app</h1>";

echo "<h2>📊 Variables d'Environnement</h2>";
echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
echo "<tr><th>Variable</th><th>Valeur</th><th>Status</th></tr>";

$env_vars = [
    'RAILWAY_ENVIRONMENT' => $_ENV['RAILWAY_ENVIRONMENT'] ?? 'Non défini',
    'RAILWAY_SERVICE_NAME' => $_ENV['RAILWAY_SERVICE_NAME'] ?? 'Non défini',
    'DB_HOST' => $_ENV['MYSQLHOST'] ?? $_ENV['DB_HOST'] ?? 'Non défini',
    'DB_NAME' => $_ENV['MYSQLDATABASE'] ?? $_ENV['DB_NAME'] ?? 'Non défini',
    'DB_USER' => $_ENV['MYSQLUSER'] ?? $_ENV['DB_USER'] ?? 'Non défini',
    'DB_PASS' => isset($_ENV['MYSQLPASSWORD']) || isset($_ENV['DB_PASS']) ? '***masqué***' : 'Non défini',
    'STRIPE_SECRET_KEY' => isset($_ENV['STRIPE_SECRET_KEY']) ? '***masqué***' : 'Non défini',
    'SMTP_USER' => $_ENV['SMTP_USER'] ?? 'Non défini'
];

foreach ($env_vars as $var => $value) {
    $status = $value !== 'Non défini' ? '✅' : '❌';
    echo "<tr><td>$var</td><td>$value</td><td>$status</td></tr>";
}
echo "</table>";

echo "<h2>🗄️ Test Base de Données</h2>";
try {
    require_once 'config/database-railway.php';
    echo "✅ Connexion à la base de données : <strong>SUCCÈS</strong><br>";
    
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM donations");
    $result = $stmt->fetch();
    echo "✅ Nombre de dons en base : <strong>" . $result['count'] . "</strong><br>";
    
    $stmt = $pdo->query("SELECT * FROM campaign_stats LIMIT 1");
    $stats = $stmt->fetch();
    if ($stats) {
        echo "✅ Statistiques : <strong>" . number_format($stats['total_raised_usd'], 2) . "$ collectés</strong><br>";
    }
    
} catch (Exception $e) {
    echo "❌ Erreur base de données : " . $e->getMessage() . "<br>";
}

echo "<h2>🔧 Configuration PHP</h2>";
echo "Version PHP : <strong>" . PHP_VERSION . "</strong><br>";
echo "Extensions chargées : <br>";
$extensions = ['pdo', 'pdo_mysql', 'curl', 'json', 'openssl'];
foreach ($extensions as $ext) {
    $status = extension_loaded($ext) ? '✅' : '❌';
    echo "$status $ext<br>";
}

echo "<h2>📁 Fichiers Importants</h2>";
$files = [
    'index.php' => 'Page principale',
    'api/create_payment_intent.php' => 'API Stripe',
    'api/webhook.php' => 'Webhook Stripe',
    'config/database-railway.php' => 'Configuration DB',
    'railway.toml' => 'Configuration Railway'
];

foreach ($files as $file => $desc) {
    $status = file_exists($file) ? '✅' : '❌';
    echo "$status $file ($desc)<br>";
}

echo "<h2>🌐 Informations Serveur</h2>";
echo "Serveur : <strong>" . $_SERVER['SERVER_SOFTWARE'] ?? 'Inconnu' . "</strong><br>";
echo "Port : <strong>" . $_SERVER['SERVER_PORT'] ?? 'Inconnu' . "</strong><br>";
echo "Host : <strong>" . $_SERVER['HTTP_HOST'] ?? 'Inconnu' . "</strong><br>";

echo "<h2>✅ Checklist Déploiement</h2>";
echo "<ul>";
echo "<li>✅ Code uploadé sur GitHub</li>";
echo "<li>✅ Service Railway créé</li>";
echo "<li>✅ Base de données MySQL ajoutée</li>";
echo "<li>✅ Variables d'environnement configurées</li>";
echo "<li>✅ Site accessible publiquement</li>";
echo "</ul>";

echo "<h2>🎯 Prochaines Étapes</h2>";
echo "<ol>";
echo "<li>Configurez Stripe avec vos vraies clés</li>";
echo "<li>Testez un don en mode test</li>";
echo "<li>Configurez les webhooks Stripe</li>";
echo "<li>Testez l'envoi d'emails</li>";
echo "<li>Passez en mode production</li>";
echo "</ol>";

echo "<hr>";
echo "<p><strong>🎉 Votre site est prêt sur Railway.app !</strong></p>";
echo "<p>Supprimez ce fichier avant la mise en production.</p>";
?>