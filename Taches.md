# Version 1: v1
- [x] base de données *(Ndroso)*
    - [x] creation table
    - [x] donnees
- [x] models:
    - [x] AdminModel *(Toky)*
- [x] opérateur:
    - [x] login *(Toky)*
    - [x] page de config de préfixe *(Toky)*
    - [x] page de config des barèmes de frais *(Toky)*
    - [x] dashboard *(Toky)*
- [x] client:
    - [x] login *(Ndroso)*
    - [x] page d’historique des opérations *(Ndroso)*
    - [x] page de nouvelle opération *(Ndroso)*

# Version 2: v2

- [ ] **Côté Opérateur**
    - [x] Configuration des préfixes valables pour les autres opérateurs (ex: 032, 031, ...)
    - [x] Configuration du % de commission supplémentaire pour les transferts vers les autres opérateurs
    - [x] Page "Situation gain via les différents frais" :
        - [x] Séparer les gains de l'opérateur principal et des autres opérateurs
    - [x] Page "Situation des montants à envoyer" :
        - [x] Suivi et affichage des montants dus à chaque opérateur tiers

- [ ] **Côté Client**
    - [x] Formulaire de nouvelle opération :
        - [x] Ajout de l'option (case à cocher) "Inclure frais de retrait lors de l'envoi"
        - [x] Règle métier : application d'aucun frais de retrait pour les autres opérateurs
        - [x] Envoi multiple vers plusieurs numéros :
            - [x] Gestion de la saisie de plusieurs numéros de téléphone
            - [x] Règle métier : division automatique du montant saisi équitablement pour chaque numéro
            - [x] Restriction de sécurité : blocage si les numéros ne sont pas du même opérateur
