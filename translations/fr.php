<?php

global $_MODULE;
$_MODULE = array();

// Module info
$_MODULE['<{postmarknewsletter}prestashop>postmarknewsletter_c11b8e8e3a60ec8d01818e22c3e7c8a4'] = 'Newsletter Postmark';
$_MODULE['<{postmarknewsletter}prestashop>postmarknewsletter_d0a48d6f6e254fc1d86b380491d2d2d6'] = 'Envoyez des newsletters via Postmark avec gestion automatique des rebonds et fonction de désinscription.';
$_MODULE['<{postmarknewsletter}prestashop>postmarknewsletter_0f40c5aa07338c79c1e690c364a78d5c'] = 'Êtes-vous sûr de vouloir désinstaller ? Tous les journaux de newsletter et paramètres seront supprimés.';

// Configuration
$_MODULE['<{postmarknewsletter}prestashop>postmarknewsletter_f4f70727dc34561dfde1a3c529b6205c'] = 'Paramètres';
$_MODULE['<{postmarknewsletter}prestashop>postmarknewsletter_c9cc8cce247e49bae79f15173ce97354'] = 'Enregistrer';
$_MODULE['<{postmarknewsletter}prestashop>postmarknewsletter_a0bfb8e59e6c13fc8d990781f77694fe'] = 'Paramètres mis à jour avec succès.';

// API Settings
$_MODULE['<{postmarknewsletter}prestashop>postmarknewsletter_2f6ad7bb9c7444a0d28da5e831e59842'] = 'Paramètres API Postmark';
$_MODULE['<{postmarknewsletter}prestashop>postmarknewsletter_8b5da736613c5c23433477b0435a5e26'] = 'Jeton API Serveur Postmark';
$_MODULE['<{postmarknewsletter}prestashop>postmarknewsletter_5e9d42bb93c0c4f1d3f1d94b6e8d1a35'] = 'Votre jeton API serveur Postmark de votre compte Postmark.';
$_MODULE['<{postmarknewsletter}prestashop>postmarknewsletter_ce8ae9da5b7cd6c3df2929543a9af92d'] = 'E-mail expéditeur';
$_MODULE['<{postmarknewsletter}prestashop>postmarknewsletter_4f1f6016fc9f3f2353c0cc7c67b292bd'] = 'Adresse e-mail de l\'expéditeur (doit être vérifiée dans Postmark).';
$_MODULE['<{postmarknewsletter}prestashop>postmarknewsletter_3b3a88e61600e8f4f4e6321df5c1e640'] = 'Nom de l\'expéditeur';
$_MODULE['<{postmarknewsletter}prestashop>postmarknewsletter_f7f9b1e3c8e0e3a1e3e0e3a1e3e0e3a1'] = 'Nom de l\'expéditeur qui apparaîtra dans les e-mails.';
$_MODULE['<{postmarknewsletter}prestashop>postmarknewsletter_d5c04ee0ad2642f04e7c8188d6f6db2d'] = 'Flux de messages';
$_MODULE['<{postmarknewsletter}prestashop>postmarknewsletter_9e9e8e8e9e9e8e8e9e9e8e8e9e9e8e8e'] = 'Flux de messages Postmark (par défaut : "broadcast").';

// Tracking Settings
$_MODULE['<{postmarknewsletter}prestashop>postmarknewsletter_5a2c3b9b5f2e6e7e8e9e0e1e2e3e4e5e'] = 'Suivre les ouvertures';
$_MODULE['<{postmarknewsletter}prestashop>postmarknewsletter_6a3a5a7a8a9a0a1a2a3a4a5a6a7a8a9a'] = 'Suivre les ouvertures d\'e-mails dans Postmark.';
$_MODULE['<{postmarknewsletter}prestashop>postmarknewsletter_7b4b6b8b9b0b1b2b3b4b5b6b7b8b9b0b'] = 'Suivre les liens';
$_MODULE['<{postmarknewsletter}prestashop>postmarknewsletter_8c5c7c9c0c1c2c3c4c5c6c7c8c9c0c1c'] = 'Suivre les clics sur les liens dans Postmark.';
$_MODULE['<{postmarknewsletter}prestashop>postmarknewsletter_00d23a76e43b46dae9ec7aa721cfbc91'] = 'Activé';
$_MODULE['<{postmarknewsletter}prestashop>postmarknewsletter_b9f5c797ebbf55adccdd8539a65a0241'] = 'Désactivé';

// Bounce Settings
$_MODULE['<{postmarknewsletter}prestashop>postmarknewsletter_9d6d7e8e9e0e1e2e3e4e5e6e7e8e9e0e'] = 'Désabonnement automatique des rebonds durs';
$_MODULE['<{postmarknewsletter}prestashop>postmarknewsletter_0e1e2e3e4e5e6e7e8e9e0e1e2e3e4e5e'] = 'Désabonner automatiquement les destinataires en cas de rebonds durs.';
$_MODULE['<{postmarknewsletter}prestashop>postmarknewsletter_1f2f3f4f5f6f7f8f9f0f1f2f3f4f5f6f'] = 'Désabonnement automatique des rebonds légers';
$_MODULE['<{postmarknewsletter}prestashop>postmarknewsletter_2g3g4g5g6g7g8g9g0g1g2g3g4g5g6g7g'] = 'Désabonner automatiquement les destinataires après le seuil de rebonds légers.';
$_MODULE['<{postmarknewsletter}prestashop>postmarknewsletter_3h4h5h6h7h8h9h0h1h2h3h4h5h6h7h8h'] = 'Seuil de rebonds légers';
$_MODULE['<{postmarknewsletter}prestashop>postmarknewsletter_4i5i6i7i8i9i0i1i2i3i4i5i6i7i8i9i'] = 'Nombre de rebonds légers avant désabonnement automatique (par défaut : 3).';

// Webhook
$_MODULE['<{postmarknewsletter}prestashop>postmarknewsletter_5j6j7j8j9j0j1j2j3j4j5j6j7j8j9j0j'] = 'URL du Webhook';
$_MODULE['<{postmarknewsletter}prestashop>postmarknewsletter_6k7k8k9k0k1k2k3k4k5k6k7k8k9k0k1k'] = 'Configurez cette URL dans votre compte Postmark :';
$_MODULE['<{postmarknewsletter}prestashop>postmarknewsletter_7l8l9l0l1l2l3l4l5l6l7l8l9l0l1l2l'] = 'Activez les webhooks pour : événements Rebond, Livraison et Plainte spam.';

// Actions
$_MODULE['<{postmarknewsletter}prestashop>postmarknewsletter_8m9m0m1m2m3m4m5m6m7m8m9m0m1m2m3m'] = 'Tester la connexion';
$_MODULE['<{postmarknewsletter}prestashop>postmarknewsletter_9n0n1n2n3n4n5n6n7n8n9n0n1n2n3n4n'] = 'Connexion réussie ! L\'API Postmark fonctionne.';
$_MODULE['<{postmarknewsletter}prestashop>postmarknewsletter_0o1o2o3o4o5o6o7o8o9o0o1o2o3o4o5o'] = 'Échec de la connexion. Veuillez vérifier votre jeton API.';
$_MODULE['<{postmarknewsletter}prestashop>postmarknewsletter_1p2p3p4p5p6p7p8p9p0p1p2p3p4p5p6p'] = 'Erreur : ';

// Stats
$_MODULE['<{postmarknewsletter}prestashop>postmarknewsletter_2q3q4q5q6q7q8q9q0q1q2q3q4q5q6q7q'] = 'Total des abonnés';
$_MODULE['<{postmarknewsletter}prestashop>postmarknewsletter_3r4r5r6r7r8r9r0r1r2r3r4r5r6r7r8r'] = 'E-mails envoyés';
$_MODULE['<{postmarknewsletter}prestashop>postmarknewsletter_4s5s6s7s8s9s0s1s2s3s4s5s6s7s8s9s'] = 'Total des rebonds';
$_MODULE['<{postmarknewsletter}prestashop>postmarknewsletter_5t6t7t8t9t0t1t2t3t4t5t6t7t8t9t0t'] = 'Désabonnés automatiquement';

// Unsubscribe page
$_MODULE['<{postmarknewsletter}prestashop>unsubscribe_c9cc8cce247e49bae79f15173ce97354'] = 'Désinscription de la newsletter';
$_MODULE['<{postmarknewsletter}prestashop>unsubscribe_f1e2e3e4e5e6e7e8e9e0e1e2e3e4e5e6'] = 'Désinscription réussie';
$_MODULE['<{postmarknewsletter}prestashop>unsubscribe_g2g3g4g5g6g7g8g9g0g1g2g3g4g5g6g7'] = 'Vous avez été désinscrit avec succès de notre newsletter.';
$_MODULE['<{postmarknewsletter}prestashop>unsubscribe_h3h4h5h6h7h8h9h0h1h2h3h4h5h6h7h8'] = 'Adresse e-mail :';
$_MODULE['<{postmarknewsletter}prestashop>unsubscribe_i4i5i6i7i8i9i0i1i2i3i4i5i6i7i8i9'] = 'Vous ne recevrez plus notre newsletter. Nous sommes désolés de vous voir partir !';
$_MODULE['<{postmarknewsletter}prestashop>unsubscribe_j5j6j7j8j9j0j1j2j3j4j5j6j7j8j9j0'] = 'Retour à l\'accueil';
$_MODULE['<{postmarknewsletter}prestashop>unsubscribe_k6k7k8k9k0k1k2k3k4k5k6k7k8k9k0k1'] = 'Erreur';
$_MODULE['<{postmarknewsletter}prestashop>unsubscribe_l7l8l9l0l1l2l3l4l5l6l7l8l9l0l1l2'] = 'Lien de désinscription invalide.';
$_MODULE['<{postmarknewsletter}prestashop>unsubscribe_m8m9m0m1m2m3m4m5m6m7m8m9m0m1m2m3'] = 'Lien de désinscription invalide ou expiré.';
$_MODULE['<{postmarknewsletter}prestashop>unsubscribe_n9n0n1n2n3n4n5n6n7n8n9n0n1n2n3n4'] = 'Ce lien de désinscription a déjà été utilisé.';
$_MODULE['<{postmarknewsletter}prestashop>unsubscribe_o0o1o2o3o4o5o6o7o8o9o0o1o2o3o4o5'] = 'Ce lien de désinscription a expiré.';
$_MODULE['<{postmarknewsletter}prestashop>unsubscribe_p1p2p3p4p5p6p7p8p9p0p1p2p3p4p5p6'] = 'Confirmer la désinscription';
$_MODULE['<{postmarknewsletter}prestashop>unsubscribe_q2q3q4q5q6q7q8q9q0q1q2q3q4q5q6q7'] = 'Êtes-vous sûr de vouloir vous désinscrire de notre newsletter ?';
$_MODULE['<{postmarknewsletter}prestashop>unsubscribe_r3r4r5r6r7r8r9r0r1r2r3r4r5r6r7r8'] = 'Oui, me désinscrire';
$_MODULE['<{postmarknewsletter}prestashop>unsubscribe_s4s5s6s7s8s9s0s1s2s3s4s5s6s7s8s9'] = 'Non, conserver mon abonnement';
$_MODULE['<{postmarknewsletter}prestashop>unsubscribe_t5t6t7t8t9t0t1t2t3t4t5t6t7t8t9t0'] = 'Que se passe-t-il lorsque vous vous désinscrivez ?';
$_MODULE['<{postmarknewsletter}prestashop>unsubscribe_u6u7u8u9u0u1u2u3u4u5u6u7u8u9u0u1'] = 'Vous ne recevrez plus nos newsletters promotionnelles';
$_MODULE['<{postmarknewsletter}prestashop>unsubscribe_v7v8v9v0v1v2v3v4v5v6v7v8v9v0v1v2'] = 'Vous recevrez toujours les e-mails transactionnels importants (confirmations de commande, mises à jour d\'expédition, etc.)';
$_MODULE['<{postmarknewsletter}prestashop>unsubscribe_w8w9w0w1w2w3w4w5w6w7w8w9w0w1w2w3'] = 'Vous pouvez vous réabonner à tout moment depuis les paramètres de votre compte';
$_MODULE['<{postmarknewsletter}prestashop>unsubscribe_x9x0x1x2x3x4x5x6x7x8x9x0x1x2x3x4'] = 'Une erreur s\'est produite lors du traitement de votre demande. Veuillez réessayer.';

// Admin templates
$_MODULE['<{postmarknewsletter}prestashop>configure_y0y1y2y3y4y5y6y7y8y9y0y1y2y3y4y5'] = 'Module Newsletter Postmark';
$_MODULE['<{postmarknewsletter}prestashop>configure_z1z2z3z4z5z6z7z8z9z0z1z2z3z4z5z6'] = 'Envoyez des newsletters professionnelles via Postmark avec gestion automatique des rebonds et fonction de désinscription facile.';
$_MODULE['<{postmarknewsletter}prestashop>configure_a2a3a4a5a6a7a8a9a0a1a2a3a4a5a6a7'] = 'Premiers pas';
$_MODULE['<{postmarknewsletter}prestashop>configure_b3b4b5b6b7b8b9b0b1b2b3b4b5b6b7b8'] = 'Créez un compte Postmark sur';
$_MODULE['<{postmarknewsletter}prestashop>configure_c4c5c6c7c8c9c0c1c2c3c4c5c6c7c8c9'] = 'Obtenez votre jeton API serveur depuis votre compte Postmark';
$_MODULE['<{postmarknewsletter}prestashop>configure_d5d6d7d8d9d0d1d2d3d4d5d6d7d8d9d0'] = 'Vérifiez votre adresse e-mail d\'expéditeur dans Postmark';
$_MODULE['<{postmarknewsletter}prestashop>configure_e6e7e8e9e0e1e2e3e4e5e6e7e8e9e0e1'] = 'Configurez les paramètres ci-dessous';
$_MODULE['<{postmarknewsletter}prestashop>configure_f7f8f9f0f1f2f3f4f5f6f7f8f9f0f1f2'] = 'Configurez l\'URL du webhook dans votre compte Postmark (voir ci-dessous)';
$_MODULE['<{postmarknewsletter}prestashop>configure_g8g9g0g1g2g3g4g5g6g7g8g9g0g1g2g3'] = 'Actions rapides';
$_MODULE['<{postmarknewsletter}prestashop>configure_h9h0h1h2h3h4h5h6h7h8h9h0h1h2h3h4'] = 'Envoyer une newsletter de test';
$_MODULE['<{postmarknewsletter}prestashop>configure_i0i1i2i3i4i5i6i7i8i9i0i1i2i3i4i5'] = 'Envoyez un e-mail de test pour vérifier votre configuration Postmark.';
$_MODULE['<{postmarknewsletter}prestashop>configure_j1j2j3j4j5j6j7j8j9j0j1j2j3j4j5j6'] = 'Adresse e-mail de test';
$_MODULE['<{postmarknewsletter}prestashop>configure_k2k3k4k5k6k7k8k9k0k1k2k3k4k5k6k7'] = 'Envoyer l\'e-mail de test';
$_MODULE['<{postmarknewsletter}prestashop>configure_l3l4l5l6l7l8l9l0l1l2l3l4l5l6l7l8'] = 'Statistiques Postmark';
$_MODULE['<{postmarknewsletter}prestashop>configure_m4m5m6m7m8m9m0m1m2m3m4m5m6m7m8m9'] = 'Consultez vos statistiques de livraison Postmark et vos indicateurs de performance.';
$_MODULE['<{postmarknewsletter}prestashop>configure_n5n6n7n8n9n0n1n2n3n4n5n6n7n8n9n0'] = 'Voir le tableau de bord Postmark';

// Customer stats
$_MODULE['<{postmarknewsletter}prestashop>customer_stats_o6o7o8o9o0o1o2o3o4o5o6o7o8o9o0o1'] = 'Statistiques Newsletter Postmark';
$_MODULE['<{postmarknewsletter}prestashop>customer_stats_p7p8p9p0p1p2p3p4p5p6p7p8p9p0p1p2'] = 'Total des e-mails envoyés :';
$_MODULE['<{postmarknewsletter}prestashop>customer_stats_q8q9q0q1q2q3q4q5q6q7q8q9q0q1q2q3'] = 'Total des rebonds :';
