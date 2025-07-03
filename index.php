<?php
session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';

// Récupérer les statistiques
$stats = getDonationStats($pdo);
$recentDonations = getRecentDonations($pdo, 8);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Palestinian Relief Donation Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <script src="https://js.stripe.com/v3/"></script>
    <link rel="stylesheet" href="assets/css/style.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#0ea5e9',
                        'primary-dark': '#0284c7'
                    }
                }
            }
        }
    </script>
</head>
<body class="min-h-screen bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center">
                    <img src="assets/images/logo.jpg" alt="Logo Najam Relief" class="h-10 sm:h-12 w-auto">
                </div>
                <div class="flex items-center space-x-4">
                    <button onclick="shareWebsite()" class="inline-flex items-center gap-2 px-4 py-2 border-2 border-gray-800 text-gray-800 font-medium rounded-lg hover:bg-gray-800 hover:text-white transition-all duration-200 text-sm sm:text-base">
                        <i data-lucide="share-2" class="w-4 h-4"></i>
                        <span class="hidden sm:inline">Partager</span>
                    </button>
                    <div class="language-selector">
                        <select onchange="changeLanguage(this.value)" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            <option value="fr">🇫🇷 FR</option>
                            <option value="en">🇬🇧 EN</option>
                            <option value="ar">🇸🇦 AR</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
        <!-- Hero Section -->
        <section class="text-center mb-8 sm:mb-12">
            <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-gray-800 mb-6 flex items-center justify-center gap-2 flex-wrap">
                <span>Vous pouvez nourrir le peuple palestinien</span>
                <i data-lucide="heart" class="text-primary w-6 h-6 sm:w-8 sm:h-8"></i>
            </h1>
            
            <!-- Progress Bar -->
            <div class="mb-8">
                <div class="w-full max-w-4xl mx-auto">
                    <div class="mb-4">
                        <p class="text-xl sm:text-2xl font-bold text-gray-800 mb-1">
                            <?php echo formatCurrency($stats['total_raised']); ?> collectés sur un objectif de <?php echo formatCurrency($stats['goal']); ?>
                        </p>
                    </div>
                    
                    <div class="relative w-full bg-gray-200 rounded-full h-4 sm:h-6 overflow-hidden">
                        <div class="absolute top-0 left-0 h-full bg-gradient-to-r from-primary to-primary-dark rounded-full transition-all duration-1000 progress-bar"
                             style="width: <?php echo min(($stats['total_raised'] / $stats['goal']) * 100, 100); ?>%">
                            <div class="absolute inset-0 bg-white/20 animate-pulse rounded-full"></div>
                        </div>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <span class="text-xs sm:text-sm font-semibold text-gray-700">
                                <?php echo number_format(($stats['total_raised'] / $stats['goal']) * 100, 1); ?>%
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hero Image -->
            <div class="mb-8">
                <img src="assets/images/hero.jpg" 
                     alt="Enfant palestinien cherchant de la nourriture"
                     class="w-full max-w-4xl mx-auto rounded-lg shadow-lg object-cover h-64 sm:h-80 md:h-96">
            </div>

            <!-- Description -->
            <div class="max-w-4xl mx-auto text-base sm:text-lg text-gray-700 leading-relaxed space-y-4 mb-8">
                <p>
                    <strong>Vous pouvez être la raison pour laquelle une famille en Palestine a un repas chaud.</strong> 
                    Le réconfort, la nourriture et l'espoir peuvent atteindre ceux qui luttent dans une crise grâce à des actes de générosité et de compassion.
                </p>
                <p>
                    Votre générosité compte, chaque repas que vous donnez apporte du soulagement et restaure la dignité. 
                    C'est plus qu'un repas, c'est un message d'amour, d'espoir et de solidarité.
                </p>
                <p class="text-lg sm:text-xl font-semibold text-primary">
                    Donnez maintenant, car votre gentillesse peut changer des vies. 🌙 🍽️
                </p>
            </div>

            <!-- Donation Button -->
            <div class="mb-8">
                <button onclick="openDonationModal()" 
                        class="bg-primary text-white px-6 sm:px-8 py-3 sm:py-4 text-lg sm:text-xl font-bold rounded-lg hover:bg-primary-dark transform hover:scale-105 transition-all duration-200 shadow-lg">
                    FAIRE UN DON
                </button>
            </div>
        </section>

        <!-- Stats Section -->
        <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-8 sm:mb-12">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6 text-center">
                <div class="text-2xl sm:text-3xl font-bold text-primary mb-2"><?php echo number_format($stats['total_donations']); ?></div>
                <div class="text-sm sm:text-base text-gray-600">Dons reçus</div>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6 text-center">
                <div class="text-2xl sm:text-3xl font-bold text-primary mb-2"><?php echo number_format($stats['families_helped']); ?></div>
                <div class="text-sm sm:text-base text-gray-600">Familles aidées</div>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6 text-center">
                <div class="text-2xl sm:text-3xl font-bold text-primary mb-2"><?php echo number_format($stats['meals_provided']); ?></div>
                <div class="text-sm sm:text-base text-gray-600">Repas fournis</div>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6 text-center">
                <div class="text-2xl sm:text-3xl font-bold text-primary mb-2"><?php echo $stats['countries']; ?></div>
                <div class="text-sm sm:text-base text-gray-600">Pays donateurs</div>
            </div>
        </section>

        <!-- Recent Donations -->
        <section class="mb-8 sm:mb-12">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
                <h3 class="text-xl sm:text-2xl font-semibold text-gray-800 mb-4 sm:mb-6">Dons récents</h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4" id="recent-donations">
                    <?php foreach ($recentDonations as $donation): ?>
                    <div class="donation-item p-3 sm:p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-xl sm:text-2xl"><?php echo $donation['country_flag']; ?></span>
                            <p class="font-medium text-gray-800 text-sm sm:text-base">
                                <span class="font-bold"><?php echo $donation['amount_display']; ?></span>
                            </p>
                        </div>
                        <p class="text-xs sm:text-sm text-gray-600 mb-1">
                            <?php echo htmlspecialchars($donation['donor_name']); ?> <?php echo $donation['donation_type']; ?>
                        </p>
                        <p class="text-xs text-gray-500">
                            <?php echo $donation['location']; ?>
                        </p>
                    </div>
                    <?php endforeach; ?>
                </div>

                <button onclick="loadMoreDonations()" 
                        class="w-full mt-4 sm:mt-6 py-2 sm:py-3 text-sm sm:text-base text-primary hover:text-primary-dark font-medium transition-colors duration-200"
                        id="load-more-btn">
                    Charger plus
                </button>
            </div>
        </section>

        <!-- FAQ Section -->
        <section class="mb-8 sm:mb-12">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="faq-item border-b border-gray-200 last:border-b-0">
                    <button onclick="toggleFAQ(this)" 
                            class="w-full p-4 sm:p-6 text-left hover:bg-gray-50 transition-colors duration-200 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <i data-lucide="shield" class="text-green-600 w-5 h-5"></i>
                            <span class="font-medium text-gray-800 text-sm sm:text-base">Mon don est-il sécurisé ?</span>
                        </div>
                        <i data-lucide="chevron-down" class="text-gray-500 w-5 h-5 transition-transform duration-200"></i>
                    </button>
                    <div class="faq-content hidden px-4 sm:px-6 pb-4 sm:pb-6">
                        <div class="pl-8">
                            <div class="text-sm text-gray-600 leading-relaxed">
                                Oui, nous utilisons Stripe, le leader mondial du paiement en ligne, avec un cryptage SSL de niveau bancaire.
                                Vos informations de carte de crédit ne transitent jamais par nos serveurs et sont directement traitées par Stripe.
                                Nous sommes conformes aux normes PCI DSS les plus strictes.
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="faq-item border-b border-gray-200 last:border-b-0">
                    <button onclick="toggleFAQ(this)" 
                            class="w-full p-4 sm:p-6 text-left hover:bg-gray-50 transition-colors duration-200 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <i data-lucide="file-text" class="text-blue-600 w-5 h-5"></i>
                            <span class="font-medium text-gray-800 text-sm sm:text-base">Ce don est-il déductible des impôts ?</span>
                        </div>
                        <i data-lucide="chevron-down" class="text-gray-500 w-5 h-5 transition-transform duration-200"></i>
                    </button>
                    <div class="faq-content hidden px-4 sm:px-6 pb-4 sm:pb-6">
                        <div class="pl-8">
                            <div class="text-sm text-gray-600 leading-relaxed">
                                Votre don est déductible des impôts conformément à votre réglementation locale.
                                Nous vous enverrons automatiquement un reçu fiscal par email après votre don.
                                Ce reçu constitue votre justificatif officiel pour votre déclaration d'impôts.
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="faq-item">
                    <button onclick="toggleFAQ(this)" 
                            class="w-full p-4 sm:p-6 text-left hover:bg-gray-50 transition-colors duration-200 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <i data-lucide="rotate-ccw" class="text-orange-600 w-5 h-5"></i>
                            <span class="font-medium text-gray-800 text-sm sm:text-base">Puis-je annuler mon don récurrent ?</span>
                        </div>
                        <i data-lucide="chevron-down" class="text-gray-500 w-5 h-5 transition-transform duration-200"></i>
                    </button>
                    <div class="faq-content hidden px-4 sm:px-6 pb-4 sm:pb-6">
                        <div class="pl-8">
                            <div class="text-sm text-gray-600 leading-relaxed">
                                Absolument. Vous gardez le contrôle total de votre don récurrent.
                                Vous pouvez le modifier, le suspendre ou l'annuler à tout moment en nous contactant.
                                Aucun frais d'annulation n'est appliqué.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer Links -->
        <footer class="text-center space-y-2 text-sm text-gray-600">
            <button onclick="openCookieModal()" 
                    class="hover:text-gray-800 transition-colors underline">
                Politique relative aux cookies
            </button>
        </footer>
    </main>

    <!-- Payment Modal avec Stripe Elements -->
    <div id="payment-modal" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4 hidden">
        <div class="bg-white rounded-lg max-w-md w-full max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between p-4 sm:p-6 border-b">
                <h2 class="text-lg sm:text-xl font-semibold text-gray-800" id="payment-title">
                    Paiement sécurisé
                </h2>
                <button onclick="closePaymentModal()" class="text-gray-500 hover:text-gray-700">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>

            <form id="payment-form" class="p-4 sm:p-6">
                <!-- Cover Fees -->
                <div class="mb-6 p-4 bg-primary/10 border border-primary/20 rounded-lg">
                    <div class="flex items-start gap-3">
                        <i data-lucide="info" class="text-primary mt-0.5 w-5 h-5"></i>
                        <div>
                            <label class="flex items-center gap-2 text-sm">
                                <input type="checkbox" id="cover-fees" 
                                       class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                                <span class="font-medium">Couvrir les frais de transaction</span>
                            </label>
                            <p class="text-xs text-gray-600 mt-1" id="fees-description">
                                Souhaitez-vous couvrir les frais de transaction (2.9% + 0.30€) 
                                afin que nous recevions 100% de votre don ?
                            </p>
                            <p class="text-sm font-medium text-primary mt-2 hidden" id="total-amount">
                                <!-- Generated by JavaScript -->
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Stripe Card Element -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Informations de carte
                    </label>
                    <div id="card-element" class="p-3 border border-gray-300 rounded-lg bg-white">
                        <!-- Stripe Elements will create form elements here -->
                    </div>
                    <div id="card-errors" class="text-red-600 text-sm mt-2 hidden"></div>
                </div>

                <div id="payment-error" class="text-red-600 text-sm mb-4 hidden"></div>

                <button type="submit" 
                        class="w-full bg-primary text-white py-3 px-4 rounded-lg font-medium hover:bg-primary-dark transition-colors flex items-center justify-center gap-2">
                    <i data-lucide="shield" class="w-5 h-5"></i>
                    Faire un don sécurisé
                </button>

                <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center gap-2 text-xs text-gray-600">
                        <i data-lucide="shield" class="w-4 h-4"></i>
                        <span>Paiement sécurisé avec Stripe - Cryptage SSL 256-bit</span>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Autres modales existantes... -->
    <!-- (Garder tous les autres modales du code précédent) -->

    <script src="assets/js/app.js"></script>
    <script src="assets/js/stripe-payment.js"></script>
    <script>
        // Initialize Lucide icons
        lucide.createIcons();
    </script>
</body>
</html>